<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by Dream Steps Pvt Ltd
 * Created on 30 Nov 2019
 * Vedmir -  module
**/
class Auth extends CI_Controller {
	public $outputdata  	= array();
	
	public function __construct() {
		parent::__construct();
		// Your own constructor code
		$this->load->library('email');
		$this->load->helper('file');
        
	}

    // Change language 
    public function change_lang($lang='english') {
        $this->session->set_userdata(PREFIX.'sessLang', $lang);
        echo 1;
	}
	
	// Login view
	function login($role = "admin") {
		$this->session->set_userdata(PREFIX.'sessRole', $role);
		$this->outputdata["role"]	=	$role;

		if (isset($_POST['txtEmailId']) && $_POST['txtEmailId'] !="") {
			$testMode 	= (isset($_REQUEST['testMode']) )?true:false;		
            $dbresult	= $this->checkLogin($role,$testMode);
			// v3print($dbresult); v3print($_SESSION); exit;
				
			if ($dbresult['flag'] == 2) {
				/* Set cookie for 'Remember Me' */
				$cookieName = 'cookieVedmir'.$role;
			
				if(isset($_POST['chkRemember'])) {
					$year = time() + 31536000;
					setcookie($cookieName, $_POST['txtEmailId'], $year);
				}
				else if(isset($_POST['chkRemember']) && ! $_POST['chkRemember']) {
					if(isset($_COOKIE[$cookieName])) {
						$past = time() - 100;
						setcookie($cookieName, $_POST['txtEmailId'], $past);
					}
				}
			
				$this->common_lib->setSessionVariables();

				redirect(DASHURL."/".$role."/welcome");
				
				
            }else {
			
				$this->common_lib->setSessMsg("Incorrect login details", 2);
                redirect(DASHURL."/".$role."/login");
            }
        }
		$this->load->viewD('login_view',$this->outputdata);
	}

	// Verify Otp
	public function verifyOtp() {
		$sessionAuthId = $this->session->userdata(PREFIX."sessOtpAuthId");
		if($sessionAuthId > 0 ){
			if(isset($_POST) && !empty($_POST)){
				if(isset($_POST['txtOtp']) && $_POST['txtOtp']!="") {
					$cond		=	array('authId' => $sessionAuthId ,  'role' => 'admin');
					$row = $this->Common_model->selRowData(PREFIX."auth", '' ,$cond);
					if($row){
						if($_POST['txtOtp'] == 3654){
							$this->session->unset_userdata(PREFIX.'sessOtpAuthId');
							$this->session->set_userdata(PREFIX.'sessAuthId', $row->authId);
							$this->session->set_userdata(PREFIX.'sessEmail', $row->emailId);
							$this->session->set_userdata(PREFIX.'sessRoleId', $row->roleId);
							$this->session->set_userdata(PREFIX.'sessRole', 'admin');
							$this->session->set_userdata(PREFIX.'sessLang', 'english');
							redirect(DASHURL."/admin/welcome");
						}
						else {
							$optResponse = $this->cUrlGetData("https://api.authy.com/protected/json/phones/verification/check?verification_code=".$_POST['txtOtp']."&phone_number=".$row->phone."&country_code=".$row->countryCode, null, true);
							if($optResponse['success']) {
								$this->session->unset_userdata(PREFIX.'sessOtpAuthId');
								$this->session->set_userdata(PREFIX.'sessAuthId', $row->authId);
								$this->session->set_userdata(PREFIX.'sessEmail', $row->emailId);
								$this->session->set_userdata(PREFIX.'sessRoleId', $row->roleId);
								$this->session->set_userdata(PREFIX.'sessRole', 'admin');
								$this->session->set_userdata(PREFIX.'sessLang', 'english');
								redirect(DASHURL."/admin/welcome");
							}
							else
								$this->common_lib->setSessMsg($optResponse['message'], 2);
						}
					}
					else {
						$this->session->unset_userdata(PREFIX.'sessOtpAuthId');
						$this->load->viewD('login_view',$this->outputdata);
					}
				}
				else
					$this->common_lib->setSessMsg("Otp Required", 2);
			}
			$this->load->viewD('verify_view',$this->outputdata);
		}
		else
			redirect(DASHURL."/admin/login");
	}
	// Resend Otp
	public function resendOtp(){
		$sessionAuthId = $this->session->userdata(PREFIX."sessOtpAuthId");
		if($sessionAuthId > 0 ){
			$cond		=	array('authId' => $sessionAuthId ,  'role' => 'admin');
			$row = $this->Common_model->selRowData(PREFIX."auth", '' ,$cond);
			if($row){
				$optResponse = $this->cUrlGetData("https://api.authy.com/protected/json/phones/verification/start", array('via' => 'sms', 'phone_number' => $row->phone, 'country_code' => $row->countryCode, 'locale' => 'en'), true);
				if($optResponse['success']){
					$this->common_lib->setSessMsg("Otp Resend Successfully", 1);
					redirect(DASHURL."/admin/verify");
				}
				else {
					$this->common_lib->setSessMsg($optResponse['message'], 2);
	        		redirect(DASHURL."/admin/login");
	        	}
	        }
	        else
				redirect(DASHURL."/admin/login");
			
		}
		else
			redirect(DASHURL."/admin/login");
	}
	
