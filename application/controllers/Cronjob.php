<?php

defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by Dream Steps Pvt Ltd
 * Created on 30 Nov 2019
 * Vedmir -  module
**/
class Cronjob extends CI_Controller {

	public $outputdata 	= array();
	
	public function __construct(){
		$testmode=0;
		parent::__construct();
		//Check login authentication & set public veriables
		$this->langSuffix = $this->lang->line('langSuffix');
		//	echo $this->sessName;

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
	public function cancelAutoRenewal1_get(){
        $qry = $this->Common_model->exequery("Select su.userName,su.email,um.* from vm_user_memberships um join vm_user su on um.userId=su.userId where um.subscriptionStatus='Active' and paymentType='Auto' and isPrevoiusLog=0 and isTrail=0");
		$cnt=0;
		$faild=0;
        if($qry) {
			foreach ($qry as $key => $membership) {
				try {
					$subscription = \Stripe\Subscription::retrieve($membership->subscriptionId);
					$cancelSub = $subscription->cancel(['at_period_end' => true]);
					$this->Common_model->update('vm_user_memberships', array('paymentType' => 'Mannual'), "membershipId= ".$membership->membershipId);
					$cnt++;
				}
				catch(Exception $e){
					$faild++;
					echo $e->getMessage();
				}
			}
		}
		echo "Success".$cnt;
		echo "Failed".$faild;
	
	}
	public function checksubscription(){
		$userMembership = $this->Common_model->exequery("SELECT * FROM vm_user_memberships WHERE isTrail='".$this->testmode."' AND endDate<='".date('Y-m-d')."' AND paymentType='Auto' AND isPrevoiusLog ='0'");
		if($userMembership) {
			foreach($userMembership as $memberdata) {
				try {
					$Subscription = \Stripe\Subscription::retrieve($memberdata->subscriptionId);
					
					
					$endDate =  date('Y-m-d',$Subscription->current_period_end);
					$startDate =  date('Y-m-d',$Subscription->current_period_start);
					
					$updateData = array();
					if(strtotime(date('Y-m-d')) <= $Subscription->current_period_end) {
						$updateData['isPrevoiusLog'] = 1;
						$updateData['subscriptionStatus'] = 'Active';
						$user_membership = $this->Common_model->insertUnique("vm_user_memberships", array('userId' => $memberdata->userId, 'planId' => $memberdata->planId, 'paymentMethod' => $memberdata->paymentMethod, 'paymentType' => $memberdata->paymentType, 'transactionId' =>$memberdata->transactionId , 'subscriptionId' => $memberdata->subscriptionId, 'cardLast4' => $memberdata->cardLast4, 'cardExpMonth' => $memberdata->cardExpMonth, 'cardExpYear' => $memberdata->cardExpYear, 'subscriptionAmount' => $memberdata->subscriptionAmount, 'paymentDate' => $memberdata->userId, 'subscriptionStatus' => 'Active', 'startDate' => $startDate,'endDate' => $endDate, 'payerId' => $memberdata->payerId, 'selfpay' => $memberdata->selfpay, 'isPrevoiusLog' => 0, 'totalreferal' => $memberdata->totalreferal, 'giftId' => $memberdata->giftId, 'isTrail' => $memberdata->isTrail, 'invoiceId' => $Subscription->latest_invoice, 'subscriptionLogStatus' => 1));	
						if( $user_membership ) {
							try {
                                $invoiceDetails = \Stripe\Invoice::retrieve($Subscription->latest_invoice);

                                	$getCouponDetails = $this->Common_model->exequery("SELECT * FROM vm_user_referal_stripe_coupon WHERE couponCode = '".$invoiceDetails->discount->coupon->id."'", 1);
                                	if($getCouponDetails) {
                                		$referalCouponId = $getCouponDetails->referalStripeCouponId;
                                		$this->Common_model->update("vm_user_memberships", array('referalCouponId' => $referalCouponId), "membershipId = ".$user_membership);
	                                    $this->Common_model->update('vm_user_referal_stripe_coupon', array('isReedem' => 1), "referalStripeCouponId=".$referalCouponId);
	                                   
	                                    $this->Common_model->update("vm_user_referal_wallets", array("status" => 0), "userId = ".$memberdata->userId." AND referalCouponId = ".$referalCouponId);
                                	}
                                    
                                
                            }
                            catch(Exception $e) {

                            }
						}				
					}
					else {
						$updateData =array('endDate' => $endDate);
						$updateData['subscriptionStatus'] = 'Expired';
					}
					$this->Common_model->update('vm_user_memberships', $updateData, "membershipId = '".$memberdata->membershipId."'");
				}
				catch(Exception $e) {
					echo $memberdata->membershipId;
					echo "Failed";
				}
			}
		}    	
    }


    /*------------ Alert user membership expiry --------*/
    public function notifyusersubscription(){
    	
    	$userMembership = $this->Common_model->exequery("SELECT um.*, (SELECT deviceToken FROM vm_auth WHERE roleId=um.userId AND role='user') as deviceToken FROM vm_user_memberships um WHERE um.endDate >= '".date('Y-m-d')."' AND um.endDate <='".date('Y-m-d', strtotime('+2 days'))."' AND um.paymentType!='Auto' AND um.subscriptionStatus='Active' AND um.isPrevoiusLog ='0'");
		if($userMembership) {
			$deviceTokenIds = array();
			foreach($userMembership as $memberdata) {
				array_push($deviceTokenIds, $memberdata->deviceToken);				
			}
			if(!empty($deviceTokenIds)) {
				$this->common_lib->sendPush("Oh, your membership is going to expire soon. Activate your auto-renewal to keep on enjoying Vedmir!",array('type' => 'membership_expire'),$deviceTokenIds, true, true);
			}
		}
    }

    /*------------ Apply Subscription Plan --------*/
    public function applysubscriptioncoupon(){
    	
    	$userMembership = $this->Common_model->exequery("SELECT um.* FROM vm_user_memberships um WHERE um.endDate >= '".date('Y-m-d')."' AND um.endDate <='".date('Y-m-d', strtotime('+1 days'))."' AND um.paymentType = 'Auto' AND um.subscriptionStatus='Active' AND um.isPrevoiusLog ='0'");
		if($userMembership) {
			foreach ($userMembership as $key => $memberdata) {
				$getAvaiableBalance = $this->Common_model->exequery("SELECT currentAvailableBalance FROM vm_user_referal_wallets WHERE userId='".$memberdata->userId."' order by referalWalletId desc limit 0,1", 1);
	    		$currentBalance = ($getAvaiableBalance) ? $getAvaiableBalance->currentAvailableBalance: 0;
    		  	if($currentBalance > 0 ) {
    		  		$discountAmount = ( $currentBalance >= $memberdata->subscriptionAmount ) ? $memberdata->subscriptionAmount :  $currentBalance;
                	$discountData = $this->common_lib->createStripeDiscountCoupon(array("userId" => $memberdata->userId, 'duration' => 'once', 'discountType' => 0, 'amount' => $discountAmount));
                	if($discountData['valid'] && isset($discountData['data']['referalStripeCouponId']) && !empty($discountData['data']['referalStripeCouponId']) && isset($discountData['data']['amount']) && !empty($discountData['data']['amount'])) {
                		try {
							$Subscription = \Stripe\Subscription::retrieve($memberdata->subscriptionId);
							try {
					            $updateSubscriptions =  \Stripe\Subscription::update(
						              $memberdata->subscriptionId,
						              [
						                'coupon' => $discountData['data']['couponCode'],
						              ]
						        );
						        $updatedCurrentbalance = $currentBalance - $discountData['data']['amount']; 
						        $updatedCurrentbalance = ( $updatedCurrentbalance > 0 ) ? $updatedCurrentbalance : 0;
					            $this->Common_model->insert("vm_user_referal_wallets", array("userId" => $memberdata->userId, "amount" => $discountData['data']['amount'], "transType" => 1, "type" => 1, "referalCouponId" => $discountData['data']['referalStripeCouponId'], "currentAvailableBalance" => $updatedCurrentbalance, 'status' => 1, "addedOn" => date('Y-m-d H:i:s')));
					        }
					        catch(Exception $e) {
					            echo $e->getMessage();
					        }
						}
						catch( Exception $e ) {
							echo $e->getMessage();
						}
                	}
    		  		
    		  	}
			}
		}
    }
	/* Run Monthly Venue Payout*/
	public function stripeVenuePayout() {
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
	        	//$query ="SELECT *,ROUND(sum(restaturantAmount),2) as resreqamount,GROUP_CONCAT(concat(restaurantId,'^',ORDERId,'^',restaturantAmount)) as resorderamoutids,GROUP_CONCAT(ORDERId) as resorderids,count(ORDERId) as totalorders FROM `vm_order` where orderStatus='Completed' AND  restaurantSettlement=0 AND paymentStatus='Completed' AND isTrail='".$this->testmode."' group by restaurantId having resreqamount>0";
				$query="SELECT *,ROUND(sum(restaturantAmount),2) as resreqamount,GROUP_CONCAT(concat(restaurantId,'^',ORDERId,'^',restaturantAmount)) as resorderamoutids,GROUP_CONCAT(ORDERId) as resorderids,count(ORDERId) as totalorders FROM `vm_order` where orderStatus='Completed' AND  restaurantSettlement=0 AND paymentStatus='Completed' AND isTrail='".$this->testmode."' AND STR_TO_DATE(addedOn,'%Y-%m-%d')<='".$previousDate."' group by restaurantId having resreqamount>0";
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
        }
        catch(Exception $e) {
         	$result = $this->Common_model->insertUnique('vm_cronjob', array('cronName'=>'venuepayout','stripeBalance'=>$stripeBalance,'totalPayoutAmount'=>number_format($totalPayoutAmount,2),'totalPaidAmount'=>number_format($totalPaidAmount,2),'totalDueAmount'=>number_format($totalDueAmount,2),'totalRecord'=>$totalRecord,'successRecord'=>$successRecord,'failureRecord'=>$failureRecord,'successData'=>serialize($successData),'failureData'=>serialize($failureData),'message'=>$msg,'paymentOrderCalDate'=>$previousDate,'isTrail'=>$this->testmode,'addedOn'=>date('Y-m-d H:i:s')));
        }
	}


