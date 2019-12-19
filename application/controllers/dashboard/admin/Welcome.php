<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by Dream Steps Pvt Ltd
 * Created on 30 Nov 2019
 * Vedmir -  module
**/
class Welcome extends CI_Controller {
	
	public $menu		= 0;
	public $subMenu		= 0;
	public $subSubMenu	= 0;
	
	public $outputdata 	= array();
	
	public function __construct(){
		parent::__construct();
		$testmode=0;
		//Check login authentication & set public veriables
		$this->session->set_userdata(PREFIX.'sessRole', "admin");
		$this->common_lib->setSessionVariables();
		$this->load->helper('file');
		$this->langSuffix = $this->lang->line('langSuffix');
		//load config
        $this->load->config('stripe', TRUE);

        //get settings from config
        $this->current_private_key = $this->config->item('current_private_key', 'stripe');
        $this->current_public_key  = $this->config->item('current_public_key', 'stripe');
        $this->testmode  =   ($this->config->item('testmode', 'stripe') == 'on')? 1 :0;

        //initialize the client
        require_once './system/static/stripe/init.php';
        \Stripe\Stripe::setApiKey($this->current_private_key);	
	}
	
	// Vedmir - Admin landing page
	public function filter($filterBy = 'today'){
		$this->index($filterBy);
	}

	public function index ($filterBy = '') {
		$query	= "SELECT COUNT(1) as totalUsers , (SELECT COUNT(1) FROM vm_auth WHERE `role` = 'teacher') as totalTeachers FROM vm_auth WHERE `role` = 'user'";
		$this->outputdata['statisticsData'] =	$this->Common_model->exequery($query,1);
		$this->load->viewD('admin/welcome_view',$this->outputdata);
	}