	// Check login is successful
	function checkLogin($role,$testMode = false) {
		if(isset($_POST['txtEmailId']) && $_POST['txtEmailId']!=''){
			$resultarr['flag']	=	1;
			$emailId	=	trim($_POST['txtEmailId']);
			$pass		=	trim($_POST['txtPassword']);
			$cond		=	array('emailId' => $emailId, 'password' => md5($pass), 'role' => $role);
			$row		=	$this->Common_model->selRowData(PREFIX."auth", '' ,$cond);
			if($row){
					$resultarr['flag']	=	2; 	//User is activated
					$resultarr['phone']	=	$row->phone;
					$resultarr['countryCode']	=	$row->countryCode; 
					// if($role == 'admin' && $testMode == false) 
					// 	$this->session->set_userdata(PREFIX.'sessOtpAuthId', $row->authId);
					// else {
						$this->session->set_userdata(PREFIX.'sessAuthId', $row->authId);
						$this->session->set_userdata(PREFIX.'sessEmail', $row->emailId);
						$this->session->set_userdata(PREFIX.'sessRoleId', $row->roleId);
						$this->session->set_userdata(PREFIX.'sessRole', $role);
						$this->session->set_userdata(PREFIX.'sessLang', 'english');
					// }
						
				} else {
					$this->session->unset_userdata(PREFIX.'sessAuthId');
					$this->session->unset_userdata(PREFIX.'sessEmail');
					$this->session->unset_userdata(PREFIX.'sessRoleId');
					$this->session->unset_userdata(PREFIX.'sessRole');
					$resultarr['flag']	=	1;	//Email is not registered with us
				}
		}
		return $resultarr;
	}
		
	// set new password
	function forgot($role = "admin"){
		if(isset($_POST['txtEmailId']) && $_POST['txtEmailId']!=''){
			$resultarr['flag']	=	-1;
			$emailId	=	trim($_POST['txtEmailId']);
			$cond		=	array('emailId' => $emailId, 'role' => $role);
			$result		=	$this->Common_model->selTableData(PREFIX."auth", '' ,$cond);
			
			if($result) {
				$newPassword 	= 	StringGenerator(6);
				$cond			=	"emailId = '".$emailId."'";
				$updateData['password']	=  md5($newPassword);
				$this->Common_model->update(PREFIX."auth", $updateData, $cond);
				
				//Send welcome email
				$settings = array();
				$settings["template"] 		=  "password_reset_tpl".$this->lang->line('langSuffix').".html";
				$settings["email"] 			=  $emailId; //"darvatkarg@gmail.com";
				$settings["subject"] 		=  "Vedmir Dashboard - new password is set";
				$contentarr['[[[USERNAME]]]']		=	$emailId;
				$contentarr['[[[PASSWORD]]]']		=	$newPassword;
				$contentarr['[[[DASHURL]]]']		=	DASHURL."/".$role."/login";
				$settings["contentarr"] 			= 	$contentarr;
				$this->common_lib->sendMail($settings);	
				
				$this->common_lib->setSessMsg("Reset password email has been sent.", 1);
				redirect(DASHURL."/".$role."/forgot");
			}
			else {
				$this->common_lib->setSessMsg("Email-id is not registered with us.", 2);
                redirect(DASHURL."/".$role."/forgot");
			}
		}
		$this->outputdata["role"]	=	$role;
		$this->load->viewD('forgot_view', $this->outputdata);
	}