	/* Run Weekly Venue Payout*/
	public function stripeVenueWeeklyPayout() {
		 /*Initilize global variable*/
		$totalRecord=$successRecord=$failureRecord=$stripeBalance=$totalPayoutAmount=$totalPaidAmount=$totalDueAmount=$check=$availableAmt=$resreqamount=0;
		$msg="";
		$successData=$failureData=array();
		$previousDate=date("Y-m-t", strtotime("-8 days"));
		try{
			/* Retrieve Stripe Balance*/
	        $transfer=\Stripe\Balance::retrieve();
	        foreach($transfer->available as $d)
          		if($d->currency=='chf')
               		$availableAmt=$d->amount;
			
	        if($availableAmt>0){
				$availableAmt=$availableAmt/100;
				$stripeBalance=$availableAmt;
	        	//$query ="SELECT *,ROUND(sum(restaturantAmount),2) as resreqamount,GROUP_CONCAT(concat(restaurantId,'^',ORDERId,'^',restaturantAmount)) as resorderamoutids,GROUP_CONCAT(ORDERId) as resorderids,count(ORDERId) as totalorders FROM `vm_order` where orderStatus='Completed' AND  restaurantSettlement=0 AND paymentStatus='Completed' AND isTrail='".$this->testmode."' group by restaurantId having resreqamount>0";
				$query="SELECT *,ROUND(restaturantAmount,2) as resreqamount, concat(restaurantId,'^',ORDERId,'^',restaturantAmount) as resorderamoutids FROM `vm_order` where orderStatus='Completed' AND  restaurantSettlement=0 AND paymentStatus='Completed' AND isTrail='".$this->testmode."' AND STR_TO_DATE(addedOn,'%Y-%m-%d')<='".$previousDate."'";
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
									            "destination" => $venueData->stripeAccId,
									            "transfer_group" => "{ORDER".$d->orderId."}"
									         ]);
	        								if(count($transfer)>0){
	        									$totalPaidAmount+=$resreqamount;
	        									/* Update  Settlement Column Record */
	        									/*$nOrderIds=array();
	        									$nOrderIds=explode(',',$d->resorderids);
	        									if(count($nOrderIds)>0){
		        									foreach ($nOrderIds as $ids) {
		        										$updateData=array();
		        										$cond     = "orderId = ".$ids;
		        										$updateData['restaurantSettlement']	=  1;
														$this->Common_model->update("vm_order", $updateData, $cond);
		        									}
	        									}*/
	        									$updateData=array();
		        										$cond     = "orderId = ".$d->orderId;
		        										$updateData['restaurantSettlement']	=  1;
														$this->Common_model->update("vm_order", $updateData, $cond);
												$transactionId=$transfer->id;
												$destinationPaymentId=$transfer->destination_payment;
	        									$paymentStatus="Success";
	        									$transMsg="Payment Successfully Of Total Amount=".$resreqamount."";
	        									array_push($successData,$d->resorderamoutids);
	        									$availableAmt=$availableAmt-$resreqamount;
	        									$successRecord++;

	        									/*try{
		        									/Mail sent to vendor for payment success/
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

												}*/

	        								}
	        								else{
	        									echo '<pre>';
												print_r($transfer);echo '</pre>';
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
											$insertData['totalorders']	 	=   1;
	        								$insertData['orderIds']	 		=   $d->orderId;
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
					$settings["subject"] 			=  "VEDMIR Stripe - Weekly Automatic Venue Payout Summary";
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
	        $result = $this->Common_model->insertUnique('vm_cronjob', array('cronName'=>'weeklyvenuepayout','stripeBalance'=>$stripeBalance,'totalPayoutAmount'=>number_format($totalPayoutAmount,2),'totalPaidAmount'=>number_format($totalPaidAmount,2),'totalDueAmount'=>number_format($totalDueAmount,2),'totalRecord'=>$totalRecord,'successRecord'=>$successRecord,'failureRecord'=>$failureRecord,'successData'=>serialize($successData),'failureData'=>serialize($failureData),'message'=>$msg,'paymentOrderCalDate'=>$previousDate,'isTrail'=>$this->testmode,'addedOn'=>date('Y-m-d H:i:s')));
        }
        catch(Exception $e) {
         	$result = $this->Common_model->insertUnique('vm_cronjob', array('cronName'=>'weeklyvenuepayout','stripeBalance'=>$stripeBalance,'totalPayoutAmount'=>number_format($totalPayoutAmount,2),'totalPaidAmount'=>number_format($totalPaidAmount,2),'totalDueAmount'=>number_format($totalDueAmount,2),'totalRecord'=>$totalRecord,'successRecord'=>$successRecord,'failureRecord'=>$failureRecord,'successData'=>serialize($successData),'failureData'=>serialize($failureData),'message'=>$msg,'paymentOrderCalDate'=>$previousDate,'isTrail'=>$this->testmode,'addedOn'=>date('Y-m-d H:i:s')));
        }
	}
    public function downloadrestaurantrevenue_excel ($restaurantId = 0) {
		if($restaurantId>0){
			$query	=	"SELECT rs.restaurantName as restaurantName,rs.address1 as restaurantAddress,rs.address2 as restaurantAddress2 from vm_restaurant rs  where rs.status!=2 AND rs.restaurantId=".$restaurantId;
			
			$queryData=$this->Common_model->exequery($query,1);
			
			$restaurantName=$msg=$payDate='';$totalAmt=0;
			if(valResultSet($queryData)){
				$restaurantName=$queryData->restaurantName;
				
				$qry = "SELECT su.userName, so.orderId, so.amt, so.payment_method, so.transactionId, so.paymentStatus, so.tableNo, so.orderStatus, so.restaturantAmount, so.addedOn,(SELECT count(de.detailId) FROM vm_order_detail as de WHERE de.orderId= so.orderId AND itemType = '0') as totalFood,(SELECT count(de.detailId) FROM vm_order_detail as de WHERE de.orderId= so.orderId AND itemType = '1') as totalDrink,(SELECT GROUP_CONCAT(pd.productName)  FROM vm_product as pd where pd.productId IN (SELECT (CASE when sod.isVariable = '1' then (select vd.productId FROM vm_variable_product vd where vd.variableId = sod.productId) else sod.productId end) FROM vm_order_detail as sod where sod.orderId=so.orderId) group by pd.restaurantId) as orderItems FROM vm_order so LEFT JOIN vm_user su ON so.userId = su.userId  where so.restaurantId = ".$restaurantId." AND so.paymentStatus='Completed' AND so.orderStatus='Completed' AND so.isTrail='0'"; 
				$queryRData = $this->Common_model->exequery("SELECT t.*, (case when (t.totalFood > 0 AND t.totalDrink > 0) then 'Food & Drink' when (t.totalDrink > 0) then 'Drink' else 'Food' end) as orderType FROM (".$qry.") as t");
				if(valResultSet($queryRData)){
					$i = 1;
					foreach($queryRData as $queryNData) {
						$totalAmt+=$queryNData->restaturantAmount;
						$msg.='<tr>';
						$msg.='<td>'.$i.'</td>';
						$msg.='<td>#'.$queryNData->orderId.'</td>';
						$msg.='<td>'. $queryNData->userName.'</td>';
						$msg.='<td>'. $queryNData->orderItems.'</td>';
						$msg.='<td>'. $queryNData->orderType.'</td>';
						$msg.='<td>CHF : '. $queryNData->restaturantAmount.'</td>';
						$msg.='<td><span style="visibility:hidden">&nbsp;</span> '.date('Y-m-d h:i A',strtotime($queryNData->addedOn)).'</td>';
						$msg.='</tr>';
						$i++;
					}
				}
							
						
					
				if($totalAmt>0)
					$msg.='<tr><td colspan="5" style="text-align: right;font-weight: 600;">Total</td><td style="font-weight: 600;">CHF : '.$totalAmt.'</td><td></td></tr>';
				
					
				
				
			
			
				$msg1="<center><h2>Hi ".$restaurantName." Order Details </h2></center>";
				$msg1.="<tr><th>Sr No.</th><th>Order Id</th><th>User Name</th><th>Description</th><th>Type</th><th>Venue Amount</th><th>Date</th></tr>";
				
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
				header("Content-Type: application/vnd.ms-excel");
				print "$data";
			}
			else{
				echo "Access Denied";
				exit;
			}
		}
		else{
			echo "Access Denied";
			exit;
		}
	}
	public function checkorder(){
		
		$userMembership = $this->Common_model->exequery("SELECT * FROM vm_order WHERE isTrail='0'");
		if($userMembership) {
			foreach($userMembership as $memberdata) {
				try {
					print_r( $memberdata);
					$Subscription = \Stripe\Charge::retrieve($memberdata->chargeId);
					
					$this->Common_model->update('vm_order', array('cardExpMonth' => @$Subscription->source->exp_month, 'cardExpYear' => @$Subscription->source->exp_year, 'brand' => @$Subscription->source->brand, 'last4' =>@$Subscription->source->last4), "orderId=".$memberdata->orderId);
					
					/*$endDate =  date('Y-m-d',$Subscription->current_period_end);
					$updateData = array('endDate' => $endDate);
					if(strtotime(date('Y-m-d')) <= $Subscription->current_period_end) 
						$updateData['subscriptionStatus'] = 'Active';					
					else
						$updateData['subscriptionStatus'] = 'Expired';
					$this->Common_model->update('vm_user_memberships', $updateData, "membershipId = '".$memberdata->membershipId."'");*/
				}
				catch(Exception $e) {
					echo "Failed";
				}
			}
		}    	
    }

