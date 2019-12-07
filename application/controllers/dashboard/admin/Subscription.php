
<?php

defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by Dream Steps Pvt Ltd
 * Created on 30 Nov 2019
 * Vedmir -  module
**/
class Subscription extends CI_Controller {
	
	public $menu		= 0;
	public $subMenu		= 0;
	public $outputdata 	= array();
	public $langSuffix = '';
	
	public function __construct(){
		parent::__construct();
		//Check login authentication & set public veriables
		$this->session->set_userdata(PREFIX.'sessRole', "admin");
		$this->common_lib->setSessionVariables();
		$this->langSuffix = $this->lang->line('langSuffix');
		//	echo $this->sessName;

		//load config
        $this->load->config('stripe', TRUE);

        //get settings from config
        $this->current_private_key = $this->config->item('current_private_key', 'stripe');
        $this->current_public_key  = $this->config->item('current_public_key', 'stripe');


        //initialize the client
        require_once './system/static/stripe/init.php';
        \Stripe\Stripe::setApiKey($this->current_private_key);	
	}
	//
	public function subscription_list(){
		$this->menu = 7;
		$this->subMenu = 74;
		$this->load->viewD($this->sessRole.'/subscription_list',$this->outputdata);
	}
	//Get Data By Id
	public function update_subscription($subid){
		$this->menu = 7;
		$this->subMenu = 73;
		$query="SELECT * from vm_subscription_plan where status != '2'";
		$this->outputdata['subList']=$this->Common_model->exequeryarray($query,0);
		$langSuffix = $this->lang->line('langSuffix');
		if(isset($subid) && !empty($subid) && $subid != 0) {
			$subdata = $this->Common_model->selRowData("vm_subscription_plan","","id = ".$subid);
			if(!$subdata)
				$this->load->viewD($this->sessRole.'/subscription_list',$this->outputdata);
			if (isset( $_POST ) && !empty( $_POST )){
				$error_message = array();
				$subdetails = array();
				$subdetails = $_POST;
				if(isset($_POST['hiddenval']) && !empty($_POST['hiddenval'])) {
					if(isset($_POST['plan_name'.$langSuffix]) && empty($_POST['plan_name'.$langSuffix]))
						$error_message['plan_name'] = $this->lang->line('planNameRequired');
					if(empty($error_message)) {
						try {
							$istrailperiod=(!empty($_POST['trialperioddays']))?$_POST['trialperioddays']:0;
							$p = \Stripe\Product::retrieve($subdata->productId);
							
							if (!empty($p) && !empty($p["id"])) {
								
								$p->name= $_POST['plan_name'.$langSuffix];
								if($p->save()){
									$updateData = array();
									$_POST['plan_name'] = (isset($_POST['plan_name']) && empty($_POST['plan_name']) && $this->sessLang != 'english') ? $this->common_lib->changeLanguage($_POST['plan_name'.$langSuffix],$this->lang->line('lang'),'en') : $_POST['plan_name'] ;

									$_POST['plan_name_fr'] = (isset($_POST['plan_name_fr']) && empty($_POST['plan_name_fr']) && $this->sessLang != 'french') ? $this->common_lib->changeLanguage($_POST['plan_name'.$langSuffix],$this->lang->line('lang'),'fr') : $_POST['plan_name_fr'] ; 

									$_POST['plan_name_gr'] = (isset($_POST['plan_name_gr']) && empty($_POST['plan_name_gr']) && $this->sessLang != 'german') ? $this->common_lib->changeLanguage($_POST['plan_name'.$langSuffix],$this->lang->line('lang'),'de') : $_POST['plan_name_gr'] ; 

									$_POST['plan_name_it'] = (isset($_POST['plan_name_it']) && empty($_POST['plan_name_it']) && $this->sessLang != 'italian') ? $this->common_lib->changeLanguage($_POST['plan_name'.$langSuffix],$this->lang->line('lang'),'it') : $_POST['plan_name_it'] ; 

									
									
									$_POST['description'] = (isset($_POST['description']) && empty($_POST['description']) && $this->sessLang != 'english') ? $this->common_lib->changeLanguage($_POST['description'.$langSuffix],$this->lang->line('lang'),'en') : $_POST['description'] ;

									$_POST['description_fr'] = (isset($_POST['description_fr']) && empty($_POST['description_fr']) && $this->sessLang != 'french') ? $this->common_lib->changeLanguage($_POST['description'.$langSuffix],$this->lang->line('lang'),'fr') : $_POST['description_fr'] ; 

									$_POST['description_gr'] = (isset($_POST['description_gr']) && empty($_POST['description_gr']) && $this->sessLang != 'german') ? $this->common_lib->changeLanguage($_POST['description'.$langSuffix],$this->lang->line('lang'),'de') : $_POST['description_gr'] ; 

									$_POST['description_it'] = (isset($_POST['description_it']) && empty($_POST['description_it']) && $this->sessLang != 'italian') ? $this->common_lib->changeLanguage($_POST['description'.$langSuffix],$this->lang->line('lang'),'it') : $_POST['description_it'] ;
									$updateData['planName'] = $_POST['plan_name'];
									$updateData['description'] = $_POST['description'];
									$updateData['planName_fr'] = $_POST['plan_name_fr'];
									$updateData['description_fr'] = $_POST['description_fr'];
									$updateData['planName_gr'] = $_POST['plan_name_gr'];
									$updateData['description_gr'] = $_POST['description_gr'];
									$updateData['planName_it'] = $_POST['plan_name_it'];
									$updateData['description_it'] = $_POST['description_it'];
									$updateData['trialperioddays'] = $istrailperiod;
									if(isset($_POST['freeperiod']) && !empty($_POST['freeperiod']))
										$updateData['numberFreeDrink'] = $_POST['freeperiod'];
									if(isset($_POST['freeduration']) && !empty($_POST['freeduration']))
										$updateData['freeDrinkPeriod'] = $_POST['freeduration'];
									if(isset($_FILES['uploadImg']['tmp_name']) && !empty($_FILES['uploadImg']['tmp_name'])){

										if(is_uploaded_file($_FILES['uploadImg']['tmp_name']) != "") {
												$photoToUpload = 	date('Ymdhis').str_replace(' ', '_', $_FILES['uploadImg']['name']);
												$uploadSettings = array();
												$uploadSettings['upload_path']   	=	ABSUPLOADPATH."/";
												$uploadSettings['allowed_types'] 	=	'gif|jpg|png';
												$uploadSettings['file_name']	  	= 	$photoToUpload;
												$uploadSettings['inputFieldName']  	=  	"uploadImg";

												$fileUpload = $this->common_lib->_doUpload($uploadSettings);

												if ($fileUpload) 
													$updateData['icon']		=   $photoToUpload;

											}

									}
									$cond ="Id = ".$subid;
									$updateSubscription = $this->Common_model->update('vm_subscription_plan', $updateData, $cond);
									if($updateSubscription)
										$this->outputdata['successMessage'] = $this->lang->line('updatePlanData');
									else
										$error_message['submit_error'] =$this->lang->line('dbError');
								}
								else
									$error_message['submit_error'] =$this->lang->line('dbError');
							}
							else
								$error_message['submit_error'] =$this->lang->line('dbError');
						}
						catch (Exception $e) {
							$error_message['payment_message'] = $e->getMessage();
						}
					}
				}
				else
					$error_message['plan_id']=$this->lang->line('planIDEmpty');
				
				$this->outputdata['error_message'] = $error_message;
				$this->outputdata['SubData'] =	$subdetails;
				$this->load->viewD('admin/subscription_update_view', $this->outputdata);
			}
			else{
					$this->outputdata['SubData'] =	$subdata;
					$this->load->viewD('admin/subscription_update_view', $this->outputdata);
				
				}		
		}
		else
			$this->load->viewD($this->sessRole.'/subscription_list',$this->outputdata);
	}
	public function view($subscriptionId = 0){
		$this->menu = 7;
		$this->subMenu = 74;

        $this->outputdata['plan'] = $this->Common_model->exequery("SELECT *,planName$this->langSuffix as planName from vm_subscription_plan where status != '2' AND id= $subscriptionId",1);
        $this->outputdata['planData'] = $this->Common_model->exequery("SELECT * from vm_subscription_details where status != '2' AND subscriptionId= $subscriptionId");
        if (!$this->outputdata['plan'])
        	redirect(DASHURL.'/admin/subscription/subscription_list');
		$this->load->viewD('admin/view_subscription_info', $this->outputdata);
	}
	public function add_subscription(){
		$this->menu = 7;
		$this->subMenu = 73;
		$error_message = array();
		$langSuffix = $this->lang->line('langSuffix');
		if (isset( $_POST ) && !empty( $_POST )){
				/*if(isset($_POST['plan_sub_id']) && empty($_POST['plan_sub_id']))
					$error_message['plan_sub_id'] = $this->lang->line('planIdRequired');*/
				if(isset($_POST['plan_name'.$langSuffix]) && empty($_POST['plan_name'.$langSuffix]))
					$error_message['plan_name'] = $this->lang->line('planNameRequired');
				if(isset($_POST['period']) && empty($_POST['period']))
					$error_message['period'] = $this->lang->line('periodRequired');
				if(isset($_POST['duration']) && empty($_POST['duration']))
					$error_message['duration'] = $this->lang->line('durationRequired');
				if(isset($_POST['currency']) && empty($_POST['currency']))
					$error_message['currency'] = $this->lang->line('currencyRequired');
				if(isset($_POST['amount']) && empty($_POST['amount']))
					$error_message['amount'] = $this->lang->line('amountRequired');
				if(isset($_POST['freeperiod']) && empty($_POST['freeperiod']))
					$error_message['freeperiod'] = $this->lang->line('freeperiodRequired');
				if(isset($_POST['freeduration']) && empty($_POST['freeduration']))
					$error_message['freeduration'] = $this->lang->line('freedurationRequired');
				if(empty($error_message)) {
						try {
							    $istrailperiod=0;
								$subscriptionPlanData = $this->Common_model->exequery("SELECT * FROM vm_subscription_plan WHERE status !='2' AND planName$langSuffix='".$_POST['plan_name'.$langSuffix]."'",true);
								if ($subscriptionPlanData) 									
									$error_message['plan_error'] = sprintf($this->lang->line('alreadyPlanId'),$subscriptionPlanData->planId);	
								else{
									
									$product = \Stripe\Product::create([
									    'name' => $_POST['plan_name'.$langSuffix],
									    'type' => 'service',
									]);

									if(isset($product->id) && !empty($product->id)) {
										$productId = $product->id;
										$_POST['plan_name'] = (isset($_POST['plan_name']) && empty($_POST['plan_name']) && $this->sessLang != 'english') ? $this->common_lib->changeLanguage($_POST['plan_name'.$langSuffix],$this->lang->line('lang'),'en') : $_POST['plan_name'] ;

										$_POST['plan_name_fr'] = (isset($_POST['plan_name_fr']) && empty($_POST['plan_name_fr']) && $this->sessLang != 'french') ? $this->common_lib->changeLanguage($_POST['plan_name'.$langSuffix],$this->lang->line('lang'),'fr') : $_POST['plan_name_fr'] ; 

										$_POST['plan_name_gr'] = (isset($_POST['plan_name_gr']) && empty($_POST['plan_name_gr']) && $this->sessLang != 'german') ? $this->common_lib->changeLanguage($_POST['plan_name'.$langSuffix],$this->lang->line('lang'),'de') : $_POST['plan_name_gr'] ; 

										$_POST['plan_name_it'] = (isset($_POST['plan_name_it']) && empty($_POST['plan_name_it']) && $this->sessLang != 'italian') ? $this->common_lib->changeLanguage($_POST['plan_name'.$langSuffix],$this->lang->line('lang'),'it') : $_POST['plan_name_it'] ; 

										
										
										$_POST['description'] = (isset($_POST['description']) && empty($_POST['description']) && $this->sessLang != 'english') ? $this->common_lib->changeLanguage($_POST['description'.$langSuffix],$this->lang->line('lang'),'en') : $_POST['description'] ;

										$_POST['description_fr'] = (isset($_POST['description_fr']) && empty($_POST['description_fr']) && $this->sessLang != 'french') ? $this->common_lib->changeLanguage($_POST['description'.$langSuffix],$this->lang->line('lang'),'fr') : $_POST['description_fr'] ; 

										$_POST['description_gr'] = (isset($_POST['description_gr']) && empty($_POST['description_gr']) && $this->sessLang != 'german') ? $this->common_lib->changeLanguage($_POST['description'.$langSuffix],$this->lang->line('lang'),'de') : $_POST['description_gr'] ; 

										$_POST['description_it'] = (isset($_POST['description_it']) && empty($_POST['description_it']) && $this->sessLang != 'italian') ? $this->common_lib->changeLanguage($_POST['description'.$langSuffix],$this->lang->line('lang'),'it') : $_POST['description_it'] ;
										$currency = (isset($_POST['currency'][0]) && !empty($_POST['currency'][0])) ? $_POST['currency'][0] : "CHF";
										$subscriptionItem  = array('productId'=>$productId,'planName'=>$_POST['plan_name'],'description'=>$_POST['description'],'planName_fr'=>$_POST['plan_name_fr'],'description_fr'=>$_POST['description_fr'],'planName_gr'=>$_POST['plan_name_gr'],'description_gr'=>$_POST['description_gr'],'planName_it'=>$_POST['plan_name_it'],'description_it'=>$_POST['description_it'],'currency'=>$currency,'numberFreeDrink' =>$_POST['freeperiod'], 'freeDrinkPeriod' => $_POST['freeduration'],'createdDate'=>date('Y-m-d H:i:s'),'isSubType'=>1);
										if(isset($_FILES['uploadImg']['tmp_name']) && !empty($_FILES['uploadImg']['tmp_name'])){

											if(is_uploaded_file($_FILES['uploadImg']['tmp_name']) != ""){
												$photoToUpload = 	date('Ymdhis').str_replace(' ', '_', $_FILES['uploadImg']['name']);
												$uploadSettings = array();
												$uploadSettings['upload_path']   	=	ABSUPLOADPATH."/";
												$uploadSettings['allowed_types'] 	=	'gif|jpg|png';
												$uploadSettings['file_name']	  	= 	$photoToUpload;
												$uploadSettings['inputFieldName']  	=  	"uploadImg";

												$fileUpload = $this->common_lib->_doUpload($uploadSettings);

												if ($fileUpload) 
													$subscriptionItem['icon']		=   $photoToUpload;

											}

										}
										$createSubscription = $this->Common_model->insertUnique('vm_subscription_plan', $subscriptionItem);
										if( !$createSubscription)
											$error_message['submit_error'] =$this->lang->line('dbError');
										else if(isset($_POST['duration']) && !empty($_POST['duration'])) {
											foreach( $_POST['duration'] as $key => $period ) {
												if(!empty($period) && isset($_POST['duration'][$key]) && !empty($_POST['duration'][$key]) && isset($_POST['amount'][$key]) && !empty($_POST['amount'][$key])) {
													try {
														$currency = (isset($_POST['currency'][$key]) && !empty($_POST['currency'][$key])) ? $_POST['currency'][$key] : "CHF";
														$istrailperiod=0;
														if($period==12) {
															$period = 1;
															$duration = 'year';
														}
														else
															$duration = 'month';
														
														$plan = \Stripe\Plan::create([
														    'currency' => $currency,
														    'interval' => $duration,
														    "interval_count"=>$period,
														    'product' => $productId,	
														    'trial_period_days' => $istrailperiod,	    
														    'amount' => number_format($_POST['amount'][$key],2)*100,
														]);

														if(isset($plan->id) && !empty($plan->id)) 
															$planDetails = $this->Common_model->insertUnique('vm_subscription_details', array("subscriptionId" => $createSubscription, "trialperioddays" => ($plan->trial_period_days==null)?0:$plan->trial_period_days, "period" => $period, "duration" =>$duration, "amount" => $_POST['amount'][$key], 'actualAmount' => (isset($_POST['actualAmount'][$key]) && !empty($_POST['actualAmount'][$key])) ? $_POST['actualAmount'][$key] : 0, "currency" => $currency, "status" => 0, "planId" => $plan->id));

													}
													catch (Exception $e) {
														echo $e->getMessage();
													}
												}
											}
											$this->outputdata['successMessage'] = $this->lang->line('successPlanData');
										}

									}
									else
										$error_message['submit_error'] =$this->lang->line('dbError');
								}
		                }
		                catch (Exception $e) {
		                    $error_message['payment_message'] = $e->getMessage();
		                }
				}
		}
		$this->outputdata['error_message'] = $error_message;
		$this->load->viewD('admin/subscription_add_view', $this->outputdata);
	}
	// Coupon Section
	public function coupon_list(){
		$this->menu = 7;
		$this->subMenu = 75;
		$subscriptionList = $this->Common_model->exequery("SELECT id as planId, planName".$this->langSuffix." as planName FROM vm_subscription_plan WHERE status='0' AND isSubType='1'");
		$this->outputdata['subscriptionList'] = ($subscriptionList) ? $subscriptionList : array();
		$this->load->viewD($this->sessRole.'/membership_coupon_list',$this->outputdata);
	}
	public function add_ambassadorplan(){
		$this->menu = 7;
		$this->subMenu = 74;
		$error_message = array();
		$langSuffix = $this->lang->line('langSuffix');
		if (isset( $_POST ) && !empty( $_POST )){
				
				if(isset($_POST['plan_name'.$langSuffix]) && empty($_POST['plan_name'.$langSuffix]))
					$error_message['plan_name'] = $this->lang->line('planNameRequired');
				if(isset($_POST['period']) && empty($_POST['period']))
					$error_message['period'] = $this->lang->line('periodRequired');
				if(isset($_POST['duration']) && empty($_POST['duration']))
					$error_message['duration'] = $this->lang->line('durationRequired');
				if(isset($_POST['currency']) && empty($_POST['currency']))
					$error_message['currency'] = $this->lang->line('currencyRequired');
				if(!isset($_POST['amount']))
					$error_message['amount'] = $this->lang->line('amountRequired');
				if(isset($_POST['freeperiod']) && empty($_POST['freeperiod']))
					$error_message['freeperiod'] = $this->lang->line('freeperiodRequired');
				if(isset($_POST['freeduration']) && empty($_POST['freeduration']))
					$error_message['freeduration'] = $this->lang->line('freedurationRequired');
				if(isset($_POST['discountAmount']) && !empty($_POST['discountAmount'])) {
					$check = 0;
					foreach($_POST['discountAmount'] as $discountAmount ) {
						if($discountAmount > 100)
							$check++;
					}
					if($check > 0 )
						$error_message['freeduration'] = $this->lang->line('freedurationRequired');
				}
				if(empty($error_message)) {
						try {
							    $istrailperiod=0;
								$subscriptionPlanData = $this->Common_model->exequery("SELECT * FROM vm_subscription_plan WHERE status !='2' AND planName$langSuffix='".$_POST['plan_name'.$langSuffix]."'",true);
								if ($subscriptionPlanData) 									
									$error_message['plan_error'] = sprintf($this->lang->line('alreadyPlanId'),$subscriptionPlanData->planId);	
								else{
									
									$product = \Stripe\Product::create([
									    'name' => $_POST['plan_name'.$langSuffix],
									    'type' => 'service',
									]);

									if(isset($product->id) && !empty($product->id)) {
										$productId = $product->id;
										$_POST['plan_name'] = (isset($_POST['plan_name']) && empty($_POST['plan_name']) && $this->sessLang != 'english') ? $this->common_lib->changeLanguage($_POST['plan_name'.$langSuffix],$this->lang->line('lang'),'en') : $_POST['plan_name'] ;

										$_POST['plan_name_fr'] = (isset($_POST['plan_name_fr']) && empty($_POST['plan_name_fr']) && $this->sessLang != 'french') ? $this->common_lib->changeLanguage($_POST['plan_name'.$langSuffix],$this->lang->line('lang'),'fr') : $_POST['plan_name_fr'] ; 

										$_POST['plan_name_gr'] = (isset($_POST['plan_name_gr']) && empty($_POST['plan_name_gr']) && $this->sessLang != 'german') ? $this->common_lib->changeLanguage($_POST['plan_name'.$langSuffix],$this->lang->line('lang'),'de') : $_POST['plan_name_gr'] ; 

										$_POST['plan_name_it'] = (isset($_POST['plan_name_it']) && empty($_POST['plan_name_it']) && $this->sessLang != 'italian') ? $this->common_lib->changeLanguage($_POST['plan_name'.$langSuffix],$this->lang->line('lang'),'it') : $_POST['plan_name_it'] ; 

										
										
										$_POST['description'] = (isset($_POST['description']) && empty($_POST['description']) && $this->sessLang != 'english') ? $this->common_lib->changeLanguage($_POST['description'.$langSuffix],$this->lang->line('lang'),'en') : $_POST['description'] ;

										$_POST['description_fr'] = (isset($_POST['description_fr']) && empty($_POST['description_fr']) && $this->sessLang != 'french') ? $this->common_lib->changeLanguage($_POST['description'.$langSuffix],$this->lang->line('lang'),'fr') : $_POST['description_fr'] ; 

										$_POST['description_gr'] = (isset($_POST['description_gr']) && empty($_POST['description_gr']) && $this->sessLang != 'german') ? $this->common_lib->changeLanguage($_POST['description'.$langSuffix],$this->lang->line('lang'),'de') : $_POST['description_gr'] ; 

										$_POST['description_it'] = (isset($_POST['description_it']) && empty($_POST['description_it']) && $this->sessLang != 'italian') ? $this->common_lib->changeLanguage($_POST['description'.$langSuffix],$this->lang->line('lang'),'it') : $_POST['description_it'] ;
										$currency = (isset($_POST['currency'][0]) && !empty($_POST['currency'][0])) ? $_POST['currency'][0] : "CHF";
										$subscriptionItem  = array('productId'=>$productId,'planName'=>$_POST['plan_name'],'description'=>$_POST['description'],'planName_fr'=>$_POST['plan_name_fr'],'description_fr'=>$_POST['description_fr'],'planName_gr'=>$_POST['plan_name_gr'],'description_gr'=>$_POST['description_gr'],'planName_it'=>$_POST['plan_name_it'],'description_it'=>$_POST['description_it'],'currency'=>$currency,'numberFreeDrink' =>$_POST['freeperiod'], 'freeDrinkPeriod' => $_POST['freeduration'],'createdDate'=>date('Y-m-d H:i:s'),'isSubType'=>2);
										if(isset($_FILES['uploadImg']['tmp_name']) && !empty($_FILES['uploadImg']['tmp_name'])){

											if(is_uploaded_file($_FILES['uploadImg']['tmp_name']) != ""){
												$photoToUpload = 	date('Ymdhis').str_replace(' ', '_', $_FILES['uploadImg']['name']);
												$uploadSettings = array();
												$uploadSettings['upload_path']   	=	ABSUPLOADPATH."/";
												$uploadSettings['allowed_types'] 	=	'gif|jpg|png';
												$uploadSettings['file_name']	  	= 	$photoToUpload;
												$uploadSettings['inputFieldName']  	=  	"uploadImg";

												$fileUpload = $this->common_lib->_doUpload($uploadSettings);

												if ($fileUpload) 
													$subscriptionItem['icon']		=   $photoToUpload;

											}

										}
										$createSubscription = $this->Common_model->insertUnique('vm_subscription_plan', $subscriptionItem);
										if( !$createSubscription)
											$error_message['submit_error'] =$this->lang->line('dbError');
										else if(isset($_POST['duration']) && !empty($_POST['duration'])) {
											foreach( $_POST['duration'] as $key => $period ) {
												if(!empty($period) && isset($_POST['duration'][$key]) && !empty($_POST['duration'][$key]) && isset($_POST['amount'][$key])) {
													try {
														$currency = (isset($_POST['currency'][$key]) && !empty($_POST['currency'][$key])) ? $_POST['currency'][$key] : "CHF";
														$istrailperiod=0;
														if($period==12) {
															$period = 1;
															$duration = 'year';
														}
														else
															$duration = 'month';
														if( $_POST['amount'][$key] > 0 ) {
															$plan = \Stripe\Plan::create([
															    'currency' => $currency,
															    'interval' => $duration,
															    "interval_count"=>$period,
															    'product' => $productId,	
															    'trial_period_days' => $istrailperiod,	    
															    'amount' => number_format($_POST['amount'][$key],2)*100,
															]);
															$planId = $plan->id;
															$trailPeriod = ($plan->trial_period_days==null)?0:$plan->trial_period_days;
														}
														else {
															$planId = '';
															$_POST['isEnableAutoRenew'][$key] = 0;
															$trailPeriod = 0;
														}
														
														$planDetails = $this->Common_model->insertUnique('vm_subscription_details', array("subscriptionId" => $createSubscription, "trialperioddays" => $trailPeriod, "period" => $period, "duration" =>$duration, "amount" => $_POST['amount'][$key], "currency" => $currency, "status" => 0, "planId" => $planId, 'isAutoRenew' => $_POST['isEnableAutoRenew'][$key], 'rewardAmount' => $_POST['rewardAmount'][$key], 'discountAmount' => $_POST['discountAmount'][$key], 'actualAmount' => (isset($_POST['actualAmount'][$key]) && !empty($_POST['actualAmount'][$key])) ? $_POST['actualAmount'][$key] : 0 ));
														if($_POST['amount'][$key] > 0  && $_POST['discountAmount'][$key] > 0) {
															try {
																$this->Common_model->update("vm_subscription_coupon", array('status' => 2), "planId = ".$planDetails);
																$couponCode = 'AMBASSADOR'.str_replace('.', '', $_POST['discountAmount'][$key]);
																$couponCode = strtoupper($this->common_lib->create_unique_slug($couponCode,"vm_subscription_coupon","couponCode",0,"planId",$counter=0, ''));
																$couponData['duration'] = 'once';
																$couponData['planId'] = $planDetails;
												    			$couponData['discountType'] = 1;
												    			$couponData['counponName'] = $couponCode;
												    			$couponData['couponCode'] = $couponCode;
												    			$couponData['amount'] =  $_POST['discountAmount'][$key];
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
														        	$discountItem['percent_off'] = ($couponData['amount'] <= 100 ) ? $couponData['amount'] : 100;
												    			$couponDetails = \Stripe\Coupon::create($discountItem);
												    			$isTrail =  0;
												    			$couponData['isTrail'] = $isTrail;
												    			$couponData['addedOn'] = date('Y-m-d H:i:s');
												    			$referalStripeCouponId = $this->Common_model->insertUnique("vm_subscription_coupon", $couponData);
															}
															catch (Exception $e) {
																echo $e->getMessage();
															}
														}	

													}
													catch (Exception $e) {
														echo $e->getMessage();
													}
												}
											}
											$this->outputdata['successMessage'] = $this->lang->line('successPlanData');
										}

									}
									else
										$error_message['submit_error'] =$this->lang->line('dbError');
								}
		                }
		                catch (Exception $e) {
		                    $error_message['payment_message'] = $e->getMessage();
		                }
				}
		}
		$this->outputdata['error_message'] = $error_message;
		$this->load->viewD('admin/ambassador_plan_view', $this->outputdata);
	}
	public function ambassador_plan_list(){
		$this->menu = 7;
		$this->subMenu = 76;
		$this->load->viewD($this->sessRole.'/ambassador_list',$this->outputdata);
	}
}