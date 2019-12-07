<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Created by Dream Steps Pvt Ltd
 * Created on 30 Nov 2019
 * Vedmir -  module
**/

class Restaurant extends CI_Controller {

	

	public $menu		= 2;

	public $subMenu		= 21;

	public $subSubMenu		= 0;

	public $outputdata 	= array();
	public $langSuffix = '';

	

	public function __construct(){

		parent::__construct();

		//Check login authentication & set public veriables

		$this->session->set_userdata(PREFIX.'sessRole', "admin");

		$this->common_lib->setSessionVariables();

		$this->load->helper('file');
		$this->langSuffix = $this->lang->line('langSuffix');	

	}

		
	public function add_category($categoryId = 0){
		$this->menu		=	2;
		$this->subMenu	=	21;
		$langSuffix = $this->lang->line('langSuffix');
		if(isset($_POST) && !empty($_POST['categoryName'.$langSuffix])) {
			$status = '';
			$insertData   =  array();

			if($categoryId > 0)
				$isExistCond = array('categoryId !=' => $categoryId);

			$isExistCond["categoryName$langSuffix"] = trim($_POST['categoryName'.$langSuffix]);
			$isExist = $this->Common_model->selRowData("vm_restaurant_category","",$isExistCond);
			$status = 'alreadyExist';

			if(!$isExist){			
				$_POST['categoryName'] = (isset($_POST['categoryName']) && empty($_POST['categoryName']) && $this->sessLang != 'english') ? $this->common_lib->changeLanguage($_POST['categoryName'.$langSuffix],$this->lang->line('lang'),'en') : $_POST['categoryName'] ;

				$_POST['categoryName_fr'] = (isset($_POST['categoryName_fr']) && empty($_POST['categoryName_fr']) && $this->sessLang != 'french') ? $this->common_lib->changeLanguage($_POST['categoryName'.$langSuffix],$this->lang->line('lang'),'fr') : $_POST['categoryName_fr'] ; 

				$_POST['categoryName_gr'] = (isset($_POST['categoryName_gr']) && empty($_POST['categoryName_gr']) && $this->sessLang != 'german') ? $this->common_lib->changeLanguage($_POST['categoryName'.$langSuffix],$this->lang->line('lang'),'de') : $_POST['categoryName_gr'] ; 

				$_POST['categoryName_it'] = (isset($_POST['categoryName_it']) && empty($_POST['categoryName_it']) && $this->sessLang != 'italian') ? $this->common_lib->changeLanguage($_POST['categoryName'.$langSuffix],$this->lang->line('lang'),'it') : $_POST['categoryName_it'] ; 


				$insertData['categoryName']	 	=   trim($_POST['categoryName']);
				$insertData['categoryName_fr']	=   trim($_POST['categoryName_fr']);
				$insertData['categoryName_gr']	=   trim($_POST['categoryName_gr']);
				$insertData['categoryName_it']	=   trim($_POST['categoryName_it']);
				$insertData['updatedOn']	 	=   date('Y-m-d H:i:s');

				if($categoryId > 0){
					$slug = $this->common_lib->create_unique_slug(trim($_POST['categoryName']),"vm_restaurant_category","categoryName",$categoryId,"categoryId",$counter=0);
					$insertData['slug']			 =   $slug;
					$cond 	=	"categoryId = ".$categoryId;
					$updatetStatus 		= 	$this->Common_model->update("vm_restaurant_category", $insertData,$cond);
					if($updatetStatus)
						$status = 'updated';
				}else if($categoryId == 0 && !$isExist){
					$slug = $this->common_lib->create_unique_slug(trim($_POST['categoryName']),"vm_restaurant_category","categoryName",0,"categoryId",$counter=0);
					$insertData['slug']			 =   $slug;
					$insertData['addedOn']	 	 =   date('Y-m-d H:i:s');
					$updatetStatus 		= 	$this->Common_model->insert("vm_restaurant_category", $insertData);
					if($updatetStatus)
						$status = 'added';
				}
			}

			if($status == 'added')
				$this->common_lib->setSessMsg($this->lang->line('addNewRecord'), 1);
			else if($status == 'updated')
				$this->common_lib->setSessMsg($this->lang->line('editRecord'), 1);
			else if($status == 'alreadyExist')
				$this->common_lib->setSessMsg($this->lang->line('alreadyExist'), 2);
			else
				$this->common_lib->setSessMsg($this->lang->line('dbError'), 2);
		}

		if ($categoryId > 0)
			$this->outputdata['categoryData'] = $this->Common_model->selRowData("vm_restaurant_category","","categoryId = ".$categoryId);

		$this->load->viewD('admin/restaurant_category_add_view', $this->outputdata);
	}
		

	// category-listing view
	public function category_list(){	
		$this->menu		=	2;
		$this->subMenu	=	22;			
		$this->load->viewD('admin/restaurant_category_list_view', $this->outputdata);
	}


