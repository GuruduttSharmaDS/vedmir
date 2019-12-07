<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class Stripe extends REST_Controller {

    function __construct()
    {
		$testmode=0;
        // Construct the parent class
        parent::__construct();
        $this->methods['order_get']['limit'] = 500; // 500 requests per hour per order/key
        $this->methods['order_post']['limit'] = 500; // 100 requests per hour per order/key
        $this->methods['order_delete']['limit'] = 50; // 50 requests per hour per order/key
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


    //Create Account
    public function createcustomer_post(){
		// for client stripe account
		//\Stripe\Stripe::setApiKey("vm_test_s8jVgKwuJYN2H9YhZ2DnN1ks");
		// for developer stripe account of developer
		\Stripe\Stripe::setApiKey("vm_test_V762kq4w2HfjA2wh6NsvxIHl");
		/*$acct = \Stripe\Account::create([
		  "type" => "custom",
		  "country" => "US",
		  "external_account" => [
			"object" => "bank_account",
			"country" => "US",
			"currency" => "usd",
			"routing_number" => "110000000",
			"account_number" => "000123456789",
		  ],
		  "tos_acceptance" => [
			"date" => 1543317536,
			"ip" => "171.61.175.113",
		  ],
		]);*/
		$acct = \Stripe\Account::create([
			"country" => "CH",
			"type" => "custom"
		]);
		if($acct)
			$this->response([
				'data'=> $acct,
				'status' => True,
				'message' => 'Successfully'
			], REST_Controller::HTTP_OK);
	   else
		   $this->response([
				'data'=> '',
				'status' => FALSE,
				'message' => 'Server Error'
			], REST_Controller::HTTP_FORBIDDEN);
        
    }
	//Update Account
	public function updatecustomer_post(){
		// for client stripe account
		//\Stripe\Stripe::setApiKey("vm_test_s8jVgKwuJYN2H9YhZ2DnN1ks");
		// for developer stripe account of developer
		\Stripe\Stripe::setApiKey("vm_test_V762kq4w2HfjA2wh6NsvxIHl");

		$account = \Stripe\Account::retrieve($_POST['acc_id']);
		//$account->support_phone='2545415496';
		//$account->save();
		$this->response([
				'data'=> $account,
				'status' => True,
				'message' => 'Successfully'
			], REST_Controller::HTTP_OK);
		$account->tos_acceptance->date = time();
		$account->tos_acceptance->ip = $_SERVER['REMOTE_ADDR'];
		$account->legal_entity->personal_id_number=$_POST['personal_id_number'];
		
		

		$account->support_phone = $_POST['phonenumber'];
		$account->business_name = $_POST['business_name'];
		$account->business_url = $_POST['business_url'];
		$account->tos_acceptance->date = time();
		$account->tos_acceptance->ip = $_SERVER['REMOTE_ADDR'];
		$account->email = $_POST['email'];
		/* Legal Information */
		$account->legal_entity->business_name=$_POST['legal_business_name'];
		$account->legal_entity->address->city=$_POST['legal_address_city'];
		$account->legal_entity->address->line1=$_POST['legal_address_line1'];
		$account->legal_entity->address->line2=$_POST['legal_address_line2'];
		$account->legal_entity->address->postal_code=$_POST['legal_address_postal_code'];
		$account->legal_entity->address->state=$_POST['legal_address_state'];
		$account->legal_entity->dob->day=$_POST['legal_dob_day'];
		$account->legal_entity->dob->month=$_POST['legal_dob_month'];
		$account->legal_entity->dob->year=$_POST['legal_dob_year'];
		$account->legal_entity->first_name=$_POST['legal_first_name'];
		$account->legal_entity->last_name=$_POST['legal_last_name'];
		$account->legal_entity->personal_address->city=$_POST['legal_personal_address_city'];
		$account->legal_entity->personal_address->line1=$_POST['legal_personal_address_line1'];
		$account->legal_entity->personal_address->line2=$_POST['legal_personal_address_line2'];
		$account->legal_entity->personal_address->postal_code=$_POST['legal_personal_address_postal_code'];
		$account->legal_entity->type=$_POST['type'];
		//$account->legal_entity->ssn_last_4=$_POST['ssn_last_4'];
		//$account->legal_entity->personal_id_number=$_POST['personal_id_number'];
		//$data=array('country'=>$_POST['country'],'currency'=>$_POST['currency'],'routing_number'=>$_POST['routing_number'],'account_number'=>$_POST['account_number']);
		//$account->external_accounts->create(["external_account" => "btok_1Db5OJCTlsXjcRo2FCeNaCRp"]);
		/* $data = array(
            "object" => "bank_account",
            "country" => "US",
            "currency" => "usd",
            "account_holder_name" => 'Jane Austen',
            "account_holder_type" => 'individual',
            "routing_number" => "110000000",
            "account_number" => "000123456789"
        ); */
		
		/*$account->external_account->currency=$_POST['currency'];
		$account->external_account->routing_number=$_POST['routing_number'];
		$account->external_account->account_number=$_POST['account_number'];*/
		$account->external_account = [
			"object" => "bank_account",
			"country" => $_POST['country'],
			"currency" => $_POST['currency'],
			"routing_number" => $_POST['routing_number'],
			"account_number" => $_POST['account_number'],
		  ];
		
		if($account->save()){
			$this->response([
				'data'=> $account,
				'status' => True,
				'message' => 'Successfully'
			], REST_Controller::HTTP_OK);
		}
		else
		{
			$this->response([
				'data'=> $account,
				'status' => FALSE,
				'message' => 'Server Error'
			], REST_Controller::HTTP_FORBIDDEN);
		}
	}
	public function customercharge_post(){
		\Stripe\Stripe::setApiKey("vm_test_V762kq4w2HfjA2wh6NsvxIHl");
		try {
			$charge = \Stripe\Charge::create([
			  "amount" => $_POST['totalamt'],
			  "currency" => "chf",
			  "source" => "tok_visa",
			  "destination" => [
				"amount" => $_POST['vendoramt'],
				"account" => $_POST['acc_id'],
			  ],
			]);
			if (!empty($charge)){
				$this->response([
					'data'=> $charge,
					'status' => True,
					'message' => 'Successfully'
				], REST_Controller::HTTP_OK);
			}
			else{
				$this->response([
					'status' => FALSE,
					'message' => 'Server Error'
				], REST_Controller::HTTP_FORBIDDEN);
			}
		}
		catch (\Exception $e) {
			$this->response([
				'status' => FALSE,
				'message' => 'Server Error In Exception'
			], REST_Controller::HTTP_FORBIDDEN);
		}
	}
	 public function uploadstripedocument_post(){
        $token = $this->input->get_request_header('Authorization', TRUE);
       
        if($token != '' && $roleData = $this->common_lib->validateToken($token)){
            $isImageUploaded = $isImageRemoved = 0; $img='';

            if($roleData['role'] != 'restaurant')
                $this->response(['status' => FALSE,'message' => $this->lang->line('unAuthorized')], REST_Controller::HTTP_BAD_REQUEST);

            if(isset($_FILES['uploadImg']) || !empty($_FILES['uploadImg'])) {
                $restaurantData = $this->Common_model->exequery("SELECT * from vm_restaurant where restaurantId = ".$roleData['roleId']." and status='0'",1);
                 if (valResultSet($restaurantData) && (isset($restaurantData->stripeAccId) && !empty($restaurantData->stripeAccId))) {
                     
                    $imgname = explode(" ", $_FILES['uploadImg']['name']);
                    $photoToUpload =    date('Ymdhis').generateStrongPassword(5,0,'l').end($imgname);
                    
                    $uploadSettings = array();
                    $uploadSettings['upload_path']      =  ABSUPLOADPATH."/restaurant_stripe_doc";
                    $uploadSettings['allowed_types']    =   'jpeg|png|txt|jpg';
                    $uploadSettings['file_name']        =   $photoToUpload;
                    $uploadSettings['inputFieldName']   =   "uploadImg";
                    if (!$this->common_lib->_doUpload($uploadSettings))
                        $this->response(['status' => FALSE,'message' => 'Error While Upload.'], REST_Controller::HTTP_BAD_REQUEST);

                    $document = UPLOADPATH."/restaurant_stripe_doc/".$photoToUpload;
                    $document=ABSUPLOADPATH."/restaurant_stripe_doc/".$photoToUpload;
                   // $this->response(['status' => TRUE,'data'=>$document], REST_Controller::HTTP_OK);
                    if($document){
                        //try{
                          // $fp = fopen($document, 'r');

                          // $f = fopen('data://text/plain,' . $document,'r');
                           $f = fopen($document, 'r');
                           //$meta = stream_get_meta_data($f);

                           //var_dump($meta['mode']);
                           //\Stripe\Stripe::setVerifySslCerts(false);
                          //$this->response(['status' => TRUE,'data'=>$f], REST_Controller::HTTP_OK);
                           \Stripe\Stripe::setApiKey("vm_test_V762kq4w2HfjA2wh6NsvxIHl");
                           $fileResponse=\Stripe\FileUpload::create(
                              [
                                "purpose" => "identity_document",
                                "file" => $f
                              ],
                              ["stripe_account" => $restaurantData->stripeAccId]
                            );
                          // $this->response(['status' => TRUE,'data'=>"dd"], REST_Controller::HTTP_OK);
                            if($fileResponse){
                                $this->response(['status' => TRUE,'data'=>$fileResponse], REST_Controller::HTTP_OK);
                             }
                             else
                                 $this->response(['status' => FALSE,'message' => $this->lang->line('internalError')], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
                        //}
                        /*catch (\Exception $e) {
                            $this->response(['status' => FALSE,'message' => $e->getMessage()], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
                        }*/
                    }
                    else
                        $this->response([ 'status' => FALSE, 'message' => $this->lang->line('notfound') ], REST_Controller::HTTP_FORBIDDEN);
                    

                    /*if ($_POST['uploadFor'] == 'gallery'){
                        $isImageUploaded    =   $this->Common_model->insert("vm_restaurant_gallary_img", array('restaurantId'=>$roleData['roleId'],'image'=>$photoToUpload));
                        $img = UPLOADPATH."/restaurant_gallary_images/".$photoToUpload;
                    }
                    else{
                        $isImageUploaded    =   $this->Common_model->update("vm_restaurant", array('img'=>$photoToUpload),"restaurantId=".$roleData['roleId']);
                       
                    }*/
               }
               else
                 $this->response([ 'status' => FALSE, 'message' => $this->lang->line('notfound') ], REST_Controller::HTTP_FORBIDDEN);
            }
            else
                $this->response(['status' => FALSE,'message' => 'Upload For Required.'], REST_Controller::HTTP_BAD_REQUEST);
        }
        else
            $this->response(['status' => FALSE,'message' => $this->lang->line('unAuthorized')], REST_Controller::HTTP_UNAUTHORIZED);
    }
    public function uploaddemostripedocument_post(){
        //$token = $this->input->get_request_header('Authorization', TRUE);
       
        //if($token != '' && $roleData = $this->common_lib->validateToken($token)){
            $isImageUploaded = $isImageRemoved = 0; $img='';

           //if($roleData['role'] != 'restaurant')
                //$this->response(['status' => FALSE,'message' => $this->lang->line('unAuthorized')], REST_Controller::HTTP_BAD_REQUEST);

            if(isset($_FILES['uploadImg']) || !empty($_FILES['uploadImg'])) {
               // $restaurantData = $this->Common_model->exequery("SELECT * from vm_restaurant where restaurantId = ".$roleData['roleId']." and status='0'",1);
                // if (valResultSet($restaurantData) && (isset($restaurantData->stripeAccId) && !empty($restaurantData->stripeAccId))) {
                     
                    $imgname = explode(" ", $_FILES['uploadImg']['name']);
                    $photoToUpload =    date('Ymdhis').generateStrongPassword(5,0,'l').end($imgname);
                    
                    $uploadSettings = array();
                    $uploadSettings['upload_path']      =  ABSUPLOADPATH."/restaurant_stripe_doc";
                    $uploadSettings['allowed_types']    =   'jpeg|png|txt|jpg';
                    $uploadSettings['file_name']        =   $photoToUpload;
                    $uploadSettings['inputFieldName']   =   "uploadImg";
                    if (!$this->common_lib->_doUpload($uploadSettings))
                        $this->response(['status' => FALSE,'message' => 'Error While Upload.'], REST_Controller::HTTP_BAD_REQUEST);

                    $document = UPLOADPATH."/restaurant_stripe_doc/".$photoToUpload;
                    $document=ABSUPLOADPATH."/restaurant_stripe_doc/".$photoToUpload;
                   // $this->response(['status' => TRUE,'data'=>$document], REST_Controller::HTTP_OK);
                    if($document){
                        //try{
                          // $fp = fopen($document, 'r');

                          // $f = fopen('data://text/plain,' . $document,'r');
                           $f = fopen($document, 'r');
                           //$meta = stream_get_meta_data($f);

                           //var_dump($meta['mode']);
                           //\Stripe\Stripe::setVerifySslCerts(false);
                          //$this->response(['status' => TRUE,'data'=>$f], REST_Controller::HTTP_OK);
                           \Stripe\Stripe::setApiKey("vm_test_V762kq4w2HfjA2wh6NsvxIHl");
                           $fileResponse=\Stripe\FileUpload::create(
                              [
                                "purpose" => "identity_document",
                                "file" => $f
                              ],
                              ["stripe_account" => $_POST['accid']]
                            );
                          // $this->response(['status' => TRUE,'data'=>"dd"], REST_Controller::HTTP_OK);
                            if($fileResponse){
                                $this->response(['status' => TRUE,'data'=>$fileResponse], REST_Controller::HTTP_OK);
                             }
                             else
                                 $this->response(['status' => FALSE,'message' => $this->lang->line('internalError')], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
                        //}
                        /*catch (\Exception $e) {
                            $this->response(['status' => FALSE,'message' => $e->getMessage()], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
                        }*/
                    }
                    else
                        $this->response([ 'status' => FALSE, 'message' => $this->lang->line('notfound') ], REST_Controller::HTTP_FORBIDDEN);
                    

                    /*if ($_POST['uploadFor'] == 'gallery'){
                        $isImageUploaded    =   $this->Common_model->insert("vm_restaurant_gallary_img", array('restaurantId'=>$roleData['roleId'],'image'=>$photoToUpload));
                        $img = UPLOADPATH."/restaurant_gallary_images/".$photoToUpload;
                    }
                    else{
                        $isImageUploaded    =   $this->Common_model->update("vm_restaurant", array('img'=>$photoToUpload),"restaurantId=".$roleData['roleId']);
                       
                    }*/
              /// }
               //else
                 //$this->response([ 'status' => FALSE, 'message' => $this->lang->line('notfound') ], REST_Controller::HTTP_FORBIDDEN);
            }
            else
                $this->response(['status' => FALSE,'message' => 'Upload For Required.'], REST_Controller::HTTP_BAD_REQUEST);
        //}
        //else
            //$this->response(['status' => FALSE,'message' => $this->lang->line('unAuthorized')], REST_Controller::HTTP_UNAUTHORIZED);
    }
	public function cronjob_post(){
		 /*Initilize global variable*/
		$totalRecord=$successRecord=$failureRecord=$stripeBalance=$totalPayoutAmount=$totalPaidAmount=$totalDueAmount=$check=$availableAmt=$resreqamount=0;
		$msg="";
		$successData=$failureData=array();
		$previousDate=date("Y-m-t", strtotime("last month"));
		try{
			/* Retrieve Stripe Balance*/
	        $transfer=\Stripe\Balance::retrieve();
	        foreach($transfer->available as $d)
          		if($d->currency=='chf')
               		$availableAmt=$d->amount;
			
	        if($availableAmt>0){
	        	$availableAmt=$availableAmt/100;
				$stripeBalance=$availableAmt;
	        	//$query ="SELECT *,ROUND(sum(restaturantAmount),2) as resreqamount,GROUP_CONCAT(concat(restaurantId,'^',ORDERId,'^',restaturantAmount)) as resorderamoutids,GROUP_CONCAT(ORDERId) as resorderids FROM `vm_order` where orderStatus='Completed' AND  restaurantSettlement=0 AND paymentStatus='Completed' AND isTrail='1'  AND STR_TO_DATE(addedOn,'%Y-%m-%d')<='".$previousDate."' group by restaurantId";
				//$query="SELECT *,ROUND(sum(restaturantAmount),2) as resreqamount,GROUP_CONCAT(concat(restaurantId,'^',ORDERId,'^',restaturantAmount)) as resorderamoutids,GROUP_CONCAT(ORDERId) as resorderids,count(ORDERId) as totalorders FROM `vm_order` where orderStatus='Completed' AND  restaurantSettlement=0 AND paymentStatus='Completed' AND isTrail='".$this->testmode."' group by restaurantId having resreqamount>0";
	        	$query="SELECT *,1 as resreqamount,GROUP_CONCAT(concat(restaurantId,'^',ORDERId,'^',restaturantAmount)) as resorderamoutids,GROUP_CONCAT(ORDERId) as resorderids,count(ORDERId) as totalorders FROM `vm_order` where orderStatus='Completed' AND  restaurantSettlement=0 AND paymentStatus='Completed' AND restaurantId=26 AND isTrail='".$this->testmode."' group by restaurantId having resreqamount>0";
	        	$qData =	$this->Common_model->exequery($query);
	        	if (valResultSet($qData)) {
					$totalRecord=count($qData);
	        		foreach ($qData as $d) {
						$resreqamount=number_format($d->resreqamount,2);
	        			$totalPayoutAmount+=$resreqamount;
	        			try{
		        			if($availableAmt>=$resreqamount){
		        				$vquery="SELECT ss.*,sa.emailId,sr.restaurantName FROM `vm_restaurant_stripe_details` ss join `vm_auth` sa on ss.restaurantId=sa.roleId join `vm_restaurant` sr on sr.restaurantId=ss.restaurantId where ss.restaurantId=".$d->restaurantId." AND sa.role='restaurant' AND sa.status='0'";
		        				$venueData =	$this->Common_model->exequery($vquery,1);
	        					if (valResultSet($venueData)) {
	        						if($venueData->legal_entity_verification_status=='verified'){
	        							/* Initilize local variable*/
	        							$transMsg="";$transactionId="";$paymentStatus="";$destinationPaymentId="";
	        							//$transactionId="tran000".uniqid().date('ymdhis')."";
	        							try{
	        								$transfer = \Stripe\Transfer::create([
									            "amount" => $resreqamount*100,
									            "currency" => "CHF",
									            "destination" => $venueData->stripeAccId
									         ]);
	        								if(count($transfer)>0){
	        									$totalPaidAmount+=$resreqamount;
	        									/* Update  Settlement Column Record */
	        									$nOrderIds=array();
	        									$nOrderIds=explode(',',$d->resorderids);
	        									if(count($nOrderIds)>0){
		        									foreach ($nOrderIds as $ids) {
		        										$updateData=array();
		        										$cond     = "orderId = ".$ids;
		        										$updateData['restaurantSettlement']	=  1;
														$this->Common_model->update("vm_order", $updateData, $cond);
		        									}
	        									}
												$transactionId=$transfer->id;
												$destinationPaymentId=$transfer->destination_payment;
	        									$paymentStatus="Success";
	        									$transMsg="Payment Successfully Of Total Amount=".$resreqamount."";
	        									array_push($successData,$d->resorderamoutids);
	        									$availableAmt=$availableAmt-$resreqamount;
	        									$successRecord++;

	        									try{
		        									/* Mail sent to vendor for payment success*/
		        									$settings = array();
													$settings["template"] 			=  "payment_tpl.html";
													$settings["email"] 				=  $venueData->emailId;
													$settings["subject"] 			=  "VEDMIR Venue - Monthly Payout Summary";
													$contentarr['[[[NAME]]]']		=	$venueData->restaurantName;
													
													$contentarr['[[[HEADERMSG]]]']	=	"Monthly payout";
													$contentarr['[[[PAYMENTHISTORYDATEMSG]]]']		=	"Bill of total orders till ".$previousDate;
													$contentarr['[[[TRANSACTIONID]]]']	=	$transactionId;
													$contentarr['[[[TRANSACTIONDATE]]]']	=	date('Y-m-d H:i:s');
													$contentarr['[[[TOTALORDERCOUNT]]]']	=	$d->totalorders;
													$contentarr['[[[TOTALORDERAMOUNT]]]']	=	$resreqamount;
													$settings["contentarr"] 		= 	$contentarr;
													$this->common_lib->sendMail($settings);	
												}
												catch(Exception $e){

												}

	        								}
	        								else{
												
	        									$paymentStatus="Failed";
	        									$transMsg="Payment Faild Of Total Amount=".$resreqamount.".From Server";
												array_push($failureData,$d->resorderamoutids);
	        									$failureRecord++;
	        								}
	        							}
	        							catch(Exception $e){
											
	        								array_push($failureData,$d->resorderamoutids);
	        								$paymentStatus="Failed";
	        								$transMsg="Payment Faild Of Total Amount=".$resreqamount.".".$e->getMessage()."";
	        								$failureRecord++;

	        							}
	        							try{
		        							/* Insert Payout Data*/
		        							$insertData   =  array();
	        								$insertData['restaurantId']	 	=   $d->restaurantId;
											$insertData['totalorders']	 	=   $d->totalorders;
	        								$insertData['orderIds']	 		=   $d->resorderamoutids;
	        								$insertData['payoutAmount']	 	=   $resreqamount;
	        								$insertData['payout_bank_stripe_id'] =   $venueData->payout_bank_stripe_id;
	        								$insertData['payout_account_holder_name'] =   $venueData->payout_account_holder_name;
	        								$insertData['payout_account_holder_type'] =   $venueData->payout_account_holder_type;
	        								$insertData['payout_bank_name']	 	=   $venueData->payout_bank_name;
	        								$insertData['payout_country']	 	=   $venueData->payout_country;
	        								$insertData['payout_currency']	 	=   $venueData->payout_currency;
	        								$insertData['payout_routing_no']	=   $venueData->payout_routing_no;
	        								$insertData['payout_acc_no']	 	=   $venueData->payout_acc_no;
	        								$insertData['status']	 			=   $paymentStatus;
	        								$insertData['transactionId']	 	=   $transactionId;
											$insertData['destinationPaymentId'] =   $destinationPaymentId;
	        								$insertData['transMsg']	 			=   $transMsg;
											$insertData['isTrail']	 			=   $this->testmode;
	        								$insertData['addedOn']	 			=   date('Y-m-d H:i:s');
	        								$insertData['updatedOn']	 		=   date('Y-m-d H:i:s');
	        								$updatetStatus 		= 	$this->Common_model->insert("vm_restaurant_payout", $insertData);
        								}
	        							catch(Exception $e){
	        							}
		        					}
		        					else{
		        						array_push($failureData,$d->resorderamoutids);
		        						$failureRecord++;
		        						try{
			        						/* Email sent to vendor for verified stripe account*/
			        						$settings = array();
											$settings["template"] 			=  "insufficient_tpl.html";
											$settings["email"] 				=  $venueData->emailId;
											$settings["subject"] 			=  "VEDMIR Stripe - Verified Account";
											$contentarr['[[[NAME]]]']		=	$venueData->restaurantName;
											$contentarr['[[[HEADERMSG]]]']	=	"verified Stripe account";
											$contentarr['[[[SUBMSG]]]']		=	"Stripe Message";
											$contentarr['[[[MESSAGE]]]']	=	"Please verified your stripe account.We are unble to payout due to unverified account.";
											$settings["contentarr"] 		= 	$contentarr;
											$this->common_lib->sendMail($settings);	
										}
										catch(Exception $e){

										}

		        					}	
		        				}
		        				else{
		        					array_push($failureData,$d->resorderamoutids);
		        					$failureRecord++;
		        				}
		        			}
		        			else{
		        				array_push($failureData,$d->resorderamoutids);
		        				$failureRecord=$totalRecord-$successRecord;
		        				if($check==0){
			        				try{
				        				/* Mail sent to admin for insufficient funds in your Stripe account*/
				        				$settings = array();
										$settings["template"] 			=  "insufficient_tpl.html";
										$settings["email"] 				=  "dsmail.alok@gmail.com";
										$settings["subject"] 			=  "VEDMIR Stripe - Insufficient Fund";
										$contentarr['[[[NAME]]]']		=	"VEDMIR";
										$contentarr['[[[HEADERMSG]]]']	=	"insufficient fund in your Stripe account during execution";
										$contentarr['[[[SUBMSG]]]']		=	"Stripe Message";
										$contentarr['[[[MESSAGE]]]']	=	"Insufficient fund in your Stripe account CHF ".$availableAmt.".We can not payout of venue account.";
										$settings["contentarr"] 		= 	$contentarr;
										$this->common_lib->sendMail($settings);	
				        				break;
				        			}
				        			catch(Exception $e){

				        			}
			        			}
			        			$check++;

		        			}
	        			}
	        			catch(Exception $e){
	        				array_push($failureData,$d->resorderamoutids);
		        			$failureRecord++;
	        			}

	        		}
	        		$msg="We got total result for execution=".$totalRecord;
	        	}
	        	else
	        		$msg="No record found=".$totalRecord;

	        	/* Mail sent for running venue cron job*/
	        	try{
	        		$totalDueAmount=$totalPayoutAmount-$totalPaidAmount;
					$settings = array();
					$settings["template"] 			=  "payment_cron_tpl.html";
					$settings["email"] 				=  "dsmail.alok@gmail.com";
					$settings["subject"] 			=  "VEDMIR Stripe - Monthly Automatic Venue Payout Summary";
					$contentarr['[[[NAME]]]']		=	"VEDMIR";
					$contentarr['[[[HEADERMSG]]]']	=	"Monthly payout";
					$contentarr['[[[PAYMENTHISTORYDATEMSG]]]']		=	"Bill of total orders of all venues till ".$previousDate;
					$contentarr['[[[PAYMENTDATE]]]']	=	date('Y-m-d H:i:s');
					$contentarr['[[[TOTALPAYOUTTOTAL]]]']	=	$totalRecord;
					$contentarr['[[[TOTALPAYOUTSUCCESS]]]']	=	$successRecord;
					$contentarr['[[[TOTALPAYOUTFAILURE]]]']	=	$failureRecord;
					$contentarr['[[[TOTALSTRIPEAMOUNT]]]']	=	$stripeBalance;
					$contentarr['[[[TOTALPAYOUTAMOUNT]]]']	=	number_format($totalPayoutAmount,2);
					$contentarr['[[[TOTALPAIDAMOUNT]]]']	=	number_format($totalPaidAmount,2);
					$contentarr['[[[TOTALDUEAMOUNT]]]']		=	number_format($totalDueAmount,2);
					
					
					$settings["contentarr"] 		= 	$contentarr;
					$this->common_lib->sendMail($settings);	
				}
				catch(Exception $e){

				}

	        }
	        else{
	        	try{
		        	$msg="You have insufficient fund in your Stripe account=".$availableAmt;
		        	/* Mail sent to admin for insufficient fund in your Stripe account*/
		        	$settings = array();
					$settings["template"] 			=  "insufficient_tpl.html";
					$settings["email"] 				=  "dsmail.alok@gmail.com";
					$settings["subject"] 			=  "VEDMIR Stripe - Insufficient Fund";
					$contentarr['[[[NAME]]]']		=	"VEDMIR";
					$contentarr['[[[HEADERMSG]]]']	=	"insufficient fund in your Stripe account";
					$contentarr['[[[SUBMSG]]]']		=	"Stripe Message";
					$contentarr['[[[MESSAGE]]]']	=	"Insufficient fund in your Stripe account CHF ".$availableAmt.".We can not execute the payout of venue account.";
					$settings["contentarr"] 		= 	$contentarr;
					$this->common_lib->sendMail($settings);	
				}
				catch(Exception $e){

				}

	        }
	        
	        $result = $this->Common_model->insertUnique('vm_cronjob', array('cronName'=>'venuepayout','stripeBalance'=>$stripeBalance,'totalPayoutAmount'=>number_format($totalPayoutAmount,2),'totalPaidAmount'=>number_format($totalPaidAmount,2),'totalDueAmount'=>number_format($totalDueAmount,2),'totalRecord'=>$totalRecord,'successRecord'=>$successRecord,'failureRecord'=>$failureRecord,'successData'=>serialize($successData),'failureData'=>serialize($failureData),'message'=>$msg,'paymentOrderCalDate'=>$previousDate,'isTrail'=>$this->testmode,'addedOn'=>date('Y-m-d H:i:s')));
			$this->response([
				'status' => True,
				'message' => 'Successfully'
			  ], REST_Controller::HTTP_OK);
       }
       catch(Exception $e){
		 
         $result = $this->Common_model->insertUnique('vm_cronjob', array('cronName'=>'venuepayout','stripeBalance'=>$stripeBalance,'totalPayoutAmount'=>number_format($totalPayoutAmount,2),'totalPaidAmount'=>number_format($totalPaidAmount,2),'totalDueAmount'=>number_format($totalDueAmount,2),'totalRecord'=>$totalRecord,'successRecord'=>$successRecord,'failureRecord'=>$failureRecord,'successData'=>serialize($successData),'failureData'=>serialize($failureData),'message'=>$msg,'paymentOrderCalDate'=>$previousDate,'isTrail'=>$this->testmode,'addedOn'=>date('Y-m-d H:i:s')));
		 $this->response([
				'status' => False,
				'message' => 'Failed'
			  ], REST_Controller::HTTP_OK);
       }
	}
	
    public function transferpayoutamount_post(){
      \Stripe\Stripe::setApiKey("vm_test_V762kq4w2HfjA2wh6NsvxIHl");
      try{
		  $transfer = \Stripe\Transfer::create([
				"amount" => 1,
				"currency" => "CHF",
				"destination" => "acct_1DgugtGsjG48Au8X"
			 ]);
       /*$transfer=\Stripe\Balance::retrieve();
       $amount=0;
        $dataValue=$transfer->available;
        foreach($dataValue as $d)
          if($d->currency=='chf')
               $amount=$d->amount;
        
        /*$transfer = \Stripe\Transfer::create([
            "amount" => $_POST['amount'],
            "currency" => "CHF",
            "destination" => $_POST['acc_id']
          ]);*/
        $this->response([
            'data'=> $transfer,
            'status' => True,
            'message' => 'Successfully'
          ], REST_Controller::HTTP_OK);
       }
       catch(Exception $e){
           $this->response([
            'data'=> $e->getMessage(),
            'status' => false,
            'message' => 'failed'
          ], REST_Controller::HTTP_OK);
       }
    }
    public function charge_post(){
      \Stripe\Stripe::setApiKey("vm_test_V762kq4w2HfjA2wh6NsvxIHl");
      try{
           //$transfer=\Stripe\Balance::retrieve();


          /*$charge =\Stripe\Charge::create(array(
              'currency' => 'CHF',
              'amount'   => 250000,
              //'card'     => "4000000000000077",
              "source" => "tok_bypassPending" 
          ));*/
           $charge = \Stripe\Charge::create([
              "amount" => 250000,
              "currency" => "CHF",
              "source" => "tok_visa" 
            ]);
           $this->response([
            'data'=> $charge,
            'status' => True,
            'message' => 'Successfully'
          ], REST_Controller::HTTP_OK);
        }
       catch(Exception $e){
          $this->response([
            'data'=> $e->getMessage(),
            'status' => false,
            'message' => 'failed'
          ], REST_Controller::HTTP_OK);
       } 
    }
}
