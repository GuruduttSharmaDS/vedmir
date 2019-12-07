<?php



defined('BASEPATH') OR exit('No direct script access allowed');



require APPPATH . 'libraries/REST_Controller.php';

 

class Membership extends REST_Controller {



    function __construct()

    {

        $client = $current_private_key = $current_public_key = '';

        $testmode=0;

        // Construct the parent class

        parent::__construct();

        $this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key

        $this->methods['users_post']['limit'] = 500; // 100 requests per hour per user/key

        $this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key





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



    // Get Subscription Plan List



    public function getsubscriptionplan_get(){

        $langSuffix = $this->lang->line('langSuffix');

        $token = $this->input->get_request_header('Authorization', TRUE);

        if($token != '' && $userData = $this->common_lib->validateToken($token)){

           $subscriptionPlan = $this->Common_model->exequery("SELECT id as planId, planName".$langSuffix." as planName, description".$langSuffix." as description, (case when icon !='' then concat('".UPLOADPATH."/',icon) else '' end ) as subscriptionLogo, amount, currency, period, duration FROM vm_subscription_plan WHERE status='0' AND isSubType='1'");

           $subscriptionPlan = ($subscriptionPlan) ? $subscriptionPlan : array();

           $subscriptionPlan = array_replace($subscriptionPlan,

                    array_fill_keys(

                        array_keys($subscriptionPlan, 'month'),

                        "MMM"

                    )

                );
            if(!empty($subscriptionPlan)) {
                foreach($subscriptionPlan as $subscriptionItem) {
                    $subscriptionPlanList = $this->Common_model->exequery("SELECT *, (case when duration='year' then '12' else period end) as period FROM vm_subscription_details WHERE status=0 AND subscriptionId='".$subscriptionItem->planId."'");
                    $subscriptionItem->planList = ($subscriptionPlanList) ? $subscriptionPlanList : array();
                }
            }

            $this->response(array('status' => true, 'message' => '','subscription' => $subscriptionPlan), REST_Controller::HTTP_OK);

        }else

                $this->response([

                    'status' => FALSE,

                    'message' => $this->lang->line('unAuthorized')

                ], REST_Controller::HTTP_UNAUTHORIZED); // HTTP_UNAUTHORIZED (401) being the HTTP response code

            

    }

    // Create membership for user 

    public function membership_plan_post() { 

        $errors = array();

        $error_message = array();  

        $langSuffix = $this->lang->line('langSuffix');

        $token = $this->input->get_request_header('Authorization', TRUE);
        $membershipCouponDiscountedPrice = 0;
        $currentInsertedReferalWalletId = 0;
        if($token != '' && $this->common_lib->validateToken($token)){

            $userId = $this->common_lib->validateToken($token);

            if(!isset($_POST['stripe_token']) || empty($_POST['stripe_token']))

                $this->response(array('status' => false, 'messgae' => $this->lang->line('stripeTokenRequired')), REST_Controller::HTTP_BAD_REQUEST);

            if(!isset($_POST['planId']) || empty($_POST['planId']))

                $this->response(array('status' => false, 'messgae' => $this->lang->line('planIdRequired')), REST_Controller::HTTP_BAD_REQUEST);
            
            if($userId['role'] != 'user')
                $this->response(array('status' => false, 'messgae' => $this->lang->line('unAuthorized')), REST_Controller::HTTP_BAD_REQUEST);
            $userDetail = $this->Common_model->exequery("SELECT userId,email,userName as first_name, lastName, CONCAT(userName,' ',lastName) as userName,refered_by, stripe_customer_id, test_stripe_customer_id from vm_user WHERE userId=".$userId['roleId']. " AND status = '0'", true);
            if(!$userDetail)
                $this->response([
                    'status' => FALSE,
                    'message' => $this->lang->line('invalidRequrest')
                ], REST_Controller::HTTP_UNAUTHORIZED);
            if(isset($_POST['coupon_code']) && !empty($_POST['coupon_code'])) {
                $checkGiftCode = $this->Common_model->exequery("SELECT * FROM vm_user WHERE userCode='".$_POST['coupon_code']."' AND userId!= '".$userId['roleId']."'",true);
                if( $checkGiftCode && $userDetail->refered_by < 1) {
                  $this->Common_model->update("vm_user", array('refered_by' => $checkGiftCode->userId), "userId = '".$userId['roleId']."'");
                  $userDetail->refered_by = $checkGiftCode->userId;
                  $currentInsertedReferalWalletId = $this->Common_model->insertUnique("vm_user_referal_wallets", array("userId" => $userId['roleId'], "amount" => 10, "transType" => 0, "type" => 0, "referalUsedId" => $checkGiftCode->userId, "currentAvailableBalance" => 10, "addedOn" => date('Y-m-d H:i:s')));                  
                }
                else{
                    $checkCouponCode = $this->Common_model->exequery("SELECT vm_coupons.*, (SELECT count(*) FROM vm_coupon_redeem WHERE userId='".$userId['roleId']."' AND couponId=vm_coupons.couponId) as reedemCount, (SELECT count(*) FROM vm_coupon_redeem WHERE couponId=vm_coupons.couponId) as TotalReedemCount FROM vm_coupons WHERE vm_coupons.status='0' AND vm_coupons.couponCode='".$_POST['coupon_code']."'",1);

                    if(isset($checkCouponCode->reedemCount)){
                        if($checkCouponCode->reedemCount == 0 && $checkCouponCode->TotalReedemCount == 0 && $checkCouponCode->discountedPrice > 0)
                            $membershipCouponDiscountedPrice = $checkCouponCode->discountedPrice;

                        else
                            $this->response(['status' => FALSE,'message' => $this->lang->line('invalidCouponCode') ], REST_Controller::HTTP_FORBIDDEN);
                    
                    }else
                        $this->response(array('status' => false, 'message' => $this->lang->line('invalidCouponCode'), 'isAmbassadorCouponCode' =>  false, 'isReferCode' => false, 'referAmount' => 10), REST_Controller::HTTP_FORBIDDEN);
                }
            }
            
            $paymentType = (isset($_POST['type']) && !empty($_POST['type'])) ? $_POST['type'] : 'Auto';

            $getAvaiableBalance = $this->Common_model->exequery("SELECT currentAvailableBalance FROM vm_user_referal_wallets WHERE userId='".$userId['roleId']."' order by referalWalletId desc limit 0,1", 1);
            $currentBalance = ($getAvaiableBalance) ? $getAvaiableBalance->currentAvailableBalance: 0;
            $subscriptionData = $this->Common_model->exequery("SELECT vm_subscription_details.planId,vm_subscription_details.amount,vm_subscription_details.currency,vm_subscription_details.detailId as id,vm_subscription_details.period,vm_subscription_details.duration,vm_subscription_plan.planName".$langSuffix." as planName, vm_subscription_plan.planName_fr as planNameFr,(case when icon !='' then concat('".UPLOADPATH."/',icon) else '' end) as planImg FROM vm_subscription_details left join vm_subscription_plan on vm_subscription_details.subscriptionId = vm_subscription_plan.id WHERE vm_subscription_details.detailId='".$_POST['planId']."'",true);

            if( !$subscriptionData )
                $this->response(array('status' => false, 'messgae' => $this->lang->line('dbError')), REST_Controller::HTTP_FORBIDDEN);

            
            $discountData = array('valid' => false);
            if( $currentBalance > 0 ) {
                $coupanDiscountedPrice = ($membershipCouponDiscountedPrice)?$membershipCouponDiscountedPrice:$subscriptionData->amount;
                $discountAmount = ( $currentBalance >= $coupanDiscountedPrice ) ? $coupanDiscountedPrice : $currentBalance;
                $discountData = $this->common_lib->createStripeDiscountCoupon(array("userId" => $userId['roleId'], 'duration' => 'once', 'discountType' => 0, 'amount' => $discountAmount));
            }elseif($membershipCouponDiscountedPrice > 0){
                $discountAmount = $subscriptionData->amount - $membershipCouponDiscountedPrice;
                $discountData = $this->common_lib->createStripeDiscountCoupon(array("userId" => $userId['roleId'], 'duration' => 'once', 'discountType' => 0, 'amount' => $discountAmount));

            }

            if( $userDetail ){

                try {

                    $subscriptionid = '';

                    $enddate = '';

                    $userMembership= $this->Common_model->exequery("SELECT membershipId,paymentType,paymentMethod,subscriptionId,payerId,endDate,cardLast4, cardExpMonth, cardExpYear FROM vm_user_memberships WHERE startDate <= '".date('Y-m-d')."' AND endDate >= '".date('Y-m-d')."' AND userId=".$userId['roleId']." AND subscriptionStatus ='Active' order by membershipId desc limit 0, 1",1);

                    $checkPrevmemmbership  = $this->Common_model->exequery("SELECT count(*) as member FROM vm_user_memberships WHERE userId = '".$userId['roleId']."' AND selfpay ='1'", true);
                    
                    if($paymentType != 'Auto') {

                        /*--------------------------- Check Wallet Amount ------------------*/
                        if($discountData['valid']) {
                            $updatedAmount =  ($discountData['data']['amount'] >= $subscriptionData->amount ) ? 0 : str_replace(',', '', number_format(($subscriptionData->amount - $discountData['data']['amount']),2)) * 100;
                        }
                        else
                            $updatedAmount = $subscriptionData->amount * 100;
                        $subscriptionInfo = array('userId' => $userId['roleId'], 'paymentMethod' => 'Stripe', 'paymentType' => 'Mannual', 'planId' => $subscriptionData->id, 'subscriptionStatus' => 'Active', 'selfpay' => '1','isTrail' =>  $this->testmode);
                        if( $updatedAmount > 0 ) {
                            $subscription = \Stripe\Charge::create(array(        

                                  "amount" => $updatedAmount,

                                  "currency" => strtoupper($subscriptionData->currency),

                                  "source" => $_POST['stripe_token'],

                                  "description" => $subscriptionData->planName.' '.$userDetail->userName)              

                            ); 

                        

                            $endDate = ($userMembership) ? (($userMembership->endDate >= date('Y-m-d')) ? date('Y-m-d',strtotime("+".$subscriptionData->period." ".$subscriptionData->duration,strtotime($userMembership->endDate))) : date('Y-m-d', strtotime("+".$subscriptionData->period." ".$subscriptionData->duration, $subscription->created))) : date('Y-m-d', strtotime("+".$subscriptionData->period." ".$subscriptionData->duration, $subscription->created));
                            $subscriptionid = $subscription->id;
                            $subscriptionInfo['transactionId'] = $subscription->balance_transaction;
                            $subscriptionInfo['subscriptionId'] = $subscription->id;
                            $subscriptionInfo['cardLast4'] = $subscription->source->last4;
                            $subscriptionInfo['cardExpMonth'] = $subscription->source->exp_month;
                            $subscriptionInfo['cardExpYear'] = $subscription->source->exp_year;
                            $subscriptionInfo['subscriptionAmount'] = $subscriptionData->amount;
                            $subscriptionInfo['paymentDate'] = date('Y-m-d h:i:s',$subscription->created);
                            $subscriptionInfo['startDate'] = date('Y-m-d h:i:s',$subscription->created);
                            $subscriptionInfo['payerId'] = $subscription->id;
                            $subscriptionInfo['isTrail'] = $this->testmode;
                            $subscriptionInfo['endDate'] = $endDate;

                        }
                        else {
                            $createdDate = strtotime(date('Y-m-d H:i:s'));
                            $endDate = ($userMembership) ? (($userMembership->endDate >= date('Y-m-d')) ? date('Y-m-d',strtotime("+".$subscriptionData->period." ".$subscriptionData->duration,strtotime($userMembership->endDate))) : date('Y-m-d', strtotime("+".$subscriptionData->period." ".$subscriptionData->duration, $createdDate))) : date('Y-m-d', strtotime("+".$subscriptionData->period." ".$subscriptionData->duration, $createdDate));
                            $subscriptionid = "WalletAmount";
                            $subscriptionInfo['transactionId'] = "Wallet Discount";
                            $subscriptionInfo['subscriptionId'] = $subscriptionid;
                            //$subscriptionInfo['cardLast4'] = $subscription->source->last4;
                            //$subscriptionInfo['cardExpMonth'] = $subscription->source->exp_month;
                           // $subscriptionInfo['cardExpYear'] = $subscription->source->exp_year;
                            $subscriptionInfo['subscriptionAmount'] = $subscriptionData->amount;
                            $subscriptionInfo['paymentDate'] = date('Y-m-d h:i:s');
                            $subscriptionInfo['startDate'] = date('Y-m-d h:i:s');
                            //$subscriptionInfo['payerId'] = $subscription->id;
                            $subscriptionInfo['isTrail'] = $this->testmode;
                            $subscriptionInfo['endDate'] = $endDate;
                        }
                        if($discountData['valid']) 
                            $subscriptionInfo['referalCouponId'] = $discountData['data']['referalStripeCouponId'];
                        
                        if(isset($checkCouponCode->couponId)) 
                            $subscriptionInfo['couponId'] = $checkCouponCode->couponId;
                        
                        $user_membership = $this->Common_model->insert("vm_user_memberships", $subscriptionInfo);
                        if(isset($subscriptionInfo['referalCouponId']) && !empty($subscriptionInfo['referalCouponId']) && $user_membership) {
                            $this->Common_model->update('vm_user_referal_stripe_coupon', array('isReedem' => 1), "referalStripeCouponId=".$subscriptionInfo['referalCouponId']);

                            if($currentInsertedReferalWalletId > 0 && isset($discountData['data']['amount']) && $discountData['data']['amount'] > 0){
                                $updatedCurrentbalance = $currentBalance - $discountData['data']['amount'];
                                $updatedCurrentbalance = ( $updatedCurrentbalance > 0 ) ? $updatedCurrentbalance : 0;
                                $this->Common_model->insert("vm_user_referal_wallets", array("userId" => $userId['roleId'], "amount" => $discountData['data']['amount'], "transType" => 1, "type" => 1, "referalCouponId" => $subscriptionInfo['referalCouponId'], "currentAvailableBalance" => $updatedCurrentbalance, "addedOn" => date('Y-m-d H:i:s')));
                            }
                        }
                        if($user_membership){
                            if(isset($checkCouponCode->couponId) && $membershipCouponDiscountedPrice > 0){
                                $couponUsed = array('userId' => $userId['roleId'], 'couponId' => $checkCouponCode->couponId, 'addedOn' => date('Y-m-d H:i:s'));
                                $this->Common_model->insert('vm_coupon_redeem', $couponUsed);
                                $this->Common_model->update("vm_coupons", array('status'=>3), "couponId = '".$checkCouponCode->couponId."'");
                            }
                        }
                        $expiryDate = $endDate;

                    }

                    else {
                        $upgradeMembership = ($userMembership) ? ( ( $userMembership->paymentType == 'Auto' ) ? true : false ): false;
                        if( $upgradeMembership ) {
                            try {
                                $subscription = \Stripe\Subscription::retrieve($userMembership->subscriptionId);
                                try {
                                    $upgradeSubscriptionInfo = [
                                      'cancel_at_period_end' => false,
                                      'items' => [
                                        [
                                          'id' => $subscription->items->data[0]->id,
                                          'plan' => $subscriptionData->planId,
                                        ],
                                      ]
                                    ];
                                    $referalCouponId = 0;
                                    if($discountData['valid'] && isset($discountData['data']['referalStripeCouponId']) && !empty($discountData['data']['referalStripeCouponId']) && isset($discountData['data']['amount']) && !empty($discountData['data']['amount'])) {
                                        $upgradeSubscriptionInfo['coupon'] = $discountData['data']['couponCode'];
                                        $referalCouponId = $discountData['data']['referalStripeCouponId'];
                                    }
                                    $subscriptionDetails = \Stripe\Subscription::update($userMembership->subscriptionId, $upgradeSubscriptionInfo);
                                    
                                    $expiryDate = date('Y-m-d',$subscriptionDetails->current_period_end);
                                    
                                    $subscriptionid = $subscriptionDetails->id;  
                                    $subscriptionInfo = array('userId' => $userId['roleId'], 'paymentMethod' => 'Stripe', 'paymentType' => 'Auto', 'planId' => $subscriptionData->id, 'transactionId' => $subscriptionDetails->id, 'subscriptionId' => $subscriptionDetails->id, 'cardLast4' => $userMembership->cardLast4, 'cardExpMonth' => $userMembership->cardExpMonth, 'cardExpYear' => $userMembership->cardExpYear, 'subscriptionAmount' => $subscriptionDetails->plan->amount / 100, 'paymentDate' => date('Y-m-d h:i:s',$subscriptionDetails->created), 'subscriptionStatus' => 'Active', 'startDate' => date('Y-m-d',$subscriptionDetails->current_period_start), 'endDate' => date('Y-m-d',$subscriptionDetails->current_period_end), 'payerId' => $subscriptionDetails->customer, 'selfpay' => '1','isTrail' =>  $this->testmode, 'isUpdatedPlan' => 1, 'invoiceId' => $subscriptionDetails->latest_invoice, 'subscriptionLogStatus' => 1);
                                    /*if($discountData['valid'] && isset($discountData['data']['referalStripeCouponId']) && !empty($discountData['data']['referalStripeCouponId']) && isset($discountData['data']['amount']) && !empty($discountData['data']['amount']))*/
                                    $user_membership = $this->Common_model->insertUnique("vm_user_memberships", $subscriptionInfo);
                                    if($user_membership) {
                                         $this->Common_model->update("vm_user_memberships", array('isPrevoiusLog' => 1, 'subscriptionStatus' => 'DeActive'), "membershipId = ".$userMembership->membershipId."" );
                                        try {
                                            $invoiceDetails = \Stripe\Invoice::retrieve($subscriptionDetails->latest_invoice);
                                            if(!empty($invoiceDetails->discount) &&  !is_null($invoiceDetails->discount)) {
                                                $this->Common_model->update("vm_user_memberships", array('referalCouponId' => $referalCouponId), "membershipId = ".$user_membership);
                                                $this->Common_model->update('vm_user_referal_stripe_coupon', array('isReedem' => 1), "referalStripeCouponId=".$referalCouponId);

                                                if($currentInsertedReferalWalletId > 0 && isset($discountData['data']['amount']) && $discountData['data']['amount'] > 0){
                                                    $updatedCurrentbalance = $currentBalance - $discountData['data']['amount'];
                                                    $updatedCurrentbalance = ( $updatedCurrentbalance > 0 ) ? $updatedCurrentbalance : 0;
                                                    $this->Common_model->insert("vm_user_referal_wallets", array("userId" => $userId['roleId'], "amount" => $discountData['data']['amount'], "transType" => 1, "type" => 1, "referalCouponId" => $referalCouponId, "currentAvailableBalance" => $updatedCurrentbalance, "addedOn" => date('Y-m-d H:i:s')));
                                                }
                                            }
                                        }
                                        catch(Exception $e) {

                                        }
                                    }
                                    
                                }
                                catch( Exception $e) {
                                    $this->response(array('status' => false, 'messgae' => $e->getMessage()), REST_Controller::HTTP_FORBIDDEN);
                                }

                            }
                            catch (Exception $e) {
                                $this->response(array('status' => false, 'messgae' => $e->getMessage()), REST_Controller::HTTP_FORBIDDEN);
                            }
                        }
                        else {
                            if($this->testmode == 1) {
                                $default_source = '';
                                $customer_id = '';
                                if($userDetail->test_stripe_customer_id !='') {
                                    $customer = \Stripe\Customer::retrieve($userDetail->test_stripe_customer_id);
                                    $cardsData = $customer->sources->create(["source" => $_POST['stripe_token']]);
                                    $default_source = $cardsData->id;
                                    $customer_id = $cardsData->customer;
                                }
                                else {
                                    $customer = \Stripe\Customer::create(array(

                                        'email' => $userDetail->email,

                                        'source'  => $_POST['stripe_token'],

                                        'metadata' => array('First Name' => $userDetail->first_name, 'Last Name' =>$userDetail->lastName ),

                                    ));
                                    $customer_id =  $customer->id;
                                    $default_source = $customer->default_source;
                                }
                            }
                            else {
                                $default_source = '';
                                $customer_id = '';
                                if($userDetail->test_stripe_customer_id !='') {
                                    $customer = \Stripe\Customer::retrieve($userDetail->stripe_customer_id);
                                    $cardsData = $customer->sources->create(["source" => $_POST['stripe_token']]);
                                    $default_source = $cardsData->id;
                                    $customer_id = $cardsData->customer;
                                }
                                else {
                                    $customer = \Stripe\Customer::create(array(

                                        'email' => $userDetail->email,

                                        'source'  => $_POST['stripe_token'],

                                        'metadata' => array('First Name' => $userDetail->first_name, 'Last Name' =>$userDetail->lastName ),

                                    ));
                                    $customer_id =  $customer->id;
                                    $default_source = $customer->default_source;
                                }
                            }
                            
                            if($this->testmode == 1)

                                $update_customer_id = $this->Common_model->update("vm_user", array('test_stripe_customer_id' => $customer_id),"userId=".$userId['roleId']);

                            else

                                $update_customer_id = $this->Common_model->update("vm_user", array('stripe_customer_id' => $customer_id),"userId=".$userId['roleId']);

                            $subscriptionDetails = array(

                                'customer' => $customer_id,

                                'items' => array(array('plan' => $subscriptionData->planId)),
                                'default_source' =>  $default_source,

                                'metadata' => array('First Name' => $userDetail->first_name, 'Last Name' =>$userDetail->lastName, 'Subscription Name' => $subscriptionData->planName)

                              );

                            $referalCouponId = 0;
                            if($discountData['valid'] && isset($discountData['data']['referalStripeCouponId']) && !empty($discountData['data']['referalStripeCouponId']) && isset($discountData['data']['amount']) && !empty($discountData['data']['amount'])) {
                                $subscriptionDetails['coupon'] = $discountData['data']['couponCode'];
                                $referalCouponId = $discountData['data']['referalStripeCouponId'];
                            }   
                            
                            $subscription = \Stripe\Subscription::create($subscriptionDetails);

                            $expiryDate = date('Y-m-d',$subscription->current_period_end);

                            $subscriptionid = $subscription->id;  

                            $user_membership = $this->Common_model->insertUnique("vm_user_memberships", array('userId' => $userId['roleId'], 'paymentMethod' => 'Stripe', 'paymentType' => 'Auto', 'planId' => $subscriptionData->id, 'transactionId' => $subscription->id, 'subscriptionId' => $subscription->id, 'cardLast4' => $customer->sources->data[0]->last4, 'cardExpMonth' => $customer->sources->data[0]->exp_month, 'cardExpYear' => $customer->sources->data[0]->exp_year, 'subscriptionAmount' => $subscription->plan->amount / 100, 'paymentDate' => date('Y-m-d h:i:s',$subscription->created), 'subscriptionStatus' => 'Active', 'startDate' => date('Y-m-d',$subscription->current_period_start), 'endDate' => date('Y-m-d',$subscription->current_period_end), 'payerId' => $subscription->customer, 'selfpay' => '1','isTrail' =>  $this->testmode, 'isUpdatedPlan' => 1, 'invoiceId' => $subscription->latest_invoice));
                            if($user_membership) {                                 
                                try {
                                    $invoiceDetails = \Stripe\Invoice::retrieve($subscription->latest_invoice);
                                    if(!empty($invoiceDetails->discount) &&  !is_null($invoiceDetails->discount)) {
                                        $this->Common_model->update("vm_user_memberships", array('referalCouponId' => $referalCouponId), "membershipId = ".$user_membership);
                                         $this->Common_model->update('vm_user_referal_stripe_coupon', array('isReedem' => 1), "referalStripeCouponId=".$referalCouponId);

                                        if($currentInsertedReferalWalletId > 0 && isset($discountData['data']['amount']) && $discountData['data']['amount'] > 0){
                                            $updatedCurrentbalance = $currentBalance - $discountData['data']['amount'];
                                            $updatedCurrentbalance = ( $updatedCurrentbalance > 0 ) ? $updatedCurrentbalance : 0;
                                            $this->Common_model->insert("vm_user_referal_wallets", array("userId" => $userId['roleId'], "amount" => $discountData['data']['amount'], "transType" => 1, "type" => 1, "referalCouponId" => $referalCouponId, "currentAvailableBalance" => $updatedCurrentbalance, "addedOn" => date('Y-m-d H:i:s')));
                                        }
                                    }
                                }
                                catch(Exception $e) {

                                }
                            }
                        }
                        

                    }

                    if( $checkPrevmemmbership->member == 0 ) {

                        if( $userDetail->refered_by != 0 ) {
                            $getReferedAvaiableBalance = $this->Common_model->exequery("SELECT currentAvailableBalance FROM vm_user_referal_wallets WHERE userId='".$userDetail->refered_by."' order by referalWalletId desc limit 0,1", 1);
                            $referalProfileInfo = $this->Common_model->exequery("SELECT userId,email,userName as first_name, lastName, CONCAT(userName,' ',lastName) as userName,refered_by, stripe_customer_id, test_stripe_customer_id from vm_user WHERE userId=".$userDetail->refered_by. " AND status = '0'", true);
                            $currentReferedBalance = ($getReferedAvaiableBalance) ? $getReferedAvaiableBalance->currentAvailableBalance: 0;
                            $currentReferedBalance = $currentReferedBalance + 10;
                            $this->Common_model->insert("vm_user_referal_wallets", array("userId" => $userDetail->refered_by, "amount" => 10, "transType" => 0, "type" => 1, "referalUsedId" => $userId['roleId'], "currentAvailableBalance" => $currentReferedBalance, "addedOn" => date('Y-m-d H:i:s')));
                            if($referalProfileInfo ) {
                                $settings = array();
                                $settings["template"]               =  "referal_bonus_tpl".$langSuffix.".html";

                                $settings["email"]                  =  $referalProfileInfo->email; 

                                $settings["subject"]                =  ($langSuffix =='_fr') ? "CHF10 ont été ajoutés à ton portefeuille Vedmir" : "CHF10 added in your Vedmir Wallet";

                                $contentarr["[[[REFERALNAME]]]"]               = $referalProfileInfo->first_name;
                                $contentarr["[[[REFREENAME]]]"]               = $userDetail->first_name;
                                

                                $settings["contentarr"]             =   $contentarr;

                                $this->common_lib->sendMail($settings);
                            }

                        }     

                    }

                    $settings = array();

                    $settings["template"]               =  "membership_tpl".$langSuffix.".html";

                    $settings["email"]                  =  $userDetail->email; 

                    $settings["subject"]                =  ($langSuffix =='_fr') ? "Ton abonnement Vedmir est maintenant actif !" : "Your Vedmir membership is now active!";

                    $contentarr["[[[USERNAME]]]"]               = $userDetail->userName;
                    $contentarr["[[[MEMBERSHIPLOGOURL]]]"]      = BASEURL."/system/static/frontend/images/membership_logo.png";
                    $contentarr["[[[SubscriptionPlan]]]"]               = $subscriptionData->planName;
                    $contentarr["[[[PURCHASEDATE]]]"]               = date('d.m.Y');
                    $total = number_format($subscriptionData->amount/(1+7.7/100), 2);
                    $taxAmount =  $subscriptionData->amount - $total;
                    $contentarr["[[[AMOUNT]]]"]               =      'CHF '.$subscriptionData->amount;
                    
                    $contentarr["[[[TAXAMOUNT]]]"]               =      'CHF '.$taxAmount;
                    $contentarr["[[[ExpiryDATE]]]"]               = date('d.m.Y', strtotime($expiryDate));
                    $contentarr["[[[VEDMIRTEXTURL]]]"]               = BASEURL."/system/static/frontend/images/vedmir_text_logo.png";

                    $settings["contentarr"]             =   $contentarr;

                    $this->common_lib->sendMail($settings);

                    /*$settings = array();

                    $settings["template"]               =  "membership_tpl_fr.html";

                    $settings["email"]                  =  $userDetail->email; 

                    $settings["subject"]                =  "Ton abonnement Vedmir est maintenant actif !";

                    $contentarr["[[[USERNAME]]]"]               = $userDetail->userName;
                    $contentarr["[[[MEMBERSHIPLOGOURL]]]"]      = BASEURL."/system/static/frontend/images/membership_logo.png";
                    $contentarr["[[[SubscriptionPlan]]]"]               = $subscriptionData->planNameFr;
                    $contentarr["[[[PURCHASEDATE]]]"]               = date('d.m.Y');
                    $contentarr["[[[AMOUNT]]]"]               = $subscriptionData->amount;
                    $contentarr["[[[ExpiryDATE]]]"]               = date('d.m.Y', strtotime($expiryDate));
                    $contentarr["[[[VEDMIRTEXTURL]]]"]               = BASEURL."/system/static/frontend/images/vedmir_text_logo.png";

                    $settings["contentarr"]             =   $contentarr;

                    $this->common_lib->sendMail($settings);*/

                    $this->response(array('status' => true, 'message' => $this->lang->line('successPayment'),'subscription' => $subscriptionid, 'planName' => @$subscriptionData->planName, 'planImg' => @$subscriptionData->planImg), REST_Controller::HTTP_OK);

                }

                catch(\Stripe\Error\Card $e) {

                  // Since it's a decline, \Stripe\Error\Card will be caught

                   $error_message['card_error'] = $e->getMessage();

                  

                } catch (\Stripe\Error\RateLimit $e) {

                    $error_message['rate_limit'] = $e->getMessage();

                  // Too many requests made to the API too quickly

                } catch (\Stripe\Error\InvalidRequest $e) {

                     $error_message['invalid_request'] = $e->getMessage();

                  // Invalid parameters were supplied to Stripe's API

                } catch (\Stripe\Error\Authentication $e) {

                    $error_message['auth_error'] = $e->getMessage();

                  // Authentication with Stripe's API failed

                  // (maybe you changed API keys recently)

                } catch (\Stripe\Error\ApiConnection $e) {

                    $error_message['connection_error'] = $e->getMessage();

                  // Network communication with Stripe failed

                } catch (\Stripe\Error\Base $e) {

                    $error_message['genric_error'] = $e->getMessage();

                  // Display a very generic error to the user, and maybe send

                  // yourself an email

                } catch (Exception $e) {

                    $error_message['message'] = $e->getMessage();

                  // Something else happened, completely unrelated to Stripe

                }

                if(!empty($error_message))

                    $this->response(array('status' => false, 'message' => $this->lang->line('paymentVerificationFailed'),'error' => $error_message), REST_Controller::HTTP_PAYMENT_REQUIRED); // HTTP_PAYMENT_REQUIRED (402) being the HTTP response code

            }

            else 

                $this->response(array('status' => false, 'message' => $this->lang->line('userVerificationFailed')), REST_Controller::HTTP_FORBIDDEN);            

            

        }

        else

            $this->response([

                    'status' => FALSE,

                    'message' => $this->lang->line('unAuthorized')

                ], REST_Controller::HTTP_UNAUTHORIZED); // HTTP_UNAUTHORIZED (401) being the HTTP response code 

    }



    public function check_gift_email_post() {

        $errors = array();

        $error_message = array();

        $this->load->library('form_validation');                   

        if(!isset($_POST['email']) || empty($_POST['email']))

           $this->response(array('status' => false, 'messgae' => $this->lang->line('emailRequired')), REST_Controller::HTTP_BAD_REQUEST); 

        $this->form_validation->set_rules('email', 'Email', 'valid_email'); 

        if ($this->form_validation->run() == FALSE)

            $this->response(array('status' => false, 'messgae' => $this->lang->line('invalidEmail')), REST_Controller::HTTP_BAD_REQUEST); 

        $checkemail = $this->Common_model->exequery(" SELECT userId FROM vm_user WHERE email='".$_POST['email']."'");

        if( $checkemail )

            $this->response(array('message' => $this->lang->line('alreadyRegistered'),'status' => false), REST_Controller::HTTP_FORBIDDEN);

        else

            $this->response(array('message' => sprintf($this->lang->line('giftMembershipEmail'), $_POST['email']),'status' => true), REST_Controller::HTTP_OK);

        



    }



    public function gift_membership_post() { 

        $errors = array();

        $error_message = array();  

        $token = $this->input->get_request_header('Authorization', TRUE);

        $langSuffix = $this->lang->line('langSuffix');

        if($token != '' && $this->common_lib->validateToken($token)){

            $userId = $this->common_lib->validateToken($token);

            $this->load->library('form_validation'); 

            if(!isset($_POST['email']) || empty($_POST['email']))

                $this->response(array('status' => false, 'messgae' =>  $this->lang->line('emailRequired')), REST_Controller::HTTP_BAD_REQUEST);

            if(!isset($_POST['name']) || empty($_POST['name']))

                $this->response(array('status' => false, 'messgae' =>  $this->lang->line('nameRequired')), REST_Controller::HTTP_BAD_REQUEST);

            if(!isset($_POST['planId']) || empty($_POST['planId']))

                $this->response(array('status' => false, 'messgae' =>  $this->lang->line('planIdRequired')), REST_Controller::HTTP_BAD_REQUEST);

            $this->form_validation->set_rules('email', 'Email', 'valid_email');

            if ($this->form_validation->run() == FALSE)

                $this->response(array('status' => false, 'messgae' => $this->lang->line('invalidEmail')), REST_Controller::HTTP_BAD_REQUEST);                           

            

            if(!isset($_POST['stripe_token']) || empty($_POST['stripe_token']))

                 $this->response(array('status' => false, 'messgae' => $this->lang->line('stripeTokenRequired')), REST_Controller::HTTP_BAD_REQUEST);

            $subscriptionData = $this->Common_model->exequery("SELECT vm_subscription_details.planId,vm_subscription_details.amount,vm_subscription_details.currency,vm_subscription_details.detailId as id,vm_subscription_details.period,vm_subscription_details.duration,vm_subscription_plan.planName".$langSuffix." as planName, vm_subscription_plan.planName_fr as planNameFr,(case when icon !='' then concat('".UPLOADPATH."/',icon) else '' end) as planImg FROM vm_subscription_details left join vm_subscription_plan on vm_subscription_details.subscriptionId = vm_subscription_plan.id WHERE vm_subscription_details.detailId='".$_POST['planId']."'",true);

            $userDetail = $this->Common_model->exequery("SELECT userId,email,CONCAT(userName,' ',lastName) as userName from vm_user WHERE userId=".$userId['roleId']. " AND status = '0'", true);

            if( $userDetail ){

                try {

                    $subscription = \Stripe\Charge::create(array(        

                                      "amount" => $subscriptionData->amount * 100,

                                      "currency" => strtoupper($subscriptionData->currency),

                                      "source" => $_POST['stripe_token'],

                                      "description" => 'Gift Membership '.$userDetail->userName)              

                                ); 

                    $couponCode = generateStrongPassword(8,false,'ud');                   

                    $user_membership = $this->Common_model->insertUnique("vm_gift_memberships", array('giftedBy' => $userId['roleId'], 'name' => $_POST['name'], 'planId' => $_POST['planId'], 'email' => $_POST['email'], 'couponCode' => $couponCode, 'paymentMethod' => 'Stripe', 'transactionId' => $subscription->balance_transaction, 'subscriptionId' => $subscription->id, 'cardLast4' => $subscription->source->last4, 'cardExpMonth' => $subscription->source->exp_month, 'cardExpYear' => $subscription->source->exp_year, 'subscriptionAmount' => $subscription->amount / 100, 'paymentDate' => date('Y-m-d h:i:s',$subscription->created), 'paymentStatus' => 'Confirm', 'payerId' => $subscription->id,'giftedDate' => date('Y-m-d h:i:s'),'isTrail' =>  $this->testmode));
                    $this->Common_model->insert('vm_coupons', array('couponCode' => $couponCode, 'giftId' => $user_membership, 'addedOn' => date('Y-m-d H:i:s')));

                    $settings = array();

                    $settings["template"]               =  "membership_gift_tpl".$this->lang->line('langSuffix').".html";

                    $settings["email"]                  =  $_POST['email']; 

                    $settings["subject"]                =  ($this->lang->line('langSuffix') == '_fr')?"Ton abonement premium Vedmir est arrivé !":"Your Premium Vedmir Gift is here!";

                    $contentarr["[[[COUPONCODE]]]"]             = $couponCode;

                    $contentarr["[[[USERNAME]]]"]               = trim($_POST['name']);

                    $contentarr["[[[SENDERNAME]]]"]             = $userDetail->userName;

                    $contentarr["[[[PLANNAME]]]"]               = $subscriptionData->planName;

                    $settings["contentarr"]             =   $contentarr;

                    $this->common_lib->sendMail($settings);

                    $this->response(array('status' => true, 'message' => $this->lang->line('successPayment')), REST_Controller::HTTP_OK);

                }

                catch(\Stripe\Error\Card $e) {

                  // Since it's a decline, \Stripe\Error\Card will be caught

                   $error_message['card_error'] = $e->getMessage();

                  

                } catch (\Stripe\Error\RateLimit $e) {

                    $error_message['rate_limit'] = $e->getMessage();

                  // Too many requests made to the API too quickly

                } catch (\Stripe\Error\InvalidRequest $e) {

                     $error_message['invalid_request'] = $e->getMessage();

                  // Invalid parameters were supplied to Stripe's API

                } catch (\Stripe\Error\Authentication $e) {

                    $error_message['auth_error'] = $e->getMessage();

                  // Authentication with Stripe's API failed

                  // (maybe you changed API keys recently)

                } catch (\Stripe\Error\ApiConnection $e) {

                    $error_message['connection_error'] = $e->getMessage();

                  // Network communication with Stripe failed

                } catch (\Stripe\Error\Base $e) {

                    $error_message['genric_error'] = $e->getMessage();

                  // Display a very generic error to the user, and maybe send

                  // yourself an email

                } catch (Exception $e) {

                    $error_message['message'] = $e->getMessage();

                  // Something else happened, completely unrelated to Stripe

                }

                if(!empty($error_message))

                    $this->response(array('status' => false, 'message' => $this->lang->line('paymentVerificationFailed'),'error' => $error_message), REST_Controller::HTTP_PAYMENT_REQUIRED); // HTTP_PAYMENT_REQUIRED (402) being the HTTP response code

            }

            else 

                $this->response(array('status' => false, 'message' => $this->lang->line('userVerificationFailed')), REST_Controller::HTTP_FORBIDDEN);            

            

        }

        else

            $this->response([

                    'status' => FALSE,

                    'message' => $this->lang->line('unAuthorized')

                ], REST_Controller::HTTP_UNAUTHORIZED); // HTTP_UNAUTHORIZED (401) being the HTTP response code 

    }



    public function check_membership_get() { 
        $langSuffix = $this->lang->line('langSuffix');
        $errors = array();

        $error_message = array();  

        $token = $this->input->get_request_header('Authorization', TRUE);

        if($token != '' && $userId = $this->common_lib->validateToken($token)){
           
            $userMembership = $this->Common_model->exequery("SELECT * FROM vm_user_memberships s WHERE startDate <= '".date('Y-m-d')."' AND endDate >= '".date('Y-m-d')."' AND userId=".$userId['roleId']." AND subscriptionStatus ='Active' order BY membershipId desc limit 0,1",true);

            if( $userMembership ){

                if($userMembership->isUpdatedPlan == 1)
                    $qry = "SELECT sp.planName$langSuffix as planName, sd.duration, sd.period FROM vm_subscription_details sd left JOIN vm_subscription_plan as sp on sp.Id = sd.subscriptionId WHERE detailId =".$userMembership->planId;
                else
                    $qry = "SELECT sp.planName$langSuffix as planName FROM vm_subscription_plan as sp WHERE sp.Id =".$userMembership->planId;
                $planDetails  = $this->Common_model->exequery($qry, true);
                $userMembership->planName = ($planDetails) ? $planDetails->planName : '';
                if( $userMembership->couponId != 0 ) {
                    $couponDetail = $this->Common_model->exequery("SELECT couponCode FROM vm_coupons where couponId='".$userMembership->couponId."'",true);
                    $userMembership->couponCode = ($couponDetail) ? $couponDetail->couponCode : '';
                }
                else if($userMembership->isAmabassadarProgram != 0) {
                    if($planDetails) {
                        if($planDetails->duration == 'month' && $planDetails->period == 1)
                            $userMembership->couponCode = 'Free';
                        else if($planDetails->duration == 'month' && $planDetails->period == 3)
                            $userMembership->couponCode = '3-M';
                        else
                            $userMembership->couponCode = '1-Y';
                    }   
                }
                else
                    $userMembership->couponCode = '';
                $to_time = strtotime($userMembership->endDate);

                $from_time = strtotime(date('Y-m-d'));

                $days = round(($to_time - $from_time) / (3600*24));

                $remainingDays = ( $days > -1 ) ? $days : -1;

                $startdate = strtotime($userMembership->startDate);

                $endDate = strtotime($userMembership->endDate);

                $totaldays = round(($endDate - $startdate) / (3600*24));

                $totaldaysdata = ( $totaldays > -1 ) ? $totaldays : -1;

                $this->response([

                    'status' => true,

                    'message' => 'User have subcription ',

                    'startdate' => $userMembership->startDate,

                    'paymentType' => $userMembership->paymentType,
                    'planName'    => $userMembership->planName,
                    'couponCode'    => $userMembership->couponCode,
                    'endDate' => $userMembership->endDate,

                    'subscriptionAmount' => $userMembership->subscriptionAmount,

                    'remainingDays' => $remainingDays,

                    'totalDays' => $totaldaysdata,

                    'membershipMode' => $userMembership->paymentType,

                    'paymentMethod' => $userMembership->paymentMethod,

                    'transactionId' => $userMembership->transactionId

                ], REST_Controller::HTTP_OK);

            }  

            else

                $this->response([

                    'status' => FALSE,

                    'message' => $this->lang->line('noActiveMember')

                ], REST_Controller::HTTP_FORBIDDEN); 

        }

        else

            $this->response([

                    'status' => FALSE,

                    'message' => $this->lang->line('unAuthorized')

                ], REST_Controller::HTTP_UNAUTHORIZED); // HTTP_UNAUTHORIZED (401) being the HTTP response code 

    }

    public function usergiftlist_get() { 

        $errors = array();

        $error_message = array();  

        $token = $this->input->get_request_header('Authorization', TRUE);

        if($token != '' && $userId = $this->common_lib->validateToken($token)){

            $giftedUser= $this->Common_model->exequery("SELECT sgm.transactionId,sgm.subscriptionAmount,sgm.paymentMethod,sgm.claimGift,sum.userId,su.userName as fullName, su.email, (case when su.img!='' then concat('".UPLOADPATH."/user_images/',su.img) when su.oauth_provider = 'facebook' then su.picture_url else '".UPLOADPATH."/user_images/default.jpg' end ) as `profile_image`  FROM vm_gift_memberships sgm LEFT JOIN vm_user_memberships sum ON sgm.giftId = sum.giftId  LEFT JOIN vm_user su ON sum.userId = su.userId   WHERE sgm.giftedBy = '".$userId['roleId']."'"); 

            $giftedUser = ($giftedUser) ? $giftedUser : array();

            $this->response(array('status' => true, 'userList' => $giftedUser), REST_Controller::HTTP_OK);



        }

        else

            $this->response([

                    'status' => FALSE,

                    'message' => $this->lang->line('unAuthorized')

                ], REST_Controller::HTTP_UNAUTHORIZED); // HTTP_UNAUTHORIZED (401) being the HTTP response code 

    }

    public function cancel_get() { 

        $errors = array();

        $error_message = array();  

        $token = $this->input->get_request_header('Authorization', TRUE);

        if($token != '' && $userId = $this->common_lib->validateToken($token)){

            $userMembership= $this->Common_model->exequery("SELECT membershipId,paymentType,paymentMethod,subscriptionId,payerId FROM vm_user_memberships WHERE startDate <= '".date('Y-m-d')."' AND endDate >= '".date('Y-m-d')."' AND userId=".$userId['roleId']." AND subscriptionStatus ='Active' order by membershipId desc limit 0, 1",1);             

            if($userMembership) { 

                if( $userMembership->paymentType == 'Auto' ){

                    try {

                        $subscription = \Stripe\Subscription::retrieve($userMembership->subscriptionId);

                        $cancelSub = $subscription->cancel(['at_period_end' => true]);

                        $update_customer_id = $this->Common_model->update("vm_user_memberships", array('paymentType' => 'Mannual'),"userId=".$userId['roleId']." AND membershipId= '".$userMembership->membershipId."'");

                        $this->response(array('status' => true, 'message' => $this->lang->line('autoRenewalOffNew')), REST_Controller::HTTP_OK);

                    }

                    catch(\Stripe\Error\Card $e) {

                      // Since it's a decline, \Stripe\Error\Card will be caught

                       $error_message['card_error'] = $e->getMessage();

                      

                    } catch (\Stripe\Error\RateLimit $e) {

                        $error_message['rate_limit'] = $e->getMessage();

                      // Too many requests made to the API too quickly

                    } catch (\Stripe\Error\InvalidRequest $e) {

                         $error_message['invalid_request'] = $e->getMessage();

                      // Invalid parameters were supplied to Stripe's API

                    } catch (\Stripe\Error\Authentication $e) {

                        $error_message['auth_error'] = $e->getMessage();

                      // Authentication with Stripe's API failed

                      // (maybe you changed API keys recently)

                    } catch (\Stripe\Error\ApiConnection $e) {

                        $error_message['connection_error'] = $e->getMessage();

                      // Network communication with Stripe failed

                    } catch (\Stripe\Error\Base $e) {

                        $error_message['genric_error'] = $e->getMessage();

                      // Display a very generic error to the user, and maybe send

                      // yourself an email

                    } catch (Exception $e) {

                        $error_message['message'] = $e->getMessage();

                      // Something else happened, completely unrelated to Stripe

                    }

                    if(!empty($error_message))

                        $this->response(array('status' => false, 'message' => $this->lang->line('failedCancelSubscription'),'error' => $error_message), REST_Controller::HTTP_PAYMENT_REQUIRED); // HTTP_PAYMENT_REQUIRED (402) being the HTTP response code

                }

                else

                   $this->response([

                    'status' => FALSE,

                    'message' => $this->lang->line('noAutoRenewal')

                ], REST_Controller::HTTP_FORBIDDEN); 

            }

            else

               $this->response([

                    'status' => FALSE,

                    'message' => $this->lang->line('noActiveSubscription')

                ], REST_Controller::HTTP_FORBIDDEN);

        }

        else

            $this->response([

                    'status' => FALSE,

                    'message' => $this->lang->line('unAuthorized')

                ], REST_Controller::HTTP_UNAUTHORIZED); // HTTP_UNAUTHORIZED (401) being the HTTP response code 

    }




    public function claimmembership_post() { 

        $errors = array();

        $error_message = array();  
        $langSuffix = $this->lang->line('langSuffix');
        $token = $this->input->get_request_header('Authorization', TRUE);

        if($token != '' && $userId = $this->common_lib->validateToken($token)){

            $userDetail = $this->Common_model->exequery("SELECT CONCAT(userName,' ',lastName) as userName, email,userId, refered_by, isFreeMembershipUsed from vm_user WHERE userId='".$userId['roleId']."' AND status ='0'",1);

            if(!$userDetail)

                $this->response([

                    'status' => FALSE,

                    'message' => $this->lang->line('noClaim')

                ], REST_Controller::HTTP_UNAUTHORIZED);

            if(!isset($_POST['coupon_code']) || empty($_POST['coupon_code']))

                $this->response(array('status' => false, 'messgae' => $this->lang->line('couponRequired')), REST_Controller::HTTP_BAD_REQUEST); 

            $checkGiftCode = $this->Common_model->exequery("SELECT * FROM vm_user WHERE userCode='".$_POST['coupon_code']."' AND userId!= '".$userId['roleId']."'",true);
            if( $checkGiftCode && $userDetail->refered_by < 1) {
              //$this->Common_model->update("vm_user", array('refered_by' => $checkGiftCode->userId), "userId = '".$userId['roleId']."'");
              //$this->Common_model->insert("vm_user_referal_wallets", array("userId" => $userId['roleId'], "amount" => 10, "transType" => 0, "type" => 0, "referalUsedId" => $checkGiftCode->userId, "currentAvailableBalance" => 10, "addedOn" => date('Y-m-d H:i:s')));
              $this->response(array('status' => true, 'message' => $this->lang->line('successfullyClaimedMembership'), 'isAmbassadorCouponCode' =>  false, 'isReferCode' => true, 'referAmount' => 10), REST_Controller::HTTP_OK);
            }
           
            $checkAmbassadorCode = $this->Common_model->exequery("SELECT * FROM vm_ambassador_user WHERE couponCode = '".$_POST['coupon_code']."'", true);
            if( $checkAmbassadorCode ) {
                $currentPlan = 0;
                $userMembership= $this->Common_model->exequery("SELECT membershipId,paymentType,paymentMethod,subscriptionId,payerId,endDate,cardLast4, cardExpMonth, cardExpYear, (SELECT planId FROM vm_user_memberships WHERE userId=".$userId['roleId']." AND isAmabassadarProgram != 0 order by membershipId desc limit 0, 1) as ambassadorplanId FROM vm_user_memberships WHERE startDate <= '".date('Y-m-d')."' AND endDate >= '".date('Y-m-d')."' AND userId=".$userId['roleId']." AND subscriptionStatus ='Active' order by membershipId desc limit 0, 1",1);
                if($userMembership) 
                    $currentPlan = (!is_null($userMembership->ambassadorplanId)) ? $userMembership->ambassadorplanId : 0;                        
                $currentPlanInfo = array();
                if( $currentPlan > 0 ) {
                    $currentPlanInfo = $this->Common_model->exequery("SELECT * FROM vm_subscription_details WHERE detailId='".$currentPlan."'", true);
                    $currentPlanInfo = ($currentPlanInfo) ? $currentPlanInfo : array();
                }
                $subscriptionPlan = $this->Common_model->exequery("SELECT id as planId, planName".$this->lang->line('langSuffix')." as planName, description".$this->lang->line('langSuffix')." as description, (case when icon !='' then concat('".UPLOADPATH."/',icon) else '' end ) as subscriptionLogo, amount, currency, period, duration FROM vm_subscription_plan WHERE status='0' AND isSubType='2'");

                $subscriptionPlan = ($subscriptionPlan) ? $subscriptionPlan : array();

                $subscriptionPlan = array_replace($subscriptionPlan,

                        array_fill_keys(

                            array_keys($subscriptionPlan, 'month'),

                            "MMM"

                        )

                    );

                if(!empty($subscriptionPlan)) {
                    foreach($subscriptionPlan as $subscriptionItem) {
                        $years = ($currentPlan > 0) ? $currentPlanInfo->duration: '';
                        $period = ($currentPlan > 0) ? $currentPlanInfo->period: 0;
                        
                        $subscriptionPlanList = $this->Common_model->exequery("SELECT *, (case when duration='year' then '12' else period end) as period, (CASE WHEN ".$currentPlan." > 0 then (CASE WHEN '".$years."' = 'year' then 1 else (CASE WHEN period > ".$period." then 0 else (CASE WHEN duration != 'year' then 1 else 0 end) end) end) else 0 end) as isEnable , (CASE WHEN ".$currentPlan." > 0 then (CASE WHEN '".$years."' = 'year' then 1 else (CASE WHEN period > ".$period." then 0 else (CASE WHEN duration != 'year' then 1 else 0 end) end) end) else 0 end) as isUsed FROM vm_subscription_details WHERE status=0 AND subscriptionId='".$subscriptionItem->planId."'");
                        if(!empty($subscriptionPlanList)){
                            foreach ($subscriptionPlanList as $planl) {
                                if(isset($planl->period) && $planl->period == 1 && $userDetail->isFreeMembershipUsed == 1)
                                    $planl->isUsed = 1;
                            }
                        }

                        $subscriptionItem->planList = ($subscriptionPlanList) ? $subscriptionPlanList : array();
                    }
                    $this->response(array('status' => true, 'message' => $this->lang->line('successfullyClaimedMembership'), 'isAmbassadorCouponCode' =>  true, 'isReferCode' => false, 'ambassadorCouponId' => $checkAmbassadorCode->ambassadorId, 'subscription' => $subscriptionPlan), REST_Controller::HTTP_OK);
                }
                else
                    $this->response(array('status' => false, 'message' => $this->lang->line('successfullyClaimedMembership'), 'isAmbassadorCouponCode' =>  false, 'isReferCode' => false), REST_Controller::HTTP_FORBIDDEN);
            }

            $checkCouponCode = $this->Common_model->exequery("SELECT vm_coupons.*, (SELECT count(*) FROM vm_coupon_redeem WHERE userId='".$userId['roleId']."' AND couponId=vm_coupons.couponId) as reedemCount, (SELECT count(*) FROM vm_coupon_redeem WHERE couponId=vm_coupons.couponId) as TotalReedemCount FROM vm_coupons WHERE vm_coupons.status='0' AND vm_coupons.couponCode='".$_POST['coupon_code']."'",1);
            

            if(isset($checkCouponCode->giftId) && $checkCouponCode->giftId == 0 && $userDetail->isFreeMembershipUsed && $checkCouponCode->discountedPrice == 0)
                $this->response(['status' => FALSE,'message' => $this->lang->line('invalidCouponCode')], REST_Controller::HTTP_FORBIDDEN);

            if( !$checkCouponCode )
                $this->response(['status' => FALSE,'message' => $this->lang->line('invalidCouponCode') ], REST_Controller::HTTP_FORBIDDEN);
            if( $checkCouponCode->type == '0' && $checkCouponCode->TotalReedemCount > 0 )
                $this->response(['status' => FALSE,'message' => $this->lang->line('invalidCouponCode') ], REST_Controller::HTTP_FORBIDDEN);
            if( $checkCouponCode->type == '1' && $checkCouponCode->TotalReedemCount >= $checkCouponCode->limituse )
                $this->response(['status' => FALSE,'message' => $this->lang->line('invalidCouponCode') ], REST_Controller::HTTP_FORBIDDEN);
            if($checkCouponCode->reedemCount > 0 )
                $this->response(['status' => FALSE,'message' => $this->lang->line('couponAlreadyUsed') ], REST_Controller::HTTP_FORBIDDEN);
            if(($checkCouponCode->giftId == 0 && $checkCouponCode->offeredType == 2) || ( $checkCouponCode->giftId == 0 && $checkCouponCode->offeredType == 3)) {
                if(strtotime(date('Y-m-d')) >= strtotime($checkCouponCode->startDate) && strtotime(date('Y-m-d')) <= strtotime($checkCouponCode->expiryDate)) {
                    if($checkCouponCode->offeredType == 3) {
                        $planId = 0;
                        $period = $checkCouponCode->period;
                        $duration = $checkCouponCode->duration;
                        $planName = $checkCouponCode->couponCode;
                        $isAdminCoupon = true;
                    }
                    else {
                        $membership = $this->Common_model->exequery("SELECT vm_subscription_details.*, vm_subscription_plan.planName, (case when vm_subscription_plan.icon !='' then concat('".UPLOADPATH."/',vm_subscription_plan.icon) else '' end ) as subscriptionLogo FROM vm_subscription_details left join vm_subscription_plan on vm_subscription_details.subscriptionId = vm_subscription_plan.Id WHERE detailId = '".$checkCouponCode->planId."'",1);
                        if(!$membership)
                            $this->response(['status' => FALSE,'message' => $this->lang->line('invalidCouponCode') ], REST_Controller::HTTP_FORBIDDEN);
                        else if($checkCouponCode->discountedPrice > 0){
                            $membership->discountedPrice = $checkCouponCode->discountedPrice;
                            $this->response(['status' => true,'isDiscountedMembership' => true, 'membershipData'=>$membership], REST_Controller::HTTP_OK);
                        }


                        $planId = $checkCouponCode->planId;
                        $period = $membership->period;
                        $duration = $membership->duration;
                        $planName = $membership->planName;
                        $isAdminCoupon = true;
                    }
                }
                else
                    $this->response(['status' => FALSE,'message' => $this->lang->line('invalidCouponCode') ], REST_Controller::HTTP_FORBIDDEN);
            }
            else if($checkCouponCode->giftId != 0){
                $checkGiftCode = $this->Common_model->exequery("SELECT sgm.*, vm_subscription_details.detailId as subscriptionId, vm_subscription_details.period, vm_subscription_details.duration, vm_subscription_plan.planName FROM vm_gift_memberships sgm left join vm_subscription_details on sgm.planId = vm_subscription_details.detailId left join vm_subscription_plan on vm_subscription_details.subscriptionId = vm_subscription_plan.Id WHERE sgm.email ='".$userDetail->email."' AND sgm.couponCode='".$_POST['coupon_code']."' AND sgm.paymentStatus='Confirm' AND sgm.giftId='".$checkCouponCode->giftId."'",true);
                if(!$checkGiftCode)
                    $this->response(['status' => FALSE,'message' => $this->lang->line('invalidCouponCode') ], REST_Controller::HTTP_FORBIDDEN);
                if( $checkGiftCode->claimGift == '1')
                    $this->response(['status' => FALSE,'message' => $this->lang->line('couponAlreadyUsed') ], REST_Controller::HTTP_FORBIDDEN);
                $planId = $checkGiftCode->subscriptionId;
                $period = $checkGiftCode->period;
                $duration = $checkGiftCode->duration;
                $planName = $checkGiftCode->planName;
                $isAdminCoupon = false;
            }
            else
                $this->response(['status' => FALSE,'message' => $this->lang->line('invalidCouponCode') ], REST_Controller::HTTP_FORBIDDEN);
            

            $userMembership= $this->Common_model->exequery("SELECT membershipId,paymentType,paymentMethod,subscriptionId,payerId,endDate FROM vm_user_memberships WHERE startDate <= '".date('Y-m-d')."' AND endDate >= '".date('Y-m-d')."' AND userId=".$userId['roleId']." AND subscriptionStatus ='Active' order by membershipId desc limit 0, 1",1);             
            $couponId = $checkCouponCode->couponId;
            if($userMembership) { 

                if( $userMembership->paymentType == 'Auto' ){

                    try {

                        $subscription = \Stripe\Subscription::retrieve($userMembership->subscriptionId);

                        $cancelSub = $subscription->cancel(['at_period_end' => true]);                        ;

                    }

                    catch(\Stripe\Error\Card $e) {

                      // Since it's a decline, \Stripe\Error\Card will be caught

                       $error_message['card_error'] = $e->getMessage();

                      

                    } catch (\Stripe\Error\RateLimit $e) {

                        $error_message['rate_limit'] = $e->getMessage();

                      // Too many requests made to the API too quickly

                    } catch (\Stripe\Error\InvalidRequest $e) {

                         $error_message['invalid_request'] = $e->getMessage();

                      // Invalid parameters were supplied to Stripe's API

                    } catch (\Stripe\Error\Authentication $e) {

                        $error_message['auth_error'] = $e->getMessage();

                      // Authentication with Stripe's API failed

                      // (maybe you changed API keys recently)

                    } catch (\Stripe\Error\ApiConnection $e) {

                        $error_message['connection_error'] = $e->getMessage();

                      // Network communication with Stripe failed

                    } catch (\Stripe\Error\Base $e) {

                        $error_message['genric_error'] = $e->getMessage();

                      // Display a very generic error to the user, and maybe send

                      // yourself an email

                    } catch (Exception $e) {

                        $error_message['message'] = $e->getMessage();

                      // Something else happened, completely unrelated to Stripe

                    }

                    if(!empty($error_message))

                        $this->response(array('status' => false, 'message' => $this->lang->line('failedCancelSubscription'),'error' => $error_message), REST_Controller::HTTP_PAYMENT_REQUIRED); // HTTP_PAYMENT_REQUIRED (402) being the HTTP response code

                }

                

                $endDate = date('Y-m-d',strtotime("+".$period." ".$duration, strtotime($userMembership->endDate))); 
                
                $giftId = (!$isAdminCoupon) ? $checkGiftCode->giftId : 0;
                $couponUsed = array('userId' => $userId['roleId'], 'couponId' => $checkCouponCode->couponId, 'addedOn' => date('Y-m-d H:i:s'));
                
                $updateData['isPrevoiusLog'] = 1;
                $update_customer_id = $this->Common_model->update("vm_user_memberships", $updateData,"userId=".$userId['roleId']." AND membershipId= '".$userMembership->membershipId."'");

                $this->Common_model->insert("vm_user_memberships", array('userId' => $userId['roleId'], 'planId' => $planId, 'paymentMethod' => 'Gift', 'paymentType' => 'Mannual', 'paymentDate' => date('Y-m-d H:i:s'), 'subscriptionStatus' => 'Active', 'startDate' => date('Y-m-d'),'endDate' => $endDate, 'isPrevoiusLog' => 0, 'totalreferal' => 0, 'giftId' => $giftId, 'couponId' => $couponId, 'isTrail' => 0, 'isUpdatedPlan' => 1));
                if(!$isAdminCoupon) {                    
                    $this->Common_model->update('vm_coupons', array('status' => 3), "couponId = ".$checkCouponCode->couponId);
                    $this->Common_model->update('vm_gift_memberships',array('claimGift' => '1'), "email ='".$userDetail->email."' AND couponCode='".$_POST['coupon_code']."' AND claimGift='0' AND paymentStatus='Confirm'");
                }
                $this->Common_model->insert('vm_coupon_redeem', $couponUsed);
                if($giftId < 1)
                    $this->Common_model->update("vm_user", array('isFreeMembershipUsed'=>1), array("userId" => $userId['roleId']));
                          

                

                    $settings = array();

                    $settings["template"]               =  "membership_tpl".$this->lang->line('langSuffix').".html";

                    $settings["email"]                  =  $userDetail->email; 

                    $settings["subject"]                =  ($langSuffix =='_fr') ? "Ton abonnement Vedmir est maintenant actif !" : "Your Vedmir membership is now active!";

                    $contentarr["[[[USERNAME]]]"]               = $userDetail->userName;
                    $contentarr["[[[MEMBERSHIPLOGOURL]]]"]      = BASEURL."/system/static/frontend/images/membership_logo.png";
                    $contentarr["[[[SubscriptionPlan]]]"]               = $planName;//$_POST['coupon_code'];
                    $contentarr["[[[PURCHASEDATE]]]"]               = date('Y-m-d');
                    $contentarr["[[[AMOUNT]]]"]               = $_POST['coupon_code'];
                    $contentarr["[[[TAXAMOUNT]]]"]               = 0.00;
                    $contentarr["[[[ExpiryDATE]]]"]               = $endDate;
                    $settings["contentarr"]             =   $contentarr;
                    $contentarr["[[[VEDMIRTEXTURL]]]"]               = BASEURL."/system/static/frontend/images/vedmir_text_logo.png";
                    $this->common_lib->sendMail($settings);

                $this->response(array('status' => true, 'message' => $this->lang->line('successfullyClaimedMembership'), 'isAmbassadorCouponCode' =>  false, 'isReferCode' => false), REST_Controller::HTTP_OK);

            }

            else {

                    /*$user_membership = $this->Common_model->insert("vm_user_memberships", array('userId' => $userDetail->userId, 'paymentMethod' => $checkGiftCode->paymentMethod, 'paymentType' => 'Mannual', 'transactionId' => $checkGiftCode->transactionId, 'subscriptionId' => $checkGiftCode->subscriptionId, 'cardLast4' => $checkGiftCode->cardLast4, 'cardExpMonth' => $checkGiftCode->cardExpMonth, 'cardExpYear' => $checkGiftCode->cardExpYear, 'subscriptionAmount' => $checkGiftCode->subscriptionAmount, 'paymentDate' => $checkGiftCode->paymentDate, 'subscriptionStatus' => 'Active', 'startDate' => date('Y-m-d'), 'endDate' => date('Y-m-d',strtotime("+1 Month")), 'payerId' => $checkGiftCode->payerId,'giftId' => $checkGiftCode->giftId));

                    $this->Common_model->update('vm_gift_memberships',array('claimGift' => '1'), "email ='".$userDetail->email."' AND couponCode='".$_POST['coupon_code']."' AND claimGift='0' AND paymentStatus='Confirm'");*/
                    $endDate = date('Y-m-d',strtotime("+".$period." ".$duration)); 
                
                    $giftId = (!$isAdminCoupon) ? $checkGiftCode->giftId : 0;
                    $couponUsed = array('userId' => $userId['roleId'], 'couponId' => $checkCouponCode->couponId, 'addedOn' => date('Y-m-d H:i:s'));                   
                    

                    $this->Common_model->insert("vm_user_memberships", array('userId' => $userId['roleId'], 'planId' => $planId, 'paymentMethod' => 'Gift', 'paymentType' => 'Mannual', 'paymentDate' => date('Y-m-d H:i:s'), 'subscriptionStatus' => 'Active', 'startDate' => date('Y-m-d'),'endDate' => $endDate, 'isPrevoiusLog' => 0, 'totalreferal' => 0, 'giftId' => $giftId, 'couponId' => $couponId, 'isTrail' => 0, 'isUpdatedPlan' => 1));
                    if(!$isAdminCoupon) {                    
                        $this->Common_model->update('vm_coupons', array('status' => 3), "couponId = ".$checkCouponCode->couponId);
                        $this->Common_model->update('vm_gift_memberships',array('claimGift' => '1'), "email ='".$userDetail->email."' AND couponCode='".$_POST['coupon_code']."' AND claimGift='0' AND paymentStatus='Confirm'");
                    }
                    $this->Common_model->insert('vm_coupon_redeem', $couponUsed);

                if($giftId < 1)
                    $this->Common_model->update("vm_user", array('isFreeMembershipUsed'=>1), array("userId" => $userId['roleId']));

                    $settings = array();

                    $settings["template"]               =  "membership_tpl".$this->lang->line('langSuffix').".html";

                    $settings["email"]                  =  $userDetail->email; 

                    $settings["subject"]                =  ($langSuffix =='_fr') ? "Ton abonnement Vedmir est maintenant actif !" : "Your Vedmir membership is now active!";

                    $contentarr["[[[USERNAME]]]"]               = $userDetail->userName;
                    $contentarr["[[[MEMBERSHIPLOGOURL]]]"]      = BASEURL."/system/static/frontend/images/membership_logo.png";                   

                    $contentarr["[[[USERNAME]]]"]               = $userDetail->userName;
                     $contentarr["[[[SubscriptionPlan]]]"]               = $planName;//$_POST['coupon_code'];
                    $contentarr["[[[PURCHASEDATE]]]"]               = date('Y-m-d');
                    $contentarr["[[[AMOUNT]]]"]               = $_POST['coupon_code'];
                    $contentarr["[[[TAXAMOUNT]]]"]               = 0.00;
                    $contentarr["[[[ExpiryDATE]]]"]               = $endDate;
                    $contentarr["[[[VEDMIRTEXTURL]]]"]               = BASEURL."/system/static/frontend/images/vedmir_text_logo.png";
                    $settings["contentarr"]             =   $contentarr;

                    $this->common_lib->sendMail($settings);

                    $this->response(array('status' => true, 'message' => $this->lang->line('successfullyClaimedMembership'), 'isAmbassadorCouponCode' =>  false, 'isReferCode' => false), REST_Controller::HTTP_OK);

            }            

        }

        else

            $this->response([

                    'status' => FALSE,

                    'message' => $this->lang->line('unAuthorized')

                ], REST_Controller::HTTP_UNAUTHORIZED); // HTTP_UNAUTHORIZED (401) being the HTTP response code 

    }

    public function ambassadorplan_post(){


        $errors = array();

        $error_message = array();  

        $langSuffix = $this->lang->line('langSuffix');

        $token = $this->input->get_request_header('Authorization', TRUE);

        if($token != '' && $this->common_lib->validateToken($token)){

            $userId = $this->common_lib->validateToken($token);
            $_POST['type'] = (isset($_POST['type']) && !empty($_POST['type'])) ? $_POST['type'] : 'Mannual';
            $paymentType = (isset($_POST['type']) && !empty($_POST['type'])) ? $_POST['type'] : 'Mannual';
            if($paymentType != 'Mannual') {
                if(!isset($_POST['stripe_token']) || empty($_POST['stripe_token']))
                    $this->response(array('status' => false, 'message' => $this->lang->line('stripeTokenRequired')), REST_Controller::HTTP_BAD_REQUEST);
            }

            if(!isset($_POST['planId']) || empty($_POST['planId']))

                $this->response(array('status' => false, 'message' => $this->lang->line('planIdRequired')), REST_Controller::HTTP_BAD_REQUEST);

            if(!isset($_POST['ambassadorCouponId']) || empty($_POST['ambassadorCouponId']))

                $this->response(array('status' => false, 'message' => $this->lang->line('ambassadorCouponIdRequired')), REST_Controller::HTTP_BAD_REQUEST);
            
            if($userId['role'] != 'user')
                $this->response(array('status' => false, 'message' => $this->lang->line('unAuthorized')), REST_Controller::HTTP_BAD_REQUEST);

            $getAmbassadorInfo = $this->Common_model->exequery("SELECT ambassadorId, userId, stripeAccountId, isMasterAmbassador, ibanNumber, status FROM vm_ambassador_user WHERE ambassadorId = '".$_POST['ambassadorCouponId']."'", true);
            if( !$getAmbassadorInfo )
                $this->response(array('status' => false, 'message' => $this->lang->line('invalidAmbassadorCouponId')), REST_Controller::HTTP_FORBIDDEN);
            $subscriptionData = $this->Common_model->exequery("SELECT vm_subscription_details.planId,vm_subscription_details.amount,vm_subscription_details.currency,vm_subscription_details.detailId as id,vm_subscription_details.period,vm_subscription_details.duration,vm_subscription_plan.planName".$langSuffix." as planName, vm_subscription_plan.planName_fr as planNameFr,(case when icon !='' then concat('".UPLOADPATH."/',icon) else '' end) as planImg, vm_subscription_details.isAutoRenew, vm_subscription_details.rewardAmount, vm_subscription_details.discountAmount FROM vm_subscription_details left join vm_subscription_plan on vm_subscription_details.subscriptionId = vm_subscription_plan.id WHERE vm_subscription_details.detailId='".$_POST['planId']."' AND vm_subscription_plan.isSubType = '2'",true);

            if( !$subscriptionData )

                $this->response(array('status' => false, 'message' => $this->lang->line('dbError')), REST_Controller::HTTP_FORBIDDEN);
            $getCouponItem = $this->Common_model->exequery("SELECT * FROM vm_subscription_coupon WHERE status='0' AND planId= '".$subscriptionData->id."'", true);
            $paymentType = ($subscriptionData->isAutoRenew == 1) ? $_POST['type'] : 'Mannual';
            
            $userDetail = $this->Common_model->exequery("SELECT userId,email,userName as first_name, lastName, CONCAT(userName,' ',lastName) as userName,refered_by, stripe_customer_id, test_stripe_customer_id from vm_user WHERE userId=".$userId['roleId']. " AND status = '0'", true);
            $discountData = array('valid' => false);
            

            if( $userDetail ){

                try {

                    $subscriptionid = '';

                    $enddate = '';

                    $userMembership= $this->Common_model->exequery("SELECT membershipId,paymentType,paymentMethod,subscriptionId,payerId,endDate,cardLast4, cardExpMonth, cardExpYear, (SELECT planId FROM vm_user_memberships WHERE userId=".$userId['roleId']." AND isAmabassadarProgram != 0 order by membershipId desc limit 0, 1) as ambassadorplanId FROM vm_user_memberships WHERE startDate <= '".date('Y-m-d')."' AND endDate >= '".date('Y-m-d')."' AND userId=".$userId['roleId']." AND subscriptionStatus ='Active' order by membershipId desc limit 0, 1",1);
                    if($userMembership) {
                        if(!is_null($userMembership->ambassadorplanId)) {
                            $getAmbassadorPlanInfo = $this->Common_model->exequery("SELECT * FROM vm_subscription_details WHERE detailId='".$userMembership->ambassadorplanId."'", true);
                            if($getAmbassadorPlanInfo) {
                                if($getAmbassadorPlanInfo->duration == 'year')
                                    $this->response(array('status' => false, 'message' => $this->lang->line('onlyUpgradeMembership')), REST_Controller::HTTP_FORBIDDEN); 
                                else if($subscriptionData->period <= $getAmbassadorPlanInfo->period  && $subscriptionData->duration != 'year')
                                    $this->response(array('status' => false, 'message' => $this->lang->line('onlyUpgradeMembership')), REST_Controller::HTTP_FORBIDDEN); 
                            }
                        }                        
                    }
                    $checkPrevmemmbership  = $this->Common_model->exequery("SELECT count(*) as member FROM vm_user_memberships WHERE userId = '".$userId['roleId']."' AND selfpay ='1'", true);
                    
                    if($paymentType != 'Auto') {

                        if( $subscriptionData->amount > 0 ) {
                            $updatedAmount = round($subscriptionData->amount - ($subscriptionData->amount * $subscriptionData->discountAmount) / 100, 2);
                            $updatedAmount = ($updatedAmount > 0 ) ? $updatedAmount : 0;
                        }
                        else
                            $updatedAmount = 0 ;

                        
                        $subscriptionInfo = array('userId' => $userId['roleId'], 'paymentMethod' => 'Stripe', 'paymentType' => 'Mannual', 'planId' => $subscriptionData->id, 'subscriptionStatus' => 'Active', 'selfpay' => '1','isTrail' =>  $this->testmode, 'isUpdatedPlan' => 1);
                        if( $subscriptionData->amount > 0 && $updatedAmount > 0 ) {
                            if( $subscriptionData->amount > 0 ) {
                                if(!isset($_POST['stripe_token']) || empty($_POST['stripe_token']))
                                    $this->response(array('status' => false, 'message' => $this->lang->line('stripeTokenRequired')), REST_Controller::HTTP_BAD_REQUEST);
                            }
                            $subscription = \Stripe\Charge::create(array(        

                                  "amount" => $updatedAmount * 100,

                                  "currency" => strtoupper($subscriptionData->currency),

                                  "source" => $_POST['stripe_token'],

                                  "description" => $subscriptionData->planName.' '.$userDetail->userName)              

                            ); 

                        

                            $endDate = ($userMembership) ? (($userMembership->endDate >= date('Y-m-d')) ? date('Y-m-d',strtotime("+".$subscriptionData->period." ".$subscriptionData->duration,strtotime($userMembership->endDate))) : date('Y-m-d', strtotime("+".$subscriptionData->period." ".$subscriptionData->duration, $subscription->created))) : date('Y-m-d', strtotime("+".$subscriptionData->period." ".$subscriptionData->duration, $subscription->created));
                            $subscriptionid = $subscription->id;
                            $subscriptionInfo['transactionId'] = $subscription->balance_transaction;
                            $subscriptionInfo['subscriptionId'] = $subscription->id;
                            $subscriptionInfo['cardLast4'] = $subscription->source->last4;
                            $subscriptionInfo['cardExpMonth'] = $subscription->source->exp_month;
                            $subscriptionInfo['cardExpYear'] = $subscription->source->exp_year;
                            $subscriptionInfo['subscriptionAmount'] = $subscriptionData->amount;
                            $subscriptionInfo['paymentDate'] = date('Y-m-d h:i:s',$subscription->created);
                            $subscriptionInfo['startDate'] = date('Y-m-d h:i:s',$subscription->created);
                            $subscriptionInfo['payerId'] = $subscription->id;
                            $subscriptionInfo['isTrail'] = $this->testmode;
                            $subscriptionInfo['endDate'] = $endDate;
                            $expiryDate = $endDate;

                        }
                        else {
                            $createdDate = strtotime(date('Y-m-d H:i:s'));
                            $endDate = ($userMembership) ? (($userMembership->endDate >= date('Y-m-d')) ? date('Y-m-d',strtotime("+".$subscriptionData->period." ".$subscriptionData->duration,strtotime($userMembership->endDate))) : date('Y-m-d', strtotime("+".$subscriptionData->period." ".$subscriptionData->duration, $createdDate))) : date('Y-m-d', strtotime("+".$subscriptionData->period." ".$subscriptionData->duration, $createdDate));
                            $subscriptionInfo['subscriptionAmount'] = $subscriptionData->amount;
                            $subscriptionInfo['paymentDate'] = date('Y-m-d h:i:s');
                            $subscriptionInfo['startDate'] = date('Y-m-d h:i:s');
                            $subscriptionInfo['isTrail'] = $this->testmode;
                            $subscriptionInfo['endDate'] = $endDate;

                            $isFreeMembershipUsed = 1;
                        }
                        $expiryDate = $endDate;
                        $subscriptionInfo['isAmabassadarProgram'] = $getAmbassadorInfo->ambassadorId;
                        
                        $user_membership = $this->Common_model->insert("vm_user_memberships", $subscriptionInfo);
                        if(isset($subscriptionInfo['isAmabassadarProgram']) && !empty($subscriptionInfo['isAmabassadarProgram']) && $user_membership && $subscriptionData->rewardAmount > 0 && $getAmbassadorInfo->status == 0) {
                            if (isset($isFreeMembershipUsed)) {
                                $this->Common_model->update("vm_user", array('isFreeMembershipUsed'=>1), array("userId" => $userId['roleId']));
                            }
                                if($getAmbassadorInfo->isMasterAmbassador ==1) {
                                    $this->Common_model->insert("vm_ambassador_commission", array("ambassadorId" => $getAmbassadorInfo->ambassadorId, "ambassadorUserId" => $getAmbassadorInfo->userId, "userId" => $userId['roleId'], "membershipId" => $user_membership, "amount" => $subscriptionData->rewardAmount, "transactionId" => '', "addedOn" => date('Y-m-d H:i:s')));
                                }
                                else{
                                    try {
                                        $transfer = \Stripe\Transfer::create([
                                                    "amount" => $subscriptionData->rewardAmount*100,
                                                    "currency" => "CHF",
                                                    "destination" => $getAmbassadorInfo->stripeAccountId
                                                 ]);
                                        if( $transfer ) {
                                            $this->Common_model->insert("vm_ambassador_commission", array("ambassadorId" => $getAmbassadorInfo->ambassadorId, "ambassadorUserId" => $getAmbassadorInfo->userId, "userId" => $userId['roleId'], "membershipId" => $user_membership, "amount" => $subscriptionData->rewardAmount, "transactionId" => @$transfer->id, "addedOn" => date('Y-m-d H:i:s')));
                                        }
                                    }
                                    catch( Exception $e) {

                                    }
                                }
                        }

                    }

                    else {
                        $upgradeMembership = ($userMembership) ? ( ( $userMembership->paymentType == 'Auto' ) ? true : false ): false;
                        if( $upgradeMembership ) {
                            try {
                                $subscription = \Stripe\Subscription::retrieve($userMembership->subscriptionId);
                                try {
                                    $upgradeSubscriptionInfo = [
                                      'cancel_at_period_end' => false,
                                      'items' => [
                                        [
                                          'id' => $subscription->items->data[0]->id,
                                          'plan' => $subscriptionData->planId,
                                        ],
                                      ]
                                    ];
                                    $referalCouponId = 0;
                                    if($getCouponItem) 
                                        $upgradeSubscriptionInfo['coupon'] = $getCouponItem->couponCode;
                                    $subscriptionDetails = \Stripe\Subscription::update($userMembership->subscriptionId, $upgradeSubscriptionInfo);
                                    
                                    $expiryDate = date('Y-m-d',$subscriptionDetails->current_period_end);
                                    
                                    $subscriptionid = $subscriptionDetails->id;  
                                    $subscriptionInfo = array('userId' => $userId['roleId'], 'paymentMethod' => 'Stripe', 'paymentType' => 'Auto', 'planId' => $subscriptionData->id, 'transactionId' => $subscriptionDetails->id, 'subscriptionId' => $subscriptionDetails->id, 'cardLast4' => $userMembership->cardLast4, 'cardExpMonth' => $userMembership->cardExpMonth, 'cardExpYear' => $userMembership->cardExpYear, 'subscriptionAmount' => $subscriptionDetails->plan->amount / 100, 'paymentDate' => date('Y-m-d h:i:s',$subscriptionDetails->created), 'subscriptionStatus' => 'Active', 'startDate' => date('Y-m-d',$subscriptionDetails->current_period_start), 'endDate' => date('Y-m-d',$subscriptionDetails->current_period_end), 'payerId' => $subscriptionDetails->customer, 'selfpay' => '1','isTrail' =>  $this->testmode, 'isUpdatedPlan' => 1, 'invoiceId' => $subscriptionDetails->latest_invoice, 'subscriptionLogStatus' => 1);
                                    
                                    $user_membership = $this->Common_model->insertUnique("vm_user_memberships", $subscriptionInfo);
                                    if($user_membership) {
                                         $this->Common_model->update("vm_user_memberships", array('isPrevoiusLog' => 1, 'subscriptionStatus' => 'DeActive'), "membershipId = ".$userMembership->membershipId."" );
                                        try {
                                            $invoiceDetails = \Stripe\Invoice::retrieve($subscriptionDetails->latest_invoice);
                                            if(!empty($invoiceDetails->discount) &&  !is_null($invoiceDetails->discount)) {
                                                $this->Common_model->update("vm_user_memberships", array('isAmabassadarProgram' => $getAmbassadorInfo->ambassadorId), "membershipId = ".$user_membership);
                                                if($getAmbassadorInfo->status == 0) {
                                                    if($getAmbassadorInfo->isMasterAmbassador ==1) {
                                                        $this->Common_model->insert("vm_ambassador_commission", array("ambassadorId" => $getAmbassadorInfo->ambassadorId, "ambassadorUserId" => $getAmbassadorInfo->userId, "userId" => $userId['roleId'], "membershipId" => $user_membership, "amount" => $subscriptionData->rewardAmount, "transactionId" => '', "addedOn" => date('Y-m-d H:i:s')));
                                                    }
                                                    else{
                                                        try {
                                                            $transfer = \Stripe\Transfer::create([
                                                                        "amount" => $subscriptionData->rewardAmount*100,
                                                                        "currency" => "CHF",
                                                                        "destination" => $getAmbassadorInfo->stripeAccountId
                                                                     ]);
                                                            if( $transfer ) {
                                                                $this->Common_model->insert("vm_ambassador_commission", array("ambassadorId" => $getAmbassadorInfo->ambassadorId, "ambassadorUserId" => $getAmbassadorInfo->userId, "userId" => $userId['roleId'], "membershipId" => $user_membership, "amount" => $subscriptionData->rewardAmount, "transactionId" => @$transfer->id, "addedOn" => date('Y-m-d H:i:s')));
                                                            }
                                                        }
                                                        catch( Exception $e) {

                                                        }
                                                    }
                                                }
                                            }
                                        }
                                        catch(Exception $e) {

                                        }
                                    }
                                    
                                }
                                catch( Exception $e) {
                                    $this->response(array('status' => false, 'message' => $e->getMessage()), REST_Controller::HTTP_FORBIDDEN);
                                }

                            }
                            catch (Exception $e) {
                                $this->response(array('status' => false, 'message' => $e->getMessage()), REST_Controller::HTTP_FORBIDDEN);
                            }
                        }
                        else {
                            if($this->testmode == 1) {
                                $default_source = '';
                                $customer_id = '';
                                if($userDetail->test_stripe_customer_id !='') {
                                    $customer = \Stripe\Customer::retrieve($userDetail->test_stripe_customer_id);
                                    $cardsData = $customer->sources->create(["source" => $_POST['stripe_token']]);
                                    $default_source = $cardsData->id;
                                    $customer_id = $cardsData->customer;
                                }
                                else {
                                    $customer = \Stripe\Customer::create(array(

                                        'email' => $userDetail->email,

                                        'source'  => $_POST['stripe_token'],

                                        'metadata' => array('First Name' => $userDetail->first_name, 'Last Name' =>$userDetail->lastName ),

                                    ));
                                    $customer_id =  $customer->id;
                                    $default_source = $customer->default_source;
                                }
                            }
                            else {
                                $default_source = '';
                                $customer_id = '';
                                if($userDetail->test_stripe_customer_id !='') {
                                    $customer = \Stripe\Customer::retrieve($userDetail->stripe_customer_id);
                                    $cardsData = $customer->sources->create(["source" => $_POST['stripe_token']]);
                                    $default_source = $cardsData->id;
                                    $customer_id = $cardsData->customer;
                                }
                                else {
                                    $customer = \Stripe\Customer::create(array(

                                        'email' => $userDetail->email,

                                        'source'  => $_POST['stripe_token'],

                                        'metadata' => array('First Name' => $userDetail->first_name, 'Last Name' =>$userDetail->lastName ),

                                    ));
                                    $customer_id =  $customer->id;
                                    $default_source = $customer->default_source;
                                }
                            }
                            
                            if($this->testmode == 1)

                                $update_customer_id = $this->Common_model->update("vm_user", array('test_stripe_customer_id' => $customer_id),"userId=".$userId['roleId']);

                            else
                                $update_customer_id = $this->Common_model->update("vm_user", array('stripe_customer_id' => $customer_id),"userId=".$userId['roleId']);

                            $subscriptionDetails = array(

                                'customer' => $customer_id,

                                'items' => array(array('plan' => $subscriptionData->planId)),
                                'default_source' =>  $default_source,

                                'metadata' => array('First Name' => $userDetail->first_name, 'Last Name' =>$userDetail->lastName, 'Subscription Name' => $subscriptionData->planName)

                              );

                            $referalCouponId = 0;
                            if($getCouponItem) 
                                $subscriptionDetails['coupon'] = $getCouponItem->couponCode;
                               
                            
                            $subscription = \Stripe\Subscription::create($subscriptionDetails);

                            $expiryDate = date('Y-m-d',$subscription->current_period_end);

                            $subscriptionid = $subscription->id;  

                            $user_membership = $this->Common_model->insertUnique("vm_user_memberships", array('userId' => $userId['roleId'], 'paymentMethod' => 'Stripe', 'paymentType' => 'Auto', 'planId' => $subscriptionData->id, 'transactionId' => $subscription->id, 'subscriptionId' => $subscription->id, 'cardLast4' => $customer->sources->data[0]->last4, 'cardExpMonth' => $customer->sources->data[0]->exp_month, 'cardExpYear' => $customer->sources->data[0]->exp_year, 'subscriptionAmount' => $subscription->plan->amount / 100, 'paymentDate' => date('Y-m-d h:i:s',$subscription->created), 'subscriptionStatus' => 'Active', 'startDate' => date('Y-m-d',$subscription->current_period_start), 'endDate' => date('Y-m-d',$subscription->current_period_end), 'payerId' => $subscription->customer, 'selfpay' => '1','isTrail' =>  $this->testmode, 'isUpdatedPlan' => 1, 'invoiceId' => $subscription->latest_invoice));
                            if($user_membership) {                                 
                                try {
                                    $invoiceDetails = \Stripe\Invoice::retrieve($subscription->latest_invoice);
                                    if(!empty($invoiceDetails->discount) &&  !is_null($invoiceDetails->discount)) {
                                        $this->Common_model->update("vm_user_memberships", array('isAmabassadarProgram' => $getAmbassadorInfo->ambassadorId), "membershipId = ".$user_membership);
                                        if($getAmbassadorInfo->status == 0 ) {    
                                            if($getAmbassadorInfo->isMasterAmbassador ==1) {
                                                $this->Common_model->insert("vm_ambassador_commission", array("ambassadorId" => $getAmbassadorInfo->ambassadorId, "ambassadorUserId" => $getAmbassadorInfo->userId, "userId" => $userId['roleId'], "membershipId" => $user_membership, "amount" => $subscriptionData->rewardAmount, "transactionId" => '', "addedOn" => date('Y-m-d H:i:s')));
                                            }
                                            else{

                                                try {
                                                    $transfer = \Stripe\Transfer::create([
                                                                "amount" => $subscriptionData->rewardAmount*100,
                                                                "currency" => "CHF",
                                                                "destination" => $getAmbassadorInfo->stripeAccountId
                                                             ]);
                                                    if( $transfer ) {
                                                        $this->Common_model->insert("vm_ambassador_commission", array("ambassadorId" => $getAmbassadorInfo->ambassadorId, "ambassadorUserId" => $getAmbassadorInfo->userId, "userId" => $userId['roleId'], "membershipId" => $user_membership, "amount" => $subscriptionData->rewardAmount, "transactionId" => @$transfer->id, "addedOn" => date('Y-m-d H:i:s')));
                                                    }
                                                }
                                                catch( Exception $e) {

                                                }
                                            }
                                        }    
                                        
                                    }
                                }
                                catch(Exception $e) {

                                }
                            }
                        }
                        

                    }
                    
                    if( $checkPrevmemmbership->member == 0 ) {

                        if( $userDetail->refered_by != 0 ) {
                            $getReferedAvaiableBalance = $this->Common_model->exequery("SELECT currentAvailableBalance FROM vm_user_referal_wallets WHERE userId='".$userDetail->refered_by."' order by referalWalletId desc limit 0,1", 1);
                            $referalProfileInfo = $this->Common_model->exequery("SELECT userId,email,userName as first_name, lastName, CONCAT(userName,' ',lastName) as userName,refered_by, stripe_customer_id, test_stripe_customer_id from vm_user WHERE userId=".$userDetail->refered_by. " AND status = '0'", true);
                            $currentReferedBalance = ($getReferedAvaiableBalance) ? $getReferedAvaiableBalance->currentAvailableBalance: 0;
                            $currentReferedBalance = $currentReferedBalance + 10;
                            $this->Common_model->insert("vm_user_referal_wallets", array("userId" => $userDetail->refered_by, "amount" => 10, "transType" => 0, "type" => 1, "referalUsedId" => $userId['roleId'], "currentAvailableBalance" => $currentReferedBalance, "addedOn" => date('Y-m-d H:i:s')));
                            if($referalProfileInfo ) {
                                $settings = array();
                                $settings["template"]               =  "referal_bonus_tpl".$langSuffix.".html";

                                $settings["email"]                  =  $referalProfileInfo->email; 

                                $settings["subject"]                =  ($langSuffix =='_fr') ? "CHF10 added in your Vedmir Wallet" : "CHF10 added in your Vedmir Wallet";

                                $contentarr["[[[REFERALNAME]]]"]               = $referalProfileInfo->first_name;
                                $contentarr["[[[REFREENAME]]]"]               = $userDetail->first_name;
                                

                                $settings["contentarr"]             =   $contentarr;

                                $this->common_lib->sendMail($settings);
                            }

                        }     

                    }
                    $settings = array();

                    $settings["template"]               =  "membership_tpl".$langSuffix.".html";

                    $settings["email"]                  =  $userDetail->email; 

                    $settings["subject"]                =  ($langSuffix =='_fr') ? "Ton abonnement Vedmir est maintenant actif !" : "Your Vedmir membership is now active!";

                    $contentarr["[[[USERNAME]]]"]               = $userDetail->userName;
                    $contentarr["[[[MEMBERSHIPLOGOURL]]]"]      = BASEURL."/system/static/frontend/images/membership_logo.png";
                    $contentarr["[[[SubscriptionPlan]]]"]               = $subscriptionData->planName;
                    $contentarr["[[[PURCHASEDATE]]]"]               = date('d.m.Y');
                    $total = number_format($subscriptionData->amount/(1+7.7/100), 2);
                    $taxAmount =  $subscriptionData->amount - $total;
                    $contentarr["[[[AMOUNT]]]"]               =      'CHF '.$subscriptionData->amount;
                    
                    $contentarr["[[[TAXAMOUNT]]]"]               =      'CHF '.$taxAmount;
                    $contentarr["[[[ExpiryDATE]]]"]               = date('d.m.Y', strtotime($expiryDate));
                    $contentarr["[[[VEDMIRTEXTURL]]]"]               = BASEURL."/system/static/frontend/images/vedmir_text_logo.png";

                    $settings["contentarr"]             =   $contentarr;

                    $this->common_lib->sendMail($settings);
                    

                    $this->response(array('status' => true, 'message' => $this->lang->line('successPayment'),'subscription' => $subscriptionid, 'planName' => @$subscriptionData->planName, 'planImg' => @$subscriptionData->planImg), REST_Controller::HTTP_OK);

                }

                catch(\Stripe\Error\Card $e) {

                  // Since it's a decline, \Stripe\Error\Card will be caught

                   $error_message['card_error'] = $e->getMessage();

                  

                } catch (\Stripe\Error\RateLimit $e) {

                    $error_message['rate_limit'] = $e->getMessage();

                  // Too many requests made to the API too quickly

                } catch (\Stripe\Error\InvalidRequest $e) {

                     $error_message['invalid_request'] = $e->getMessage();

                  // Invalid parameters were supplied to Stripe's API

                } catch (\Stripe\Error\Authentication $e) {

                    $error_message['auth_error'] = $e->getMessage();

                  // Authentication with Stripe's API failed

                  // (maybe you changed API keys recently)

                } catch (\Stripe\Error\ApiConnection $e) {

                    $error_message['connection_error'] = $e->getMessage();

                  // Network communication with Stripe failed

                } catch (\Stripe\Error\Base $e) {

                    $error_message['genric_error'] = $e->getMessage();

                  // Display a very generic error to the user, and maybe send

                  // yourself an email

                } catch (Exception $e) {

                    $error_message['message'] = $e->getMessage();

                  // Something else happened, completely unrelated to Stripe

                }

                if(!empty($error_message))

                    $this->response(array('status' => false, 'message' => $this->lang->line('paymentVerificationFailed'),'error' => $error_message), REST_Controller::HTTP_PAYMENT_REQUIRED); // HTTP_PAYMENT_REQUIRED (402) being the HTTP response code

            }

            else 

                $this->response(array('status' => false, 'message' => $this->lang->line('userVerificationFailed')), REST_Controller::HTTP_FORBIDDEN);            

            

        }

        else

            $this->response([

                    'status' => FALSE,

                    'message' => $this->lang->line('unAuthorized')

                ], REST_Controller::HTTP_UNAUTHORIZED); // HTTP_UNAUTHORIZED (401) being the HTTP response code 

    
    }
}