	// add restaurant
	public function add_restaurant($restaurantId = 0){

		$this->menu		=	2;

		$this->subMenu	=	23;

		$langSuffix = $this->lang->line('langSuffix');
		if(isset($_POST) && !empty($_POST['txtrestaurantName'])) {

			// $this->common_lib->updateVenueTiming($restaurantId , $_POST);
			// // v3print($_POST);
			// 				exit;
			$status = '';
			$insertData   =  array();

			if($restaurantId > 0)
                $this->db->where('restaurantId !=', $restaurantId);
            $this->db->where('status !=', '2')
            	->group_start()
            		->where('restaurantName', trim($_POST['txtrestaurantName']))
            		->or_where('email', trim($_POST['txtemail']))
            	->group_end();
            $qry = $this->db->get('vm_restaurant');
            $isExist = $qry->row();

			$status = 'alreadyExist';

			if(!$isExist){	
				
				$_POST['txtabout'] = (isset($_POST['txtabout']) && empty($_POST['txtabout']) && $this->sessLang != 'english') ? $this->common_lib->changeLanguage($_POST['txtabout'.$langSuffix],$this->lang->line('lang'),'en') : $_POST['txtabout'] ;

				$_POST['txtabout_fr'] = (isset($_POST['txtabout_fr']) && empty($_POST['txtabout_fr']) && $this->sessLang != 'french') ? $this->common_lib->changeLanguage($_POST['txtabout'.$langSuffix],$this->lang->line('lang'),'fr') : $_POST['txtabout_fr'] ; 

				$_POST['txtabout_gr'] = (isset($_POST['txtabout_gr']) && empty($_POST['txtabout_gr']) && $this->sessLang != 'german') ? $this->common_lib->changeLanguage($_POST['txtabout'.$langSuffix],$this->lang->line('lang'),'de') : $_POST['txtabout_gr'] ; 

				$_POST['txtabout_it'] = (isset($_POST['txtabout_it']) && empty($_POST['txtabout_it']) && $this->sessLang != 'italian') ? $this->common_lib->changeLanguage($_POST['txtabout'.$langSuffix],$this->lang->line('lang'),'it') : $_POST['txtabout_it'] ; 


				$_POST['txtaddress1'] = (isset($_POST['txtaddress1']) && empty($_POST['txtaddress1']) && $this->sessLang != 'english') ? $this->common_lib->changeLanguage($_POST['txtaddress1'.$langSuffix],$this->lang->line('lang'),'en') : $_POST['txtaddress1'] ;

				$_POST['txtaddress1_fr'] = (isset($_POST['txtaddress1_fr']) && empty($_POST['txtaddress1_fr']) && $this->sessLang != 'french') ? $this->common_lib->changeLanguage($_POST['txtaddress1'.$langSuffix],$this->lang->line('lang'),'fr') : $_POST['txtaddress1_fr'] ; 

				$_POST['txtaddress1_gr'] = (isset($_POST['txtaddress1_gr']) && empty($_POST['txtaddress1_gr']) && $this->sessLang != 'german') ? $this->common_lib->changeLanguage($_POST['txtaddress1'.$langSuffix],$this->lang->line('lang'),'de') : $_POST['txtaddress1_gr'] ; 

				$_POST['txtaddress1_it'] = (isset($_POST['txtaddress1_it']) && empty($_POST['txtaddress1_it']) && $this->sessLang != 'italian') ? $this->common_lib->changeLanguage($_POST['txtaddress1'.$langSuffix],$this->lang->line('lang'),'it') : $_POST['txtaddress1_it'] ; 
 

				$_POST['selCountry'] = (isset($_POST['selCountry']) && empty($_POST['selCountry']) && $this->sessLang != 'english') ? $this->common_lib->changeLanguage($_POST['selCountry'.$langSuffix],$this->lang->line('lang'),'en') : $_POST['selCountry'] ;

				$_POST['selCountry_fr'] = (isset($_POST['selCountry_fr']) && empty($_POST['selCountry_fr']) && $this->sessLang != 'french') ? $this->common_lib->changeLanguage($_POST['selCountry'.$langSuffix],$this->lang->line('lang'),'fr') : $_POST['selCountry_fr'] ; 

				$_POST['selCountry_gr'] = (isset($_POST['selCountry_gr']) && empty($_POST['selCountry_gr']) && $this->sessLang != 'german') ? $this->common_lib->changeLanguage($_POST['selCountry'.$langSuffix],$this->lang->line('lang'),'de') : $_POST['selCountry_gr'] ; 

				$_POST['selCountry_it'] = (isset($_POST['selCountry_it']) && empty($_POST['selCountry_it']) && $this->sessLang != 'italian') ? $this->common_lib->changeLanguage($_POST['selCountry'.$langSuffix],$this->lang->line('lang'),'it') : $_POST['selCountry_it'] ; 

				$_POST['selCity'] = (isset($_POST['selCity']) && empty($_POST['selCity']) && $this->sessLang != 'english') ? $this->common_lib->changeLanguage($_POST['selCity'.$langSuffix],$this->lang->line('lang'),'en') : $_POST['selCity'] ;

				$_POST['selCity_fr'] = (isset($_POST['selCity_fr']) && empty($_POST['selCity_fr']) && $this->sessLang != 'french') ? $this->common_lib->changeLanguage($_POST['selCity'.$langSuffix],$this->lang->line('lang'),'fr') : $_POST['selCity_fr'] ; 

				$_POST['selCity_gr'] = (isset($_POST['selCity_gr']) && empty($_POST['selCity_gr']) && $this->sessLang != 'german') ? $this->common_lib->changeLanguage($_POST['selCity'.$langSuffix],$this->lang->line('lang'),'de') : $_POST['selCity_gr'] ; 

				$_POST['selCity_it'] = (isset($_POST['selCity_it']) && empty($_POST['selCity_it']) && $this->sessLang != 'italian') ? $this->common_lib->changeLanguage($_POST['selCity'.$langSuffix],$this->lang->line('lang'),'it') : $_POST['selCity_it'] ; 

				$_POST['selState'] = (isset($_POST['selState']) && empty($_POST['selState']) && $this->sessLang != 'english') ? $this->common_lib->changeLanguage($_POST['selState'.$langSuffix],$this->lang->line('lang'),'en') : $_POST['selState'] ;

				$_POST['selState_fr'] = (isset($_POST['selState_fr']) && empty($_POST['selState_fr']) && $this->sessLang != 'french') ? $this->common_lib->changeLanguage($_POST['selState'.$langSuffix],$this->lang->line('lang'),'fr') : $_POST['selState_fr'] ; 

				$_POST['selState_gr'] = (isset($_POST['selState_gr']) && empty($_POST['selState_gr']) && $this->sessLang != 'german') ? $this->common_lib->changeLanguage($_POST['selState'.$langSuffix],$this->lang->line('lang'),'de') : $_POST['selState_gr'] ; 

				$_POST['selState_it'] = (isset($_POST['selState_it']) && empty($_POST['selState_it']) && $this->sessLang != 'italian') ? $this->common_lib->changeLanguage($_POST['selState'.$langSuffix],$this->lang->line('lang'),'it') : $_POST['selState_it'] ;				

				$status = 'success';
				$queryData   =  array();
				$queryData['category']	 	 	=   (isset($_POST['category']) && !empty($_POST['category'])) ? implode(',',$_POST['category']) : '';
				$queryData['restaurantName']	=   trim($_POST['txtrestaurantName']);
				$queryData['restaurantName_fr']	=   trim($_POST['txtrestaurantName']);
				$queryData['restaurantName_gr']	=   trim($_POST['txtrestaurantName']);
				$queryData['restaurantName_it']	=   trim($_POST['txtrestaurantName']);
				$queryData['since']	 			=   trim($_POST['txtsince']);
				$queryData['about']	 			=   trim($_POST['txtabout']);
				$queryData['about_fr']	 		=   trim($_POST['txtabout_fr']);
				$queryData['about_gr']	 		=   trim($_POST['txtabout_gr']);
				$queryData['about_it']	 		=   trim($_POST['txtabout_it']);
				$queryData['openCloseType']	 	=   trim($_POST['openCloseType']);
				$queryData['website']	 		=   trim($_POST['txtwebsite']);
				$queryData['iframeUrl']	 		=   trim($_POST['txtiframe']);
				$queryData['facebookPageUrl']	=   trim($_POST['txtfacebookPageUrl']);
				$queryData['instagramPageUrl']	=   trim($_POST['txtinstagramPageUrl']);
				$queryData['contactName']	 	=   trim($_POST['txtcontactName']);
				$queryData['contactName_fr']	=   trim($_POST['txtcontactName']);
				$queryData['contactName_gr']	=   trim($_POST['txtcontactName']);
				$queryData['contactName_it']	=   trim($_POST['txtcontactName']);
				$queryData['mobile']	 		=   trim($_POST['txtmobile']);
				$queryData['venuePhone']	 	=   trim($_POST['venuePhone']);
				$queryData['email']	 			=   trim($_POST['txtemail']);
				$queryData['address1']	 		=   trim($_POST['txtaddress1']);
				$queryData['address1_fr']	 	=   trim($_POST['txtaddress1_fr']);
				$queryData['address1_gr']	 	=   trim($_POST['txtaddress1_gr']);
				$queryData['address1_it']	 	=   trim($_POST['txtaddress1_it']);
				$queryData['city']	 			=   trim($_POST['selCity']);
				$queryData['city_fr']	 		=   trim($_POST['selCity_fr']);
				$queryData['city_gr']	 		=   trim($_POST['selCity_gr']);
				$queryData['city_it']	 		=   trim($_POST['selCity_it']);
				$queryData['state']	 			=   trim($_POST['selState']);
				$queryData['state_fr']	 		=   trim($_POST['selState_fr']);
				$queryData['state_gr']	 		=   trim($_POST['selState_gr']);
				$queryData['state_it']	 		=   trim($_POST['selState_it']);
				$queryData['country']	 		=   trim($_POST['selCountry']);
				$queryData['country_fr']	 	=   trim($_POST['selCountry_fr']);
				$queryData['country_gr']	 	=   trim($_POST['selCountry_gr']);
				$queryData['country_it']	 	=   trim($_POST['selCountry_it']);
				$queryData['postalCode']	 	=   trim($_POST['txtpostalCode']);
				$queryData['lat']	 			=   trim($_POST['latitude']);
				$queryData['lang']	 			=   trim($_POST['longitude']);
				$queryData['tax']	 			=   trim($_POST['txttax']);
				$queryData['rating']	 		=   trim($_POST['txtrating']);
				$queryData['password']	 		=   trim($_POST['txtpassword']);
				$queryData['updatedOn']	 		=   date('Y-m-d H:i:s');
				if(isset($_FILES['restaurantLogo']) && !empty($_FILES['restaurantLogo'])) {
					if(is_uploaded_file($_FILES['restaurantLogo']['tmp_name']) != "") {
						$logoToUpload = 	date('Ymdhis').str_replace(' ', '_', $_FILES['restaurantLogo']['name']);
						$uploadSettings = array();
						$uploadSettings['upload_path']   	=	ABSUPLOADPATH."/restaurant_images/";
						$uploadSettings['allowed_types'] 	=	'gif|jpg|png';
						$uploadSettings['file_name']	  	= 	$logoToUpload;
						$uploadSettings['inputFieldName']  	=  	"restaurantLogo";

						$fileUpload = $this->common_lib->_doUpload($uploadSettings);

						if ($fileUpload) 
							$queryData['logo']		=   $logoToUpload;

					}
				}

				if($restaurantId > 0){
					$isfileUploaded = $this->upload_icon();

					if ($isfileUploaded['status'] == 2 || $isfileUploaded['status'] == 3)
						$status = 'uploadImageError';

					else if($isfileUploaded['status'] == 1  && ($isfileUploaded['fileName'] != '' || $isfileUploaded['gallaryfileName'] != '')){

						if ($isfileUploaded['fileName'] != '') {

							$queryData['img']	 =   $isfileUploaded['fileName'];

							$status = 'Feature image uploaded';

						}

						if ($isfileUploaded['gallaryfileName'] != '') {

							$imgArry = array_filter(explode(',', $isfileUploaded['gallaryfileName'] ));
							foreach ($imgArry as $value) {
								$insertGallaryData = array();
								$insertGallaryData['image']	 		=   $value;
								$insertGallaryData['restaurantId']	=   $restaurantId;
								$insertGallaryStatus 			 	= 	$this->Common_model->insert("vm_restaurant_gallary_img", $insertGallaryData);

								if($insertGallaryStatus){
									$status = 'Gallary images added';
								}

							}

						}

					}

					if ($status != 'uploadImageError') {

						$slug = $this->common_lib->create_unique_slug(trim($_POST['txtrestaurantName']),"vm_restaurant","restaurantName",$restaurantId,"restaurantId",$counter=0);
						$queryData['slug']			 =   $slug;
						$isQRExist = $this->Common_model->selRowData("vm_restaurant","qrcode"," restaurantId = '".$restaurantId."'");
						if (!valResultSet($isQRExist) || $isQRExist->qrcode == ''){
							$data = md5(100000+$restaurantId);
							$QRname = $this->common_lib->getQRcode('restaurant',$data,'png');
							if ($QRname !='')
								$queryData['qrcode'] =  $QRname;

						}

						$cond 	=	"restaurantId = ".$restaurantId;
						$updatetStatus 		= 	$this->Common_model->update("vm_restaurant", $queryData,$cond);
						if($updatetStatus){
							$this->common_lib->updateVenueTiming($restaurantId , $_POST);
							$this->Common_model->update("vm_auth", array('password'=>md5(trim($_POST['txtpassword'])),'emailId'=>trim($_POST['txtemail']))," role = 'restaurant' and roleId=".$restaurantId);
							$status = 'updated';
						}

					}




				}else{
					$isfileUploaded = $this->upload_icon();
					$imgArry = array();
					if ($isfileUploaded['status'] == 2 || $isfileUploaded['status'] == 3)
						$status = 'uploadImageError';

					else if($isfileUploaded['status'] == 1  && ($isfileUploaded['fileName'] != '' || $isfileUploaded['gallaryfileName'] != '')){
						if ($isfileUploaded['fileName'] != '') {
							$queryData['img']	 =   $isfileUploaded['fileName'];
							$status = 'Feature image uploaded';
						}

						if ($isfileUploaded['gallaryfileName'] != '') {
							$imgArry = array_filter(explode(',', $isfileUploaded['gallaryfileName'] ));

						}

					}


					if ($status != 'uploadImageError') {
						$slug = $this->common_lib->create_unique_slug(trim($_POST['txtrestaurantName']),"vm_restaurant","restaurantName",0,"restaurantId",$counter=0);

						$queryData['slug']			 =   $slug;
						$queryData['addedOn']	 	 =   date('Y-m-d H:i:s');
						$insertStatus 		= 	$this->Common_model->insertUnique("vm_restaurant", $queryData);

						if($insertStatus){

							$this->common_lib->updateVenueTiming($insertStatus , $_POST);
							$checkMenucanvas = $this->Common_model->exequery("SELECT * FROM vm_canvas_category where status='0'");
							if($checkMenucanvas) {
								$categoryOrderNo = 1;
								foreach( $checkMenucanvas as $menuCanvasData) {
									$categoryId = ($menuCanvasData->type == 1) ? 4 : 5;
									$slug = $this->common_lib->create_unique_slug(trim($menuCanvasData->categoryName),"vm_product_subcategory","subcategoryName",0,"subcategoryId",$counter=0);
									$subcategoryId = $this->Common_model->insertUnique("vm_product_subcategory",array("restaurantId" =>$insertStatus, "categoryId" =>$categoryId, "subcategoryName" =>$menuCanvasData->categoryName, "subcategoryName_fr" => $menuCanvasData->categoryName_fr,"subcategoryName_gr" => $menuCanvasData->categoryName_gr, "subcategoryName_it" => $menuCanvasData->categoryName_it, "orderNo" => $categoryOrderNo, "slug" => $slug, "addedOn" => date('Y-m-d H:i:s'), "updatedOn" => date('Y-m-d H:i:s'), "status" => 0 ));
									$categoryOrderNo++;
									$checkCanvasSubcategory = $this->Common_model->exequery("SELECT * FROM vm_canvas_subcategory where status='0' AND categoryId='".$menuCanvasData->categoryId."'");
									if($checkCanvasSubcategory) {
										$subCategoryOrderNo = 1;
										foreach($checkCanvasSubcategory as $menuSubcatData) {
											$slug = $this->common_lib->create_unique_slug(trim($menuSubcatData->subcategoryName),"vm_product_subcategoryitem","subcategoryitemName",0,"subcategoryitemId",$counter=0);
											$this->Common_model->insert("vm_product_subcategoryitem",array("restaurantId" =>$insertStatus, "categoryId" =>$categoryId, "subcategoryId" => $subcategoryId, "subcategoryitemName" =>$menuSubcatData->subcategoryName, "subcategoryitemName_fr" => $menuSubcatData->subcategoryName_fr,"subcategoryitemName_gr" => $menuSubcatData->subcategoryName_gr, "subcategoryitemName_it" => $menuSubcatData->subcategoryName_it, "orderNo" => $subCategoryOrderNo, "slug" => $slug, "addedOn" => date('Y-m-d H:i:s'), "updatedOn" => date('Y-m-d H:i:s'), "status" => 0 ));
											$subCategoryOrderNo++;
										}
									}
								}
							}
							if (count($imgArry) > 0) {

								foreach ($imgArry as $value) {

									$insertGallaryData = array();

									$insertGallaryData['image']	 		=   $value;
									$insertGallaryData['restaurantId']	 	=   $insertStatus;
									$insertGallaryStatus 			 	= 	$this->Common_model->insert("vm_restaurant_gallary_img", $insertGallaryData);

									if($insertGallaryStatus)
										$status = 'Gallary images added';

									

								}

							}



							$updateData = array();

							$data = md5(100000+$insertStatus);
							$QRname = $this->common_lib->getQRcode('restaurant',$data,'png');
							if ($QRname !='')
								$updateData['qrcode'] =  $QRname;

							$updateData['generatedId'] =   $data;


					        $this->load->config('stripe', TRUE);
					        //get settings from config
					        $current_private_key = $this->config->item('current_private_key', 'stripe');


					        //initialize the client
					        require_once './system/static/stripe/init.php';
					        \Stripe\Stripe::setApiKey($current_private_key);
							$insertStripeData = array();
														try{
								$accData = \Stripe\Account::create([
									"country" => "CH",
									"type" => "custom",
									"payout_schedule"=> [ 
										"delay_days"=> 7,
										"interval"=> "monthly",
										"monthly_anchor" => 30,
									],
									"tos_acceptance" => [
										"date" => time(),
										"ip" => $_SERVER['REMOTE_ADDR'],
									],
								]);

								if (isset($accData->id) && !empty($accData->id)) {
									$updateData['stripeAccId'] = $accData->id;
									$insertStripeData['restaurantId'] = $insertStatus;
									$insertStripeData['stripeAccId'] = $accData->id;
									$insertStripeData['payout_schedule_delay_days'] = $accData->payout_schedule->delay_days;
									$insertStripeData['payout_schedule_interval'] = $accData->payout_schedule->interval;
									$insertStripeData['tos_acceptance_date'] = $accData->tos_acceptance->date;
									$insertStripeData['tos_acceptance_ip'] = $accData->tos_acceptance->ip;
									$insertStripeData['addedOn'] = date('Y-m-d H:i:s');
									$insertStripeData['updatedOn'] = date('Y-m-d H:i:s');
								}
								$cond 	=	"restaurantId = ".$insertStatus;
								$updatetStatus 		= 	$this->Common_model->update("vm_restaurant",$updateData, $cond);

								if(!empty($insertStripeData) && $updatetStatus)
									$this->Common_model->insert("vm_restaurant_stripe_details", $insertStripeData);

								$status = 'added';
								$isAuthCreated = $this->create_auth($insertStatus);
								if (isset($isAuthCreated['auth']) && $isAuthCreated['auth'] == 'welcomeMailSent')
									$status = 'welcomeMailSent';

							}catch (\Exception $e) {
								echo $msg = $e->getMessage(); exit;
							}						

						}

					}

				}
			}



			if($status == 'welcomeMailSent')
				$this->common_lib->setSessMsg($this->lang->line('addNewRecord').' '.$this->lang->line('welcomeMailSent'), 1);
			else if($status == 'added')
				$this->common_lib->setSessMsg($this->lang->line('addNewRecord'), 1);
			else if($status == 'updated')
				$this->common_lib->setSessMsg($this->lang->line('editRecord'), 1);
			else if($status == 'alreadyExist'){
				if (valResultSet($isExist) && $isExist->email == $_POST['txtemail'])
				$this->common_lib->setSessMsg($this->lang->line('restaurant').' '.$this->lang->line('emailAlreadyExist'), 2);
				else if (valResultSet($isExist) && $isExist->restaurantName == $_POST['txtrestaurantName'])
				$this->common_lib->setSessMsg($this->lang->line('restaurant').' '.$this->lang->line('nameAlreadyExist'), 2);

			}

		}

		



		if ($restaurantId > 0){

			$restaurantData = $this->Common_model->selRowData("vm_restaurant","","restaurantId = ".$restaurantId);
			if(!$restaurantData)
				redirect(DASHURL.'/admin/restaurant/restaurant-list');
			$restaurantData->openCloseData = $this->Common_model->selTableData("vm_restaurant_time","","status = 0 AND restaurantId = ".$restaurantId." AND openCloseType = '".$restaurantData->openCloseType."'");

			$this->outputdata['restaurantData'] = $restaurantData;

			$this->outputdata['restaurantGallaryData'] = $this->Common_model->selTableData("vm_restaurant_gallary_img","","restaurantId = ".$restaurantId);

		}

		// Get category list

		$this->outputdata['categoryData']      =   $this->Common_model->selTableData(PREFIX."restaurant_category","categoryId,categoryName$this->langSuffix as categoryName", "status =0", "categoryName");

		$this->load->viewD('admin/restaurant_add_view', $this->outputdata);

	}

		



