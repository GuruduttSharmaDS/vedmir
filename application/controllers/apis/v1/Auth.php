<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class Auth extends REST_Controller {

    function __construct() {
        // Construct the parent class
        parent::__construct();
        $this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['users_post']['limit'] = 500; // 100 requests per hour per user/key
        $this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
    }

    // test push 
    public function testpush_post() {
        if($_POST['deviceToken']){
            $this->common_lib->sendPush("push testing", array('type' => 'testing'),trim($_POST['deviceToken']));
            $this->response(array('status' => true,'message' => 'testing push sent.'),REST_Controller::HTTP_OK);
        }else
            $this->response(['status' => FALSE,'message' => 'deviceToken required'], REST_Controller::HTTP_BAD_REQUEST);
    }

    // User Signup - Validate Signup Data
    public function validateSignUp () {
        if(!isset($_POST['name']) || empty($_POST['name']))
            $this->response(['status' => FALSE,'message' => $this->lang->line('nameRequired')], REST_Controller::HTTP_BAD_REQUEST);
        if(!isset($_POST['email']) || empty($_POST['email']))
            $this->response(['status' => FALSE,'message' => $this->lang->line('emailRequired')], REST_Controller::HTTP_BAD_REQUEST);
        if(!isset($_POST['password']) || empty($_POST['password']))
            $this->response(['status' => FALSE,'message' => $this->lang->line('passwordRequired')], REST_Controller::HTTP_BAD_REQUEST);
        if(!isset($_POST['mobile']) || empty($_POST['mobile']))
            $this->response(['status' => FALSE,'message' => $this->lang->line('mobileRequired')], REST_Controller::HTTP_BAD_REQUEST);
        if(!isset($_POST['countryCode']) || empty($_POST['countryCode']))
            $this->response(['status' => FALSE,'message' => $this->lang->line('countryCodeRequired')], REST_Controller::HTTP_BAD_REQUEST);
        if(!isset($_POST['role']) || empty($_POST['role']))
            $this->response(['status' => FALSE,'message' => $this->lang->line('roleRequired')], REST_Controller::HTTP_BAD_REQUEST);

        $isExist = $this->Common_model->selRowData("vm_auth","emailId, status, roleId","emailId = '".$_POST['email']."'");
        
        if (valResultSet($isExist)) {
            $status['status'] = false;
            $status['message'] = $this->lang->line('emailAlreadyExist');
            $this->response($status, REST_Controller::HTTP_CONFLICT);
        }

        return true;
    }

    // User Signup - Parse User Data
    public function parseUserData () {
        $queryData  =  array();                
        $queryData['userName']      =   trim($_POST['name']);
        $queryData['email']         =   trim($_POST['email']);
        $queryData['mobile']        =   trim($_POST['mobile']);
        $queryData['countryCode']   =   trim($_POST['countryCode']);

        $slug = $this->common_lib->create_unique_slug(trim($_POST['name']),"vm_user","userName",0,"userId",$counter=0);

        $queryData['slug']           =   $slug;
        $queryData['addedOn']        =   date('Y-m-d H:i:s');

        return $queryData;
    }

    // User Signup - Parse Auth Data
    public function parseAuthData ($insertStatus) {
        $authData   =  array();
        $authData['emailId']    =   trim($_POST['email']);
        $authData['password']   =   md5(trim($_POST['password']));
        $authData['deviceId']   =   (isset($_POST['deviceId']) && !empty($_POST['deviceId'])) ? $_POST['deviceId'] : '';
        $authData['role']       =   trim($_POST['role']);
        $authData['roleId']     =   $insertStatus;

        return $authData;
    }

    // User Signup - Main Function
    public function signup_post() {
        // Validate User Data
        $this->validateSignUp ();   
               
        // Insert data in "User Table"
        $queryData      = $this->parseUserData ();     
        $insertStatus   =   $this->Common_model->insertUnique("vm_user", $queryData);

        if ($insertStatus) {           

            // Insert Data in "Auth Table"
            $authData   = $this->parseAuthData ($insertStatus);
            $insertAuth = $this->Common_model->insert("vm_auth", $authData);

            $token = md5('VM'.$insertStatus.date('mdY_His').'user');
            $insertTokensStatus = 0;  
            $insertTokensStatus = $this->Common_model->insert('vm_api_token',array('role' => trim($_POST['role']),'roleId' => $insertStatus,'emailId' => $_POST['email'],'token' => $token));
             
            $users = $this->Common_model->exequery("SELECT userId,userName as fullName, lastName, countryCode, mobile, gender, DATE_FORMAT(dob, '%d-%m-%Y') as dob, address, postalCode, isAutoLock as isActiveSecurity,city, email, touchIdInterval, (case when img !='' then concat('".UPLOADPATH."/user_images/',img) when (img ='' && gender ='Female')  THEN '".UPLOADPATH."/user_images/default_female.png' else '".UPLOADPATH."/user_images/default_male.png' end ) as `profile_image`,(SELECT (CASE WHEN count(*) > 0 then 1 else 0 end) as membership_count FROM vm_user_memberships WHERE startDate <= '".date('Y-m-d')."' AND endDate >= '".date('Y-m-d')."' AND userId=".$authData['roleId']." AND subscriptionStatus ='Active' ORDER BY membershipId desc limit 0,1) as `membership` from vm_user WHERE userId='".$authData['roleId']."'",true); 

            $language = (isset($_POST['language']) && !empty($_POST['language'])) ? $_POST['language']:'';
           
            $users->language = $this->common_lib->getUpdateLanguage( $insertStatus, 'user', $language);
            
            $this->response (
                [
                    'status' => TRUE,
                    'message'=> $this->lang->line('successRegister'),
                    'token'  => $token, 
                    'data'   => $users
                ], 
                REST_Controller::HTTP_OK
            );            
            
        } else {
            $status['status'] = false;
            $status['message'] = $this->lang->line('failedAddUser');

            $this->response($status, REST_Controller::HTTP_CONFLICT);
        }
        
    }

    // User Login - Validate Login Data
    public function validateLogin () {
        if(!isset($_POST['email']) || empty($_POST['email']))
            $this->response(['status' => FALSE,'message' => $this->lang->line('emailRequired')], REST_Controller::HTTP_BAD_REQUEST);
        if(!isset($_POST['password']) || empty($_POST['password']))
            $this->response(['status' => FALSE,'message' => $this->lang->line('passwordRequired')], REST_Controller::HTTP_BAD_REQUEST);

        $this->form_validation->set_rules('email', 'Email', 'valid_email');
        if ($this->form_validation->run() == FALSE)
            $this->response(['status' => FALSE,'message' => $this->lang->line('invalidEmail')], REST_Controller::HTTP_BAD_REQUEST);   

        return true;
    }

    // User Login - Main Function (multiple roles like user, teacher, admin) 
    public function login_post() {
        $errors = array();
        $this->load->library('form_validation');          
        
        // Validate User Data
        $this->validateLogin ();          
        
        $role = 'user';
        $authData = $this->Common_model->exequery("SELECT sa.`roleId`, sa.`emailId`,sa.`deviceId`,sa.`status` FROM `vm_auth` sa join vm_user su on (sa.roleid=su.userid and sa.role='user') where (sa.emailId = '".$_POST['email']."' and sa.password = '".md5($_POST['password'])."' and sa.role = '".$role."') and su.status=0",1);

        if (valResultSet($authData)) {
            if ($authData->status == 0) {
                $deviceId = (isset($_POST['deviceId']) && !empty($_POST['deviceId'])) ? $_POST['deviceId'] : '';

                if ($deviceId != '')
                    $this->Common_model->update('vm_auth',array('deviceId' => '')," deviceId='".$deviceId."'");

                $tokenDataId =   $this->Common_model->getSelectedField("vm_api_token", 'id' ,"role= '".$role."' and roleId=".$authData->roleId);
                $token       = md5('AL'.$authData->roleId.date('mdY_His').'user');
                
                $insertTokensStatus = 0;
                $updateDeviceId = $this->Common_model->update('vm_auth',array('deviceId' => $deviceId),"roleId=".$authData->roleId ." and role = '".$role."'");
                
                if (!valResultSet($tokenDataId))                   
                    $insertTokensStatus = $this->Common_model->insert('vm_api_token',array('role' => $role,'roleId' => $authData->roleId,'emailId' => $authData->emailId,'token' => $token));
                 else
                   $insertTokensStatus = $this->Common_model->update('vm_api_token',array('role' => $role,'roleId' => $authData->roleId,'emailId' => $authData->emailId,'token' => $token),"id=".$tokenDataId);

                $users = $this->Common_model->exequery("SELECT userId,userName as fullName, lastName, countryCode, mobile, gender, DATE_FORMAT(dob, '%d-%m-%Y') as dob, address, postalCode, isAutoLock as isActiveSecurity,city, email, touchIdInterval, (case when img !='' then concat('".UPLOADPATH."/user_images/',img) when (img ='' && gender ='Female')  THEN '".UPLOADPATH."/user_images/default_female.png' else '".UPLOADPATH."/user_images/default_male.png' end ) as `profile_image`,(SELECT (CASE WHEN count(*) > 0 then 1 else 0 end) as membership_count FROM vm_user_memberships WHERE startDate <= '".date('Y-m-d')."' AND endDate >= '".date('Y-m-d')."' AND userId=".$authData->roleId." AND subscriptionStatus ='Active' ORDER BY membershipId desc limit 0,1) as `membership` from vm_user WHERE userId='".$authData->roleId."'",true); 

                if ($insertTokensStatus){           
                    $language = (isset($_POST['language']) && !empty($_POST['language']))?$_POST['language']:'';
                    $users->language = $this->common_lib->getUpdateLanguage( $authData->roleId, 'user', $language);
                    $this->response(array('status' => true,'message' => $this->lang->line('loginSuccess'),'token' => $token,'data' => $users),REST_Controller::HTTP_OK);
                } else {
                    $this->response(array('status' => false,'message' => $this->lang->line('internalError')),REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
                }                    
                
            } else {
                $this->response(['status' => FALSE,'message' => $this->lang->line('userDeActive') ], REST_Controller::HTTP_UNAUTHORIZED);
            }                

        } else {
            $this->response(['status' => FALSE,'message' => $this->lang->line('incorrectLogin') ], REST_Controller::HTTP_FORBIDDEN);
        }           
    }

    // Change password after clicking on forgot password button
    public function forgotpassword_post(){
        if(isset($_POST['email']) && $_POST['email']!=''){
            
            $this->load->library('form_validation');
            $this->form_validation->set_rules('email', 'Email', 'valid_email');
            if ($this->form_validation->run() == FALSE){
                $status['status'] = false;
                $status['message'] = $this->lang->line('invalidEmail');
                $this->response($status, REST_Controller::HTTP_BAD_REQUEST);

            }

            $userAuthData=$this->Common_model->selRowData("vm_auth","role,language","emailId = '".$_POST['email']."' AND role='user'");
            if (!valResultSet($userAuthData) || $userAuthData->role == '')
                $this->response(['status' => FALSE,'message' => $this->lang->line('userNotExists') ], REST_Controller::HTTP_FORBIDDEN);

            $pass = generateStrongPassword(6,false,'lud');
            $authData               =   array();
            $authData['password']   =   md5($pass);
            $authUpdateStatus       =   $this->Common_model->update("vm_auth", $authData,"emailId = '".trim($_POST['email'])."' and role='user'");
            if($authUpdateStatus){
                $langSuffix = '';
                $changePasswordTitle = $this->lang->line('changePasswordTitle');
                if($userAuthData->language){
                    $langSuffix = $this->common_lib->translate('langSuffix',$userAuthData->language);
                    $changePasswordTitle = $this->common_lib->translate('changePasswordTitle',$userAuthData->language);
                }
                //Send welcome email
                $passwordKeyUrl=BASEURL.'/system/static/emailTemplates/images/key.png';
                $settings = array();
                $settings["template"]               =   "password_changed_tpl".$langSuffix.".html";
                $settings["email"]                  =   trim($_POST['email']);
                $settings["subject"]                =   $changePasswordTitle;
                $contentarr['[[[ROLE]]]']           =   ucfirst($userAuthData->role);
                $contentarr['[[[USERNAME]]]']       =   trim($_POST['email']);
                $contentarr['[[[PASSWORD]]]']       =   $pass;
                $contentarr['[[[LOGINURL]]]']       =   BASEURL.'/login';
                $contentarr['[[[PASSWORDKEYURL]]]'] =   $passwordKeyUrl;
                $settings["contentarr"]             =   $contentarr;
                $ismailed = $this->common_lib->sendMail($settings); 

                
                $status['status']        = true;
                $status['ismailed']        = $ismailed;
                $status['message']      = $this->lang->line('successPassword');
                 $this->response($status, REST_Controller::HTTP_OK); // SUCCESS (200) being the HTTP response code 
            }else{
                $status['status']        = False;
                $status['message']      = $this->lang->line('failedPassword');
                 $this->response($status, REST_Controller::HTTP_INTERNAL_SERVER_ERROR); // HTTP_INTERNAL_SERVER_ERROR (500) being the HTTP response code
            }
        }
        else
            $this->response(['status' => False,'message' => $this->lang->line('emailRequired')], REST_Controller::HTTP_BAD_REQUEST);
    }

    // Change password after clicking on forgot password button
    public function resetpassword_post(){
        $token = $this->input->get_request_header('Authorization', TRUE);
        $tokendata = explode(' ', $token);
        if($token != '' && $this->common_lib->validateToken($token)){
            if( !isset($_POST['password']) || empty($_POST['password'])){
                $status['status'] = false;
                $status['message'] = $this->lang->line('passwordRequired');
                $this->response($status, REST_Controller::HTTP_BAD_REQUEST);
            }
            if( !isset($_POST['newpassword']) || empty($_POST['newpassword'])){
                $status['status'] = false;
                $status['message'] = $this->lang->line('newPasswordField');
                $this->response($status, REST_Controller::HTTP_BAD_REQUEST);
            }

            $query = "SELECT au.authId, au.emailId, au.role, au.language FROM vm_api_token as at INNER JOIN vm_auth as au on at.emailId = au.emailId  WHERE au.emailId = at.emailId and au.password='". md5(trim($_POST['password']))."' and at.token='".$tokendata[1]."' AND au.role='user'";
            $authData =   $this->Common_model->exequery($query,1);
            
            if(valResultSet($authData)) {
            
                //Update password
                $langSuffix = ($authData->language)?$this->common_lib->translate('langSuffix',$authData->language):'';
                $updateData['password'] =   md5(trim($_POST['newpassword']));
                $changePasswordTitle = $this->common_lib->translate('successPassword',$authData->language);
                $updateStatus           =   $this->Common_model->update("vm_auth", $updateData, "authId = ".$authData->authId);
                if($updateStatus){ 
                    $passwordKeyUrl=BASEURL.'/system/static/emailTemplates/images/key.png';
                    $settings = array();
                    $settings["template"]               =  "resetpassword_changed_tpl".$langSuffix.".html";
                    $settings["email"]                  =  $authData->emailId; 
                    $settings["subject"]                =  "Vedmir - ".$changePasswordTitle;
                    $contentarr['[[[USERNAME]]]']       =   $authData->emailId;
                    $contentarr['[[[PASSWORD]]]']       =   trim($_POST['newpassword']);
                    $contentarr['[[[DASHURL]]]']        =   BASEURL."/login";
                    $contentarr['[[[PASSWORDKEYURL]]]'] =   $passwordKeyUrl;
                    $settings["contentarr"]             =   $contentarr;
                    $this->common_lib->sendMail($settings);  
                    $status['status']        = true;
                    $status['message']      = $this->lang->line('updatePassword');
                    $status['language'] = $langSuffix;
                    $this->response($status, REST_Controller::HTTP_OK); // SUCCESS (200) being the HTTP response code 
                }else{
                    $status['status']        = False;
                    $status['message']      = $this->lang->line('failedUpdatePassword');
                    $this->response($status, REST_Controller::HTTP_INTERNAL_SERVER_ERROR); // HTTP_INTERNAL_SERVER_ERROR (500) being the HTTP response code
                }
            }else{
                $this->response([
                'status' => FALSE,
                'message' => $this->lang->line('oldNotMatch')
            ], REST_Controller::HTTP_FORBIDDEN); // HTTP_UNAUTHORIZED (401) being the HTTP response code
            }
        }else{
                $this->response([
                'status' => FALSE,
                'message' => $this->lang->line('unAuthorized')
            ], REST_Controller::HTTP_UNAUTHORIZED); // HTTP_UNAUTHORIZED (401) being the HTTP response code
        }
    }

    public function logout_get(){
       
        $token = $this->input->get_request_header('Authorization', TRUE);
        $tokendata = explode(' ', $token);
        $userData = $this->common_lib->validateToken($token);
        if($token != '' && $userData){
            $isDelete = $this->Common_model->del('vm_api_token',"token='".$tokendata[1]."'");
            $updateDeviceToken = $this->Common_model->update('vm_auth',array('deviceId' => '','deviceToken' => '')," roleId='".$userData->roleId."' AND role ='".$userData->role."'");
            if ($isDelete) {
                $this->response([
                'status' => true,
                'message' => $this->lang->line('logout')
                ], REST_Controller::HTTP_OK);
            }else{
                $this->response([
                'status' => FALSE,
                'message' => $this->lang->line('internalError')
                ], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
            }

        }else{
            $this->response([
            'status' => FALSE,
            'message' => $this->lang->line('unAuthorized')
            ], REST_Controller::HTTP_UNAUTHORIZED); 
        }
    }

    public function updatelanguage_post(){
        $token = $this->input->get_request_header('Authorization', TRUE);

        if($token != '' && $roleData = $this->common_lib->validateToken($token)){

            if(!isset($_POST['lang']) || empty($_POST['lang']))
                $this->response(['status' => FALSE,'message' =>$this->lang->line('languageReq')], REST_Controller::HTTP_BAD_REQUEST);
            if(!in_array(trim($_POST['lang']), array('english','french','german','italian')))
                $this->response(['status' => FALSE,'message' =>$this->lang->line('invalidLanguage')], REST_Controller::HTTP_BAD_REQUEST);
            $language = $this->common_lib->getUpdateLanguage( $roleData['roleId'], $roleData['role'], trim($_POST['lang']));

            if ($language)
                $this->response(['status' => true, 'message' => $this->lang->line('languageUpdated'), 'language' => $language], REST_Controller::HTTP_OK);
            else
                $this->response(['status' => FALSE,'message' => $this->lang->line('internalError')], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);

        }else
            $this->response(['status' => FALSE,'message' => $this->lang->line('unAuthorized')], REST_Controller::HTTP_UNAUTHORIZED);

    }

    public function updatedevicetoken_post(){
        $token = $this->input->get_request_header('Authorization', TRUE);
        if($token != '' && $roleData = $this->common_lib->validateToken($token)){
            if(isset($_POST['deviceToken']) && $_POST['deviceToken'] !=''){

                $updateData   =  array();
                $updateData['deviceToken']     =   '';
                $userStatus = $this->Common_model->update("vm_auth", $updateData,"deviceToken = '".trim($_POST['deviceToken'])."'");

                $updateTokenData   =  array();
                $updateTokenData['deviceToken']     =   trim($_POST['deviceToken']);
                $updateTokenStatus = $this->Common_model->update("vm_auth", $updateTokenData,"roleId='".$roleData->roleId."' AND role='".$roleData->role."'");
                if ($updateTokenStatus)
                    $this->response(array('status' => TRUE,'message' => $this->lang->line('updateProfile')),REST_Controller::HTTP_OK);
                else
                    $this->response(array('status' => false,'message' => $this->lang->line('internalError')),REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
            }else
                $this->response(array('status' => false,'message' => $this->lang->line('deviceTokenRequired')),REST_Controller::HTTP_BAD_REQUEST);
        }else
            $this->response(array('status' => false,'message' => $this->lang->line('unAuthorized')),REST_Controller::HTTP_UNAUTHORIZED);

    }


}