    public function payoutslip(){
		$lastMonthDate = date("Y-m-d", strtotime("last day of previous month"));
		$startDate = date("Y-m-d", strtotime("first day of previous month"));
		$restaurantOrder = $this->Common_model->exequery("SELECT vm_order.restaurantId, vm_restaurant_stripe_details.legal_entity_business_name as legalName, sr.restaurantName, sr.address1, sr.address2, sr.country, sr.mobile, sr.email, vm_restaurant_stripe_details.legal_entity_business_tax_id as taxDetails FROM vm_order left join vm_restaurant sr on vm_order.restaurantId = sr.restaurantId left join vm_restaurant_stripe_details on sr.restaurantId = vm_restaurant_stripe_details.restaurantId WHERE paymentStatus='Completed' AND orderStatus='Completed' AND DATE(vm_order.addedOn) >= '".$startDate."' AND DATE(vm_order.addedOn) <= '".$lastMonthDate."' AND vm_order.isTrail='0'  group by vm_order.restaurantId");
		
		if($restaurantOrder) {
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
			  //echo $mail_content;
			  //echo '<br/>';
			  //echo $restaurantOrderItem->email;
			  //echo '<br/>';
			  
			  $ismailed = $this->common_lib->sendMail($settings); 
				//exit;
			}
		}
	}
    public function ordernotification(){ 
	  
	  
		$currentTime = date('Y-m-d H:i:s', strtotime("-3 minutes"));
		$pendingOrder = $this->Common_model->exequery("SELECT rs.email,vm_order.restaurantId, (SELECT language FROM vm_auth WHERE role='restaurant' AND roleId = rs.restaurantId) as currentLanguage FROM vm_order left join vm_restaurant rs on vm_order.restaurantId = rs.restaurantId WHERE vm_order.orderStatus='Pending' AND vm_order.addedOn <= '".$currentTime."' AND vm_order.paymentStatus = 'Completed' AND vm_order.autoSentMailToRes = '0' group by restaurantId");
		if($pendingOrder) {
			foreach( $pendingOrder as $pendingItem) {
				$currentLanguage = (isset($pendingItem->currentLanguage) && !empty($pendingItem->currentLanguage)) ? $pendingItem->currentLanguage : 'english';
				if($pendingItem->email != '') {
					$settings["template"]    =   ($currentLanguage == 'english') ? "pending_order_tpl.html": "pending_order_tpl_fr.html";
					$settings["email"]       =   $pendingItem->email;
					$settings["cc"]         =   "support@vedmir.com";
				    $settings["bcc"]         =   "hello@vedmir.com";
                	$settings["subject"]     =   ($currentLanguage == 'english') ? "Important - Vedmir order pending!" : "Important - Commande Vedmir en attente !";
                	$ismailed = $this->common_lib->sendMail($settings);
                	$this->Common_model->update("vm_order", array("autoSentMailToRes" => 1), "restaurantId = ".$pendingItem->restaurantId." AND orderStatus='Pending' AND addedOn <= '".$currentTime."' AND paymentStatus = 'Completed' AND autoSentMailToRes = '0'");
				}
			}
		}

	}
    public function orderusernotification(){
		$currentTime = date('Y-m-d').' 03:00:00';
		$this->Common_model->update("vm_order", array('isUserNotify' => 1), "orderStatus = 'Completed' AND addedOn <= '".$currentTime."'");

	}
  
    public function downloaduser(){
            $userList = $this->Common_model->exequery("SELECT * FROM vm_user");
            $msg = '';
            if($userList) {
                $count = 1;
                foreach($userList as $userDetails) {
                    $msg.="<tr><td>".$count."</td><td>".ucwords($userDetails->userName." ".$userDetails->lastName)."</td><td>".$userDetails->email."</td></tr>";
                    $count++;
                }
            }
            $msg1="";
            $msg1.="<tr><th>Sr No.</th><th>User Name</th><th>Email Address</th></tr>";
            
            $data="<html>";
            $data.="<meta http-equiv=\"Content-Type\" content=\"text/html; charset=Windows-1252\">";
            $data.="<body class='fixed-top'>";
            $data.="<table cellspacing='0' border='1' style='text-align:center;'><tbody>";
            $data.=$msg1.$msg;
            $data.='</table>';
            $data.="</body>";
            $data.="</html>";
            
            $filename = 'users_'. date('Y/m/d') . ".xls";
            header("Content-Disposition: attachment; filename=\"$filename\"");
            header("Content-Type: application/vnd.ms-excel");
            print "$header\n$data";
    }
}