	// restaurant-listing view

	public function restaurant_new_signup(){	

		$this->menu		=	2;

		$this->subMenu	=	25;	
	

		$this->load->viewD('admin/restaurant_new_signup_view', $this->outputdata);

	}

	// restaurant-listing view

	public function restaurant_list(){	

		$this->menu		=	2;

		$this->subMenu	=	24;	
	

		$this->load->viewD('admin/restaurant_list_view', $this->outputdata);

	}

	// blocked restaurant-listing view

	public function blocked_restaurant_list(){	

		$this->menu		=	2;

		$this->subMenu	=	26;	
	

		$this->load->viewD('admin/restaurant_blocked_list_view', $this->outputdata);

	}	


	
	
	// restaurant profile view
	public function view_restaurant($restaurantId){		
		$this->menu		=	2;
		$this->subMenu	=	24;
		$query	=	"SELECT *,restaurantName$this->langSuffix as restaurantName,contactName$this->langSuffix as contactName,about$this->langSuffix as about from vm_restaurant where restaurantId = ".$restaurantId;
		$restaurantData = $this->Common_model->exequery($query,1);
		if(!$restaurantData)
			redirect(DASHURL.'/admin/restaurant/restaurant-list');

		if (isset($restaurantData->category) && !empty($restaurantData->category)) {
			
			$query01	=	"SELECT *,categoryName$this->langSuffix as categoryName from vm_restaurant_category where categoryId IN (".$restaurantData->category.")";
			$categoryData = $this->Common_model->exequery($query01);
			$restaurantData->categoryData = (valResultSet($categoryData))?$categoryData:'';
		}

		$condT = '';
		if ($restaurantData->openCloseType == 'specificDate') {
			$condT = " AND day >= '".date('Y-m-d')."'";
		}
		$restaurantData->timeData = $this->Common_model->exequery("Select * from vm_restaurant_time where restaurantId=".$restaurantId." AND openCloseType='".$restaurantData->openCloseType."' AND status = 0 ".$condT." ORDER BY day asc ");
		// v3print($restaurantData->timeData); exit;
		$restaurantData->openCloseData = (object)$this->common_lib->checkrestaurantopenclosed($restaurantId, $restaurantData->openCloseType);
		$this->outputdata['profile'] =	$restaurantData;
		// Get gallary img  list
		$this->outputdata['restaurantGallaryData'] = $this->Common_model->selTableData("vm_restaurant_gallary_img","","restaurantId = ".$restaurantId);
		$this->load->viewD($this->sessRole.'/view_restaurant_info',$this->outputdata);
	}	

	// Upload icon image

	public function remove_gallery_image(){

		if(isset($_POST['img_id']) && $_POST['img_id'] > 0) {

			$restaurantgallaryData = $this->Common_model->selRowData("vm_restaurant_gallary_img","","id = ".$_POST['img_id']);

			unlink(ABSUPLOADPATH."/restaurant_gallary_images/".$restaurantgallaryData->image);



			$isdeleted = $this->Common_model->del("vm_restaurant_gallary_img","id = ".$_POST['img_id']);

			echo ($isdeleted)? 'deleted':'failed';

			

		}



	}	



	// Upload icon image