	public function indexOld($filterBy = '') {
		if ($filterBy == 'lastweek'){
			$cond = " AND (addedOn between date_sub(now(),INTERVAL 1 WEEK) and now())";
			$cond1 = $cond2 = " AND (paymentDate between date_sub(now(),INTERVAL 1 WEEK) and now())";
		}
		else if ($filterBy == 'year'){
			$cond = " AND (addedOn  between date_sub(now(),INTERVAL 1 YEAR) and now()) ";
			$cond1 = $cond2 = " AND (paymentDate  between date_sub(now(),INTERVAL 1 YEAR) and now()) ";
		}
		else if ($filterBy == 'today'){
			$cond = " AND  DATE(`addedOn`) = CURDATE()";
			$cond1 = $cond2 = " AND  DATE(`paymentDate`) = CURDATE()";
		}
		else{
			$cond = "";
			$cond1 = "";
			$cond2 = " AND paymentDate > (NOW() - INTERVAL 1 MONTH)";
		}
		
		$query	=	"SELECT count(*) as totalProduct,
		(SELECT count(DISTINCT userId) FROM vm_user_memberships WHERE userId >0 $cond1 ) as TotalAmountOfSubscribers,
		(SELECT count(DISTINCT userId) FROM vm_user_memberships where giftId = 0 AND subscriptionStatus='Active' AND endDate >='".date('Y-m-d')."' AND isPrevoiusLog='0' $cond1 ) as TotalAamountOfVikings,
		(SELECT Sum(subscriptionAmount) FROM vm_user_memberships WHERE subscriptionAmount > 0 $cond1 ) as TotalRevenueMadeByTheSubscribers,
		(SELECT Sum(amt) FROM vm_order where orderStatus = 'Completed' $cond ) as TotalRevenueMadeByTheRestaurantOrders,
		(SELECT count(DISTINCT userId) FROM vm_user_memberships where userId > 0 AND subscriptionStatus='Active' AND endDate >='".date('Y-m-d')."' AND isPrevoiusLog='0' $cond2) as TotalAmountOfNewSubscribersThisMonth,
		(SELECT COUNT(*) AS Total FROM (SELECT COUNT(userId) AS cou FROM vm_user_memberships where userId > 0  $cond1  GROUP BY userId HAVING cou>1 ) as vv) AS TotalAmountOfRenewedSubscribers,
		(SELECT count(DISTINCT userId) FROM vm_user_memberships where userId > 0 AND couponId != 0 AND giftId = 0 AND subscriptionStatus='Active' AND endDate >='".date('Y-m-d')."' AND isPrevoiusLog='0' $cond1 )  AS couponSold,
		(SELECT count(DISTINCT userId) FROM vm_user_memberships where userId > 0 AND couponId = 0 AND subscriptionStatus='Active' AND endDate >='".date('Y-m-d')."' AND planId IN (5,6) AND isPrevoiusLog='0' $cond1   ) AS earlyBirdSubscription,		
		(SELECT count(restaurantId) FROM vm_restaurant where restaurantId > 0 $cond ) as totalRestaurant,
		(SELECT count(userId) FROM vm_user where userId > 0 $cond ) as totalUser,
		(SELECT count(orderId) FROM vm_order where orderStatus = 'Pending' $cond ) as totalPendingOrder,
		(SELECT count(orderId) FROM vm_order where orderStatus = 'Processing' $cond ) as totalOngoingOrder,
		(SELECT count(orderId) FROM vm_order where orderStatus = 'Completed' $cond ) as totalServedOrder,
		(SELECT count(orderId) FROM vm_order where orderStatus = 'Cancelled' $cond ) as totalRefundedOrder,
		(SELECT count(detailId) FROM vm_order_detail where detailId > 0 AND orderId IN(SELECT orderId FROM vm_order where orderId > 0 $cond) ) as totalProductOrder,
		(SELECT Sum(subtotal) FROM vm_order_detail where isServed = 1 $cond ) as totalOrderRevenue
		 from vm_product where productId != 0 $cond ";
		
		/* Calculate Stripe Account & Show Marquee Line */
		$availableAmt=0;$calculateMsg="";$billPayDate="";$langSuffix="";
		try{
			$langSuffix = $this->lang->line('langSuffix');
			$transfer=\Stripe\Balance::retrieve();
	        foreach($transfer->available as $d)
          		if($d->currency=='chf')
               		$availableAmt=$d->amount;
			
			if($availableAmt>0)
				$availableAmt=$availableAmt/100;
			
			$calculateMsg=(($langSuffix=='_fr')?"Solde disponible du compte Vedmir Stripe : CHF ".$availableAmt:(($langSuffix=='_gr')?"Vedmir Stripe Konto verfügbares Guthaben : CHF ".$availableAmt:(($langSuffix=='_it')?"Saldo disponibile del conto Vedmir Stripe : CHF ".$availableAmt:"Vedmir Stripe account available balance : CHF ".$availableAmt)));
			/* Calculate Total Order Amount */
			try{
				$d=date('d');$totalRecord=$totalPayoutAmount=0;$totalOrder=0;
				
				if($d<8){
					$previousDate=date("Y-m-t", strtotime("last month"));
					$billPayDate=(date('Y-m-'."07")==date('Y-m-d'))?"Today":date('Y-m-'."07");
				}
				else
				{	
					$m=date('m');
					$y=date('Y');
					$d=cal_days_in_month(CAL_GREGORIAN,$m,$y);
					$previousDate=$y."-".$m."-".$d;
					$billPayDate=date('Y-m-'."07", strtotime('+1 month'));
				}
				
				$q="SELECT *,ROUND(sum(restaturantAmount),2) as resreqamount,GROUP_CONCAT(concat(restaurantId,'^',ORDERId,'^',restaturantAmount)) as resorderamoutids,GROUP_CONCAT(ORDERId) as resorderids,count(ORDERId) as totalorders FROM `vm_order` where orderStatus='Completed' AND  restaurantSettlement=0 AND paymentStatus='Completed' AND isTrail='".$this->testmode."' AND STR_TO_DATE(addedOn,'%Y-%m-%d')<='".$previousDate."' group by restaurantId having resreqamount>0";
				
				$qData =	$this->Common_model->exequery($q);
				
				if (valResultSet($qData)) {
					$totalRecord=count($qData);
					
					foreach ($qData as $d) {
						$resreqamount=number_format($d->resreqamount,2);
						$totalOrder+=$d->totalorders;
	        			$totalPayoutAmount+=$resreqamount;
					}
					$calculateMsg.=(($langSuffix=='_fr')?" Bill en attente du nombre total de lieux : ".$totalRecord." & total des commandes  : ".$totalOrder." & montant total : CHF ".$totalPayoutAmount." de tous les lieux jusqu'à ".$previousDate." & Date de paiement suivante ".$billPayDate.".":(($langSuffix=='_gr')?" Noch ausstehende Rechnung der Gesamtstandorte : ".$totalRecord." & Gesamtbestellungen  : ".$totalOrder." & Gesamtmenge : CHF ".$totalPayoutAmount." von allen Orten bis ".$previousDate." & Nächstes Auszahlungsdatum ".$billPayDate.".":(($langSuffix=='_it')?" Bill in attesa di sedi totali : ".$totalRecord." e ordini totali  : ".$totalOrder." & importo totale : CHF ".$totalPayoutAmount." di tutti i luoghi fino a ".$previousDate." & Prossima data di pagamento ".$billPayDate.".":" Bill pending of total venues : ".$totalRecord." & total orders  : ".$totalOrder." & total amount : CHF ".$totalPayoutAmount." of all venues till ".$previousDate." & Next payout date ".$billPayDate.".")));
				}
				
			}
			catch(Exception $e){
				
			}
		}
		catch(Exception $e){}
		
		$this->outputdata['filterBy'] =	$filterBy;
		$this->outputdata['statisticsData'] =	'';//$this->Common_model->exequery($query,1);
		$this->load->viewD('admin/welcome_view',$this->outputdata);
		
	}
	
