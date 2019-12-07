<?php



defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';

class User extends REST_Controller {

    public $userProfileQry= ",userName as fullName, lastName, countryCode, mobile, gender, DATE_FORMAT(dob, '%d-%m-%Y') as dob, address, postalCode, isAutoLock as isActiveSecurity,city, email, touchIdInterval, (case when img !='' then concat('".UPLOADPATH."/user_images/',img) when (img ='' && gender ='Female')  THEN '".UPLOADPATH."/user_images/default_female.png' else '".UPLOADPATH."/user_images/default_male.png' end ) as `profile_image`";

    function __construct()

    {
        // Construct the parent class
        parent::__construct();
        $this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['users_post']['limit'] = 500; // 100 requests per hour per user/key
        $this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key

        /* Stripe Account */
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

    public function getuser_get(){

        $token = $this->input->get_request_header('Authorization', TRUE);

        if($token != '' && $roleData = $this->common_lib->validateToken($token)){

            $langSuffix = $this->lang->line('langSuffix');

            $userData = $this->Common_model->exequery("SELECT userId $this->userProfileQry ,(SELECT (CASE WHEN count(*) > 0 then 1 else 0 end) as membership_count FROM vm_user_memberships WHERE startDate <= '".date('Y-m-d')."' AND endDate >= '".date('Y-m-d')."' AND userId=".$roleData->roleId." AND subscriptionStatus ='Active' ORDER BY membershipId desc limit 0,1) as `membership` from vm_user WHERE userId='".$roleData->roleId."'" ,true);        

                if ($userData)
                    $this->response(['data'=>$userData], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
                
                else
                    $this->response(['status' => FALSE, 'message' => $this->lang->line('userNotFound') ], REST_Controller::HTTP_FORBIDDEN);
        }else
            $this->response(['status' => FALSE, 'message' => $this->lang->line('unAuthorized') ], REST_Controller::HTTP_UNAUTHORIZED);
    }

    public function updateprofile_post(){

        $token = $this->input->get_request_header('Authorization', TRUE);

        if($token != '' && $roleData = $this->common_lib->validateToken($token)){

            $msg = '';
            if(!isset($_POST['name']) || empty($_POST['name']))
                $this->response(['status' => FALSE,'message' => $this->lang->line('nameRequired')], REST_Controller::HTTP_BAD_REQUEST);
            if(!isset($_POST['mobile']) || empty($_POST['mobile']))
                $this->response(['status' => FALSE,'message' => $this->lang->line('mobileRequired')], REST_Controller::HTTP_BAD_REQUEST);
            if(!isset($_POST['countryCode']) || empty($_POST['countryCode']))
                $this->response(['status' => FALSE,'message' => $this->lang->line('countryCodeRequired')], REST_Controller::HTTP_BAD_REQUEST);

            $queryData   =  array();
            $queryData['userName']   =   trim($_POST['name']);
            $queryData['mobile']        =   trim($_POST['mobile']);
            $queryData['countryCode']        =   trim($_POST['countryCode']);

            if( isset($_POST['gender']) && !empty($_POST['gender']))
                $queryData['gender']     =   trim($_POST['gender']);

            if( isset($_POST['dob']) && !empty($_POST['dob']))
                $queryData['dob']           =   date('Y-m-d',strtotime($_POST['dob']));

            if( isset($_POST['address']) && !empty($_POST['address']))
                $queryData['address']       =   trim($_POST['address']);

            if( isset($_POST['postalCode']) && !empty($_POST['postalCode']))
                $queryData['postalCode']    =   trim($_POST['postalCode']);

            if( isset($_POST['city']) && !empty($_POST['city']))
                $queryData['city']          =   trim($_POST['city']);

            $queryData['updatedOn']  =   date('Y-m-d H:i:s');

            $updatetStatus      =   $this->Common_model->update("vm_user", $queryData,"userId = ".$roleData->roleId);

            $userData = $this->Common_model->exequery("SELECT userId $this->userProfileQry ,(SELECT (CASE WHEN count(*) > 0 then 1 else 0 end) as membership_count FROM vm_user_memberships WHERE startDate <= '".date('Y-m-d')."' AND endDate >= '".date('Y-m-d')."' AND userId=".$roleData->roleId." AND subscriptionStatus ='Active' ORDER BY membershipId desc limit 0,1) as `membership` from vm_user WHERE userId='".$roleData->roleId."'" ,true);

            if($updatetStatus){
                $status['status']        = true;
                $status['message']      = $this->lang->line('updateProfile');
                $status['data'] = $userData;
                 $this->response($status, REST_Controller::HTTP_OK);
            }else{
                $status['status']        = False;
                $status['message']      = $this->lang->line('failedUpdateProfile');
                 $this->response($status, REST_Controller::HTTP_FORBIDDEN);
            }
        }else{
            $status['status']        = False;
            $status['message']      = $this->lang->line('unAuthorized');
             $this->response($status, REST_Controller::HTTP_UNAUTHORIZED); 
        }
    }

    public function changeemail_post(){
        $token = $this->input->get_request_header('Authorization', TRUE);
        if($token != '' && $roleData = $this->common_lib->validateToken($token)){

            $stripeMsg = '';
            $errors = array();
           
            if(!isset($_POST['email']) || empty($_POST['email']))
                $this->response(['status' => FALSE,'message' => $this->lang->line('emailRequired')], REST_Controller::HTTP_BAD_REQUEST);
            if(!isset($roleData->role) || $roleData->role != 'user')
                $this->response(['status' => FALSE,'message' => $this->lang->line('unAuthorized')], REST_Controller::HTTP_UNAUTHORIZED);            

            $isExist = $this->Common_model->selRowData("vm_user","userName",array('userId !=' => $roleData->roleId, "email"=>trim($_POST['email'])));

            if(!empty($isExist))
                $this->response(['status' => FALSE,'message' =>$this->lang->line('emailExists') ], REST_Controller::HTTP_BAD_REQUEST);

            $userData = $this->Common_model->selRowData("vm_user_memberships","payerId",array('userId =' => $roleData->roleId));               

            $this->db->trans_begin();

            $updatetStatus      =   $this->Common_model->update("vm_user", array('email'=>trim($_POST['email'])), "userId = ".$roleData->roleId);
            if($updatetStatus){
                $this->Common_model->update("vm_auth", array('emailId'=>trim($_POST['email']))," role = 'user' and roleId=".$roleData->roleId);

                if (isset($userData->payerId) && !empty($userData->payerId)) {
                    try{
                        $cu = \Stripe\Customer::retrieve($userData->payerId);
                        $cu->email = trim($_POST['email']);
                        $cu->save();
                    }catch (\Exception $e) {
                        $stripeMsg = $e->getMessage();
                     }
                }
            }

            if ($this->db->trans_status() === FALSE || !empty($stripeMsg)){
                $this->db->trans_rollback();
                $this->response([ 'status' => FALSE, 'message' => (!empty($stripeMsg))?$stripeMsg:$this->lang->line('internalError') ], REST_Controller::HTTP_INTERNAL_SERVER_ERROR); 
            }else{
                $this->db->trans_commit();
                $this->response([ 'status' => TRUE, 'message' => $this->lang->line('profileUpdated') ], REST_Controller::HTTP_OK);
            } 
        }else
            $this->response(['status' => FALSE,'message' => $this->lang->line('unAuthorized')], REST_Controller::HTTP_UNAUTHORIZED);
    }

    // Upload Profile image
    public function uploadprofileimage_post(){

        $token = $this->input->get_request_header('Authorization', TRUE);

        if($token != '' && $roleData = $this->common_lib->validateToken($token)){

            $cond   =   "userId = ".$roleData->roleId;
            $userimage  =   $this->Common_model->getSelectedField("vm_user","img",$cond);

            if(!isset($_FILES['uploadImg']['name']) || empty($_FILES['uploadImg']['name'])) {

                if ($userimage != '')
                    unlink(ABSUPLOADPATH."/user_images/".$userimage);

                $queryData['img']   =   '';
                $cond   =   "userId = ".$roleData->roleId;
                $updatetStatus  =   $this->Common_model->update("vm_user", $queryData,$cond);
                if($updatetStatus)
                     $this->response(['status' => TRUE,'message' => $this->lang->line('imageRemoved')], REST_Controller::HTTP_OK);
                else
                    $this->response(['status' => FALSE,'message' => $this->lang->line('failedRemoveImg')], REST_Controller::HTTP_FORBIDDEN);

            }else if(isset($_FILES['uploadImg']) && is_uploaded_file($_FILES['uploadImg']['tmp_name']) != "") {

                $imgname = explode(" ", $_FILES['uploadImg']['name']);
                $photoToUpload =    md5(date('Ymdhis')).end($imgname);
                $uploadSettings = array();
                $uploadSettings['upload_path']      =  ABSUPLOADPATH."/user_images";
                $uploadSettings['allowed_types']    =   'gif|jpg|jpeg|png';
                $uploadSettings['file_name']        =   $photoToUpload;
                $uploadSettings['inputFieldName']   =   "uploadImg";

                $fileUpload = $this->common_lib->_doUpload($uploadSettings);
                if ($fileUpload) {
                    $queryData['img']   =   $photoToUpload;
                    $updatetStatus  =   $this->Common_model->update("vm_user", $queryData,$cond);
                    $userData = $this->Common_model->exequery("SELECT userId $this->userProfileQry ,(SELECT (CASE WHEN count(*) > 0 then 1 else 0 end) as membership_count FROM vm_user_memberships WHERE startDate <= '".date('Y-m-d')."' AND endDate >= '".date('Y-m-d')."' AND userId=".$roleData->roleId." AND subscriptionStatus ='Active' ORDER BY membershipId desc limit 0,1) as `membership` from vm_user WHERE userId='".$roleData->roleId."'" ,true);
                    if($updatetStatus){

                        if ($userimage != '')
                            unlink(ABSUPLOADPATH."/user_images/".$userimage);

                         $this->response(['status' => TRUE,'message' => $this->lang->line('successImage'),'data' => $userData], REST_Controller::HTTP_OK);
                     }else
                        $this->response(['status' => FALSE,'message' => $this->lang->line('failedUpload')], REST_Controller::HTTP_FORBIDDEN);
                }else
                    $this->response(['status' => FALSE,'message' => $this->lang->line('failedUpload')], REST_Controller::HTTP_FORBIDDEN);
            } 
        }else
            $this->response(['status' => FALSE,'message' => $this->lang->line('unAuthorized')], REST_Controller::HTTP_UNAUTHORIZED); 
    }

    public function getmycard_get() {

        $token = $this->input->get_request_header('Authorization', TRUE);
        if($token != '' && $roleData = $this->common_lib->validateToken($token)){
            $cardData = $this->Common_model->exequery("SELECT cardId, cardHolderName, cardNo, expMonth, SUBSTRING(  `expYear` , -2 )  as expYear, cvv, isPriority  from vm_user_card_details WHERE userId='".$roleData->roleId."' ORDER BY cardId DESC");

            if(!empty($cardData)){
                foreach ($cardData as $key => $value)
                    $value->cvv = $this->common_lib->decrypt_ccv($value->cvv);
            }
            $cardData = ( $cardData ) ? $cardData : array();
            $this->response(['data'=>$cardData], REST_Controller::HTTP_OK); 
        }else
            $this->response(['status' => FALSE, 'message' => $this->lang->line('unAuthorized') ], REST_Controller::HTTP_UNAUTHORIZED);
    }

    public function addmycard_post() {

        $token = $this->input->get_request_header('Authorization', TRUE);
        if($token != '' && $roleData = $this->common_lib->validateToken($token)){
            if(!isset($_POST['cardNo']) || empty($_POST['cardNo']))
                $this->response(['status' => FALSE,'message' => $this->lang->line('cardNoRequired')], REST_Controller::HTTP_BAD_REQUEST);

            if(!isset($_POST['expMonth']) || empty($_POST['expMonth']))
                $this->response(['status' => FALSE,'message' => $this->lang->line('expMonthRequired')], REST_Controller::HTTP_BAD_REQUEST);

            if(!isset($_POST['expYear']) || empty($_POST['expYear']))
                $this->response(['status' => FALSE,'message' => $this->lang->line('expYearRequired')], REST_Controller::HTTP_BAD_REQUEST);

            if(!isset($_POST['cvv']) || empty($_POST['cvv']))
                $this->response(['status' => FALSE,'message' => $this->lang->line('cvvRequired')], REST_Controller::HTTP_BAD_REQUEST);

            if(!isset($_POST['cardHolderName']) || empty($_POST['cardHolderName']))
                $this->response(['status' => FALSE,'message' => $this->lang->line('cardHolderNameRequired')], REST_Controller::HTTP_BAD_REQUEST);

            $isPriority = (isset($_POST['isPriority'])) ? $_POST['isPriority'] : 0;

            $myCard = $this->Common_model->exequery("SELECT count(*) as totalCard from vm_user_card_details WHERE userId='".$roleData->roleId."'",1);

            if( $myCard->totalCard == 0 )
                $isPriority = 1; 

            if(isset($_POST['cardNo']) && !empty($_POST['cardNo']) && isset($_POST['expMonth']) && !empty($_POST['expMonth']) && is_numeric($_POST['expMonth']) && isset($_POST['expYear']) && !empty($_POST['expYear']) && is_numeric($_POST['expYear']) && isset($_POST['cvv']) && !empty($_POST['cvv']) && is_numeric($_POST['cvv'])) {

                $checkUserCard = $this->Common_model->exequery( "SELECT * FROM vm_user_card_details  WHERE cardNo='".$_POST['cardNo']."' AND expMonth='".$_POST['expMonth']."' AND ( expYear='".$_POST['expYear']."' OR expYear='".substr($_POST['expYear'], -2)."')  AND userId='".$roleData->roleId."'",true );
                $holderName = (isset($_POST['cardHolderName']) && !empty($_POST['cardHolderName'])) ? $_POST['cardHolderName'] : '';
                $cardId = 0;
                if( ! $checkUserCard )
                    $cardId = $this->Common_model->insertUnique('vm_user_card_details' , array('cardNo' => $_POST['cardNo'], 'expMonth' => $_POST['expMonth'], 'expYear' => $_POST['expYear'], 'userId' => $roleData->roleId, 'cvv' => $this->common_lib->encrypt_ccv($_POST['cvv']), 'currentDateTime' => date('Y-m-d H:i:s'),'cardHolderName' => $holderName , 'isPriority' => $isPriority));
                else {
                    $isPriority = ($isPriority == 0 && $checkUserCard->isPriority == 1) ? 1 : $isPriority;
                    $this->Common_model->update('vm_user_card_details' , array('cardNo' => $_POST['cardNo'], 'expMonth' => $_POST['expMonth'], 'expYear' => $_POST['expYear'], 'userId' => $roleData->roleId, 'cvv' => $this->common_lib->encrypt_ccv($_POST['cvv']), 'cardHolderName' => $holderName , 'isPriority' => $isPriority), "cardId = ".$checkUserCard->cardId);
                    $cardId = $checkUserCard->cardId;
                }

                if( $cardId > 0  && $isPriority == 1 )
                    $this->Common_model->update('vm_user_card_details' , array('isPriority' => 0), "cardId != ".$cardId." AND userId='".$roleData->roleId."'");

            }

            $cardData = $this->Common_model->exequery("SELECT cardId, cardHolderName, cardNo, expMonth, expYear from vm_user_card_details WHERE userId='".$roleData->roleId."'");
            $cardData = ( $cardData ) ? $cardData : array();  
            $this->response(['data'=>$cardData], REST_Controller::HTTP_OK);
        }else
            $this->response(['status' => FALSE, 'message' => $this->lang->line('unAuthorized') ], REST_Controller::HTTP_UNAUTHORIZED);
    }

    public function deletemycard_post() {
        $token = $this->input->get_request_header('Authorization', TRUE);
        if($token != '' && $roleData = $this->common_lib->validateToken($token)){
            if( !isset($_POST['cardId']) || empty($_POST['cardId']) )
                $this->response(['status' => FALSE, 'message' => $this->lang->line('cardIdRequired') ], REST_Controller::HTTP_BAD_REQUEST);

            $query="SELECT ucard.cardId, ucard.cardNo, ucard.expMonth, ucard.expYear, ucard.isPriority, (SELECT cardId FROM vm_user_card_details WHERE userId=".$roleData->roleId." AND cardId != ucard.cardId limit 0, 1) as `nextCardId` from vm_user_card_details ucard WHERE ucard.userId=".$roleData->roleId." AND ucard.cardId=".$_POST['cardId'];
            $checkCard = $this->Common_model->exequery($query,1);

            if( $checkCard ){
                    if($checkCard->isPriority == 1 && $checkCard->nextCardId != NULL) 
                        $this->Common_model->update("vm_user_card_details", array('isPriority' => 1), "cardId = ".$checkCard->nextCardId);
                    $this->Common_model->del("vm_user_card_details" , array( 'cardId' => $_POST['cardId'], 'userId' => $roleData->roleId ));

                    $this->response(array('status' => true, 'message' => $this->lang->line('cardDelete')), REST_Controller::HTTP_OK);
            }
            else
                $this->response(['status' => FALSE, 'message' => $this->lang->line('cardIdNotExists') ], REST_Controller::HTTP_FORBIDDEN);
        }else
            $this->response(['status' => FALSE, 'message' => $this->lang->line('unAuthorized') ], REST_Controller::HTTP_UNAUTHORIZED);
    }

    public function setting_post() {

        $token = $this->input->get_request_header('Authorization', TRUE);

        if($token != '' && $roleData = $this->common_lib->validateToken($token)){
            $_POST['touchIdInterval'] = (isset($_POST['touchIdInterval'])) ? $_POST['touchIdInterval'] : '';
            $_POST['isActiveSecurity'] = (isset($_POST['isActiveSecurity'])) ? $_POST['isActiveSecurity'] : 0;
            $isUpdated = $this->Common_model->update("vm_user" , array( 'touchIdInterval' => $_POST['touchIdInterval'], 'isAutoLock' => $_POST['isActiveSecurity']), array('userId' => $roleData->roleId ));
            if( $isUpdated )
                $this->response(array('status' => true, 'message' => $this->lang->line('updateProfile')), REST_Controller::HTTP_OK);
            else
                $this->response(['status' => FALSE, 'message' => $this->lang->line('failedUpdateProfile') ], REST_Controller::HTTP_FORBIDDEN);
        }else
            $this->response(['status' => FALSE, 'message' => $this->lang->line('unAuthorized') ], REST_Controller::HTTP_UNAUTHORIZED);
    }


}