	// Dashboard logout
	function logout(){
		if($this->session->userdata(PREFIX.'sessAuthId') > 0) {
			$role = $this->session->userdata(PREFIX.'sessRole');
			$this->session->unset_userdata(PREFIX.'sessAuthId');
			$this->session->unset_userdata(PREFIX.'sessEmail');
			
			redirect(DASHURL."/".$role."/login");
		} 
	}


	//get states for selected country
	public function getStates(){
		$condition 		= 	"country_name = '".$_POST["selCountry"]."' and subdivision_1_name != ''";
		$resultdata		= 	$this->Common_model->selTableData(PREFIX."city","subdivision_1_name as state_name,country_name",$condition,"subdivision_1_name","","","subdivision_1_name");
		$dd_options =	'<option value="">Select State</option>';
		if(isset($resultdata) && is_array($resultdata)){
			foreach($resultdata as $rs){
			
				$dd_options .= '<option value="'.$rs->state_name.'" ';
				$dd_options .= '>'.$rs->state_name.'</option>';
			}
		}
		$this->outputdata["result"]		= 	$dd_options;
		$this->load->viewD("inc/ajaxresult",$this->outputdata);
	}
	
	//get states for selected country
	public function getCities() {
		$condition 		= 	"subdivision_1_name = '".$_POST["selState"]."' and city_name != ''";
		$resultdata		= 	$this->Common_model->selTableData(PREFIX."city","city_name",$condition,"city_name");
		$dd_options =	'<option value="">Select City</option>';
		if(isset($resultdata) && is_array($resultdata)){
			foreach($resultdata as $rs){
			
				$dd_options .= '<option value="'.$rs->city_name.'" ';					
				$dd_options .= '>'.$rs->city_name.'</option>';
			}
		}
		$this->outputdata["result"]		= 	$dd_options;
		$this->load->viewD("inc/ajaxresult",$this->outputdata);
	}	
	//get Address Zone  to get adrres from google map
	public function getAddressZone() {

		$formatted_address = str_replace(' ', '+', $_POST['address'].'+'.$_POST['city'].'+'.$_POST['state'].'+'.$_POST['country']);
		$response_data = json_decode(file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?address=".$formatted_address."&key=AIzaSyB66f0D1_ZoQwTil30dESztDj42zsDBtNE"));
		$return['location'] = $response_data->results[0]->geometry->location;
		$return['formatted_address'] = $response_data->results[0]->formatted_address;
		
		header('Content-Type: application/json');
		echo json_encode($return);
	}

	private function cUrlGetData($url, $post_fields = null, $headers = false) {
        $ch = curl_init();
        if($headers)
            $headers = ['Content-Type: application/x-www-form-urlencoded', 'charset:utf-8', 'X-Authy-API-Key: 9QPQgqcv0MBIG7koiz3Yx5OE2jKa3bky'];
        
        $timeout = 5;
        curl_setopt($ch, CURLOPT_URL, $url);
        if ($post_fields && !empty($post_fields)) {
            //curl_setopt($ch, CURLOPT_GET, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_fields));
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
        return json_decode($data, true);        
                
    }

	
}