	// Vedmir - Admin landing page
	public function category($restaurantId){


		$subcategoryData =	$this->Common_model->exequery("SELECT * from vm_product_subcategory where restaurantId =27 and subcategoryId IN ( 146, 147, 148, 149 )");
		if (valResultSet($subcategoryData)) {
			echo "subcategoryData mil gya <br>";
			foreach ($subcategoryData as $subcategory) {
				
				$subcategoryItemData =	$this->Common_model->exequery("SELECT * from vm_product_subcategoryitem where subcategoryId =".$subcategory->subcategoryId);

				$subcategory->subcategoryItemData = (valResultSet($subcategoryItemData))?$subcategoryItemData:array();
			}

			foreach ($subcategoryData as $subcategory) {

				$insertData   =  array();
				$insertData['categoryId']	 		=   $subcategory->categoryId;
				$insertData['subcategoryName']		=   $subcategory->subcategoryName;
				$insertData['subcategoryName_fr']	=   $subcategory->subcategoryName_fr;
				$insertData['subcategoryName_gr']	=   $subcategory->subcategoryName_gr;
				$insertData['subcategoryName_it']	=   $subcategory->subcategoryName_it;
				$insertData['updatedOn']	 		=   date('Y-m-d H:i:s');
				$slug = $this->common_lib->create_unique_slug($subcategory->subcategoryName,"vm_product_subcategory","subcategoryName",0,"subcategoryId",$counter=0);
				$insertData['slug']			 =   $slug;
				$insertData['restaurantId']	 =   $restaurantId;
				$insertData['addedOn']	 	 =   date('Y-m-d H:i:s');
				$subcategoryId 		= 	$this->Common_model->insertUnique("vm_product_subcategory", $insertData);

			echo "subcategoryData insert ho gya - $subcategoryId <br>";
				if ($subcategoryId > 0 && !empty($subcategory->subcategoryItemData) && count($subcategory->subcategoryItemData) > 0) {
					foreach ($subcategory->subcategoryItemData as $item) {
						$insertData   =  array();
						$insertData['categoryId']	 			=   $subcategory->categoryId;
						$insertData['subcategoryId']	 		=   $subcategoryId;
						$insertData['subcategoryitemName']		=   $item->subcategoryitemName;
						$insertData['subcategoryitemName_fr']	=   $item->subcategoryitemName_fr;
						$insertData['subcategoryitemName_gr']	=   $item->subcategoryitemName_gr;
						$insertData['subcategoryitemName_it']	=   $item->subcategoryitemName_it;
						$insertData['updatedOn']	 		=   date('Y-m-d H:i:s');
						$insertData['restaurantId']	 =   $restaurantId;


						$slug = $this->common_lib->create_unique_slug($item->subcategoryitemName,"vm_product_subcategoryitem","subcategoryitemName",0,"subcategoryitemId",$counter=0);
							$insertData['slug']			 =   $slug;
							$insertData['addedOn']	 	 =   date('Y-m-d H:i:s');
							$this->Common_model->insert("vm_product_subcategoryitem", $insertData);

			echo "subcategoryData insert ho gya - $item->subcategoryitemName <br>";
					}
				}
			}
			
		}

	}