	public function upload_icon(){



		$uploadFlag = array();

		$uploadFlag['status'] = 0;

		$uploadFlag['fileName'] = '';

		$uploadFlag['gallaryfileName'] = '';

		$uploadFlag['imgName'] = '';



		if(!empty($_FILES['uploadImg']['tmp_name'])){

			if($_FILES['uploadImg']['type']=='image/jpeg' || $_FILES['uploadImg']['type']=='image/png' || $_FILES['uploadImg']['type']=='image/jpg'){

				

				$uploadSettings = array();

				$filePhotoName = 	$_FILES['uploadImg']['name'];

				$filePhotoType =	$_FILES['uploadImg']['type'];

				$array 			= 	explode('.', $_FILES['uploadImg']['name']);

				$extension 		= 	end($array);

				$photoToUpload = 	date('Ymdhis').$filePhotoName;

				list($type, $data) = explode(';', $_POST["upload_file_data"]);
				list(, $data)      = explode(',', $data);
				$data = base64_decode($data);
				$im = imagecreatefromstring($data);
				$imagename=time().'.png';
				imagepng($im,ABSUPLOADPATH."/restaurant_images/".$photoToUpload);
				imagedestroy($im);
				$uploadFlag['fileName']		=   $photoToUpload;
				$uploadFlag['status'] = 1;
				$uploadFlag['imgName'] .= $filePhotoName.', ';
			}

		}


		if (isset($_POST["txtgallaryImgsVal"]) && !empty($_POST["txtgallaryImgsVal"])){
			foreach ($_POST["txtgallaryImgsVal"] as $i => $img) {
				if($img != "") {
					list($type, $data) = explode(';', $img);
					list(, $data)      = explode(',', $data);
					if ($data === base64_encode(base64_decode($data))){
						$data = base64_decode($data);
						$im = imagecreatefromstring($data);
						$imagename='venue'.generateStrongPassword(8, false,'ld').date('Ymdhis').'.png';
						if($type=='data:image/png' || $type=='data:image/jpg' || $type=='data:image/jpeg') {
							imagepng($im,ABSUPLOADPATH."/restaurant_gallary_images/".$imagename);
							imagedestroy($im);
							if(file_exists(ABSUPLOADPATH."/restaurant_gallary_images/".$imagename)){

									$uploadFlag['gallaryfileName']		.=   $imagename.',';

									$uploadFlag['status'] = 1;

									$uploadFlag['imgName'] .= $imagename.', ';
							}
						}
					}
				}
			}
			
		}

	
		return $uploadFlag;

	}



	// Creating login details for restaurant

	public function create_auth($insertId){

		// inserting authentication details

		$authstatus = array();
		$pass = (isset($_POST['txtpassword']))?trim($_POST['txtpassword']):generateStrongPassword(6,false,'lud');
    	$authData         		=   array();
		$authData['emailId'] 	=   trim($_POST['txtemail']);
		$authData['password']	=	md5($pass);
		$authData['role']		=   'restaurant';
		$authData['roleId']		=   $insertId;
		$authInsert 			= 	$this->Common_model->insert("vm_auth", $authData);

		if ($authInsert) {
			$authstatus['auth']='welcomeMailSent';
			//Send welcome email
			$settings = array();
			$settings["template"] 				= 	"welcome_email_tpl".$this->lang->line('langSuffix').".html";
			$settings["email"] 					= 	trim($_POST['txtemail']);
			$settings["subject"] 				=	"Welcome to Vedmir";
			$contentarr['[[[ROLE]]]']			=	'Restaurant';
			$contentarr['[[[USERNAME]]]']		=	trim($_POST['txtemail']);
			$contentarr['[[[PASSWORD]]]']		=	$pass;
			$contentarr['[[[LOGINURL]]]']		=	BASEURL."/dashboard/restaurant/login";
			$settings["contentarr"] 			= 	$contentarr;
			$ismailed = $this->common_lib->sendMail($settings);	
		}else{
			$authstatus['auth']='not created';
		}

		return $authstatus;
	}


	// Creating Resturant Plan For event

	public function event_plan () { 
		$this->menu		=	2;

		$this->subMenu	=	23;

		$this->outputdata['planList'] = $this->Common_model->exequery("SELECT planId,planName,duration,period,amount,status FROM vm_restaurant_plan WHERE status != '2'");

		$this->load->viewD('admin/event_plan', $this->outputdata);
	}
	

	public function stripe_details ($restaurantId = 0) { 
		$this->menu		=	2;
		$this->subMenu	=	24;

        $this->load->config('stripe', TRUE);
        //get settings from config
        $current_private_key = $this->config->item('current_private_key', 'stripe');


        //initialize the client
        require_once './system/static/stripe/init.php';
        \Stripe\Stripe::setApiKey($current_private_key);

		// $account = \Stripe\Account::retrieve('acct_1DdAEKKhgfHUeGVw');
		// v3print($account); 
		// echo "----------------------------------------------------------------<br>";

		// v3print($account->legal_entity->dob->day); exit;
		
		if (isset($_POST['stripeAccId']) && !empty($_POST['stripeAccId']) && isset($_POST['payout_acc_no']) && !empty($_POST['payout_acc_no'])) {
			$account = \Stripe\Account::retrieve($_POST['stripeAccId']);
			$stripeData = $this->Common_model->exequery("SELECT rsd.*, rs.restaurantName FROM vm_restaurant as rs left join vm_restaurant_stripe_details as rsd on rs.restaurantId = rsd.restaurantId WHERE rs.status != '2' and rs.restaurantId = '".$restaurantId."'",1);
			try{
				$account = \Stripe\Account::retrieve($_POST['stripeAccId']);
				
				$account->tos_acceptance->date = time();
				$account->tos_acceptance->ip = $_SERVER['REMOTE_ADDR'];
				
				$account->external_account = [
					"object" => "bank_account",
					"country" => 'CH',
					"currency" => 'CHF',					
					"account_number" => $_POST['payout_acc_no'],
				];

				try {
					if($account->save()){
						$account = \Stripe\Account::retrieve($_POST['stripeAccId']);
						$updateData = array(
							"support_phone"								=>$account->support_phone,
							"business_name"								=>$account->business_name,
							"business_url"								=>$account->business_url,
							"email"										=>$account->email,
							"display_name"								=>$account->display_name,
							"legal_entity_address_line1"				=>$account->legal_entity->address->line1,
							"legal_entity_address_line2"				=>$account->legal_entity->address->line2,
							"legal_entity_address_postal_code"			=>$account->legal_entity->address->postal_code,
							"legal_entity_address_state"				=>$account->legal_entity->address->state,
							"legal_entity_address_city"					=>$account->legal_entity->address->city,
							"legal_entity_address_country"				=>$account->legal_entity->address->country,
							"legal_entity_business_name"				=>$account->legal_entity->business_name,
							//"legal_entity_business_tax_id"				=>$_POST['legal_entity_business_tax_id'],
							"legal_entity_dob_day"						=>$account->legal_entity->dob->day,
							"legal_entity_dob_month"					=>$account->legal_entity->dob->month,
							"legal_entity_dob_year"						=>$account->legal_entity->dob->year,
							"legal_entity_first_name"					=>$account->legal_entity->first_name,
							"legal_entity_last_name"					=>$account->legal_entity->last_name,
							"legal_entity_personal_address_city"		=>$account->legal_entity->personal_address->city,
							"legal_entity_personal_address_state"		=>$account->legal_entity->personal_address->state,
							"legal_entity_personal_address_country"		=>$account->legal_entity->personal_address->country,
							"legal_entity_personal_address_line1"		=>$account->legal_entity->personal_address->line1,
							"legal_entity_personal_address_line2"		=>$account->legal_entity->personal_address->line2,
							"legal_entity_personal_address_postal_code"	=>$account->legal_entity->personal_address->postal_code,
							"legal_entity_personal_id_number_provided"	=>($account->legal_entity->personal_id_number_provided)?'1':'0',
							"legal_entity_ssn_last_4_provided"			=>($account->legal_entity->ssn_last_4_provided)?'1':'0',
							"legal_entity_type"							=>$account->legal_entity->type,
							"legal_entity_verification_document"		=>$account->legal_entity->verification->document,
							"legal_entity_verification_document2"		=>$account->legal_entity->verification->document_back,
							"legal_entity_verification_status"			=>$account->legal_entity->verification->status,
							"payouts_enabled"							=>($account->payouts_enabled)?'1':'0',
							"statement_descriptor"						=>$account->statement_descriptor,
							"support_email"								=>$account->support_email,
							"support_phone"								=>$account->support_phone,
							"support_url"								=>$account->support_url,
							"country"									=>$account->country,
							"payout_bank_stripe_id"						=>(isset($account->external_accounts->data[0]['id']) && !empty($account->external_accounts->data[0]['id']) ? $account->external_accounts->data[0]['id']:''),
							"payout_account_holder_name"				=>(isset($account->external_accounts->data[0]['account_holder_name']) && !empty($account->external_accounts->data[0]['account_holder_name'])?$account->external_accounts->data[0]['account_holder_name']:''),
							"payout_account_holder_type"				=>(isset($account->external_accounts->data[0]['account_holder_type']) && !empty($account->external_accounts->data[0]['account_holder_type']) ? $account->external_accounts->data[0]['account_holder_type']:''),
							"payout_bank_name"							=>(isset($account->external_accounts->data[0]['bank_name']) && !empty($account->external_accounts->data[0]['bank_name']) ? $account->external_accounts->data[0]['bank_name']:''),
							"payout_country"							=>(isset($account->external_accounts->data[0]['country']) && !empty($account->external_accounts->data[0]['country']) ? $account->external_accounts->data[0]['country']:''),
							"payout_currency"							=>(isset($account->external_accounts->data[0]['currency']) && !empty($account->external_accounts->data[0]['currency']) ? $account->external_accounts->data[0]['currency']:''),
							"payout_routing_no"							=>(isset($account->external_accounts->data[0]['routing_number']) && !empty($account->external_accounts->data[0]['routing_number']) ? $account->external_accounts->data[0]['routing_number']:''),
							"payout_acc_no"								=>(isset($account->external_accounts->data[0]['last4']) && !empty($account->external_accounts->data[0]['last4']) ? $account->external_accounts->data[0]['last4']:''),
							"tos_acceptance_date" => (isset($account->tos_acceptance->date) && !empty($account->tos_acceptance->date)?$account->tos_acceptance->date:''),
							"tos_acceptance_ip" => (isset($account->tos_acceptance->ip) && !empty($account->tos_acceptance->ip)?$account->tos_acceptance->ip:'')
							//"type"										=>$account->type
						);
						$this->Common_model->update("vm_restaurant_stripe_details",$updateData,"stripeAccId ='".$_POST['stripeAccId']."'"); 
						$this->common_lib->setSessMsg($this->lang->line('editRecord'), 1);
						echo '<script>setTimeout(function(){ window.location.href="'.DASHURL.'/admin/restaurant/restaurant-list"; }, 3000);</script>';

					}
					else
						$this->common_lib->setSessMsg($this->lang->line('dbError'), 2);
				}
				catch(Exception $e) {
					$this->common_lib->setSessMsg($e->getMessage(), 2);
				}
			}catch (Exception $e) {
				
				$this->common_lib->setSessMsg($e->getMessage(), 2);
			}

		}

		$stripeData = $this->Common_model->exequery("SELECT rsd.*, rs.restaurantName FROM vm_restaurant as rs left join vm_restaurant_stripe_details as rsd on rs.restaurantId = rsd.restaurantId WHERE rs.status != '2' and rs.restaurantId = '".$restaurantId."'",1);
		if ($stripeData->stripeAccId == '') {
				$updateData = array();
				$insertStripeData = array();
				try{
					$accData = \Stripe\Account::create([
						"country" => "CH",
						"type" => "custom",
						"payout_schedule"=> [ 
							"delay_days"=> 7,
							"interval"=> "monthly",
							"monthly_anchor" => 30,
						],
						"tos_acceptance" => [
							"date" => time(),
							"ip" => $_SERVER['REMOTE_ADDR'],
						],
					]);
				}catch (Exception $e) { }

				if (isset($accData->id) && !empty($accData->id)) {
					$updateData['stripeAccId'] = $accData->id;
					$insertStripeData['restaurantId'] = $restaurantId;
					$insertStripeData['stripeAccId'] = $accData->id;
					$insertStripeData['payout_schedule_delay_days'] = $accData->payout_schedule->delay_days;
					$insertStripeData['payout_schedule_interval'] = $accData->payout_schedule->interval;
					$insertStripeData['tos_acceptance_date'] = $accData->tos_acceptance->date;
					$insertStripeData['tos_acceptance_ip'] = $accData->tos_acceptance->ip;
					$insertStripeData['addedOn'] = date('Y-m-d H:i:s');
					$insertStripeData['updatedOn'] = date('Y-m-d H:i:s');
				}

				$isUpdated = $this->Common_model->update("vm_restaurant", $updateData, "restaurantId =".$restaurantId);
				if(!empty($insertStripeData) && $isUpdated){
					$isStripeUpdated = $this->Common_model->insert("vm_restaurant_stripe_details", $insertStripeData);
					$stripeData = $this->Common_model->exequery("SELECT rsd.*, rs.restaurantName FROM vm_restaurant as rs left join vm_restaurant_stripe_details as rsd on rs.restaurantId = rsd.restaurantId WHERE rs.status != '2' and rs.restaurantId = '".$restaurantId."'",1);
				}
		}
		/*if (isset($stripeData->business_name) && isset($stripeData->restaurantName))
			$stripeData->business_name = ($stripeData->restaurantName)?$stripeData->restaurantName:'VEDMIR';*/
		$this->outputdata['restaurantId']=$restaurantId;
		$this->outputdata['account'] = isset($account)?$account:'';
		$this->outputdata['stripeData'] = $stripeData;
		$this->load->viewD('admin/restaurant_stripe_details', $this->outputdata);
	}
	/*venue payouts*/
	// public function payouts ($restaurantId=0) { 
	// 	$query	=	"SELECT *,restaurantName$this->langSuffix as restaurantName  from vm_restaurant where restaurantId = ".$restaurantId;
	// 	$restaurantData = $this->Common_model->exequery($query,1);
		
