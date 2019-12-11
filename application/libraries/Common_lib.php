<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Format class
 * Help convert between various formats such as XML, JSON, CSV, etc.
 *
 * @author    Phil Sturgeon, Chris Kacerguis, @softwarespot
 * @license   http://www.dbad-license.org/
 */
class Common_lib {	
	
	public $outputdata 	= array();
	public function __construct(){		
		$CI = &get_instance();
		//echo PREFIX;

		$language = 'english' ;
		if(isset($_REQUEST['language']) && !empty($_REQUEST['language']))
			$language =   $_REQUEST['language'];
		else if(isset($CI->session->userdata[PREFIX."sessLang"]) && !empty($CI->session->userdata[PREFIX."sessLang"])) 
			$language =  $CI->session->userdata[PREFIX."sessLang"];	

		// For Rest APIs
		$getallheaders = getallheaders();	
		if (isset ($getallheaders['language'])) {
			if ($getallheaders['language'] == 'rusia') {
				$language = "rusia";
			}
		}

		$CI->lang->load('custom_messages',$language);		

	}

	public function directories($directory){
	    $glob = glob($directory . '/*');

	    if($glob === false)
	    {
	        return array();
	    }
		$directList = array();
		if(!empty($glob)) {
			foreach($glob as $glabDir) {
				if(is_dir($glabDir)) {
					$a =  explode('/', $glabDir);
					array_push($directList, end($a));
				}
			}
		}
		return $directList;
	}
	public function translate($string, $language) {
		$responce = '';
		$language=($language=='Anglais')?'french':$language;
		if (in_array($language, $this->directories('application/language'))) {
			$CI = &get_instance();
			$currentLang = $CI->lang->line('language');
			if ($currentLang == $language)
				$responce = $CI->lang->line($string);
			else if ($currentLang) {
				$CI->lang->load('custom_messages',$language);
				$responce = $CI->lang->line($string);
				$currentLang=($currentLang=='Anglais')?'french':$currentLang;
				$CI->lang->load('custom_messages',$currentLang);				
			}
		}
		return ($responce);
		
	}
	
	public function cleanString($string) {

		$CI = &get_instance();
		$db = get_instance()->db->conn_id;
		$string = str_replace('"','',$string);
		$detagged = trim($string);
		if(function_exists('mysqli_real_escape_string')) {
		    if(get_magic_quotes_gpc()) {
		        $stripped = stripslashes($detagged);
		        $escaped = mysqli_real_escape_string($db, $stripped);
		    } else
		        $escaped = mysqli_real_escape_string($db, $detagged);
		    $escaped = str_replace('\\\\', '', $escaped);
		    return $escaped;
		}
		else{
			return $detagged ;	    
		}
	}