	// Vedmir - Admin landing page
	public function product($restaurantId){	

		$productData =	$this->Common_model->exequery("SELECT * from vm_product where restaurantId = 27 and productId between 934 and 952");

		foreach ($productData as $product) {
			$product->variableData = array();
			if ($product->productType == 1) {
				$product->variableData =	$this->Common_model->exequery("SELECT * from vm_variable_product where productId =".$product->productId);
			}
		}
		// v3print($productData);exit;
		foreach ($productData as $value) {
			
			$subcategoryData =	$this->Common_model->exequery("SELECT * from vm_product_subcategory where subcategoryId =".$value->subcategoryId,1);
			if (valResultSet($subcategoryData)) {
				
				$existSubcategoryData = $this->Common_model->selRowData("vm_product_subcategory","","subcategoryName = '".$subcategoryData->subcategoryName."' and restaurantId = '".$restaurantId."' and categoryId = '".$value->categoryId."'");
				if (valResultSet($existSubcategoryData)) {
					$value->subcategoryId= $existSubcategoryData->subcategoryId;
				}
			}

			if ($value->subcategoryitemId > 0) {
				$subcategoryItemData =	$this->Common_model->exequery("SELECT * from vm_product_subcategoryitem where subcategoryitemId =".$value->subcategoryitemId,1);
				if (valResultSet($subcategoryItemData)) {
					
					$existSubcategoryItemData = $this->Common_model->selRowData("vm_product_subcategoryitem","","subcategoryitemName = '".$subcategoryItemData->subcategoryitemName."' and restaurantId = '".$restaurantId."' and categoryId = '".$value->categoryId."'");
					if (valResultSet($existSubcategoryItemData)) {
						$value->subcategoryitemId= $existSubcategoryItemData->subcategoryitemId;
					}
				}
			}


			$insertData   =  array();
			$insertData['restaurantId']	 		=   $restaurantId;
			$insertData['productType']	 		=   $value->productType;
			$insertData['categoryId']	 		=   $value->categoryId;
			$insertData['subcategoryId']		=   $value->subcategoryId;
			$insertData['subcategoryitemId']	=   $value->subcategoryitemId;
			$insertData['productName']	 		=   $value->productName;
			$insertData['productName_fr']	 	=   $value->productName_fr;
			$insertData['productName_gr']	 	=   $value->productName_gr;
			$insertData['productName_it']	 	=   $value->productName_it;
			$insertData['sortDescription'] 		=   $value->sortDescription;
			$insertData['sortDescription_fr'] 	=   $value->sortDescription_fr;
			$insertData['sortDescription_gr'] 	=   $value->sortDescription_gr;
			$insertData['sortDescription_it'] 	=   $value->sortDescription_it;
			$insertData['description']	 		=   $value->description;
			$insertData['description_fr']	 	=   $value->description_fr;
			$insertData['description_gr']	 	=   $value->description_gr;
			$insertData['description_it']	 	=   $value->description_it;
			$insertData['price']	 	 		=   $value->price;
			$insertData['tags']	 		 		=   $value->tags;
			$insertData['tags_fr']	 		 	=   $value->tags_fr;
			$insertData['tags_gr']	 		 	=   $value->tags_gr;
			$insertData['tags_it']	 		 	=   $value->tags_it;
			$insertData['isFeatured']	 		=   $value->isFeatured;
			$insertData['isAvailableInFree']	=   $value->isAvailableInFree;
			$insertData['updatedOn']	 		=   date('Y-m-d H:i:s');
			$insertData['addedOn']	 			=   date('Y-m-d H:i:s');

			$slug = $this->common_lib->create_unique_slug($value->productName,"vm_product","productName",0,"productId",$counter=0);
			$insertData['slug']			 =   $slug;

			$productId 		= 	$this->Common_model->insertUnique("vm_product", $insertData);

			if($productId){

				if (!empty($value->variableData) && count($value->variableData) > 0 && $productId > 0) {

					foreach ($value->variableData as $variable) {
							
						$queryData = array();		
						$queryData['productId']		=   $productId;
						$queryData['variableName']	=   $variable->variableName;
						$queryData['variableName_gr']=   $variable->variableName_gr;
						$queryData['variableName_fr']=   $variable->variableName_fr;
						$queryData['variableName_it']=   $variable->variableName_it;
						$queryData['updatedOn']		=   date('Y-m-d H:i:s');
						$queryData['price']			=   $variable->price;
						$queryData['addedOn']		=   date('Y-m-d H:i:s');

						$this->Common_model->insert("vm_variable_product", $queryData);
					}
				}
			}
		}
	}


