<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by Dream Steps Pvt Ltd
 * Created on 30 Nov 2019
 * Vedmir -  module
**/
class Profile extends CI_Controller {
	
	public $menu		= 1;
	public $subMenu		= 1;
	public $subSubMenu		= 0;
	public $outputData 	= array();
	
	public function __construct(){
		parent::__construct();
		//Check login authentication & set public veriables
		$this->session->set_userdata(PREFIX.'sessRole', "admin");
		$this->common_lib->setSessionVariables();
	}

	//change password
	public function change_password() {
		if(isset($_POST['btnChangePassword'])) {
			//Verify current password
			// v3print($_POST);v3print($_FILES);exit;
			if(trim($_POST['txtNewPassword']) == trim($_POST['txtConfirmPassword'])) {
    			$condPass = "authId = ".$this->sessAuthId." AND password = '". md5(trim($_POST['txtCurrentPassword']))."'";
    			$authId =	$this->Common_model->getSelectedField("vm_auth", "authId", $condPass);
    			// v3print($condPass);exit;
    			if($authId) {
    				$cond     = "authId = ".$this->sessAuthId;
    				//Update password
    				$updateData['password']	=   md5(trim($_POST['txtNewPassword']));
    				$updateStatus 			= 	$this->Common_model->update("vm_auth", $updateData, $cond);
    
    				if($updateStatus)	{	
    					$passwordKeyUrl=BASEURL.'/system/static/emailTemplates/images/key.png';
    					$status		= "success";
    					//Send welcome email
    					$settings = array();
    					$settings["template"] 			=  "password_changed_tpl".$this->lang->line('langSuffix').".html";
    					$settings["email"] 				=  $this->sessEmail;
    					$settings["subject"] 			=  $this->lang->line('changePasswordTitle');
    					$contentarr['[[[USERNAME]]]']	=	$this->sessEmail;
    					$contentarr['[[[PASSWORD]]]']	=	trim($_POST['txtNewPassword']);
    					$contentarr['[[[DASHURL]]]']	=	DASHURL."/".$this->sessRole."/login";
    					$contentarr['[[[PASSWORDKEYURL]]]'] =   $passwordKeyUrl;
    					$settings["contentarr"] 		= 	$contentarr;
    					$this->common_lib->sendMail($settings);	
    					
    					$this->common_lib->setSessMsg("Success! new password is set.", 1);
    				} 
    				
    			}else {
    				$this->common_lib->setSessMsg("Current password is incorrect.", 2);
    			}
			}
			else
			    $this->common_lib->setSessMsg("New password and confirm password does not match", 2);
		}
		$this->load->viewD($this->sessRole.'/change_password_view');
	}	
}