	public function sanitize($input) {
		$output = is_array($input)?array():'';
	    if (is_array($input)) {
	        foreach($input as $var=>$val) {
	            $output[$var] =$this->sanitize($val);
	        }
	    }
	    else {
	        $input  = $this->cleanString($input);
	        $output = $input;
	    }
	    return $output;
	}
	public function setLangData() {
		$CI = &get_instance();
		$CI->Common_model->exequery("SET NAMES utf8");
	}
	public function slugstring($string){
	   $CI = &get_instance();
		return strtolower(trim(preg_replace('~[^0-9a-z]+~i', '-', html_entity_decode(preg_replace('~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($string, ENT_QUOTES, 'UTF-8')), ENT_QUOTES, 'UTF-8')), '-'));
	}
	/*-------------- Generate Slug ----*/
	public function Slug($string){
	   $CI = &get_instance();
		return strtolower(trim(preg_replace('~[^0-9a-z]+~i', '-', html_entity_decode(preg_replace('~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($string, ENT_QUOTES, 'UTF-8')), ENT_QUOTES, 'UTF-8')), '-'));

	}
	public function check_slug_exists($slug,$table_name,$field_name,$id,$field_id){
	   $CI = &get_instance();
		$cond=($id!='')?" AND ".$field_id."!='".$id."'":"";		
		$qry=$CI->Common_model->selRowData($table_name,"",$field_name."='$slug' ".$cond."");
		// $qry=mysql_query("SELECT * FROM ".$table_name." WHERE ".$field_name."='$slug' ".$cond."");
		if($qry)
			return true;		
		else		
			return false;					
	}
	public function create_unique_slug($val,$table_name,$field_name,$id,$fieldid,$counter=0, $operator= '-')	{	
	   $CI = &get_instance();	
		if($counter>0)		
			$slug = $this->Slug($val).$operator.$counter;		
		else		
			$slug = $this->Slug($val);	
		$check_slug_exists =$this->check_slug_exists($slug,$table_name,$field_name,$id,$fieldid);
		if($check_slug_exists){
			$counter++;
			 return $this->create_unique_slug($val,$table_name,$field_name,$id,$fieldid, $counter, $operator);	
		}
		return $slug;		
	}

	public function cUrlGetData($method,$url, $post_fields = array(), $headers = false) {

	   $CI = &get_instance();	
		$ACCESSTOKEN = (isset($CI->sessToken) && !empty($CI->sessToken))?$CI->sessToken:$CI->Common_model->getSelectedField("vm_api_token","token","role='restaurant' and roleId ='".$CI->sessRoleId."'");
		// v3print($this->input->get_request_header); 
		// echo $ACCESSTOKEN; exit;
		if (empty($ACCESSTOKEN)){
			$this->setSessMsg("Your Session has expired.", 2);
			redirect(DASHURL.'/restaurant/login');
		}

	   $ch = curl_init();
	    $headers = ['Content-Type: application/x-www-form-urlencoded', 'charset:utf-8', 'Authorization:JWT '.$ACCESSTOKEN.''];
	   
	   $timeout = 200;
	   curl_setopt($ch, CURLOPT_URL, $url);
	   if(strtolower($method) == "get") {
		    // curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_fields));
		}
		else {
			curl_setopt($ch, CURLOPT_POST, 1);
		    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
		}
	   if ($headers && !empty($headers)) {
	    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	   }
	   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	   curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	   curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	   curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	   $data = curl_exec($ch);
	   if (curl_errno($ch)) {
	    echo 'Error:' . curl_error($ch);
	   }
	   curl_close($ch);
	   return $data;
	  }
	    
	 

    
    public function getUpdateLanguage( $roleId = 0, $role = 'user', $lang= '')  {
       $CI = &get_instance();
       $responce = 'english';   
        if($lang != ''){
            $responce = $lang;
            $CI->Common_model->update("vm_auth", array("language"=>$lang),"role ='".$role."' AND roleId ='".$roleId."'");
        }else{
            $langg = $CI->Common_model->getSelectedField("vm_auth","language","role ='".$role."' AND roleId ='".$roleId."'");
            $responce = ($langg)?$langg:$responce;
        }

        return $responce;       
    }

    
	public function validateToken($token = '')	{	
	   $CI = &get_instance();
	   $responce = 0;	
		if($token != ''){
			$tokenArray = explode(" ", $token);
			if(count($tokenArray) != 2)
				return $responce;	
			$token = $tokenArray[1];
			$tokenData=$CI->Common_model->exequery("SELECT at.roleId, at.role, at.emailId, at.language, us.gender as gender FROM vm_api_token as at lEFT JOIN vm_user as us ON (at.role = 'user' AND us.userId = at.roleId) WHERE at.token ='".$token."'",1);
			if(valResultSet($tokenData) ){
	               $responce = $tokenData;
	            if(isset($_REQUEST['language']) && !empty($_REQUEST['language']))
	             	$CI->Common_model->update("vm_auth", array("language" => $_REQUEST['language']), "roleId = '".$tokenData->roleId."' AND role ='".$tokenData->role."'");
            }
		}

		return $responce;		
	}

	public function getPublicFileName($fileName){
	   	$CI = &get_instance();
		$CI->load->library('encryption');
		$fileName=str_replace(array('+', '/', '='), array('-', '_', '~'), ($CI->encryption->encrypt($fileName)));
		if($fileName)
			$this->getFileAccessToken();
		return $fileName;		
	}

	public function getFileAccessToken(){	
	   	$CI = &get_instance();
	   	$token = generateStrongPassword(50, false, 'lud');	
		$status = $CI->Common_model->insert("vm_file_token", array("token" => $token, "addedOn" => date('Y-m-d H:i:s')));
		if($status)
			$CI->session->set_userdata(PREFIX.'fileAccessToken', $token);
		return ($status)?$token:'';		
	}

	public function checkFileAccessToken(){
		$CI = &get_instance();
		$tokenId = 0;
	   	$token = $CI->session->userdata(PREFIX.'fileAccessToken');
	   	$CI->session->unset_userdata(PREFIX.'fileAccessToken');
		if($token){
			$tokenId  = $CI->Common_model->selTableData("vm_file_token","tokenId","token='".$token."'");
			if($tokenId)
				$CI->Common_model->del("vm_file_token", array("token" => $token));
		}
		return $tokenId;		
	}

	public function getRoleByToken($token = '')	{	
	   $CI = &get_instance();
	   $responce = '';	
		if($token != ''){		
			$query = "SELECT au.roleId, au.emailId, au.role FROM vm_avm_token as at INNER JOIN vm_auth as au on at.emailId = au.emailId  WHERE au.emailId = at.emailId and at.token='".$token."'";
            $authData =   $CI->Common_model->exequery($query,1);
			if(valResultSet($authData) )
                $responce = $authData;
		}

		return $responce;		
	}



	// update restaurant order in database 
	public function updateOrder($tableId = 0, $orderNo = 0, $table_name='') {
		$CI = &get_instance();
		$cond = "";
		// echo $table_name ;exit();
		if($table_name == 'vm_product_subcategory'){
			$rowId = "subcategoryId";
			$parantId = "categoryId";
		}elseif($table_name == 'vm_product_subcategoryitem'){
			$rowId = "subcategoryitemId";
			$parantId = "subcategoryId";
		}elseif($table_name == 'vm_product'){
			$rowId = "productId";
			$parantId = "restaurantId";
		}elseif($table_name == 'welcomeDrink'){
			return $this->updateWelcomeDrinkOrder($tableId, $orderNo, $table_name);

		}
		$cond .= " and $rowId = '".$tableId."'";
		$queryData = $CI->Common_model->exequery("SELECT orderNo, $parantId as parantId, restaurantId from $table_name WHERE status != 2 $cond",1);

		if(empty($queryData))
			return array('status'=>false, 'msg'=>$CI->lang->line('invalidRequrest'));

		$queryCountData = $CI->Common_model->exequery("SELECT count(*) as totalrows,orderNo, $parantId as parantId from $table_name WHERE status != 2  and restaurantId = $queryData->restaurantId and $parantId = $queryData->parantId",1);
		// v3print($queryCountData);exit;

		if($queryCountData->totalrows < $orderNo || $orderNo < 1)
			return array('status'=>false, 'msg'=>$CI->lang->line('orderNoBetween')." $queryCountData->totalrows");

		try{	
			if($orderNo < $queryData->orderNo ){

				$CI->Common_model->runquery("UPDATE $table_name SET  `orderNo` =  `orderNo`+1 WHERE status != 2 and `orderNo` >= $orderNo AND `orderNo` <= $queryData->orderNo and $parantId = $queryData->parantId and restaurantId = $queryData->restaurantId");
				$CI->Common_model->runquery("UPDATE $table_name SET  `orderNo` =  $orderNo WHERE status != 2 $cond");
			}elseif($orderNo > $queryData->orderNo ){

				$CI->Common_model->runquery("UPDATE $table_name SET  `orderNo` =  `orderNo`-1 WHERE status != 2 and `orderNo` <= $orderNo AND `orderNo` >= $queryData->orderNo and $parantId = $queryData->parantId and restaurantId = $queryData->restaurantId");
				$CI->Common_model->runquery("UPDATE $table_name SET  `orderNo` =  $orderNo WHERE status != 2 $cond");

			}else
				$this->reupdateOrder($table_name,$rowId," and $parantId = $queryData->parantId and restaurantId = $queryData->restaurantId");

			return array('status'=>true, 'msg'=>$CI->lang->line('editRecord'));
		}catch (\Exception $e) {
			return array('status'=>false, 'msg'=>$e->getMessage());
		}

		return array('status'=>false, 'msg'=>$CI->lang->line('internalError')); 
	}
    
    // update restaurant order in database 
	public function updateProductOrder($tableId = 0, $orderNo = 0, $subOrderNo=0) {
		$CI = &get_instance();	
		$cond = " and productId = '".$tableId."'";
		$queryData = $CI->Common_model->exequery("SELECT orderNo, subcategoryId, subOrderNo, subcategoryitemId, restaurantId from vm_product WHERE status != 2 $cond",1);

		if(empty($queryData))
			return array('status'=>false, 'msg'=>$CI->lang->line('invalidRequrest'));
		$subquery = '';
		$table_name = 'vm_product';
		if( $subOrderNo > 0 )
			$subquery .= " ,( SELECT count(*)  from vm_product WHERE status != 2  and restaurantId = $queryData->restaurantId and subcategoryitemId = $queryData->subcategoryitemId ) as totalSubCatRows";
		$queryCountData = $CI->Common_model->exequery("SELECT t.* FROM (SELECT count(*) as totalCatRows ".$subquery."  from vm_product WHERE status != 2  and restaurantId = $queryData->restaurantId and subcategoryId = $queryData->subcategoryId) t",1);
		// v3print($queryCountData);exit;

		if($queryCountData->totalCatRows < $orderNo || $orderNo < 1)
			return array('status'=>false, 'msg'=>$CI->lang->line('orderNoBetween')." $queryCountData->totalCatRows");
		if( $subOrderNo > 0 ) {
			if($queryCountData->totalSubCatRows < $subOrderNo || $subOrderNo < 1)
				return array('status'=>false, 'msg'=>$CI->lang->line('orderNoBetween')." $queryCountData->totalSubCatRows");
		}

		try{
			$rowId = 'productId';
			if($orderNo < $queryData->orderNo ){

				$CI->Common_model->runquery("UPDATE $table_name SET  `orderNo` =  `orderNo`+1 WHERE status != 2 and `orderNo` >= $orderNo AND `orderNo` <= $queryData->orderNo and subcategoryId = $queryData->subcategoryId and restaurantId = $queryData->restaurantId");
				$CI->Common_model->runquery("UPDATE $table_name SET  `orderNo` =  $orderNo WHERE status != 2 $cond");
			}elseif($orderNo > $queryData->orderNo ){

				$CI->Common_model->runquery("UPDATE $table_name SET  `orderNo` =  `orderNo`-1 WHERE status != 2 and `orderNo` <= $orderNo AND `orderNo` >= $queryData->orderNo and subcategoryId = $queryData->subcategoryId and restaurantId = $queryData->restaurantId");
				$CI->Common_model->runquery("UPDATE $table_name SET  `orderNo` =  $orderNo WHERE status != 2 $cond");

			}else
				$this->reupdateOrder($table_name,$rowId," and subcategoryId = $queryData->subcategoryId and restaurantId = $queryData->restaurantId");
			if( $subOrderNo > 0 ) {
				if($subOrderNo < $queryData->subOrderNo ){

					$CI->Common_model->runquery("UPDATE $table_name SET  `subOrderNo` =  `subOrderNo`+1 WHERE status != 2 and `subOrderNo` >= $subOrderNo AND `subOrderNo` <= $queryData->subOrderNo and subcategoryitemId = $queryData->subcategoryitemId and restaurantId = $queryData->restaurantId");
					$CI->Common_model->runquery("UPDATE $table_name SET  `subOrderNo` =  $subOrderNo WHERE status != 2 $cond");
				}elseif($subOrderNo > $queryData->subOrderNo ){

					$CI->Common_model->runquery("UPDATE $table_name SET  `subOrderNo` =  `subOrderNo`-1 WHERE status != 2 and `subOrderNo` <= $subOrderNo AND `subOrderNo` >= $queryData->subOrderNo and subcategoryitemId = $queryData->subcategoryitemId and restaurantId = $queryData->restaurantId");
					$CI->Common_model->runquery("UPDATE $table_name SET  `subOrderNo` =  $subOrderNo WHERE status != 2 $cond");

				}else
					$this->reupdateOrder($table_name,$rowId," and subcategoryitemId = $queryData->subcategoryitemId and restaurantId = $queryData->restaurantId");
			}

			return array('status'=>true, 'msg'=>$CI->lang->line('editRecord'));
		}catch (\Exception $e) {
			return array('status'=>false, 'msg'=>$e->getMessage());
		}

		return array('status'=>false, 'msg'=>$CI->lang->line('internalError')); 
	}
	// Auto Arrange Product After Delete or Change Category
	public function reArrangeProductOrder($productId = 0, $status = 'delete') {
		$CI = &get_instance();
		if( $productId > 0 ) {
			$queryData = $CI->Common_model->exequery("SELECT orderNo, subcategoryId, subOrderNo, welcomeDrinkOrderNo, isAvailableInFree, subcategoryitemId, restaurantId from vm_product WHERE status != 2 AND productId =".$productId."",1);
			if( $queryData ) {
				if( $queryData->subcategoryId > 0 && ($status == 'delete' || $status == 'subcategory')) {
					$CI->Common_model->runquery("UPDATE vm_product SET  `orderNo` =  `orderNo`-1 WHERE status != 2 and `orderNo` > $queryData->orderNo and subcategoryId = $queryData->subcategoryId and restaurantId = $queryData->restaurantId");
				}
				if( $queryData->subcategoryitemId > 0 && ($status == 'delete' || $status == 'subcategoryItem')) {
					$CI->Common_model->runquery("UPDATE vm_product SET  `subOrderNo` =  `subOrderNo`-1 WHERE status != 2 and `subOrderNo` > $queryData->subOrderNo and subcategoryitemId = $queryData->subcategoryitemId and restaurantId = $queryData->restaurantId");
				}
				if( $queryData->isAvailableInFree == 1 && ($status == 'delete' || $status == 'freeDrink')) {
					$CI->Common_model->runquery("UPDATE vm_product SET  `welcomeDrinkOrderNo` =  `welcomeDrinkOrderNo`-1 WHERE status != 2 and `welcomeDrinkOrderNo` > $queryData->welcomeDrinkOrderNo and isAvailableInFree = $queryData->isAvailableInFree and restaurantId = $queryData->restaurantId");
				}
			}
		}
		
	}
	// update restaurant order in database 
	public function updateWelcomeDrinkOrder($tableId = 0, $welcomeDrinkOrderNo = 0, $table_name='') {
		$CI = &get_instance();
		$cond = "";
		$table_name = "vm_product";
		$rowId = "productId";
		$parantId = "restaurantId";

		$cond .= " and $rowId = '".$tableId."'";
		$queryData = $CI->Common_model->exequery("SELECT welcomeDrinkOrderNo, $parantId as parantId, restaurantId from $table_name WHERE status != 2 $cond",1);

		if(empty($queryData))
			return array('status'=>false, 'msg'=>$CI->lang->line('invalidRequrest'));

		$queryCountData = $CI->Common_model->exequery("SELECT count(*) as totalrows from $table_name WHERE status != 2  and isAvailableInFree = '1'  and restaurantId = $queryData->restaurantId and $parantId = $queryData->parantId",1);
		// v3print($queryCountData);exit;

		if($queryCountData->totalrows < $welcomeDrinkOrderNo || $welcomeDrinkOrderNo < 1)
			return array('status'=>false, 'msg'=>$CI->lang->line('orderNoBetween')." $queryCountData->totalrows");

		try{	
			if($welcomeDrinkOrderNo < $queryData->welcomeDrinkOrderNo ){

				$CI->Common_model->runquery("UPDATE $table_name SET  `welcomeDrinkOrderNo` =  `welcomeDrinkOrderNo`+1 WHERE status != 2 and `welcomeDrinkOrderNo` >= $welcomeDrinkOrderNo AND `welcomeDrinkOrderNo` <= $queryData->welcomeDrinkOrderNo and $parantId = $queryData->parantId  and isAvailableInFree = '1'");
				$CI->Common_model->runquery("UPDATE $table_name SET  `welcomeDrinkOrderNo` =  $welcomeDrinkOrderNo WHERE status != 2 $cond");
			}elseif($welcomeDrinkOrderNo > $queryData->welcomeDrinkOrderNo ){

				$CI->Common_model->runquery("UPDATE $table_name SET `welcomeDrinkOrderNo` =  `welcomeDrinkOrderNo`-1 WHERE status != 2 and `welcomeDrinkOrderNo` <= $welcomeDrinkOrderNo AND `welcomeDrinkOrderNo` >= $queryData->welcomeDrinkOrderNo and $parantId = $queryData->parantId  and isAvailableInFree = '1'");
				$CI->Common_model->runquery("UPDATE $table_name SET `welcomeDrinkOrderNo` =  $welcomeDrinkOrderNo WHERE status != 2 $cond");

			}else{
				$welcomeDrinkOrderNo =1;
				$queryData = $CI->Common_model->exequery("SELECT welcomeDrinkOrderNo, $rowId as rowId from $table_name WHERE status != 2 and isAvailableInFree = '1' and $parantId = $queryData->parantId order by welcomeDrinkOrderNo");
				
				if (valResultSet($queryData)) {
					foreach ($queryData as $row) {
						if ($CI->Common_model->update($table_name, array("welcomeDrinkOrderNo"=>$welcomeDrinkOrderNo),"$rowId = ".$row->rowId))
							++$welcomeDrinkOrderNo;
					}
				}
			}

			return array('status'=>true, 'msg'=>$CI->lang->line('editRecord'));
		}catch (\Exception $e) {
			return array('status'=>false, 'msg'=>$e->getMessage());
		}

		return array('status'=>false, 'msg'=>$CI->lang->line('internalError')); 
	}
	

	// Upload image
	public function reupdateOrder($table_name, $rowId, $cond = '') {
		$CI = &get_instance();
		$orderNo =1;
		$queryData = $CI->Common_model->exequery("SELECT orderNo, $rowId as rowId from $table_name WHERE status != 2 $cond order by orderNo");
		
		if (valResultSet($queryData)) {
			foreach ($queryData as $row) {
				if ($CI->Common_model->update($table_name, array("orderNo"=>$orderNo),"$rowId = ".$row->rowId))
					++$orderNo;
			}
		}
	}	
	
	// get restaurant open or close
	public function updateVenueTiming($restaurantId, $postData) {
		$responce = array('valid'=> false, 'msg'=> 'dbError');
    	$weeks = array('firstWeek','secondWeek','thirdWeek','fourthWeek','fifthWeek');
    	$days = array('sunday','monday','tuesday','wednesday','thursday','friday','saturday');
		$date = date('Y-m-d H:i:s');
		$queryStatus = '';
		$CI = &get_instance();
		$CI->Common_model->update('vm_restaurant_time', array("status"=>2),"restaurantId = ".$restaurantId);
		if ($restaurantId > 0 && !empty($postData)) {
			if (isset($postData['openCloseType']) && !empty($postData['openCloseType'])) {
				$insertData = array();
				if ($postData['openCloseType'] == 'regular') {
					$week = '';
					foreach ($days as $key => $day) {
						if (isset($postData[$postData['openCloseType'].$week.$day]) && isset($postData[$postData['openCloseType'].$week.$day.'Open']) && !empty($postData[$postData['openCloseType'].$week.$day.'Open']) && isset($postData[$postData['openCloseType'].$week.$day.'Close'])&& !empty($postData[$postData['openCloseType'].$week.$day.'Close'])) {

							$isExit = $CI->Common_model->exequery("Select timeId from vm_restaurant_time where restaurantId=".$restaurantId." AND openCloseType='".$postData['openCloseType']."' AND week='".$week."' AND day='".$day."'",1);
							$insertData = array("status"=>0, "restaurantId"=>$restaurantId, "openCloseType"=>$postData['openCloseType'], "week"=>$week, "day"=>$day, "open"=>$postData[$postData['openCloseType'].$week.$day.'Open'], "close"=>$postData[$postData['openCloseType'].$week.$day.'Close'], "updatedOn"=>$date);
							if (isset($isExit->timeId) && $isExit->timeId> 0)
								$queryStatus = $CI->Common_model->update('vm_restaurant_time', $insertData,"timeId = ".$isExit->timeId);
							else{
								$insertData['addedOn'] = $date;
								$queryStatus = $CI->Common_model->insert('vm_restaurant_time', $insertData);
							}
						}
					}
				}elseif ($postData['openCloseType'] == 'weekDays') {
					if ((isset($postData['weekName']) && !empty($postData['weekName']))) {
						foreach ($postData['weekName'] as $week) {
							foreach ($days as $key => $day) {
								if (isset($postData[$postData['openCloseType'].$week.$day]) && isset($postData[$postData['openCloseType'].$week.$day.'Open']) && !empty($postData[$postData['openCloseType'].$week.$day.'Open']) && isset($postData[$postData['openCloseType'].$week.$day.'Close'])&& !empty($postData[$postData['openCloseType'].$week.$day.'Close'])) {

									$isExit = $CI->Common_model->exequery("Select timeId from vm_restaurant_time where restaurantId=".$restaurantId." AND openCloseType='".$postData['openCloseType']."' AND week='".$week."' AND day='".$day."'",1);
									$insertData = array("status"=>0, "restaurantId"=>$restaurantId, "openCloseType"=>$postData['openCloseType'], "week"=>$week, "day"=>$day, "open"=>$postData[$postData['openCloseType'].$week.$day.'Open'], "close"=>$postData[$postData['openCloseType'].$week.$day.'Close'], "updatedOn"=>$date);
									if (isset($isExit->timeId) && $isExit->timeId> 0)
										$queryStatus = $CI->Common_model->update('vm_restaurant_time', $insertData,"timeId = ".$isExit->timeId);
									else{
										$insertData['addedOn'] = $date;
										$queryStatus = $CI->Common_model->insert('vm_restaurant_time', $insertData);
									}
								}
							}
						}
					}
				}elseif ($postData['openCloseType'] == 'specificDate' || $postData['openCloseType'] == 'specificDateOfMonth') {

					if (isset($postData[$postData['openCloseType']]) && !empty($postData[$postData['openCloseType']]) && isset($postData[$postData['openCloseType'].'Open']) && !empty($postData[$postData['openCloseType'].'Open']) && isset($postData[$postData['openCloseType'].'Close'])&& !empty($postData[$postData['openCloseType'].'Close'])) {
						foreach ($postData[$postData['openCloseType']] as $key => $specificDate) {							

							$isExit = $CI->Common_model->exequery("Select timeId from vm_restaurant_time where restaurantId=".$restaurantId." AND openCloseType='".$postData['openCloseType']."' AND day='".$specificDate."'",1);
							$insertData = array("status"=>0, "restaurantId"=>$restaurantId, "openCloseType"=>$postData['openCloseType'], "week"=>'', "day"=>$specificDate, "open"=>$postData[$postData['openCloseType'].'Open'][$key], "close"=>$postData[$postData['openCloseType'].'Close'][$key], "updatedOn"=>$date);
							if (isset($isExit->timeId) && $isExit->timeId> 0)
								$queryStatus = $CI->Common_model->update('vm_restaurant_time', $insertData,"timeId = ".$isExit->timeId);
							else{
								$insertData['addedOn'] = $date;
								$queryStatus = $CI->Common_model->insert('vm_restaurant_time', $insertData);
							}
						}
					}
				}
			}
		}
		if ($queryStatus)
			$responce = array('valid'=> true, 'msg'=> 'editRecord');
        return $responce;
	}

	public function getWeekNo($week){
		return ($week == 'firstWeek')?1:(($week == 'secondWeek')?2:(($week == 'thirdWeek')?3:(($week == 'fourthWeek')?4:(($week == 'fifthWeek')?5:1))));
	}
	// get restaurant open or close
	public function checkrestaurantopenclosed($restaurantId, $type = 'regular') {
		$responce = array('isOpen'=> 0, 'openTime'=> '', 'closeTime'=> '', 'nextOpenBy'=> $type, 'nextOpenDate'=> '', 'nextOpenDay'=> '', 'nextOpenTime'=> '', 'nextOpenCLoseString'=> '', 'currentDay'=> '', 'currentWeek'=> '');

	    $weeks = array('firstWeek','secondWeek','thirdWeek','fourthWeek','fifthWeek');
	    $days = array('sunday','monday','tuesday','wednesday','thursday','friday','saturday');
		$CI = &get_instance();
		$cond = '';
		$today = $responce['currentDay'] = strtolower(date('l'));
		$date = date('Y-m-d');
		$dayNo = date('d');
		$firstOfMonth = date("Y-m-01", strtotime($date));
    	$weekNo =  intval(date("W", strtotime($date))) - intval(date("W", strtotime($firstOfMonth)));
		$week = $responce['currentWeek'] = ($weekNo == 0)?'firstWeek':(($weekNo == 1)?'secondWeek':(($weekNo == 2)?'thirdWeek':(($weekNo == 3)?'fourthWeek':(($weekNo == 4)?'fifthWeek':'firstWeek'))));
		if($type == 'regular')
			$cond = " AND day = '".$today."'";
		else if($type == 'weekDays')
			$cond = " AND week = '".$week."' AND day = '".$today."'";
		else if($type == 'specificDate')
			$cond = " AND day = '".$date."'";
		else if($type == 'specificDateOfMonth')
			$cond = " AND day = '".$dayNo."'";

		$todayData = $CI->Common_model->exequery("Select * from vm_restaurant_time where restaurantId=".$restaurantId." AND openCloseType='".$type."' AND status = 0".$cond,1);
		// echo '<br>';  v3print($todayData); exit;
		if ($todayData) {

			$isOpen = 0;
	        $time0  =  strtotime($todayData->open);
	        $time1  =  strtotime($todayData->close);
	        $time2  =  strtotime(date('H:i'));
	        if($time0 > $time1){
	            if(($time2  <= $time1) || ($time2  >= $time0)){
	                $responce['isOpen'] = 1;
	                $responce['openTime'] = $todayData->open;
	                $responce['closeTime'] = $todayData->close;
	            }
	        }else if($time1 > $time0){
	            if($time0 <= $time2 && $time1 >= $time2 ){
	                $responce['isOpen'] = 1;
	                $responce['openTime'] = $todayData->open;
	                $responce['closeTime'] = $todayData->close;
	            }
	        }
		}
		// v3print($responce); exit;
		if($responce['isOpen'] == 0){
			$myArray = array();
			$nextOpenDate = '';
			$nextOpenDay = '';
			$nextOpenTime = '';
			if($type == 'regular'){
				$currentDay = $today;
				$cond = "";
			}
			else if($type == 'weekDays'){
				$currentWeek = $week;
				$cond = "";
			}
			else if($type == 'specificDate'){
				$current = $date;
				$cond = " AND day >= '".$date."' ORDER BY day ASC";
			}
			else if($type == 'specificDateOfMonth'){

				$currentDayNo = $dayNo;
				$cond = "";
			}

			$timeData = $CI->Common_model->exequery("Select * from vm_restaurant_time where restaurantId=".$restaurantId." AND openCloseType='".$type."' AND status = 0".$cond);
			if ($timeData) {
				foreach ($timeData as $time) {
					if($time->week)
						$myArray[$time->week] = $time->week;
					
					$myArray[$time->week.$time->day.'open'] = $time->open;
					$myArray[$time->week.$time->day.'close'] = $time->close;
					$myArray[$time->day] = $time->day;

					if(empty($nextOpenTime) && $type == 'specificDate'){

						if ($time->day == $date) {
							if (strtotime($time->open) > strtotime(date('H:i'))) {
								$nextOpenDate = $time->day;
								$nextOpenDay = strtolower(date("l", strtotime($time->day)));
								$nextOpenTime = $time->open;
							}
						}else{
							$nextOpenDate = $time->day;
							$nextOpenDay = strtolower(date("l", strtotime($time->day)));
							$nextOpenTime = $time->open;
						}

					}
				}

				if($type == 'regular'){ 
					foreach ($days as $key => $day) {
						if (empty($nextOpenTime)) {
							if (isset($myArray[$currentDay.'open'])) {
								// v3print($currentDay); exit;

								$nextOpenDate = ($myArray[$currentDay.'open'] > date('H:i'))?date("Y-m-d"):date("Y-m-d", strtotime('next '.$currentDay));
								$nextOpenDay = $currentDay;
								$nextOpenTime = $myArray[$currentDay.'open'];
							}
							$currentDay = $this->getNextCheckOption($type, $currentDay);
						}
					}
				}elseif($type == 'weekDays'){
					foreach ($weeks as $w) {
						if (empty($nextOpenTime)) {
							if (isset($myArray[$currentWeek])) {
								$currentDay = $today;
								foreach ($days as $key => $day) {
									if (empty($nextOpenTime)) {
										$currentDay = $this->getNextCheckOption('regular', $currentDay);

										if (isset($myArray[$currentWeek.$currentDay.'open'])) {

											$n = str_replace('Week', '', $currentWeek);

											$d = substr($currentDay, 0, 3);

											if ((($weekNo+1) == $this->getWeekNo($currentWeek))) {
												if ($currentDay == $today) {

											        $openTime  =  strtotime($myArray[$currentWeek.$currentDay.'open']);
											        $closeTime  =  strtotime($myArray[$currentWeek.$currentDay.'close']);
													$currentTime  =  strtotime(date('H:i'));
													if ($openTime > $currentTime) {
														$m = strtolower(date('F'));
													}else if($currentTime > $closeTime){
														$m = strtolower(date('F',strtotime('first day of +1 month')));
													}
												}else{
													$m = strtolower(date('F'));
												}
											}else if((($weekNo+1) > $this->getWeekNo($currentWeek))){
												$m = strtolower(date('F',strtotime('first day of +1 month')));
											}else
												$m = strtolower(date('F'));
											  
											$y = (date('m') == 12 && $m == 1)?date('Y', strtotime('+1 year')):date('Y');
											
											$nextOpenDate = date('Y-m-d', strtotime($n.' '.$d.' of '.$m.' '.$y));
											$nextOpenDay = $currentDay;
											$nextOpenTime = $myArray[$currentWeek.$currentDay.'open'];
										}
									}
								}
							}
						}
						$currentDay = $this->getNextCheckOption($type, $currentWeek);
					}
				}elseif($type == 'specificDateOfMonth'){
					for ($i=1; $i < 32; $i++) {
						if (empty($nextOpenTime)) {
							if (isset($myArray[$currentDayNo.'open'])) {

								if ($time->day == date('d')) {
									if (strtotime($time->open) > strtotime(date('H:i'))) {
										$nextOpenDate = date('Y-m-d');
										$nextOpenDay = strtolower(date("l"));
										$nextOpenTime = $time->open;
									}
								}else{

									$d = (strlen($currentDayNo) > 1)?$currentDayNo:'0'.$currentDayNo;

									$m = ($currentDayNo > date('d'))?date('m'):date('m',strtotime('first day of +1 month'));
									  
									$y = (date('m') == 12 && $m == 1)?date('Y', strtotime('+1 year')):date('Y');
									$nextOpenDate = $y.'-'.$m.'-'.$d;

									$nextOpenDay = strtolower(date('l', strtotime($nextOpenDate)));
									$nextOpenTime = $myArray[$currentDayNo.'open'];
								}
							}
							$currentDayNo = $this->getNextCheckOption($type, $currentDayNo);
						}
					}
				}
			}
			if ($nextOpenTime) {
				$responce = array('isOpen'=> 0, 'openTime'=> '', 'closeTime'=> '', 'nextOpenBy'=> $type,  'nextOpenDate'=> $nextOpenDate,  'nextOpenDay'=> $nextOpenDay,  'nextOpenTime'=> $nextOpenTime, 'currentDay'=> $responce['currentDay'], 'currentWeek'=> $responce['currentWeek']);
			}

		}

		if($responce['isOpen']) 
			$responce['nextOpenCLoseString'] = sprintf($CI->lang->line('closeAt'), date('H:i',strtotime($responce['closeTime'])));
		else {
			if($responce['nextOpenDate'] == date('Y-m-d'))
				$responce['nextOpenCLoseString'] = sprintf($CI->lang->line('openAt'), date('H:i',strtotime($responce['nextOpenTime'])));
			else{
				if($responce['nextOpenDate']){    						$responce['nextOpenCLoseString'] = ($CI->lang->line('langSuffix') !='') ? sprintf($CI->lang->line('openCloseSentence'), $CI->lang->line($responce['nextOpenDay']).' '.date('d M Y',strtotime($responce['nextOpenDate'])), date('H:i',strtotime($responce['nextOpenTime']))) : sprintf($CI->lang->line('openCloseSentence'), date('H:i',strtotime($responce['nextOpenTime'])), $CI->lang->line($responce['nextOpenDay']).' '.date('d M Y',strtotime($responce['nextOpenDate'])));    					
				}
			}
		}
        return $responce;
	}

	// get Time OpenDays

	public function getNextCheckOption($type, $current){
		if($type == 'regular')
			$responce = strtolower(date('l', strtotime(ucfirst($current)." +1 days")));
		else if($type == 'weekDays')
			$responce = ($current == 'firstWeek')?'secondWeek':(($current == 'secondWeek')?'thirdWeek':(($current == 'thirdWeek')?'fourthWeek':(($current == 'fourthWeek')?'fifthWeek':'firstWeek')));
		else if($type == 'specificDateOfMonth')
			$responce = ($current == 31)?1:$current+1;
		else
			$responce = "";

		return $responce;	
	}

	// get week Days Timing

	public function weekDaysTiming($restaurantId, $openCloseType, $openCloseData){

		$CI = &get_instance();
		$responce = array('monday'=>'','tuesday'=>'','wednesday'=>'','thursday'=>'','friday'=>'','saturday'=>'','sunday'=>'');
        $day = strtolower(date('l'));
        $remainsdays = $this->daysfortimes($day);
        $condT = '';
	    if ($openCloseType == 'weekDays') {
	        $condT = " AND week = '".$openCloseData['currentWeek']."'";
	    }elseif ($openCloseType == 'specificDate') {
	        $condT = " AND day >= '".date('Y-m-d')."'";
	    }
        $timeData  = $CI->Common_model->exequery("Select * from vm_restaurant_time where restaurantId=".$restaurantId." AND openCloseType='".$openCloseType."' AND status = 0 ".$condT." ORDER BY day asc ");
        if ($timeData) {
            if ($openCloseType == 'specificDate') {
            	foreach ($timeData as  $time) {
                    if (in_array($time->day, $remainsdays['dates'])) {
                        if ($time->day == date('Y-m-d')) {
                            if ($openCloseData['isOpen'] == 1 || ($openCloseData['isOpen'] == 0 && $openCloseData['nextOpenDate'] == date('Y-m-d')))
                                $responce[strtolower(date("l", strtotime($time->day)))] = $time->open.' - '.$time->close;
                            
                        }else
                            $responce[strtolower(date("l", strtotime($time->day)))] = $time->open.' - '.$time->close;
                    }
                }
            }else if ($openCloseType =='specificDateOfMonth') {
            	foreach ($timeData as  $time) {
                    if (in_array($time->day, $remainsdays['monthday'])) {
                        if ($time->day == date('d')) {
                            if ($openCloseData['isOpen'] == 1 || ($openCloseData['isOpen'] == 0 && $openCloseData['nextOpenDate'] == date('Y-m-d')))
                                $responce[$remainsdays['days'][array_search($time->day, $remainsdays['monthday'])]] = $time->open.' - '.$time->close;
                            
                        }else
                            $responce[$remainsdays['days'][array_search($time->day, $remainsdays['monthday'])]] = $time->open.' - '.$time->close;
                    }
                }
            }else if($openCloseType == 'weekDays'){
            	foreach ($timeData as  $time) {
                    if (in_array($time->day, $remainsdays['days'])) {
                        if ($time->day == $day) {
                            if ($openCloseData['isOpen'] == 1 || ($openCloseData['isOpen'] == 0 && $openCloseData['nextOpenDate'] == date('Y-m-d')))
                                $responce[$time->day] = $time->open.' - '.$time->close;
                            
                        }else
                            $responce[$time->day] = $time->open.' - '.$time->close;
                    }
                }
            }else{
            	foreach ($timeData as  $time) {
                    if ($time->day == $day) {
                        if ($openCloseData['isOpen'] == 1 || ($openCloseData['isOpen'] == 0 && $openCloseData['nextOpenDate'] == date('Y-m-d')))
                            $responce[$time->day] = $time->open.' - '.$time->close;
                        
                    }else
                        $responce[$time->day] = $time->open.' - '.$time->close;
                }
            }
        }

		return $responce;	
	}

    public function daysfortimes($day) {
        $response = array('days'=>array(), 'dates'=>array(), 'monthday'=>array() );
        $days =  array('monday','tuesday','wednesday','thursday','friday','saturday','sunday');
        $key = array_search($day, $days);
        for ($i=$key; $i < 7; $i++) { 
            array_push($response['days'], $days[$i]);
            array_push($response['dates'], date("Y-m-d", strtotime($days[$i])));
            array_push($response['monthday'], date("d", strtotime($days[$i])));
        }
        return $response;

    }
	
	// get restaurant open or close
	public function getrestaurantopenclosed($restaurantTime) {
		$isOpen = 0;
        $times  =  explode(' - ', $restaurantTime);
        $time0  =  strtotime($times[0]);
        $time1  =  strtotime($times[1]);
        $time2  =  strtotime(date('H:i'));
        if($time0 > $time1){
            if(($time2  <= $time1) || ($time2  >= $time0))
                $isOpen = 1;
        }else if($time0 < $time1){
            if($time0 <= $time2 && $time1 >= $time2 )
                $isOpen = 1;
        }
        return $isOpen;
	}

    // get next restaurant open or close time
	public function getrestaurantnextopencloseTime($restaurantData) {
		$closeDays = ($restaurantData->closeDays !='') ? explode(',',$restaurantData->closeDays): array();
		if(count($closeDays) >= 7 )
			return "00:00";
		$today = strtolower(date('l'));
		/*Open Days*/	
		// $day = ( $restaurantData->isRestaurantOpen == 1 ) ? $this->getNextOpenDays($closeDays, date('Y-m-d')) : $this->getNextOpenDays($closeDays, date('Y-m-d', strtotime('+1 days')));
		$day = $this->getNextOpenDays($closeDays, date('Y-m-d'));	
		// echo($day); exit;
		$restaurantTime = $restaurantData->$day;
		$times  =  explode(' - ', $restaurantTime);
        $time0  =  strtotime($times[0]); // 1:30
        $time1  =  strtotime($times[1]); //1:00
        $time2  =  strtotime(date('H:i')); // 3:30
        $result = array('day' => $day, 'isSame' => 1,'time' => strtotime('00:00'));
		if($day == $today) {
			if($time0 > $time1){
	            if(($time2  <= $time1) || ($time2  >= $time0))
	            	$result['time'] = $time1;
	            else
	            	$result['time'] = $time0;

	        }else if($time0 < $time1){
	            if($time0 <= $time2 && $time1 >= $time2 )
	                $result['time'] = $time1;
	            else if($time0 > $time2 )
	            	$result['time'] = $time0;
	            else {
	            	$day = $this->getNextOpenDays($closeDays, date('Y-m-d', strtotime('+1 days')));
	            	$restaurantTime = $restaurantData->$day;
					$times  =  explode(' - ', $restaurantTime);
			        $time0  =  strtotime($times[0]);
			        $time1  =  strtotime($times[1]);
			        $time2  =  strtotime(date('H:i'));
			        $result['time'] = $time0;
	            }
	        }
		}
		else {
			$result['time'] = $time0;
			$result['isSame'] = 0;
		} 
		$result['time'] = date('H:i', $result['time']);
		return $result;
			 
	}

	// get Time OpenDays

	public function getNextOpenDays($closeDays, $currentDate){
		$day = strtolower(date('l', strtotime($currentDate)));
		if(!in_array($day, $closeDays)) 
			return $day;
		else {
			$newDate = date('Y-m-d',strtotime($currentDate. '+1 days'));
			return $this->getNextOpenDays($closeDays, $newDate);
		}		
	}
	
	// get Week Timing 

	/*public function getWeeKDaysTiming($restaurantData){
		$weekDays = array('monday', 'tuesday', 'wednesday', 'thursday', 'friday' , 'saturday', 'sunday');
		$weeKDaysTiming = array();
		$closeDays = ($restaurantData->closeDays !='') ? explode(',',$restaurantData->closeDays): array();
		foreach ($weekDays as $key => $value) {
			$isClose = (in_array($value, $closeDays)) ? true : false;
			$weeKDaysTiming[$value] = array('dayName' => ucwords($value), 'openTime' => $restaurantData->$value, 'closeTime' => $restaurantData->$value, 'isClose' => $isClose);
		}
		return $weeKDaysTiming;
	}*/
	
	public function getWeeKDaysTiming($restaurantData){
	    $CI = &get_instance();
		$weekDays = array('monday', 'tuesday', 'wednesday', 'thursday', 'friday' , 'saturday', 'sunday');
		$weeKDaysTiming = array();
		$closeDays = ($restaurantData->closeDays !='') ? explode(',',$restaurantData->closeDays): array();
		$openDays = array();
		$timeData  = $CI->Common_model->exequery("Select * from vm_restaurant_time where restaurantId=".$restaurantData->restaurantId." AND openCloseType='regular' AND status = 0 ORDER BY day asc ");
		
		if($timeData) {
    		foreach ($timeData as  $time) {			
    	        $openDays[$time->day] = array('openTime' => $time->open, 'closeTime' => $time->close);
    	    }
		}
		foreach ($weekDays as $key => $value) {
			if(isset($openDays[$value]) && !empty($openDays[$value]))
				$weeKDaysTiming[$value] = array('dayName' => ucwords($value), 'openTime' => $openDays[$value]['openTime'].' - '.$openDays[$value]['closeTime'], 'closeTime' => $openDays[$value]['closeTime'], 'isClose' => 0);
			else
				$weeKDaysTiming[$value] = array('dayName' => ucwords($value), 'openTime' => '00:00 - 00:00', 'closeTime' => '00:00', 'isClose' => 1);
		}
		return $weeKDaysTiming;
	}
	// get user membership and free Drink status

	/*public function getUserFreeDrinkAndMembership($userId=0){
		$CI = &get_instance();
		$userMembershipStatus = array('free_drink' => 2, 'membership' => 0, 'remainingDays' => 0);
		if($userId == 0)
			return $userMembershipStatus;
		$userMembership = $CI->Common_model->exequery("SELECT um.*, sp.planName, sp.numberFreeDrink, sp.freeDrinkPeriod FROM vm_user_memberships um left join vm_subscription_plan sp on um.planId = sp.Id WHERE um.startDate <= '".date('Y-m-d')."' AND um.endDate >= '".date('Y-m-d')."' AND um.userId=".$userId." AND um.subscriptionStatus ='Active' ORDER BY um.membershipId desc limit 0,1",true);
		if($userMembership) {
			$userMembershipStatus['membership'] = 1;
			$to_time = strtotime($userMembership->endDate);
            $from_time = strtotime(date('Y-m-d'));

            $days = round(($to_time - $from_time) / (3600*24));

            $remainingDays = ( $days > -1 ) ? $days : -1;
            $userMembershipStatus['remainingDays'] = $remainingDays;
			$cond = " AND DATE(`currentTimestamp`)  = '".date('Y-m-d')."'";
			if($userMembership->freeDrinkPeriod == "month") {
				$lastMonthDate = date("Y-m-d", strtotime("last day of previous month"));
				$startDate = date("Y-m-d", strtotime("first day of previous month"));
				$cond = " AND DATE(`currentTimestamp`) >= '".$startDate."' AND DATE(`currentTimestamp`) >= '".$lastMonthDate."'";
			}

			$checkFreeDrink = $CI->Common_model->exequery("SELECT count(*) as freeDrink FROM `vm_user_daily_drink` WHERE userId = ".$userId."  ".$cond."  AND (servedStatus='1' OR servedStatus='0')", true);
			if($checkFreeDrink) {
				if($checkFreeDrink->freeDrink < $userMembership->numberFreeDrink)
					$userMembershipStatus['free_drink'] = 1;
			}
			else
				$userMembershipStatus['free_drink'] = 1;
		}
		return $userMembershipStatus;
	}*/
	public function getUserFreeDrinkAndMembership($userId=0){
		$CI = &get_instance();
		$userMembershipStatus = array('free_drink' => 2, 'membership' => 0, 'remainingDays' => 0);
		if($userId == 0)
			return $userMembershipStatus;
		//$userMembership = $CI->Common_model->exequery("SELECT um.*, sp.planName, sp.numberFreeDrink, sp.freeDrinkPeriod FROM vm_user_memberships um left join vm_subscription_plan sp  on (CASE WHEN um.isUpdatedPlan = 1 then um.planId= else um.planId = sp.Id end) WHERE um.startDate <= '".date('Y-m-d')."' AND um.endDate >= '".date('Y-m-d')."' AND um.userId=".$userId." AND um.subscriptionStatus ='Active' ORDER BY um.membershipId desc limit 0,1",true);
		$userMembership = $CI->Common_model->exequery("SELECT um.* FROM vm_user_memberships um WHERE um.startDate <= '".date('Y-m-d')."' AND um.endDate >= '".date('Y-m-d')."' AND um.userId=".$userId." AND um.subscriptionStatus ='Active' ORDER BY um.membershipId desc limit 0,1",true);
		if($userMembership) {
			$cond = ($userMembership->isUpdatedPlan == 1) ? " AND spd.detailId='".$userMembership->planId."'" : " AND sp.Id ='".$userMembership->planId."'";
			
			$membershipDetails = $CI->Common_model->exequery("SELECT sp.planName, sp.numberFreeDrink, sp.freeDrinkPeriod FROM vm_subscription_plan sp left join vm_subscription_details spd ON sp.Id = spd.subscriptionId WHERE 1 ".$cond, 1);
			$userMembership->planName = '';
			$userMembership->numberFreeDrink = 1;
			$userMembership->freeDrinkPeriod = 'day';
			if($membershipDetails) {
				$userMembership->planName = $membershipDetails->planName;
				$userMembership->numberFreeDrink = $membershipDetails->numberFreeDrink;
				$userMembership->freeDrinkPeriod = $membershipDetails->freeDrinkPeriod;
			}
			
			$userMembershipStatus['membership'] = 1;
			$to_time = strtotime($userMembership->endDate);
            $from_time = strtotime(date('Y-m-d'));

            $days = round(($to_time - $from_time) / (3600*24));

            $remainingDays = ( $days > -1 ) ? $days : -1;
            $userMembershipStatus['remainingDays'] = $remainingDays;
			$cond = " AND DATE(`currentTimestamp`)  = '".date('Y-m-d')."'";
			if($userMembership->freeDrinkPeriod == "month") {
				$lastMonthDate = date("Y-m-d", strtotime("last day of previous month"));
				$startDate = date("Y-m-d", strtotime("first day of previous month"));
				$cond = " AND DATE(`currentTimestamp`) >= '".$startDate."' AND DATE(`currentTimestamp`) >= '".$lastMonthDate."'";
			}
			else if($userMembership->freeDrinkPeriod == "week") {
				$lastWeekDate = date("Y-m-d", strtotime("sunday this week"));
				$startWeekDate = date("Y-m-d", strtotime("monday this week"));
				$cond = " AND DATE(`currentTimestamp`) >= '".$startWeekDate."' AND DATE(`currentTimestamp`) <= '".$lastWeekDate."'";
			}
			$checkFreeDrink = $CI->Common_model->exequery("SELECT count(*) as freeDrink FROM `vm_user_daily_drink` WHERE userId = ".$userId."  ".$cond."  AND (servedStatus='1' OR servedStatus='0')", true);
			if($checkFreeDrink) {
				if($checkFreeDrink->freeDrink < $userMembership->numberFreeDrink)
					$userMembershipStatus['free_drink'] = 1;
			}
			else
				$userMembershipStatus['free_drink'] = 1;
		}
		return $userMembershipStatus;
	}

	// update restaurant happy hour
	public function updateHappyhourProduct($happyhourId,$postData){
		$CI = &get_instance();
		$queryStatus = 0;
		if (isset($postData['productId']) && !empty($postData['productId']) && $happyhourId > 0) {

			$CI->Common_model->update("vm_happyhour_product",array('status' => 3, 'updatedOn' => date('Y-m-d H:i:s')),"status != '2' and happyhourId = ".$happyhourId);
			foreach ($postData['productId'] as $key => $productId){
				if(isset($postData['variableId'.$productId]) && !empty($postData['variableId'.$productId])){
					foreach ($postData['variableId'.$productId] as $vKey => $variableId) {
						$variableDiscountedPrice	= 	(isset($postData['discountedPrice'.$productId][$vKey]) && !empty($postData['discountedPrice'.$productId][$vKey]))?$postData['discountedPrice'.$productId][$vKey]:0;
						$variablePrice	= 	(isset($postData['variablePrice'.$productId][$vKey]) && !empty($postData['Price'.$productId][$vKey]))?$postData['variablePrice'.$productId][$vKey]:0;
						if ($variableDiscountedPrice > 0) {
							$cond01 = "status != '2' and happyhourId = '".$happyhourId."' and productId = '".$productId."' and variableId = '".$variableId."'";
							$happyhourProduct = array('happyhourId' => $happyhourId, 'productId' => $productId, 'variableId' => $variableId, 'oldPrice' => $variablePrice, 'price' => $variableDiscountedPrice, 'status' => 0, 'updatedOn' => date('Y-m-d H:i:s'));
							$isHappyhourExist = $CI->Common_model->selRowData("vm_happyhour_product","",$cond01);
							if (!empty($isHappyhourExist))
								$queryStatus = $CI->Common_model->update("vm_happyhour_product",$happyhourProduct,$cond01);
							else{
								$happyhourProduct['addedOn']	=	date('Y-m-d H:i:s');
								$queryStatus = $CI->Common_model->insert("vm_happyhour_product", $happyhourProduct);
							}
						}
					}
				}else{
					$discountedPrice	= 	(isset($postData['discountedPrice'][$key]) && !empty($postData['discountedPrice'][$key]))?$postData['discountedPrice'][$key]:0;
					if ($discountedPrice > 0) {
						$cond01 = "status != '2' and happyhourId = '".$happyhourId."' and productId = '".$productId."'";
						$happyhourProduct = array('happyhourId' => $happyhourId, 'productId' => $productId, 'oldPrice' => $postData['price'][$key], 'price' =>$discountedPrice, 'status' => 0, 'updatedOn' => date('Y-m-d H:i:s'));
						$isHappyhourExist = $CI->Common_model->selRowData("vm_happyhour_product","",$cond01);
						if (!empty($isHappyhourExist))
							$queryStatus = $CI->Common_model->update("vm_happyhour_product",$happyhourProduct,$cond01);
						else{
							$happyhourProduct['addedOn']	=	date('Y-m-d H:i:s');
							$queryStatus = $CI->Common_model->insert("vm_happyhour_product", $happyhourProduct);
						}
					}
				}
			}
			$CI->Common_model->update("vm_happyhour_product",array('status' => 2, 'updatedOn' => date('Y-m-d H:i:s')),"status = 3");
		}
		return $queryStatus;
	}


	// get restaurant UpcommingHappyhour
	public function getUpcommingHappyhour($restaurantId){
		$CI = &get_instance();
		$text = '';
		$date = date('Y-m-d'); //today date
		$weekOfdays = array();
		for($i =1; $i <= 7; $i++){
		    $date = date('Y-m-d', strtotime('+1 day', strtotime($date)));
		    $weekOfdays[] = strtolower( date('l', strtotime($date)));
		}
		if (count($weekOfdays)) {
			foreach ($weekOfdays as $key => $day) {
				if(empty($text)){
					$happyhourData = $CI->Common_model->exequery("SELECT happyhourId, day, startTime, endTime from vm_happyhour where status = 0 AND day = '".$day."' AND restaurantId = '".$restaurantId."'",1);
					if(!empty($happyhourData)){
						return $text .= sprintf($CI->lang->line('nextHappyhour'), $CI->lang->line($day), $happyhourData->startTime, $happyhourData->endTime);
					}
				}
			}
		}
		
		return $text;
	}
	
   /* Create Authentication for user */
    function create_auth($role,$insertId,$email,$pass='',$status=0,$langSuffix='') {
    	$CI = &get_instance();	
        $responseStr = 0;

        if ($email != '') {
        // inserting authentication details
            $pass = (empty($pass))?generateStrongPassword(6,false,'lud'):$pass;
            $authData               =   array();
            $authData['emailId']    =   $email;
            $authData['password']   =   md5($pass);
            $authData['role']       =   $role;
            $authData['roleId']     =   $insertId;
		    $authData['status']     =   $status;
            $authInsert             =   $CI->Common_model->insertUnique("vm_auth", $authData);
            if ($authInsert) {
                //Send welcome email
                $settings = array();
                $settings["template"]               =   "welcome_email_tpl".$langSuffix.".html";
                $settings["email"]                  =   $email;
                $settings["subject"]                =   "Welcome to Vedmir";
                $contentarr['[[[ROLE]]]']           =   ucfirst($role);
                $contentarr['[[[USERNAME]]]']       =   $email;
                $contentarr['[[[PASSWORD]]]']       =   $pass;
                $contentarr['[[[LOGINURL]]]']       =   BASEURL."/dashboard/".$role."/login";
                $settings["contentarr"]             =   $contentarr;
                $ismailed = $this->sendMail($settings); 

                $responseStr =  $authInsert;
            }else{
                $responseStr =  0;
            }
        }
         return $responseStr;
    }


	// Upload image
	public function _doUpload($uploadSettings) {
	   $CI = &get_instance();
	   if(!is_dir($uploadSettings['upload_path'])) mkdir($uploadSettings['upload_path'], 0777, TRUE);
	   
		$CI->load->library('upload', $uploadSettings);
		$CI->upload->initialize($uploadSettings);
		if ( !$CI->upload->do_upload($uploadSettings['inputFieldName'])){
			$error = array('error' => $CI->upload->display_errors());
			return 0;	
		}
		else {
			$data = array('upload_data' => $CI->upload->data());
			if(isset($uploadSettings['createThumb']) && file_exists($uploadSettings['upload_path'] . "/". $uploadSettings['file_name'])) {
				$thumbPath = $uploadSettings['upload_path'] . "/". $uploadSettings['file_name'];
				if($uploadSettings['createThumb']) {
					$uploadSettings['thumbPath'] = $thumbPath;					
					$this->_createThumbnail($uploadSettings);
				}
			}
			return 1;
		}
	}

	//Create thumbnail
	private function _createThumbnail($fileSettings) {
		$CI = &get_instance();
		/* Create thumbnail image*/   
		$config['image_library'] 	= 'gd2';
		$config['source_image']  	= $fileSettings['thumbPath'];
		//$config['create_thumb'] 	= TRUE;
		$config['maintain_ratio'] 	= TRUE;
		$config['quality'] 	= 100;
		$config['width'] 	= $fileSettings['thumbWidth'];
		$config['height'] 	= $fileSettings['thumbHeight'];
		$CI->load->library('image_lib', $config);
		if(! $CI->image_lib->resize()) {
			return 3; //echo  $this->image_lib->display_errors();
		} else 
			return 1;
	}
	
	/* Send email */	
	public function sendMail($settings){
		$CI = &get_instance();
		$CI->load->library('email');
		$logoUrl				=		BASEURL."/system/static/frontend/images/logo.png";		
		$siteUrl				=		BASEURL;		
		$contactMail			=		'support@vedmir.com';
		$templateImageUrl		=		BASEURL.'/system/static/emailTemplates/images';		
		$vedmirTextUrl          = BASEURL."/system/static/frontend/images/vedmir_text_logo.png";
		$contactNEMail="hello@vedmir.com";
		$logoEmailNUrl=BASEURL."/system/static/emailTemplates/images/logo2.png";	
		$templateInstraUrl    =BASEURL.'/system/static/emailTemplates/images/insta_new.png';
		$templateFacebookUrl    =BASEURL.'/system/static/emailTemplates/images/facebook_new.png';
		$template 			= 	ABSSTATICPATH."/emailTemplates/".$settings["template"];
		$subject			= 	$settings["subject"];	
		$mail_content 		= 	file_get_contents($template);
		$mail_content		= 	str_replace("[[[LOGO]]]", $logoUrl, $mail_content);
		$mail_content		= 	str_replace("[[[VEDMIRTEXTURL]]]", $vedmirTextUrl, $mail_content);
		$mail_content		= 	str_replace("[[[SITEURL]]]", $siteUrl, $mail_content);
		$mail_content		= 	str_replace("[[[CONTACTMAIL]]]", $contactMail, $mail_content);
		$mail_content		= 	str_replace("[[[TEMPLATEIMAGEURL]]]", $templateImageUrl, $mail_content);
        
		$mail_content		= 	str_replace("[[[LOGON]]]", $logoEmailNUrl, $mail_content);
		$mail_content		= 	str_replace("[[[CONTACTNMAIL]]]", $contactNEMail, $mail_content);
		$mail_content		= 	str_replace("[[[TEMPLATEINSTRAURL]]]", $templateInstraUrl, $mail_content);
		$mail_content		= 	str_replace("[[[TEMPLATEIPFACEURL]]]", $templateFacebookUrl, $mail_content);
		

		if(array_key_exists('contentarr', $settings)){
			$contentarr			=		$settings["contentarr"];
			foreach($contentarr as $key=>$value){
				$mail_content		= 	str_replace($key, $value, $mail_content);
			}
		}
		$mail_content		= 	str_replace("[[[YEAR]]]",date('Y'), $mail_content);
		if(isset($settings['EXPIREDDATE']) && !empty($settings['EXPIREDDATE']))
			$mail_content		= 	str_replace("[[[EXPIREDDATE]]]",$settings['EXPIREDDATE'], $mail_content);
		if(isset($settings['couponCode']) && !empty($settings['couponCode']))
			$mail_content		= 	str_replace("[[[COUPONCODE]]]",$settings['couponCode'], $mail_content);

		$config = Array(
                'protocol' => 'smtp',
                'smtp_host' => 'smtp.gmail.com',
                'smtp_port' => 587,
                'smtp_user' => 'dsmail.vivek@gmail.com', // change it to yours
                'smtp_pass' => 'cykanxrhwepvmupl', // change it to yours
                'smtp_timeout'=>20,
                'mailtype' => 'html',
                'charset' => 'iso-8859-1',
                'wordwrap' => TRUE
               );


		$config['protocol'] = 'sendmail';
		$config['mailtype'] = 'html';
		$CI->email->initialize($config);

		$CI->email->set_newline("\r\n");
		$CI->email->from('hello@vedmir.com',"Vedmir");
			
		$CI->email->to($settings["email"]); 
		$CI->email->subject($subject);
		$CI->email->message($mail_content); 
			
		if(isset($settings['cc']) && $settings['cc']){
			$CI->email->cc(trim($settings['cc']));
		}
		if(isset($settings['bcc'])&&$settings['bcc']){
			$CI->email->bcc(trim($settings['bcc']));
		}
	  
		if(isset($settings['attachementFlag'])&&$settings['attachementFlag']){
			foreach($settings['resumeArray'] as $resumefile){	
				$CI->email->attach($resumefile);	
			}
		}
		
		if($CI->email->send())
			return TRUE;
		else
			$CI->email->print_debugger();
		
	}
	
	public function sendMailWithOutTemplate($settings){
		$CI = &get_instance();
		$CI->load->library('email');
		$logoUrl				=		BASEURL."/system/static/frontend/images/logo.png";		
		$siteUrl				=		BASEURL;		
		$contactMail			=		'info@vedmir.com';
		$templateImageUrl		=		BASEURL.'/system/static/emailTemplates/images';		
		
		$contactNEMail="knut@vedmir.com";
		$logoEmailNUrl=BASEURL."/system/static/emailTemplates/images/logo2.png";	
		$templateInstraUrl    =BASEURL.'/system/static/emailTemplates/images/insta_new.png';
		$templateFacebookUrl    =BASEURL.'/system/static/emailTemplates/images/facebook_new.png';
		
		$subject			= 	$settings["subject"];	
		
		$mail_content ="Please find attachment";

		
		
		
		$config['protocol'] = 'mail';
		$config['smtp_host'] = 'smtp.gmail.com';
		$config['smtp_port'] = '465';
		$config['smtp_timeout'] = '45';
		$config['smtp_user'] = 'hello@vedmir.com';
		$config['smtp_pass'] = 'Hel$kwoll321lo';
		$config['charset'] = 'utf-8';
		$config['newline'] = "\r\n";
		$config['mailtype'] = 'html'; 
		$config['validation'] = TRUE;     

	    $CI->email->initialize($config);
		$CI->email->set_newline("\r\n");
		$CI->email->from('hello@vedmir.com',"Vedmir");
			
		$CI->email->to($settings["email"]); 
		$CI->email->subject($subject);
		$CI->email->message($mail_content); 
			


		if(isset($settings['cc']) && $settings['cc']){
			$CI->email->cc(trim($settings['cc']));
		}
		if(isset($settings['bcc'])&&$settings['bcc']){
			$CI->email->bcc(trim($settings['bcc']));
		}
	  
		if(isset($settings['attachementFlag']) && $settings['attachementFlag']){
			foreach($settings['filePath'] as $fileBaseUrl){	
				$CI->email->attach($fileBaseUrl);	
			}
		}		
		if($CI->email->send())
			return TRUE;
		else
			$CI->email->print_debugger();
		
	}
	#--------------Send email --------------#
	public function sendmail_phpmailer($settings){
		$CI = &get_instance();
		$CI->load->library('email');
		$logoUrl				=		BASEURL."/system/static/frontend/images/logo.png";		
		$siteUrl				=		BASEURL;		
		$termUrl				=		BASEURL;		
		
		$template 			= 	ABSSTATICPATH."/emailTemplates/".$settings["template"];
		$subject			= 	$settings["subject"];	
		$mail_content 		= 	file_get_contents($template);
		$mail_content		= 	str_replace("[[[LOGO]]]", $logoUrl, $mail_content);
		$mail_content		= 	str_replace("[[[SITEURL]]]", $siteUrl, $mail_content);
		$mail_content		= 	str_replace("[[[TERMURL]]]", $termUrl, $mail_content);
		

		if(array_key_exists('contentarr', $settings)){
			$contentarr			=		$settings["contentarr"];
			foreach($contentarr as $key=>$value){
				$mail_content		= 	str_replace($key, $value, $mail_content);
			}
		}
		$mail_content		= 	str_replace("[[[YEAR]]]",date('Y'), $mail_content);

		include ('./system/static/phpmailer/PHPMailerAutoload.php');
		$mail = new PHPMailer;
		$mail->isSMTP();                    // Set mailer to use SMTP
		
		$mail->Host = 'mail.infomaniak.com';   // Specify main and backup server
		$mail->SMTPAuth = true;                               // Enable SMTP authentication
		$mail->Username = 'hello@vedmir.com';                            // SMTP username
		$mail->Password = 'hel$kwoll321lo';                           // SMTP password
		$mail->SMTPSecure = 'ssl';       

		$mail->From = isset($settings["mailFrom"]) ? $settings["mailFrom"] : 'hello@vedmir.com';
		$mail->FromName = 'Vedmir';
		$mail->addAddress($settings["email"]);// Add a recipient
		$mail->addReplyTo('hello@vedmir.com', 'Vedmir');

		$mail->WordWrap = 50;                 // Set word wrap to 50 characters
		$mail->isHTML(true);                  // Set email format to HTML
		$mail->Subject = $subject;
		$mail->Body    = $mail_content;
		return $mail->send();
	}
		/* Generate QR code */
	public function mailcheck($cgh =''){
		$CI = &get_instance();
		$CI->load->library('email');

		$CI->email->from('thatsvivek007@gmail.com', 'Vivek');
		$CI->email->to('dsmail.vivek@gmail.com');

		$CI->email->subject('Email Test');
		$CI->email->message('Testing the email class.');

		$CI->email->send();
	}
	
		/* Generate QR code */
	public function getsendmessage(){

		$CI = &get_instance();
		// require_once ('./system/static/twilio-php-master/Twilio/autoload.php');

		// Use the REST API Client to make requests to the Twilio REST API
		// use Twilio\Rest\Client;

		// Your Account SID and Auth Token from twilio.com/console
		// $sid = 'ACcdfc4cbd03f09bfde81d664131df5408';
		// $token = 'f758b714f1d05aaeead5d51ef9477532';
		// $client = new Client($sid, $token);

		// Use the client to do fun stuff like send text messages!
		// $client->messages->create(
		//     // the number you'd like to send the message to
		//     '+15558675309',
		//     array(
		//         // A Twilio phone number you purchased at twilio.com/console
		//         'from' => '+15017250604',
		//         // the body of the text message you'd like to send
		//         'body' => "Hey Jenny! Good luck on the bar exam!"
		//     )
		// );
	}
		/* Generate QR code */
	public function getQRcode($type,$data,$extsn){

		$CI = &get_instance();
		include ('./system/static/phpqrcode/qrlib.php');
		$PNG_TEMP_DIR= ABSUPLOADPATH."/".$type."_qrcodes/";
		//ofcourse we need rights to create temp dir
    	if (!file_exists($PNG_TEMP_DIR))
       		mkdir($PNG_TEMP_DIR);

		$errorCorrectionLevel = 'H';
		$matrixPointSize = 8;  
		$name = md5($type.$data);
		$filename = $PNG_TEMP_DIR.$name.'.'.$extsn;
		QRcode::$extsn($data, $filename, $errorCorrectionLevel, $matrixPointSize, 2);
		return $name.'.'.$extsn;
	}
	
	/* Set member session info in public veriable */
	public function setSessionVariables(){	
		$CI = &get_instance();	
		$CI->sessRole	=	$CI->session->userdata(PREFIX.'sessRole');
		/* check sessIsNotTour = 1 then redirect to tour page */
		if($CI->session->userdata(PREFIX.'sessAuthId') > 0) {
		
			$CI->sessAuthId		=	$CI->session->userdata(PREFIX.'sessAuthId');
			$CI->sessEmail		=	$CI->session->userdata(PREFIX."sessEmail");
			$CI->sessRoleId		=	$CI->session->userdata(PREFIX."sessRoleId");
			$CI->sessLang		=	$CI->session->userdata(PREFIX."sessLang");
			
		} else {
			redirect(DASHURL."/".$CI->sessRole."/login");
		}
	}

	public function setSessMsg($message="", $msgtype=1, $sesstype=0){
		$CI = &get_instance();		//echo 12;exit;
		$alertArray	=	array();

		if($msgtype == 1){ //Success message
			$alertArray["alertType"] = "success"; 
			$alertArray["alertMessage"] = $message; 
			$alertArray["alertMessageHtml"] = '<li onclick="javascript:$(this).fadeOut(500)" style="list-style: none;overflow: hidden; margin: 4px 0px; border-radius: 2px; border-width: 2px; border-style: solid; border-color: rgb(124, 221, 119); box-shadow: rgba(0, 0, 0, 0.1) 0px 2px 4px; background-color: rgb(188, 245, 188); color: darkgreen; cursor: pointer;" class="animated flipInX"><div class="noty_bar noty_type_success" id="noty_1432600013676628200"><div class="noty_message" style="font-size: 14px; line-height: 16px; text-align: center; padding: 10px; width: auto; position: relative;"><span class="noty_text">'.$message.'</span></div></div></li>';
		} elseif($msgtype == 2){ //Error message
			$alertArray["alertType"] = "danger"; 
			$alertArray["alertMessage"] = $message; 
			$alertArray["alertMessageHtml"] = '<li onclick="javascript:$(this).fadeOut(500)" style="list-style: none;overflow: hidden; margin: 4px 0px; border-radius: 2px; border-width: 2px; border-style: solid; border-color: rgb(226, 83, 83); box-shadow: rgba(0, 0, 0, 0.1) 0px 2px 4px; background-color: rgb(255, 129, 129); color: rgb(255, 255, 255); cursor: pointer;" class="animated flipInX"><div class="noty_bar noty_type_error" id="noty_505214828237683140"><div class="noty_message" style="font-size: 14px; line-height: 16px; text-align: center; padding: 10px; width: auto; position: relative; font-weight: bold;"><span class="noty_text">'.$message.'</span></div></div></li>';
		} elseif($msgtype == 3){ //Warning message
			$alertArray["alertType"] = "warning"; 
			$alertArray["alertMessage"] = $message; 
			$alertArray["alertMessageHtml"] = '<li onclick="javascript:$(this).fadeOut(500)" style="list-style: none;overflow: hidden; margin: 4px 0px; border-radius: 2px; border-width: 2px; border-style: solid; border-color: rgb(255, 194, 55); box-shadow: rgba(0, 0, 0, 0.1) 0px 2px 4px; background-color: rgb(255, 234, 168); color: rgb(130, 98, 0); cursor: pointer;" class="animated flipInX"><div class="noty_bar noty_type_warning" id="noty_140323524152335250"><div class="noty_message" style="font-size: 14px; line-height: 16px; text-align: center; padding: 10px; width: auto; position: relative;"><span class="noty_text"><strong>Warning!</strong> <br> '.$message.'</span></div></div></li>';
		}       
		if($sesstype==1)		
			$CI->session->set_userdata('sessMessage', $alertArray);
		else 
			$CI->session->set_flashdata('sessMessage', $alertArray);
	}	
	
	/* Show session message */
	public function showSessMsg($plainText = false){
		$CI = &get_instance();		
		$alertArray = array();
		$msg	=	"";
		if($plainText){
      if($CI->session->userdata('sessMessage') != ""){			
				$msg	=	$CI->session->userdata('sessMessage');
				$CI->session->set_userdata('sessMessage', '');			
			}else if($CI->session->flashdata('sessMessage') != ""){
				$msg	=	$CI->session->flashdata('sessMessage');
			}
		}else{
			if($CI->session->userdata('sessMessage') != ""){			
				$alertArray	=	$CI->session->userdata('sessMessage');
				$msg	=	$alertArray["alertMessageHtml"];
				$CI->session->set_userdata('sessMessage', "");			
			}else if($CI->session->flashdata('sessMessage') != ""){			
				$alertArray	=	$CI->session->flashdata('sessMessage');
				$msg	=	$alertArray["alertMessageHtml"];
			}
		}
		return $msg;
	}

	/******************** Push Notification Service **************/
	public function sendPush($msg,$data,$deviceToken, $touser = true, $isMultiple = false)	{	
	    $CI = &get_instance();

	    $API_ACCESS_KEY = ($touser) ? 'AAAAfUY9jg8:APA91bH8oWAoM_YAtGF1sIcnNelh91jcTlTkQvPrw4gE2IbteisXWyfGPg4QIgHrEDDTYLVZeWiYlMZ-wSpoBkfGS7qN6bRcRdhRvPPp2m_R5FOqEN3fCfWemqpRkcJpHlW1b1A5cAn9OEU60BaesOyZqUxmuNwz3w' : 'AAAAv1gHFeg:APA91bHbU_p3YKwPjue_sbuWzG3Sm-Sy-jfObvJDExA7D1mWOakyikHMXs_x9wZEz1H1qGr8xj8Ly7oB7nyqOTwkhhv5UV9ZaHXywtwz2Iv8u0E6bL1xdv9j-Fm-EZw5s5P_R-d5Qg1e';
	    $type=(isset($data['type']) && !empty($data['type']))?$data['type']:'';
		$title=($type!='' && $type=='massNotification')?((isset($data['title']) && !empty($data['title']))?$data['title']:"What’s hot?"):'VEDMIR';
	    $fcmMsg = array(
	        'body' => $msg,
	        'title' => $title,              
	        'sound' => "FriendlyClickButton.wav",
	        'color' => "#203E78",
	        'badge'=>0
	    );
	    if($isMultiple)
	    	$fcmFields = array(
		        'registration_ids' => $deviceToken,
		        'priority' => 'high',
		        'notification' => $fcmMsg,
		        'data' => $data
		    );
	    else
    	    $fcmFields = array(
    	        'to' => $deviceToken,
    	        'priority' => 'high',
    	        'notification' => $fcmMsg,
    	        'data' => $data
    	    );

	    $headers = array(
	        'Authorization: key=' . $API_ACCESS_KEY,
	        'Content-Type: application/json'
	    );
	     
	    $ch = curl_init();
	    curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
	    curl_setopt( $ch,CURLOPT_POST, true );
	    curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
	    curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
	    curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
	    curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fcmFields ) );
	    $result = curl_exec($ch );
	    curl_close( $ch );
	    return $result;		
	}
	// Encrypt CVV Data
	function encrypt_ccv ($cvv) {
		$finalcvv = '';
		for ($i=0; $i< strlen($cvv); $i++) {
			$finalcvv .=$cvv[$i].rand(11,99); 
		}
		return $finalcvv;
	}
	// DeCrypt CVV Data
	function decrypt_ccv ($cvv) {
		$finalcvv = '';
		if(strlen($cvv) > 5)
			$finalcvv = $cvv[0].$cvv[3].$cvv[6];
        if(isset($cvv[9]))
            $cvv .= $cvv[9];
        return $finalcvv;
		
	}

	/*Change text from one langulage to another language.*/ 
	function changeLanguage($text,$source,$target){
		require_once './system/static/domParsing/simple_html_dom.php';

		$result=''; 
		return $result; 
		try{
			if(!empty($text) && !empty($source) && !empty($target)){
				$lan=trim($source).'|'.trim($target);
				 /*Initiliza Array*/ 
				$replace_pairs = array( "\t" => '%20', " " => '%20', );
				$url='http://www.google.com/translate_t?hl=en&ie=UTF8&text='.$text.'&langpair='.$lan.'';
				$finalurl = strtr($url, $replace_pairs);
				//$data=file_get_contents();
				try {
					$html = file_get_html($finalurl);
					try {
						$result= (is_object($html->find('#result_box',0)))? $html->find('#result_box',0)->plaintext :'';
				
					}
					catch(Exception $e){ $result=''; }
				}
				catch(Exception $e){ $result=''; }
				
			}
		}
		catch(Exception $e){ $result=''; }

		return $result; 
	}

	
	
    public function uploadstripedocument($restaurantId,array $post,array $file){
    	$CI = &get_instance();
    	 $CI->load->config('stripe', TRUE);

        //get settings from config
        $current_private_key = $CI->config->item('current_private_key', 'stripe');
        $current_public_key  = $CI->config->item('current_public_key', 'stripe');
 		require_once './system/static/stripe/init.php';
        \Stripe\Stripe::setApiKey($current_private_key); 

        $isImageUploaded = $isImageRemoved = 0; $img='';
        if(isset($file['uploadImg']) && !empty($file['uploadImg'])) {
            if (isset($post['uploadFor']) && !empty($post['uploadFor'])){                    
                $uploadFor=$post['uploadFor'];
                if($uploadFor == 'document' || $uploadFor =='document_back'){
               		$restaurantData = $CI->Common_model->exequery("SELECT * from vm_restaurant where restaurantId = ".$restaurantId." and status='0'",1);
	                if (valResultSet($restaurantData) && (isset($restaurantData->stripeAccId) && !empty($restaurantData->stripeAccId))) {
	                    try{
	                         /* Upload New File */
	                        $imgname = explode(" ", $file['uploadImg']['name']);
	                        $photoToUpload =    date('Ymdhis').generateStrongPassword(5,0,'l').end($imgname);
	                        
	                        $uploadSettings = array();
	                        $uploadSettings['upload_path']      =  ABSUPLOADPATH."/restaurant_stripe_doc";
	                        $uploadSettings['allowed_types']    =   'jpeg|png';
	                        $uploadSettings['file_name']        =   $photoToUpload;
	                        $uploadSettings['inputFieldName']   =   "uploadImg";
	                        if (!$this->_doUpload($uploadSettings))
	                         	return $status = array('status'=>false, 'msg'=>'Error while upload, Only JPEG and PNG file is valid');
	                        $documentFile = UPLOADPATH."/restaurant_stripe_doc/".$photoToUpload;
	                        $document= ABSUPLOADPATH."/restaurant_stripe_doc/".$photoToUpload;
	                        
                            $f = fopen($document, 'r');
                            $fileResponse=\Stripe\FileUpload::create(
                               [
                                "purpose" => "identity_document",
                                "file" => $f
                               ],
                               ["stripe_account" => $restaurantData->stripeAccId]
                            );
                             
                            if(isset($fileResponse) && isset($fileResponse->id) && !empty($fileResponse->id)){
                                try{
                                    $fileStripeId=$fileResponse->id;
                                    $account = \Stripe\Account::retrieve($restaurantData->stripeAccId);
                                    if($uploadFor == 'document')
                                        $account->legal_entity->verification->document = $fileStripeId;
                                    else
                                        $account->legal_entity->verification->document_back = $fileStripeId;

                                    if($account->save()){

                                        $chk=false;
                                        $colName=($uploadFor=='document')? 'legal_entity_verification_document2_name':"legal_entity_verification_document_name";
                                        $fileData =  $CI->Common_model->exequery("SELECT ".$colName." as docfile from vm_restaurant_stripe_details where restaurantId= '".$restaurantId."'",1);
                                         /* Remove File If Exist */
                                        if(valResultSet($fileData->docfile)) {
                                            if($uploadFor == 'document')
                                                $CI->Common_model->update("vm_restaurant_stripe_details",array('legal_entity_verification_document'=>'','legal_entity_verification_document_name'=>''),"restaurantId = ".$restaurantId);
                                            else
                                                $CI->Common_model->update("vm_restaurant_stripe_details",array('legal_entity_verification_document2'=>'','legal_entity_verification_document2_name'=>''),"restaurantId = ".$restaurantId);

                                            if($isImageRemoved)
                                                $isUnlink = unlinkImage($fileData->docfile, 'restaurant_stripe_doc');
                                        }

                                        $getAccount = \Stripe\Account::retrieve($restaurantData->stripeAccId);
                                        $stripeStatus=$getAccount->legal_entity->verification->status;
                                        $getAccount->save();
                                        if($uploadFor == 'document')
                                            $chk=$CI->Common_model->update("vm_restaurant_stripe_details", array('legal_entity_verification_document'=>$fileStripeId,'legal_entity_verification_document_name'=>$photoToUpload,'legal_entity_verification_status'=>$stripeStatus),"restaurantId = ".$restaurantId);
                                        else
                                            $chk=$CI->Common_model->update("vm_restaurant_stripe_details", array('legal_entity_verification_document2'=>$fileStripeId,'legal_entity_verification_document2_name'=>$photoToUpload,'legal_entity_verification_status'=>$stripeStatus),"restaurantId = ".$restaurantId);
                                      	if($chk)
                                      		$status = array('status'=>TRUE, 'msg'=>$CI->lang->line('ImageRemovedAndUpdated'),'document'=>$documentFile);
                                        else
                                        	$status = array('status'=>false, 'msg'=>$CI->lang->line('internalError'));
                                    }else
                                    	$status = array('status'=>false, 'msg'=>$CI->lang->line('internalError'));
                                }catch (\Exception $e){
                                	$status = array('status'=>false, 'msg'=>$e->getMessage());   
                                }
                            }else
                            	$status = array('status'=>false, 'msg'=>$CI->lang->line('internalError')); 
	                     }catch (\Exception $e){
	                     	$status = array('status'=>false, 'msg'=>$e->getMessage());
	                     }                     
	                }
	                else
	                	$status = array('status'=>false, 'msg'=>'Not found stripe account id.Please update stripe account info.');
	            }else
                	$status = array('status'=>false, 'msg'=>'Please Provide valid name for upload.');
            }else
            	$status = array('status'=>false, 'msg'=>$post['uploadFor'], 'stats'=>'Document upload for required.');
        }else
        	$status = array('status'=>false, 'msg'=>'Document required.');

       return $status; 
        
    }

	
    public function deletestripbankaccount($restaurantId){
    	$CI = &get_instance();
    	 $CI->load->config('stripe', TRUE);

        //get settings from config
        $current_private_key = $CI->config->item('current_private_key', 'stripe');
        $current_public_key  = $CI->config->item('current_public_key', 'stripe');
 		require_once './system/static/stripe/init.php';
        \Stripe\Stripe::setApiKey($current_private_key);

        $restaurantData = $CI->Common_model->exequery("SELECT rsd.stripeAccId, rsd.payout_bank_stripe_id, rs.restaurantName FROM vm_restaurant as rs left join vm_restaurant_stripe_details as rsd on rs.restaurantId = rsd.restaurantId WHERE rs.status != '2' and rs.restaurantId = '".$restaurantId."'",1);
        if(!empty($restaurantData)) {
        	try{
	            $account = \Stripe\Account::retrieve($restaurantData->stripeAccId);
	            $isAccDeleted = $account->external_accounts->retrieve($restaurantData->payout_bank_stripe_id)->delete();
	            if(isset($isAccDeleted->delete) && $isAccDeleted->delete){
	            	$chk=$CI->Common_model->update("vm_restaurant_stripe_details", array('payout_bank_stripe_id'=>'','payout_account_holder_name'=>'','payout_account_holder_type'=>'','payout_bank_name'=>'','payout_routing_no'=>'','payout_acc_no'=>''),"restaurantId = ".$restaurantId);
	            	$status = ($chk)?array('status'=>TRUE, 'msg'=>'Deleted successfully.'):array('status'=>false, 'msg'=>'Internal server error occured.');
	            }else
	        		$status = array('status'=>false, 'msg'=>'Something is wrong');

	        }catch (\Exception $e) {
				$status = array('status'=>false, 'msg'=>$e->getMessage());
			}
        }else
        	$status = array('status'=>false, 'msg'=>'Invalid venue.');

       return $status; 
        
    }

    public function getSalesReportHistory($restaurantId=0, $startDate='', $endDate='') {
    	$CI = &get_instance();
    	if($restaurantId > 0 ){
			$lastMonthDate = ($endDate != '') ? $endDate : date('Y-m-d'); //endDate
			$startDate = ($startDate != '') ? $startDate : date('Y-m-d'); //endDate
			$restaurantOrder = $CI->Common_model->exequery("SELECT vm_order.restaurantId, vm_restaurant_stripe_details.legal_entity_business_name as legalName, sr.restaurantName, sr.address1, sr.address2, sr.country, sr.mobile, sr.email, vm_restaurant_stripe_details.legal_entity_business_tax_id as taxDetails FROM vm_order left join vm_restaurant sr on vm_order.restaurantId = sr.restaurantId left join vm_restaurant_stripe_details on sr.restaurantId = vm_restaurant_stripe_details.restaurantId WHERE paymentStatus='Completed' AND orderStatus='Completed' AND DATE(vm_order.addedOn) >= '".$startDate."' AND DATE(vm_order.addedOn) <= '".$lastMonthDate."'  AND vm_order.restaurantId ='".$restaurantId."'  AND vm_order.isTrail='0'  group by vm_order.restaurantId");
			
			if($restaurantOrder) {
				foreach($restaurantOrder as $restaurantOrderItem) {
					$totalDrinkAmount = $totalFoodAmount =  $freeDrinkAmount = $drinkQuantity = $totalFreeDrinkQuantity = $foodQuantity = 0;
					$drinkOrderBodyHtml = $previousCategoryItem = $foodOrderBodyHtml = $previousFoodCategoryItem = '';
					$getDrinkOrder = $CI->Common_model->exequery("SELECT t.*, (SELECT count(*) FROM vm_order_detail where productId=t.productId AND isFree='1' AND orderId IN (SELECT orderId FROM vm_order WHERE restaurantId = '".$restaurantOrderItem->restaurantId."' AND paymentStatus='Completed' AND orderStatus='Completed' AND DATE(addedOn) >= '".$startDate."' AND DATE(addedOn) <= '".$lastMonthDate."'  AND isTrail='0')) as welcomeDrinkCount, (SELECT SUM(quantity) FROM vm_order_detail where productId=t.productId AND orderId IN (SELECT orderId FROM vm_order WHERE restaurantId = '".$restaurantOrderItem->restaurantId."' AND paymentStatus='Completed' AND orderStatus='Completed' AND DATE(addedOn) >= '".$startDate."' AND DATE(addedOn) <= '".$lastMonthDate."'  AND isTrail='0')) as quantityItem, (SELECT SUM(subtotal) FROM vm_order_detail where productId=t.productId AND orderId IN (SELECT orderId FROM vm_order WHERE restaurantId = '".$restaurantOrderItem->restaurantId."' AND paymentStatus='Completed' AND orderStatus='Completed' AND DATE(addedOn) >= '".$startDate."' AND DATE(addedOn) <= '".$lastMonthDate."'  AND isTrail='0')) as totalSubTotal FROM (SELECT od.*, vm_product_subcategory.subcategoryName, (CASE WHEN od.isVariable = '1' THEN CONCAT(pd.productName,' (',vd.variableName, ')') ELSE  pd.productName END) as productName FROM vm_order_detail as od left join vm_variable_product as vd on (od.productId= vd.variableId and od.isVariable = '1') left join vm_product as pd on (CASE WHEN od.isVariable = '1' THEN (pd.productId = vd.productId) ELSE  (pd.productId = od.productId) END) left join vm_product_subcategory ON pd.subcategoryId = vm_product_subcategory.subcategoryId  WHERE orderId IN (SELECT orderId FROM vm_order WHERE restaurantId = '".$restaurantOrderItem->restaurantId."' AND paymentStatus='Completed' AND orderStatus='Completed' AND DATE(addedOn) >= '".$startDate."' AND DATE(addedOn) <= '".$lastMonthDate."'  AND isTrail='0') AND itemType='1' AND od.price != 0 group by od.productId) as t order by t.subcategoryName desc");
					$drinkOrderHtml = '<table width="1000" cellspacing="0" cellpadding="0" border="0" align="" style="background-color: #f2f2f2;font-size:13px;margin-top:10px;"><thead class="drinks" style="text-align: left;background-color:#a6a6a6;"><tr class="drinks"><td style="width:200px;margin:0px;"><span style="font-size: 20px; padding-left:10px;"><b>DRINKS</b></span></td><td style="width:400px;margin:0px;"><p><b>Product</b></p></td><td style="width:100px;margin:0px;"><p style="text-align:center;"><b>Qty Wecome Drinks</b></p></td><td style="width:100px;margin:0px;"><p style="text-align:center;"><b>Qty Sold</b></p></td><td style="width:100px;margin:0px;"><p style="text-align:center;"><b>CHF / Unit</b></p></td><td style="width:100px;margin:0px;text-align:right;"><p style="text-align:right;padding-right:10px;"><b>Total CHF</b></p></td></tr></thead>
								<tbody>';
					if( $getDrinkOrder ) {
						//$drinkQuantity = 0;
						foreach ($getDrinkOrder as $drinkOrderItem) {
							
							if($previousCategoryItem != $drinkOrderItem->subcategoryName) {
								$previousCategoryItem = $drinkOrderItem->subcategoryName;
								$categoryNameHtml = '<td style="margin:0px;width:200px;"><span style="padding-left:10px;">'.$drinkOrderItem->subcategoryName.'</span></td>';
							}
							else
								$categoryNameHtml = '<td colspan="1" style="margin:0px;width:200px;"></td>';
							/*if($drinkOrderItem->isFree == 1 ) {
								$freeDrinkQuantity = 1;
								$freeDrinkAmount = $freeDrinkAmount + $drinkOrderItem->price;
								$totalFreeDrinkQuantity  = $totalFreeDrinkQuantity + 1;
							}
							else
								$freeDrinkQuantity = 0;*/
						    $totalFreeDrinkQuantity  = $totalFreeDrinkQuantity + $drinkOrderItem->welcomeDrinkCount;
							$totalDrinkAmount = $totalDrinkAmount + $drinkOrderItem->totalSubTotal;
							$drinkQuantity = $drinkQuantity + $drinkOrderItem->quantityItem;
							$drinkOrderBodyHtml .= '<tr>'.$categoryNameHtml.'<td style="margin:0px; width:400px;"><p style="margin:0px; width:400px;">'.$drinkOrderItem->productName.'</p></td><td style="margin:0px;"><p style="text-align:center; margin:0px;">'.$drinkOrderItem->welcomeDrinkCount.'</p></td><td style="margin:0px;"><p style="text-align:center; margin:0px;">'.$drinkOrderItem->quantityItem.'</p></td><td style="margin:0px;"><p style="text-align:center; margin:0px;">'.str_replace('.', ',', number_format($drinkOrderItem->price,2,'.','')).'</p></td><td style="margin:0px;text-align:right;"><p style="text-align:right; margin:0px; padding-right:10px;">'.str_replace('.', ',', number_format($drinkOrderItem->totalSubTotal,2,'.','')).'</p></td></tr>';
						}
						
					}
					if($drinkOrderBodyHtml == '')
						$drinkOrderBodyHtml = '<tr><td style="margin:0px;"><span style="padding-left:10px;">NONE</span></td><td style="margin:0px; width:400px;"><p style="margin:0px; width:400px;">-</p></td><td style="margin:0px;"><p style="text-align:center; margin:0px;">0</p></td><td style="margin:0px;"><p style="text-align:center; margin:0px;">0</p></td><td style="margin:0px;"><p style="text-align:center; margin:0px;">0</p></td><td style="margin:0px;text-align:right;"><p style="text-align:right; margin:0px;padding-right:10px;">0</p></td></tr>';
					$drinkOrderHtml .= $drinkOrderBodyHtml.'<tr class="center"><td style="margin:0px;"><p style="font-weight:bold; text-align:left;padding-left:10px; margin:0px;">Sub-total </p></td><td colspan="1" style="margin:0px;"></td><td colspan="1" style="margin:0px;"><p style="font-weight:bold; text-align:center; margin:0px;">'.$totalFreeDrinkQuantity.'</p></td><td style="margin:0px;"><p style="font-weight:bold; text-align:center; margin:0px;">'.$drinkQuantity.'</p></td><td style="margin:0px;"><p style="font-weight:bold; text-align:center; margin:0px;"></p></td><td style="margin:0px;text-align:right;"><p style="font-weight:bold; text-align:right; margin:0px; padding-right:10px;">'.str_replace('.', ',', number_format($totalDrinkAmount,2,'.','')).'</p></td>
							</tr></tbody></table>';
					$getFoodOrder = $CI->Common_model->exequery("SELECT t.*, (SELECT SUM(quantity) FROM vm_order_detail where productId=t.productId AND  orderId IN (SELECT orderId FROM vm_order WHERE restaurantId = '".$restaurantOrderItem->restaurantId."' AND paymentStatus='Completed' AND orderStatus='Completed' AND DATE(addedOn) >= '".$startDate."' AND DATE(addedOn) <= '".$lastMonthDate."' AND isTrail='0' )) as quantityItem, (SELECT SUM(subtotal) FROM vm_order_detail where productId=t.productId AND  orderId IN (SELECT orderId FROM vm_order WHERE restaurantId = '".$restaurantOrderItem->restaurantId."' AND paymentStatus='Completed' AND orderStatus='Completed' AND DATE(addedOn) >= '".$startDate."' AND DATE(addedOn) <= '".$lastMonthDate."' AND isTrail='0' )) as totalSubTotal FROM (SELECT od.*, vm_product_subcategory.subcategoryName, (CASE WHEN od.isVariable = '1' THEN CONCAT(pd.productName,' (',vd.variableName, ')') ELSE  pd.productName END) as productName FROM vm_order_detail as od left join vm_variable_product as vd on (od.productId= vd.variableId and od.isVariable = '1') left join vm_product as pd on (CASE WHEN od.isVariable = '1' THEN (pd.productId = vd.productId) ELSE  (pd.productId = od.productId) END) left join vm_product_subcategory ON pd.subcategoryId = vm_product_subcategory.subcategoryId  WHERE orderId IN (SELECT orderId FROM vm_order WHERE restaurantId = '".$restaurantOrderItem->restaurantId."' AND paymentStatus='Completed' AND orderStatus='Completed' AND DATE(addedOn) >= '".$startDate."' AND DATE(addedOn) <= '".$lastMonthDate."' AND isTrail='0' ) AND itemType='0' AND od.price != 0 group by od.productId ) as t order by t.subcategoryName desc");
					$foodOrderHtml = '<table width="1000" cellspacing="0" cellpadding="0" border="0" align="" style="background-color: #f2f2f2;font-size:13px;margin-top:10px;"><thead class="drinks" style="text-align: left;background-color:#a6a6a6;"><tr class="drinks"><td style="width:200px;margin:0px;"><span style="font-size: 20px; padding-left:10px;"><b>FOOD</b></span></td><td style="width:400px;margin:0px;"><p><b>Product</b></p></td><td style="width:100px;margin:0px;"><p style="text-align:center;"><b>Qty Wecome Drinks</b></p></td><td style="width:100px;margin:0px;"><p style="text-align:center;"><b>Qty Sold</b></p></td><td style="width:100px;margin:0px;"><p style="text-align:center;"><b>CHF / Unit</b></p></td><td style="width:100px;margin:0px;text-align:right;"><p style="text-align:right;padding-right:10px;"><b>Total CHF</b></p></td></tr></thead>
								<tbody>';
					if( $getFoodOrder ) {
						//$drinkQuantity = 0;
						foreach ($getFoodOrder as $foodOrderItem) {
							
							if($previousFoodCategoryItem != $foodOrderItem->subcategoryName) {
								$previousFoodCategoryItem = $foodOrderItem->subcategoryName;
								$categoryNameHtml = '<td style="margin:0px;width:200px;"><span style="padding-left:10px; margin:0px;">'.$foodOrderItem->subcategoryName.'</span></td>';
							}
							else
								$categoryNameHtml = '<td colspan="1" style="margin:0px;"></td>';
							
							$totalFoodAmount = $totalFoodAmount + $foodOrderItem->totalSubTotal;
							$foodQuantity = $foodQuantity + $foodOrderItem->quantityItem;
							$foodOrderBodyHtml .= '<tr>'.$categoryNameHtml.'<td style="margin:0px; width:400px;"><p style="margin:0px; width:400px;">'.$foodOrderItem->productName.'</p></td><td style="margin:0px;"><p style="text-align:center; margin:0px;">0</p></td><td style="margin:0px;"><p style="text-align:center; margin:0px;">'.$foodOrderItem->quantityItem.'</p></td><td style="margin:0px;"><p style="text-align:center; margin:0px;">'.str_replace('.', ',', number_format($foodOrderItem->price,2,'.','')).'</p></td><td style="margin:0px;text-align:right;"><p style="text-align:right; margin:0px;padding-right:10px;">'.str_replace('.', ',', number_format($foodOrderItem->totalSubTotal,2,'.','')).'</p></td></tr>';
						}
						
					}
					if($foodOrderBodyHtml == '')
						$foodOrderBodyHtml = '<tr><td style="margin:0px;width:100px;"><span style="padding-left:10px;">NONE</span></td><td style="margin:0px;width:400px;"><p style="margin:0px;400px;">-</p></td><td style="margin:0px;width:100px;"><p style="text-align:center; margin:0px;">0</p></td><td style="margin:0px;width:100px;"><p style="text-align:center; margin:0px;">0</p></td><td style="margin:0px; width:100px;"><p style="text-align:center; margin:0px;">0</p></td><td style="margin:0px; width:100px;"><p style="text-align:right; margin:0px;padding-right:10px;">0</p></td></tr>';
					$foodOrderHtml .= $foodOrderBodyHtml.'<tr class="center"><td style="margin:0px;"><p style="font-weight:bold;text-align:left; padding-left:10px; margin:0px;">Sub-total </p></td><td colspan="1"></td><td style="margin:0px;"><p style="font-weight:bold;text-align:center; margin:0px;"></p></td><td style="margin:0px;"><p style="font-weight:bold;text-align:center; margin:0px;">'.$foodQuantity.'</p></td><td colspan="1"></td><td style="margin:0px;text-align:right;"><p style="font-weight:bold;text-align:right; margin:0px;padding-right:10px;">'.str_replace('.', ',', number_format($totalFoodAmount,2,'.','')).'</p></td></tr></tbody></table>';
					$totalInivoceAmount = $totalDrinkAmount + $totalFoodAmount;
					$vedmirCommession = 0.05 * $totalInivoceAmount;
					$vedmirCommessionTax = $vedmirCommession - ($vedmirCommession/1.077);
					
					$payoutAmount = $totalInivoceAmount - $vedmirCommession;
					$taxAmount = $payoutAmount - ($payoutAmount/1.077);
					$invoiceContent = '<table width="1000" cellspacing="0" cellpadding="0" border="0"  style="background-color: #fce4d6;font-size: 13px;margin-top:10px;padding-right:10px;"><tbody><tr class="center"><td style="margin:0px; width:500px;"><p style="padding-left:10px; margin:0px;">Drinks revenue </p></td><td style="margin:0px; width:500px;text-align:right;"><p style="padding-right:10px; text-align:right;">CHF '.str_replace('.', ',', number_format($totalDrinkAmount,2,'.','')).'</p></td></tr><tr class="center"><td style="margin:0px; width:500px;"><p style="padding-left:10px; margin:0px;">Food revenue</p></td><td style="margin:0px; width:500px;text-align:right;"><p style="padding-right:10px; text-align:right;">CHF '.str_replace('.', ',', number_format($totalFoodAmount,2,'.','')).'</p></td></tr><tr class="center"><td style="margin:0px; width:500px;"><p style="font-size:17px;padding-left:10px; margin:0px;">TOTAL REVENUE (Tax Incl.)</p></td><td style="margin:0px; width:500px;text-align:right;"><p style="font-size:17px; padding-right:10px; text-align:right;">CHF '.str_replace('.', ',', number_format($totalInivoceAmount,2,'.','')).'</p></td></tr></tbody></table><table width="1000" cellspacing="0" cellpadding="0" border="0"  style="background-color: #a6a6a6;font-size: 13px; padding-right:10px;"><tbody><tr class="center"><td style="margin:0px; width:500px;"><p style="padding-left:10px; margin:0px;">Vedmir Commission</p></td><td style="margin:0px; width:500px;text-align:right;"><p style="padding-right:10px; text-align:right;">CHF '.str_replace('.', ',', number_format($vedmirCommession,2,'.','')).'</p></td></tr><tr class="center"><td style="margin:0px; width:500px;"><p style="padding-left:10px; margin:0px;">(Including 7,7% TVA on Commission)</p></td><td style="margin:0px; width:500px;text-align:right;"><p style="padding-right:10px; text-align:right;">CHF '.str_replace('.', ',', number_format($vedmirCommessionTax,2,'.','')).'</p></td></tr></tbody></table><table width="1000" cellspacing="0" cellpadding="0" border="0"  style="background-color: #ed7d31;font-size: 13px; padding-right:10px;"><tbody><tr class="center"><td style="margin:0px; width:500px; font-weight:bold;"><p style="font-size:17px; padding-left:10px; margin:0px;">TOTAL PAYOUT</p></td><td style="margin:0px; width:500px;text-align:right;font-weight:bold;"><p style="font-size:17px; text-align:right; padding-right:10px;">CHF '.str_replace('.', ',', number_format($payoutAmount,2,'.','')).'</p></td></tr><tr class="center"><td style="margin:0px; width:500px;"><p style="font-size:17px; padding-left:10px; margin:0px;">(including 7,7% TVA)</p></td><td style="margin:0px; width:500px;text-align:right;"><p style="font-size:17px; text-align:right; padding-right:10px;">CHF '.str_replace('.', ',', number_format($taxAmount,2,'.','')).'</p></td></tr></tbody></table>';
					$settings = array();
	                $settings["template"]               =   "sales_tpl.html";
	                $settings["email"]                  =   $restaurantOrderItem->email;//"charles@vedmir.com,dsmail.shivank@gmail.com,dsmail.arshad@gmail.com";
	                $settings["subject"]                =   "Payout Summary ".date('F Y', strtotime($lastMonthDate));
	                $contentarr['[[[VenueName]]]']           =   $restaurantOrderItem->restaurantName;
	                $contentarr['[[[SATRTDATE]]]']           =   $startDate;
	                $contentarr['[[[ENDDATE]]]']           =   $lastMonthDate;
	                $contentarr['[[[PayoutNumber]]]']       =   'SK'.$restaurantOrderItem->restaurantId.date('y', strtotime($startDate)).date('m', strtotime($startDate));
	                $contentarr['[[[DATEOFISSUE]]]']       =   DATE('Y-m-d');
	                $contentarr['[[[PAYOUTMONTH]]]']       =   DATE('F Y', strtotime($lastMonthDate));
	                $contentarr['[[[PAYOUTCONTENT]]]']           =  $drinkOrderHtml.$foodOrderHtml.$invoiceContent;
	                $contentarr['[[[LEGALNAME]]]']       =   $restaurantOrderItem->legalName;
	                $contentarr['[[[ADDRESSLINE1]]]']       =   $restaurantOrderItem->address1;
	                $contentarr['[[[ADDRESSLINE2]]]']       =   $restaurantOrderItem->address2;
	                $contentarr['[[[COUNTRY]]]']           =   $restaurantOrderItem->country;
	                $contentarr['[[[PHONE]]]']       =   $restaurantOrderItem->mobile;
	                $contentarr['[[[TAXNUMBER]]]']       =   $restaurantOrderItem->taxDetails;
	                $contentarr['[[[LOGOBLACK]]]']		=  BASEURL."/system/static/frontend/images/logo_black.png";
	                $settings["contentarr"]             =   $contentarr;
	                $template 			= 	ABSSTATICPATH."/emailTemplates/".$settings["template"];
					$subject			= 	(!isset($settings["subject"]))?"Subject":$settings["subject"];
					$mail_content 		= 	file_get_contents($template);
					$logoUrl				=		BASEURL."/system/static/frontend/images/logo.png";		
					$siteUrl				=		BASEURL;		
					$termUrl				=		BASEURL;
					$mail_content		= 	str_replace("[[[LOGO]]]", $logoUrl, $mail_content);
					$mail_content		= 	str_replace("[[[SITEURL]]]", $siteUrl, $mail_content);
					$mail_content		= 	str_replace("[[[TERMURL]]]", $termUrl, $mail_content);
					

					if(array_key_exists('contentarr', $settings)){
						$contentarr			=		$settings["contentarr"];
						foreach($contentarr as $key=>$value){
							$mail_content		= 	str_replace($key, $value, $mail_content);
						}
					}
					return array('valid' => true, 'dataHtml' => $mail_content, 'showButton' => true);					
				}
				
			}
			else
				return array('valid' => false, 'dataHtml' => '', 'showButton' => false);
		}
		else
			return array('valid' => false, 'dataHtml' => '', 'showButton' => false);
    }
    public function createStripeDiscountCoupon($couponData){
        $CI = &get_instance();
    	$CI->load->config('stripe', TRUE);

        //get settings from config
        $current_private_key = $CI->config->item('current_private_key', 'stripe');
        $current_public_key  = $CI->config->item('current_public_key', 'stripe');
 		require_once './system/static/stripe/init.php';
        \Stripe\Stripe::setApiKey($current_private_key);
    	if(isset($couponData['userId']) && !empty($couponData['userId']) && isset($couponData['amount']) && !empty($couponData['amount'])) {
    		$couponCode = $this->GenerateUniqueCouponCode();
    		try {
    			$couponData['duration'] = (isset($couponData['duration']) && !empty($couponData['duration'])) ? $couponData['duration'] : 'once';
    			$couponData['discountType'] = (isset($couponData['discountType']) && !empty($couponData['discountType'])) ? $couponData['discountType'] : 0;
    			$couponData['counponName'] = $couponCode;
    			$couponData['couponCode'] = $couponCode;
    			$discountItem = [		                       
		            "id" => $couponCode, 
		            "name" => $couponCode,
		            "duration" => $couponData['duration']
		        ];
		        if($couponData['discountType'] == 0) {
		        	$discountItem['amount_off'] = str_replace(',', '',number_format($couponData['amount'], 2)) * 100;
		        	$discountItem['currency'] = 'CHF';
		        }
		        else 
		        	$discountItem['percent'] = ($couponData['amount'] <= 100 ) ? $couponData['amount'] : 100;
    			$couponDetails = \Stripe\Coupon::create($discountItem);
    			$isTrail = ($couponDetails->livemode) ? 0 : 1;
    			$couponData['isTrail'] = $isTrail;
    			$couponData['addedOn'] = date('Y-m-d H:i:s');
    			$referalStripeCouponId = $CI->Common_model->insertUnique("vm_user_referal_stripe_coupon", $couponData);
    			if( $referalStripeCouponId ) {
    				$couponData['referalStripeCouponId'] = $referalStripeCouponId;
    				return array('valid' => true, 'data' => $couponData);
    			}
    			else
    				return array('valid' => false, 'errorMessage' => $e->getMessage());
    		}
    		catch(Exception $e) {
    			return array('valid' => false, 'errorMessage' => $e->getMessage());
    		}
    	}
    	else
    		return array('valid' => false, 'errorMessage' => "UserId Required");
    }
    public function createCouponCode ($len = 10) {
        $alphabet = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < $len; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode('',$pass);
    }
    public function GenerateUniqueCouponCode ($specifickey = '') {
    	$CI = &get_instance();
        $couponCode = $this->createCouponCode(8);
        $checkcode = $CI->Common_model->exequery("SELECT couponCode, referalStripeCouponId FROM vm_user_referal_stripe_coupon WHERE couponCode = '".$couponCode."'");
        if($checkcode) 
            return $this->GenerateUniqueCouponCode();
        else
            return $couponCode;
    }
			
}