	// Vedmir - Admin landing page
	public function delete($restaurantId){	

		$this->Common_model->del("vm_product","DATE(addedOn) =CURDATE() and restaurantId=".$restaurantId);
		$this->Common_model->del("vm_variable_product","DATE(addedOn) =CURDATE() ");
		$this->Common_model->del("vm_product_subcategory","DATE(addedOn) =CURDATE() and restaurantId=".$restaurantId);
		$this->Common_model->del("vm_product_subcategoryitem","DATE(addedOn) =CURDATE() and restaurantId=".$restaurantId);
	}

	// Vedmir - Admin landing page
	public function mailcheck(){
		//Send welcome email
		$passwordKeyUrl=BASEURL.'/system/static/emailTemplates/images/key.png';
		$settings = array();
		$settings["template"] 			=  "password_changed_tpl".$this->lang->line('langSuffix').".html";
		$settings["email"] 				=  'dsmail.vivek@gmail.com';
		$settings["subject"] 			=  "VEDMIR Dashboard - password has been changed";
		$contentarr['[[[USERNAME]]]']	=	'dsmail.vivek@gmail.com';
		$contentarr['[[[PASSWORD]]]']	=	'123456';
		$contentarr['[[[DASHURL]]]']	=	DASHURL."/".$this->sessRole."/login";
		$contentarr['[[[PASSWORDKEYURL]]]'] =   $passwordKeyUrl;
		$settings["contentarr"] 		= 	$contentarr;
		echo $this->common_lib->sendMail($settings);	
		
	}

	public function lang_excel () {
		$langArr = array();
		$currentLang = $this->lang->line('language');
		$langs = array('english', 'french');
		foreach ($langs as $key => $language) {
			$this->lang->load('custom_messages',$language);
			$langArr[$language] = $this->lang->language;
		}
		$this->lang->load('custom_messages',$currentLang);		 


		  function cleanData(&$str)
		  {
		    if($str == 't') $str = 'TRUE';
		    if($str == 'f') $str = 'FALSE';
		    if(preg_match("/^0/", $str) || preg_match("/^\+?\d{8,}$/", $str) || preg_match("/^\d{4}.\d{1,2}.\d{1,2}/", $str)) {
		      $str = "'$str";
		    }
		    if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
		    $str = mb_convert_encoding($str, 'UTF-16LE', 'UTF-8');
		  }
		  // filename for download
		  // $filename = "english_data_" . date('Ymd') . ".xls";

		  // header("Content-Disposition: attachment; filename=\"$filename\"");
		  // header("Content-Type: application/vnd.ms-excel");
		  // echo "english"."\r\n";
		  // array_walk($langArr['english'], __NAMESPACE__ . '\cleanData');
		  // echo implode("\r\n", array_values($langArr['english'])) . "\t";


		  $filename = "french_data_" . date('Ymd') . ".csv";

		  header("Content-Disposition: attachment; filename=\"$filename\"");
		  header("Content-Type: text/csv; charset=UTF-16LE");

		  
		  echo "french"."\r\n";
		  array_walk($langArr['french'], __NAMESPACE__ . '\cleanData');
		  echo implode("\r\n", array_values($langArr['french'])) . "\t";
		  exit;



	}


	
}