	// 	$restaurantName=(isset($restaurantData) && !empty($restaurantData))?$restaurantData->restaurantName:"";
		
	// 	$this->outputdata['restaurantName']=$restaurantName;
	// 	$this->outputdata['restaurantId']=$restaurantId;
	// 	$this->load->viewD('admin/restaurant_payout_list_view', $this->outputdata);
	// }
	// /*venue payouts*/
	// public function payout_details ($restaurantId=0,$payoutId = 0) {
	// 	$query	=	"SELECT rp.*,rs.restaurantName$this->langSuffix as restaurantName,rs.address1$this->langSuffix as restaurantAddress,rs.address2$this->langSuffix as restaurantAddress2,rsd.legal_entity_business_tax_id as restaurantVat from vm_restaurant_payout as rp left join vm_restaurant rs on rs.restaurantId = rp.restaurantId join vm_restaurant_stripe_details rsd on rs.restaurantId = rsd.restaurantId where rs.status!=2 AND rs.restaurantId=".$restaurantId." AND rp.payoutId=".$payoutId;
		
	// 	$this->outputdata['restaurantId']=$restaurantId;
	// 	$this->outputdata['payoutId']=$payoutId;
	// 	$this->outputdata['payoutData'] = $this->Common_model->exequery($query,1);

	// 	$this->load->viewD('admin/restaurant_payout_details_view', $this->outputdata);
	// }
	/* Download Excel*/
	public function download_excel ($restaurantId=0,$payoutId = 0) {
		if($payoutId>0){
			$query	=	"SELECT rp.*,rs.restaurantName$this->langSuffix as restaurantName,rs.address1$this->langSuffix as restaurantAddress,rs.address2$this->langSuffix as restaurantAddress2 from vm_restaurant_payout rp left join vm_restaurant rs on rs.restaurantId = rp.restaurantId  where rs.status!=2 AND rs.restaurantId=".$restaurantId." AND rp.payoutId=".$payoutId;
			
			$queryData=$this->Common_model->exequery($query,1);
			
			$restaurantName=$msg=$payDate='';$totalAmt=0;
			if(valResultSet($queryData)){
				$restaurantName=$queryData->restaurantName;
				//$payDate="(".$queryData->addedOn.")";
				$payDate=$queryData->addedOn;
				if(isset($queryData->orderIds) && !empty($queryData->orderIds)){
					$QExplodeIds=explode(',',$queryData->orderIds);
					$i=1;
					foreach($QExplodeIds as $pData){
						if($pData){
							$productIds=explode("^",$pData);
							if(isset($productIds[1]) && !empty($productIds[1])){
								$qry = "SELECT su.userName,(SELECT count(de.detailId) FROM vm_order_detail as de WHERE de.orderId= so.orderId AND itemType = '0') as totalFood,
             (SELECT count(de.detailId) FROM vm_order_detail as de WHERE de.orderId= so.orderId AND itemType = '1') as totalDrink ,  so.orderId, so.amt, so.payment_method, so.transactionId, so.paymentStatus, so.tableNo, so.orderStatus, so.addedOn,so.restaturantAmount FROM vm_order so LEFT JOIN vm_user su ON so.userId = su.userId  where so.orderId=".$productIds[1]." AND so.restaurantId = ".$restaurantId; 
								$queryNData = $this->Common_model->exequery("SELECT t.*, (case when (t.totalFood > 0 AND t.totalDrink > 0) then 'Food & Drink' when (t.totalDrink > 0) then 'Drink' else 'Food' end) as orderType FROM (".$qry.") as t",1);
								if(valResultSet($queryNData)){
									$totalAmt+=$queryNData->restaturantAmount;
									$msg.='<tr>';
									$msg.='<td>'.$i.'</td>';
									$msg.='<td>#'.$queryNData->orderId.'</td>';
									$msg.='<td>'. $queryNData->userName.'</td>';
									$msg.='<td>'. $queryNData->orderType.'</td>';
									$msg.='<td>CHF : '. $queryNData->restaturantAmount.'</td>';
									$msg.='<td><span style="visibility:hidden">&nbsp;</span> '.date('Y-m-d h:i A',strtotime($queryNData->addedOn)).'</td>';
									$msg.='</tr>';
									$i++;
								}
							}
						}
					}
					if($totalAmt>0){
						$msg.='<tr><td colspan="4" style="text-align: right;font-weight: 600;">Total</td><td style="font-weight: 600;">CHF : '.$totalAmt.'</td><td></td></tr>';
					}
					
				}
				
			}
			
			$msg1="<center><h2>Hi ".$restaurantName." Monthly Payout ".date("Y-m-d h:i A", strtotime($payDate))."</h2></center>";
			$msg1.="<tr><th>Sr No.</th><th>Order Id</th><th>User Name</th><th>Type</th><th>Venue Amount</th><th>Date</th></tr>";
			
			$data="<html>";
			$data.="<meta http-equiv=\"Content-Type\" content=\"text/html; charset=Windows-1252\">";
			$data.="<body class='fixed-top'>";
			$data.="<table cellspacing='0' border='1' style='text-align:center;'><tbody>";
			$data.=$msg1.$msg;
			$data.='</table>';
			$data.="</body>";
			$data.="</html>";
			
			$filename = 'payout_'. date('Y/m/d') . ".xls";
			header("Content-Disposition: attachment; filename=\"$filename\"");
			header("Content-Type: application/vnd.ms-excel");
			print "$header\n$data";
		}
		else{
			echo "Access Denied";
			exit;
		}
	}

	public function export($restaurantId = 0){
		if(isset($_POST['daterange']) && !empty($_POST['daterange']) && isset($_POST['selRestaurant']) && !empty($_POST['selRestaurant'])){
			$restaurantId = $_POST['selRestaurant'];
			$dates = explode(' - ', $_POST['daterange']);
			if($restaurantId>0){
				$query	=	"SELECT rs.restaurantName as restaurantName,rs.address1 as restaurantAddress,rs.address2 as restaurantAddress2, srsd.legal_entity_business_name as legalName, (SELECT GROUP_CONCAT(categoryName) FROM vm_restaurant_category where categoryId IN (rs.category) ) venueType from vm_restaurant rs left join vm_restaurant_stripe_details srsd on rs.restaurantId = srsd.restaurantId where rs.status!=2 AND rs.restaurantId=".$restaurantId;
				
				$queryData=$this->Common_model->exequery($query,1);
				
				$restaurantName=$msg=$payDate='';$totalAmt=0;
				if(valResultSet($queryData)){
					$restaurantName=$queryData->restaurantName;
					
					$qry = "SELECT su.userName, so.orderId, so.amt, so.payment_method, so.transactionId, so.paymentStatus, so.tableNo, so.orderDescription as description, so.last4, so.brand, so.orderStatus, so.restaturantAmount, so.addedOn,(SELECT count(de.detailId) FROM vm_order_detail as de WHERE de.orderId= so.orderId AND itemType = '0') as totalFood,(SELECT count(de.detailId) FROM vm_order_detail as de WHERE de.orderId= so.orderId AND itemType = '1') as totalDrink,(SELECT GROUP_CONCAT(pd.productName)  FROM vm_product as pd where pd.productId IN (SELECT (CASE when sod.isVariable = '1' then (select vd.productId FROM vm_variable_product vd where vd.variableId = sod.productId) else sod.productId end) FROM vm_order_detail as sod where sod.orderId=so.orderId) group by pd.restaurantId) as orderItems FROM vm_order so LEFT JOIN vm_user su ON so.userId = su.userId  where so.restaurantId = ".$restaurantId." AND so.paymentStatus='Completed' AND so.orderStatus='Completed' AND so.isTrail='0' AND DATE(so.addedOn) >= '".$dates[0]."' AND DATE(so.addedOn) <= '".$dates[1]."'"; 
					$queryRData = $this->Common_model->exequery("SELECT t.*, (case when (t.totalFood > 0 AND t.totalDrink > 0) then 'Food & Drink' when (t.totalDrink > 0) then 'Drink' else 'Food' end) as orderType FROM (".$qry.") as t");
					if(valResultSet($queryRData)){
						$i = 1;
						foreach($queryRData as $queryNData) {
							$totalAmt+=$queryNData->restaturantAmount;
							
							$welcomeDrink = 'No';
							$orderDeatils  =   "SELECT od.*,rs.tax as tax,
				                pd.description as description, spsi.subcategoryitemName, sps.subcategoryName, pd.productName, vd.variableName from vm_order_detail as od left join vm_variable_product as vd on (od.productId= vd.variableId and od.isVariable = '1') left join vm_product as pd on (CASE WHEN od.isVariable = '1' THEN (pd.productId = vd.productId) ELSE  (pd.productId = od.productId) END)  left join vm_restaurant as rs on pd.restaurantId=rs.restaurantId left join vm_product_subcategory sps on pd.subcategoryId = sps.subcategoryId left join vm_product_subcategoryitem spsi on pd.subcategoryitemId = spsi.subcategoryitemId where od.orderId = ".$queryNData->orderId." order by od.detailId asc";
				            $orderItems =    $this->Common_model->exequery($orderDeatils);
				            $orderItems = ($orderItems) ? $orderItems : array();
				            $categoryHtml = $subcategoryHtml = $productHtml = $variableHtml = ''; 
				            $count = 0;
				            foreach ($orderItems as $orderDetailsItem) {
				            	$freeQuanttity = 0;
				            	if($orderDetailsItem->isFree == 1) {
				            		$welcomeDrink = 'Yes';
				            		$freeQuanttity = 1;
				            		$msg.='<tr>';
									$msg.='<td>'.date('H:i',strtotime($queryNData->addedOn)).'</td>';
									$msg.='<td>'.date('Y-m-d',strtotime($queryNData->addedOn)).'</td>';
									$msg.='<td>Order</td>';
									$msg.='<td>'.mb_convert_encoding($queryNData->orderItems,'utf-16','utf-8').'</td>';
									$msg.='<td>'.$queryNData->orderId.'</td>';
									$msg.='<td>'. $queryData->venueType.'</td>';
									$msg.='<td>'.mb_convert_encoding($queryData->restaurantName,'utf-16','utf-8').'</td>';
									$msg.='<td>'. mb_convert_encoding($queryData->legalName,'utf-16','utf-8').'</td>';
									$msg.='<td>'. $queryNData->orderType.'</td>';
					            	//$style = ($count > 0) ?'style="border-bottom:1px solid;"':'';
					            	//$productName = ($orderDetailsItem->quantity > 1) ? $orderDetailsItem->productName .' x '.$orderDetailsItem->quantity: $orderDetailsItem->productName;
					            	$msg.='<td >'.mb_convert_encoding($orderDetailsItem->subcategoryName,'utf-16','utf-8').'</td>';
					            	$msg.='<td >'.mb_convert_encoding($orderDetailsItem->subcategoryitemName,'utf-16','utf-8').'</td>';
					            	$msg.='<td >'.mb_convert_encoding($orderDetailsItem->productName,'utf-16','utf-8').'</td>';
					            	$msg.='<td >'.$freeQuanttity.'</td>';
					            	$msg.='<td >'.mb_convert_encoding($orderDetailsItem->variableName,'utf-16','utf-8').'</td>';
					            	$msg .= '<td>'.$welcomeDrink.'</td>';
						            $msg .= '<td>0</td>';
						            $msg .= '<td>CHF</td>';
						            $msg .= '<td>'.mb_convert_encoding($queryNData->description,'utf-16','utf-8').'</td>';
						            $msg .= '<td></td>';
						            $msg .= '<td>'.$queryNData->last4.'</td>';
						            $msg .= '<td>'.$queryNData->brand.'</td>';
						            $stripecommission = 0;
						            $availableAmount = 0;
						            $msg .= '<td>'.$stripecommission.'</td>';
						            $msg .= '<td>'.$availableAmount.'</td>';
									$msg.='<td>Paid</td>';	
									$msg.='<td></td>';						
									$msg.='</tr>';
				            	}
				            	else if(($orderDetailsItem->isFree == 1 && $orderDetailsItem->quantity > 1 ) || $orderDetailsItem->isFree != 1){
					            	$welcomeDrink = 'No';
					            	$msg.='<tr>';
									$msg.='<td>'.date('H:i',strtotime($queryNData->addedOn)).'</td>';
									$msg.='<td>'.date('Y-m-d',strtotime($queryNData->addedOn)).'</td>';
									$msg.='<td>Order</td>';
									$msg.='<td>'.mb_convert_encoding($queryNData->orderItems,'utf-16','utf-8').'</td>';
									$msg.='<td>'.$queryNData->orderId.'</td>';
									$msg.='<td>'. $queryData->venueType.'</td>';
									$msg.='<td>'.mb_convert_encoding($queryData->restaurantName,'utf-16','utf-8').'</td>';
									$msg.='<td>'. mb_convert_encoding($queryData->legalName,'utf-16','utf-8').'</td>';
									$msg.='<td>'. $queryNData->orderType.'</td>';
					            	//$style = ($count > 0) ?'style="border-bottom:1px solid;"':'';
					            	//$productName = ($orderDetailsItem->quantity > 1) ? $orderDetailsItem->productName .' x '.$orderDetailsItem->quantity: $orderDetailsItem->productName;
					            	$msg.='<td >'.mb_convert_encoding($orderDetailsItem->subcategoryName,'utf-16','utf-8').'</td>';
					            	$msg.='<td >'.mb_convert_encoding($orderDetailsItem->subcategoryitemName,'utf-16','utf-8').'</td>';
					            	$msg.='<td >'.mb_convert_encoding($orderDetailsItem->productName,'utf-16','utf-8').'</td>';
					            	$quantity = $orderDetailsItem->quantity - $freeQuanttity;
					            	$msg.='<td >'.$quantity.'</td>';
					            	$msg.='<td >'.mb_convert_encoding($orderDetailsItem->variableName,'utf-16','utf-8').'</td>';
					            	$msg .= '<td>'.$welcomeDrink.'</td>';
						            $msg .= '<td>'.$orderDetailsItem->subtotal.'</td>';
						            $msg .= '<td>CHF</td>';
						            $msg .= '<td>'.mb_convert_encoding($queryNData->description,'utf-16','utf-8').'</td>';
						            $msg .= '<td></td>';
						            $msg .= '<td>'.$queryNData->last4.'</td>';
						            $msg .= '<td>'.$queryNData->brand.'</td>';
						            $stripecommission = ($orderDetailsItem->subtotal > 0 ) ? ($orderDetailsItem->subtotal * 3.07 )/100 : 0;
						            $availableAmount = $orderDetailsItem->subtotal - $stripecommission;
						            $msg .= '<td>'.$stripecommission.'</td>';
						            $msg .= '<td>'.$availableAmount.'</td>';
									$msg.='<td>Paid</td>';	
									$msg.='<td></td>';						
									$msg.='</tr>';
								}
				            	
				            }
				            /*$msg .= '<td><table>'.$categoryHtml.'</table></td>';
				            $msg .= '<td><table>'.$subcategoryHtml.'</table></td>';
				            $msg .= '<td><table>'.$productHtml.'</table></td>';
				            $msg .= '<td><table>'.$variableHtml.'</table></td>';*/
				            
							$i++;
						}
					}
								
							
						
					if($totalAmt>0)
						$msg.='<tr><td colspan="18" style="text-align: right;font-weight: 600;">Total Restaurant Revenue</td><td style="font-weight: 600;">'.$totalAmt.'</td><td></td></tr>';
					
						
					
					
				
				
					$msg1="<center><h2>Hi ".$restaurantName." Order Details </h2></center>";
					$msg1.="<tr><th>Time</th><th>Date</th><th>Nature of the transaction</th><th>Description the transaction</th><th>Order #</th><th>Venue type</th><th>Venue name</th><th>Venue legal name </th><th>Type</th><th>Category</th><th>Subcategory</th><th>Product</th><th>Quantity</th><th>Variable item name</th><th>Welcome product (offered)</th><th>Gross Amount</th><th>Currency</th><th>Note</th><th>Card ID</th><th>Card last 4</th><th>Card brand</th><th>Stripe fee</th><th>Available amount</th><th>Status</th><th>Amount refunded</th></tr>";
					
					$data="<html>";
					$data.="<meta http-equiv=\"Content-Type\" content=\"text/html; charset=Windows-1252\">";
					$data.="<body class='fixed-top'>";
					$data.="<table cellspacing='0' border='1' style='text-align:center;'><tbody>";
					$data.=$msg1.$msg;
					$data.='</table>';
					$data.="</body>";
					$data.="</html>";
					
					$filename = $restaurantName."_". date('Y/m/d') . ".xls";
					header("Content-Disposition: attachment; filename=\"$filename\"");
					header("Content-Type: application/vnd.ms-excel;charset=UTF-8");
					print "$data";
				}
				
			}
			
		}
		$this->outputdata['restaurantId'] = $restaurantId;
		$this->outputdata['restaurantData'] = $this->Common_model->selTableData("vm_restaurant","restaurantId,restaurantName$this->langSuffix as restaurantName","status = 0","restaurantName");
		$this->load->viewD('admin/product_sold_view', $this->outputdata);
	}

    public function payouts($restaurantId = 0){
		$lastMonthDate = date("Y-m-d", strtotime("last day of previous month"));
		$startDate = date("Y-m-d", strtotime("first day of previous month"));
		//$restaurantOrder = $this->Common_model->exequery("SELECT vm_order.restaurantId, vm_restaurant_stripe_details.legal_entity_business_name as legalName, sr.restaurantName, sr.address1, sr.address2, sr.country, sr.mobile, sr.email, vm_restaurant_stripe_details.legal_entity_business_tax_id as taxDetails FROM vm_order left join vm_restaurant sr on vm_order.restaurantId = sr.restaurantId left join vm_restaurant_stripe_details on sr.restaurantId = vm_restaurant_stripe_details.restaurantId WHERE paymentStatus='Completed' AND orderStatus='Completed' AND DATE(vm_order.addedOn) >= '".$startDate."' AND DATE(vm_order.addedOn) <= '".$lastMonthDate."' AND vm_order.isTrail='0' AND vm_order.restaurantId ='".$restaurantId."'  group by vm_order.restaurantId");
		$restaurantOrder = $this->Common_model->exequery("SELECT vm_order.restaurantId, vm_restaurant_stripe_details.legal_entity_business_name as legalName, sr.restaurantName, sr.address1, sr.address2, sr.country, sr.mobile, sr.email, vm_restaurant_stripe_details.legal_entity_business_tax_id as taxDetails FROM vm_order left join vm_restaurant sr on vm_order.restaurantId = sr.restaurantId left join vm_restaurant_stripe_details on sr.restaurantId = vm_restaurant_stripe_details.restaurantId WHERE paymentStatus='Completed' AND orderStatus='Completed' AND DATE(vm_order.addedOn) >= '".$startDate."' AND DATE(vm_order.addedOn) <= '".$lastMonthDate."' AND vm_order.isTrail='0'  AND vm_order.restaurantId ='".$restaurantId."'  group by vm_order.restaurantId");
			
		if($restaurantOrder) {
			foreach($restaurantOrder as $restaurantOrderItem) {
				$totalDrinkAmount = $totalFoodAmount =  $freeDrinkAmount = $drinkQuantity = $totalFreeDrinkQuantity = $foodQuantity = 0;
				$drinkOrderBodyHtml = $previousCategoryItem = $foodOrderBodyHtml = $previousFoodCategoryItem = '';
				$getDrinkOrder = $this->Common_model->exequery("SELECT t.*, (SELECT count(*) FROM vm_order_detail where productId=t.productId AND isFree='1' AND orderId IN (SELECT orderId FROM vm_order WHERE restaurantId = '".$restaurantOrderItem->restaurantId."' AND paymentStatus='Completed' AND orderStatus='Completed' AND isTrail='0' AND DATE(addedOn) >= '".$startDate."' AND DATE(addedOn) <= '".$lastMonthDate."' )) as welcomeDrinkCount, (SELECT SUM(quantity) FROM vm_order_detail where productId=t.productId AND orderId IN (SELECT orderId FROM vm_order WHERE restaurantId = '".$restaurantOrderItem->restaurantId."' AND paymentStatus='Completed' AND isTrail='0' AND orderStatus='Completed' AND DATE(addedOn) >= '".$startDate."' AND DATE(addedOn) <= '".$lastMonthDate."' )) as quantityItem, (SELECT SUM(subtotal) FROM vm_order_detail where productId=t.productId AND orderId IN (SELECT orderId FROM vm_order WHERE restaurantId = '".$restaurantOrderItem->restaurantId."' AND paymentStatus='Completed' AND isTrail='0' AND orderStatus='Completed' AND DATE(addedOn) >= '".$startDate."' AND DATE(addedOn) <= '".$lastMonthDate."' )) as totalSubTotal FROM (SELECT od.*, vm_product_subcategory.subcategoryName, (CASE WHEN od.isVariable = '1' THEN CONCAT(pd.productName,' (',vd.variableName, ')') ELSE  pd.productName END) as productName FROM vm_order_detail as od left join vm_variable_product as vd on (od.productId= vd.variableId and od.isVariable = '1') left join vm_product as pd on (CASE WHEN od.isVariable = '1' THEN (pd.productId = vd.productId) ELSE  (pd.productId = od.productId) END) left join vm_product_subcategory ON pd.subcategoryId = vm_product_subcategory.subcategoryId  WHERE orderId IN (SELECT orderId FROM vm_order WHERE restaurantId = '".$restaurantOrderItem->restaurantId."' AND paymentStatus='Completed' AND orderStatus='Completed' AND isTrail='0' AND DATE(addedOn) >= '".$startDate."' AND DATE(addedOn) <= '".$lastMonthDate."' ) AND itemType='1' AND od.price != 0 group by od.productId) as t order by t.subcategoryName desc");
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
				$getFoodOrder = $this->Common_model->exequery("SELECT t.*, (SELECT SUM(quantity) FROM vm_order_detail where productId=t.productId AND orderId IN (SELECT orderId FROM vm_order WHERE restaurantId = '".$restaurantOrderItem->restaurantId."' AND paymentStatus='Completed' AND isTrail='0' AND orderStatus='Completed' AND DATE(addedOn) >= '".$startDate."' AND DATE(addedOn) <= '".$lastMonthDate."' )) as quantityItem, (SELECT SUM(subtotal) FROM vm_order_detail where productId=t.productId AND orderId IN (SELECT orderId FROM vm_order WHERE restaurantId = '".$restaurantOrderItem->restaurantId."' AND paymentStatus='Completed' AND isTrail='0' AND orderStatus='Completed' AND DATE(addedOn) >= '".$startDate."' AND DATE(addedOn) <= '".$lastMonthDate."' )) as totalSubTotal FROM (SELECT od.*, vm_product_subcategory.subcategoryName, (CASE WHEN od.isVariable = '1' THEN CONCAT(pd.productName,' (',vd.variableName, ')') ELSE  pd.productName END) as productName FROM vm_order_detail as od left join vm_variable_product as vd on (od.productId= vd.variableId and od.isVariable = '1') left join vm_product as pd on (CASE WHEN od.isVariable = '1' THEN (pd.productId = vd.productId) ELSE  (pd.productId = od.productId) END) left join vm_product_subcategory ON pd.subcategoryId = vm_product_subcategory.subcategoryId  WHERE orderId IN (SELECT orderId FROM vm_order WHERE restaurantId = '".$restaurantOrderItem->restaurantId."' AND paymentStatus='Completed' AND orderStatus='Completed' AND DATE(addedOn) >= '".$startDate."' AND DATE(addedOn) <= '".$lastMonthDate."' ) AND itemType='0' AND od.price != 0 group by od.productId ) as t order by t.subcategoryName desc");
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
                $settings["template"]               =   "payout_tpl.html";
                $settings["email"]                  =   $restaurantOrderItem->email;//"charles@vedmir.com,dsmail.shivank@gmail.com,dsmail.arshad@gmail.com";
                $settings["subject"]                =   "Payout Summary ".date('F Y', strtotime($lastMonthDate));
                $contentarr['[[[VenueName]]]']           =   $restaurantOrderItem->restaurantName;
                $contentarr['[[[PayoutNumber]]]']       =   'SK'.$restaurantOrderItem->restaurantId.date('y', strtotime($startDate)).date('m', strtotime($startDate));
                $contentarr['[[[DATEOFISSUE]]]']       =   DATE('d.m.Y');
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
				$this->outputdata['viewData'] = $mail_content;				
			}
			
		}
		/*if($restaurantOrder) {
			foreach($restaurantOrder as $restaurantOrderItem) {
				$totalDrinkAmount = $totalFoodAmount =  $freeDrinkAmount = $drinkQuantity = $totalFreeDrinkQuantity = $foodQuantity = 0;
				$drinkOrderBodyHtml = $previousCategoryItem = $foodOrderBodyHtml = $previousFoodCategoryItem = '';
				$getDrinkOrder = $this->Common_model->exequery("SELECT od.*, vm_product_subcategory.subcategoryName, (CASE WHEN od.isVariable = '1' THEN CONCAT(pd.productName,' (',vd.variableName, ')') ELSE  pd.productName END) as productName FROM vm_order_detail as od left join vm_variable_product as vd on (od.productId= vd.variableId and od.isVariable = '1') left join vm_product as pd on (CASE WHEN od.isVariable = '1' THEN (pd.productId = vd.productId) ELSE  (pd.productId = od.productId) END) left join vm_product_subcategory ON pd.subcategoryId = vm_product_subcategory.subcategoryId  WHERE orderId IN (SELECT orderId FROM vm_order WHERE restaurantId = '".$restaurantOrderItem->restaurantId."' AND paymentStatus='Completed' AND orderStatus='Completed' AND DATE(addedOn) >= '".$startDate."' AND DATE(addedOn) <= '".$lastMonthDate."' AND isTrail='0') AND itemType='1' order by subcategoryName desc");
				$drinkOrderHtml = '<table width="800" cellspacing="0" cellpadding="0" border="0" align="" style="background-color: #f2f2f2;font-size:13px;margin-top:10px;">
								<thead class="drinks" style="text-align: left;background-color:#a6a6a6;">
								  <tr class="drinks">
									<th><span style="font-size: 20px; padding-left:10px;">DRINKS</span></th>
									<th><p>Product</p></th>
									<th><p style="text-align:center;">CHF / Unit</p></th>
									<th><p style="text-align:center;">Qty Wecome Drinks</p></th>
									<th><p style="text-align:center;">Qty</p></th>
									<th><p style="text-align:center;">Total CHF</p></th>
								  </tr>
								</thead>
							<tbody>';
				if( $getDrinkOrder ) {
					//$drinkQuantity = 0;
					foreach ($getDrinkOrder as $drinkOrderItem) {
						
						if($previousCategoryItem != $drinkOrderItem->subcategoryName) {
							$previousCategoryItem = $drinkOrderItem->subcategoryName;
							$categoryNameHtml = '<td style="margin:0px;"><span style="padding-left:10px;">'.$drinkOrderItem->subcategoryName.'</span></td>';
						}
						else
							$categoryNameHtml = '<td colspan="1" style="margin:0px;"></td>';
						if($drinkOrderItem->isFree == 1 ) {
							$freeDrinkQuantity = 1;
							$freeDrinkAmount = $freeDrinkAmount + $drinkOrderItem->price;
							$totalFreeDrinkQuantity  = $totalFreeDrinkQuantity + 1;
						}
						else
							$freeDrinkQuantity = 0;
						$totalDrinkAmount = $totalDrinkAmount + $drinkOrderItem->subtotal;
						$drinkQuantity = $drinkQuantity + $drinkOrderItem->quantity;
						$drinkOrderBodyHtml .= '<tr>'.$categoryNameHtml.'<td style="margin:0px;"><p style="margin:0px; width:200px;">'.$drinkOrderItem->productName.'</p></td><td style="margin:0px;"><p style="text-align:center; margin:0px;">'.str_replace('.', ',', $drinkOrderItem->price).'</p></td><td style="margin:0px;"><p style="text-align:center; margin:0px;">'.$freeDrinkQuantity.'</p></td><td style="margin:0px;"><p style="text-align:center; margin:0px;">'.$drinkOrderItem->quantity.'</p></td><td style="margin:0px;"><p style="text-align:center; margin:0px;">'.str_replace('.', ',', $drinkOrderItem->subtotal).'</p></td></tr>';
					}
					
				}
				if($drinkOrderBodyHtml == '')
					$drinkOrderBodyHtml = '<tr><td style="margin:0px;"><span style="padding-left:10px;">NONE</span></td><td style="margin:0px;"><p style="margin:0px;">-</p></td><td style="margin:0px;"><p style="text-align:center; margin:0px;">0</p></td><td style="margin:0px;"><p style="text-align:center; margin:0px;">0</p></td><td style="margin:0px;"><p style="text-align:center; margin:0px;">0</p></td><td style="margin:0px;"><p style="text-align:center; margin:0px;">0</p></td></tr>';
				$drinkOrderHtml .= $drinkOrderBodyHtml.'<tr class="center"><td style="margin:0px;"><p style="font-weight:bold; text-align:left;padding-left:10px; margin:0px;">Sub-total </p></td><td colspan="1" style="margin:0px;"></td><td style="margin:0px;"><p style="font-weight:bold; text-align:center; margin:0px;"></p></td><td colspan="1" style="margin:0px;"><p style="font-weight:bold; text-align:center; margin:0px;">'.$totalFreeDrinkQuantity.'</p></td><td style="margin:0px;"><p style="font-weight:bold; text-align:center; margin:0px;">'.$drinkQuantity.'</p></td><td style="margin:0px;"><p style="font-weight:bold; text-align:center; margin:0px;">'.str_replace('.', ',', $totalDrinkAmount).'</p></td>
						</tr></tbody></table>';
				$getFoodOrder = $this->Common_model->exequery("SELECT od.*, vm_product_subcategory.subcategoryName, (CASE WHEN od.isVariable = '1' THEN CONCAT(pd.productName,' (',vd.variableName, ')') ELSE  pd.productName END) as productName FROM vm_order_detail as od left join vm_variable_product as vd on (od.productId= vd.variableId and od.isVariable = '1') left join vm_product as pd on (CASE WHEN od.isVariable = '1' THEN (pd.productId = vd.productId) ELSE  (pd.productId = od.productId) END) left join vm_product_subcategory ON pd.subcategoryId = vm_product_subcategory.subcategoryId  WHERE orderId IN (SELECT orderId FROM vm_order WHERE restaurantId = '".$restaurantOrderItem->restaurantId."' AND paymentStatus='Completed' AND orderStatus='Completed' AND DATE(addedOn) >= '".$startDate."' AND DATE(addedOn) <= '".$lastMonthDate."' AND isTrail='0') AND itemType='0' order by subcategoryName desc");
				$foodOrderHtml = '<table width="800" cellspacing="0" cellpadding="0" border="0" align="" style="background-color: #f2f2f2;font-size:13px; margin-top:10px;">
								<thead class="drinks" style="text-align: left; background-color:#a6a6a6;">
								  <tr class="drinks">
									<th><span style="font-size: 20px; padding-left:10px;">FOOD</span></th>
									<th><p>Product</p></th>
									<th><p style="text-align:center;">CHF / Unit</p></th>
									<th><p style="text-align:center;">Qty Wecome Drinks</p></th>
									<th><p style="text-align:center;">Qty</p></th>
									<th><p style="text-align:center;">Total CHF</p></th>
								  </tr>
								</thead>
							<tbody>';
				if( $getFoodOrder ) {
					//$drinkQuantity = 0;
					foreach ($getFoodOrder as $foodOrderItem) {
						
						if($previousFoodCategoryItem != $foodOrderItem->subcategoryName) {
							$previousFoodCategoryItem = $foodOrderItem->subcategoryName;
							$categoryNameHtml = '<td style="margin:0px;"><span style="padding-left:10px; margin:0px;">'.$foodOrderItem->subcategoryName.'</span></td>';
						}
						else
							$categoryNameHtml = '<td colspan="1" style="margin:0px;"></td>';
						
						$totalFoodAmount = $totalFoodAmount + $foodOrderItem->subtotal;
						$foodQuantity = $foodQuantity + $foodOrderItem->quantity;
						$foodOrderBodyHtml .= '<tr>'.$categoryNameHtml.'<td style="margin:0px;"><p style="margin:0px; width:200px;">'.$foodOrderItem->productName.'</p></td><td style="margin:0px;"><p style="text-align:center; margin:0px;">'.str_replace('.', ',', $foodOrderItem->price).'</p></td><td style="margin:0px;"><p style="text-align:center; margin:0px;">0</p></td><td style="margin:0px;"><p style="text-align:center; margin:0px;">'.$foodOrderItem->quantity.'</p></td><td style="margin:0px;"><p style="text-align:center; margin:0px;">'.str_replace('.', ',', $foodOrderItem->subtotal).'</p></td></tr>';
					}
					
				}
				if($foodOrderBodyHtml == '')
					$foodOrderBodyHtml = '<tr><td style="margin:0px;"><span style="padding-left:10px;">NONE</span></td><td style="margin:0px;"><p style="margin:0px;">-</p></td><tdstyle="margin:0px;"><p style="text-align:center; margin:0px;">0</p></td><td style="margin:0px;"><p style="text-align:center; margin:0px;">0</p></td><td style="margin:0px;"><p style="text-align:center; margin:0px;">0</p></td><td style="margin:0px;"><p style="text-align:center; margin:0px;">0</p></td></tr>';
				$foodOrderHtml .= $foodOrderBodyHtml.'<tr class="center"><td style="margin:0px;"><p style="font-weight:bold;text-align:left; padding-left:10px; margin:0px;">Sub-total </p></td><td colspan="1"></td><td style="margin:0px;"><p style="font-weight:bold;text-align:center; margin:0px;">0</p></td><td colspan="1"></td><td style="margin:0px;"><p style="font-weight:bold;text-align:center; margin:0px;">'.$foodQuantity.'</p></td><td style="margin:0px;"><p style="font-weight:bold;text-align:center; margin:0px;">'.str_replace('.', ',', $totalFoodAmount).'</p></td>
						</tr></tbody></table>';
				$totalInivoceAmount = $totalDrinkAmount + $totalFoodAmount;
				$vedmirCommession = 0.05 * $totalInivoceAmount;
				$taxAmount = 0.077 * $totalInivoceAmount;
				$payoutAmount = $totalInivoceAmount - $vedmirCommession;
				$invoiceContent = '<table width="800" cellspacing="0" cellpadding="0" border="0"  style="background-color: #fce4d6;font-size: 13px;margin-top:10px;padding-right:10px;"><tbody><tr class="center"><td style="margin:0px;"><p style="padding-left:10px; margin:0px;">Drinks revenue </p></td><td style="margin:0px;"><p style="margin:0px; text-align:right;">CHF '.str_replace('.', ',', $totalDrinkAmount).'</p></td></tr><tr class="center"><td style="margin:0px;"><p style="padding-left:10px; margin:0px;">Food revenue</p></td><td style="margin:0px;"><p style="margin:0px; text-align:right;">CHF '.str_replace('.', ',', $totalFoodAmount).'</p></td></tr><tr class="center"><td style="margin:0px;"><p style="font-size:17px;padding-left:10px; margin:0px;">TOTAL REVENUE</p></td><td><p style="font-size:17px; margin:0px; text-align:right;">CHF '.str_replace('.', ',', $totalInivoceAmount).'</p></td></tr><tr class="center"><td><p style="padding-left:10px; margin:0px;">Vedmir Commission (5%) </p></td><td><p style="margin:0px; text-align:right;">CHF '.str_replace('.', ',', $vedmirCommession).'</p></td></tr><tr class="center"><td><p style="padding-left:10px; margin:0px;">Including Tax 7,7%</p></td><td><p style="margin:0px; text-align:right;">CHF '.str_replace('.', ',', $taxAmount).'</p></td></tr></tbody></table><table width="800" cellspacing="0" cellpadding="0" border="0"  style="background-color: #ed7d31;font-size: 13px;margin-top:10px; padding-right:10px;"><tbody><tr class="center"><td><p style="font-size:17px; padding-left:10px; margin:0px;">TOTAL PAYOUT</p></td><td><p style="font-size:17px; text-align:right; margin:0px;">CHF '.str_replace('.', ',', $payoutAmount).'</p></td></tr></tbody></table><table width="800" cellspacing="0" cellpadding="0" border="0"  style="background-color: #f2f2f2;font-size: 13px;margin-top:10px; padding-right:10px;"><tbody><tr class="center"><td><p style="padding-left:10px; margin:0px;">Total amount of Welcome drinks</p></td><td><p style="margin:0px; text-align:right;">'.str_replace('.', ',', $freeDrinkAmount).'</p></td></tr><tr class="center"><td><p style="padding-left:10px; margin:0px;">Total amount of paid products </p></td><td><p style="margin:0px; text-align:right;">'.str_replace('.', ',', $totalInivoceAmount).'</p></td></tr></tbody></table>';
				$settings = array();
                $settings["template"]               =   "payout_tpl.html";
                $settings["email"]                  =   $restaurantOrderItem->email;//"charles@vedmir.com,dsmail.shivank@gmail.com,dsmail.arshad@gmail.com";
                $settings["subject"]                =   "Payout Summary ".date('F Y', strtotime($lastMonthDate));
                $contentarr['[[[VenueName]]]']           =   $restaurantOrderItem->restaurantName;
                $contentarr['[[[PayoutNumber]]]']       =   'SK'.$restaurantOrderItem->restaurantId.date('y', strtotime($startDate)).date('m', strtotime($startDate));
                $contentarr['[[[DATEOFISSUE]]]']       =   DATE('d.m.Y');
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
				$this->outputdata['viewData'] = $mail_content;
                // $ismailed = $this->common_lib->sendMail($settings); 
				//exit;
			}
		}*/

		$this->load->viewD('admin/restaurant_payout_view', $this->outputdata);
	}
	

}