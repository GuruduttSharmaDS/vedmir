<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

/**
 * Created by Dream Steps Pvt Ltd
 * Created on 30 Nov 2019
 * Vedmir -  module
**/
	public $outputData  	= array();
	public $sessLang  	= '';
	public $langSuffix  	= '_en';
	
    function __construct()
    {
        // Construct the parent class
        parent::__construct();

        if (empty($this->sessLang)) {
        	if ($this->session->userdata(PREFIX.'sessLang') == '') {
				$this->session->set_userdata(PREFIX.'sessLang', "english");
        		$this->sessLang = 'english';
        	}else{
        		$this->sessLang = $this->session->userdata(PREFIX.'sessLang');
        	}
        }

		$this->lang->load('custom_language_frontend',$this->sessLang);	
		$this->langSuffix = $this->lang->line('langSuffix');
    }

	//Home page
	public function index() {
		$this->outputData['title'] = 'HOME';
		$this->outputData['fileName'] = $this->common_lib->getPublicFileName('stream.mp4');
		$this->load->viewF('home_view',$this->outputData);
	}
	//Home page
	public function streming($fileName) {
		if($this->common_lib->checkFileAccessToken()){
			$this->load->library('encryption');
			$dec_fileName=str_replace(array('-', '_', '~'), array('+', '/', '='), $fileName);
			$this->load->library('VideoStream', ['file'=> ABSUPLOADPATH.'/course_videos/'.$this->encryption->decrypt($dec_fileName)]);
			$this->videostream->start();
		}else{
			echo '<!DOCTYPE html> <html> <head> <title>403 Forbidden</title> </head> <body> <p>Directory access is forbidden.</p> </body> </html>';
		}
		exit;
	}

	//FAQ page
	public function faq() {
		$this->outputData['title'] = 'FAQ';
		$this->load->viewF('faq_view',$this->outputData);
	}
	//Blog page
	public function blog($filterType='',$filterBy='') {
		$this->outputData['categoryData'] =	$this->Common_model->selTableData("vm_blog_category","","status = 0","categoryName");
		$this->outputData['latestBlogData'] = $this->Common_model->selTableData("vm_blog","title,img,DATE_FORMAT(addedOn, '%M %d,%Y')as addedOn,slug","status = 0","blogId DESC","0","3");
		$this->outputData['filterType'] = $filterType;
		$this->outputData['filterBy'] = $filterBy;
		$this->outputData['title'] = 'Blog';

		$this->load->viewF('blog_view',$this->outputData);
	}

	//Blog Details page
	public function blog_details($slug='') {
		$this->outputData['categoryData'] =	$this->Common_model->selTableData("vm_blog_category","","status = 0","categoryName");
		$query	=	"SELECT  bc.categoryName, bl.img, bl.title,SUBSTRING(bl.description, 1, 80) as description, bl.slug, DATE_FORMAT(bl.addedOn, '%M %d,%Y')as addedOn from vm_blog as bl left join vm_blog_category as bc on bc.categoryId=bl.categoryId  where bl.status =0 and bl.slug != '".$slug."' ORDER BY blogId DESC LIMIT 0,3";
		$this->outputData['latestBlogData'] =	$this->Common_model->exequery($query);

		$query	=	"SELECT bl.blogId,
		 (SELECT count(*) from vm_blog_comment where vm_blog_comment.blogId = bl.blogId ) as totalComment,
		 bc.categoryName,
		 bc.slug as bcslug,
		 bl.img,
		 bl.title,bl.description,
		    bl.tags,bl.slug, DATE_FORMAT(bl.addedOn, '%M %d,%Y')as addedOn,case when bl.status='0' then 'Active' else 'DeActive' end as status,
			case when bl.status='0' then 'act fa fa-circle' else 'dct fa fa-circle' end as class from vm_blog as bl left join vm_blog_category as bc on bc.categoryId=bl.categoryId  where bl.status =0 and bl.slug = '".$slug."'";
		$blogDetailsData = $this->Common_model->exequery($query,1);
		$this->outputData['blogDetailsData'] =	$blogDetailsData;
		if (valResultSet($blogDetailsData)) {
		$this->outputData['blogCommentData'] = $this->Common_model->selTableData("vm_blog_comment","`name`, `comment`,DATE_FORMAT(addedOn, '%M %d,%Y')as addedOn","status = 0 and blogId = '".$blogDetailsData->blogId."'");
		}
		$this->outputData['title'] = 'Blog';

		$this->load->viewF('blog_details_view',$this->outputData);
	}
	//Contact page
	public function contact_us() {
		$this->outputData['title'] = 'Contact Us';
		$this->load->viewF('contact_view',$this->outputData);
	}

	public function signup() {

		$this->outputData['title'] = 'Venue Signup';
		$this->load->viewF('signup_view',$this->outputData);
	}
	//Login page
	public function login($role = 'restaurant') {
		$this->session->set_userdata(PREFIX.'sessRole', $role);
		$this->outputdata["role"]	=	$role;
		//echo $role;
		if(isset($_POST) && !empty($_POST)){
			if(isset($_POST['txtEmailId']) && empty($_POST['txtEmailId'])){
				$this->common_lib->setSessMsg("Email Field required", 2);
                redirect(BASEURL."/login");
			}
		}
		if(isset($_POST['txtEmailId']) && $_POST['txtEmailId']!="") {		
            $dbresult	=	$this->checkLogin($role);
			//v3print($dbresult); exit;
				
			if($dbresult['flag'] == 2){
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
				
            }else{
				if ($dbresult['flag'] == 3)
					$this->common_lib->setSessMsg("Sorry your account has been temporarily disabled as a security precaution.", 2);
				else
					$this->common_lib->setSessMsg("Incorrect login details", 2);

                redirect(BASEURL."/login");
            }
        }	

		$this->outputData['title'] = 'Login';
		$this->load->viewF('login_view',$this->outputData);
	}
	public function privacy() {
		$this->outputData['title'] = 'Privacy Policy';
		$this->load->viewF('privacy_view',$this->outputData);
	}
	public function terms() {
		$this->outputData['title'] = 'Term & Conditions';
		$this->load->viewF('term_view',$this->outputData);
	}

	/* ----- send comment of blog  */
	public function add_blog_comment() {

		$responseStr = 0;
		if (isset($_POST['email']) && $_POST['email'] != '') {

	 		$queryData   =  array();
	        $queryData['name']      =   trim($_POST['name']);
	        $queryData['email']     =   trim($_POST['email']);
	        $queryData['comment']   =   trim($_POST['comment']);
	        $queryData['blogId']    =   (trim($_POST['blogId']) > 0)?trim($_POST['blogId']):0;
	        $queryData['ip']   		=   trim($_POST['ip']);
	        $queryData['addedOn']   =   date('Y-m-d H:i:s');
	        $insertStatus       =   $this->Common_model->insert("vm_blog_comment", $queryData);
	        if($insertStatus){
				$responseStr =  1;
			}
		}else{
				$responseStr =  'email';
		}

		echo $responseStr;
	}


	/* ----- send mail to admin for query from enquiry form */
	public function send_enquiry() {

		$responseStr = 0;
		if (isset($_POST['email']) &&  $_POST['email'] != '') {

	 		$queryData   =  array();
	        $queryData['name']      =   (isset($_POST['name']))?trim($_POST['name']):'';
	        $queryData['email']     =   (isset($_POST['email']))?trim($_POST['email']):'';
	        $queryData['mobile']    =   (isset($_POST['mobile']))?trim($_POST['mobile']):'';
	        $queryData['subject']   =   (isset($_POST['subject']))?trim($_POST['subject']):'New Enquiry From Vedmir';
	        $queryData['message']   =  (isset($_POST['message']))?trim($_POST['message']):'';
	        $queryData['addedOn']   =   date('Y-m-d H:i:s');
	        $insertStatus       =   $this->Common_model->insertUnique("vm_contact_mail", $queryData);
	        if($insertStatus){
				$settings = array();
				$settings["template"] 				= 	"send_enquiry_tpl".$this->lang->line('langSuffix').".html";
				$settings["email"] 					= 	'support@vedmir.com';//ADMINMAIL;
				$settings["subject"] 				=	"New Enquiry From Vedmir";
				$contentarr['[[[NAME]]]']			=	$queryData['name'];
				$contentarr['[[[EMAIL]]]']			=	$queryData['email'];
				$contentarr['[[[SUBJECT]]]']		=	$queryData['subject'];
				$contentarr['[[[MESSAGE]]]']		=	$queryData['message'];
				$settings["contentarr"] 			= 	$contentarr;	        	
				$ismailed = $this->common_lib->sendMail($settings);	
				$settings = array();
		        $settings["template"]               =   "thank_tpl".$this->lang->line('langSuffix').".html";
		        $settings["email"]                  =   $_POST['email'];//USEREMail;
		        $settings["subject"]                =   "Thank You for interest";            
		        $settings["contentarr"]             =   $contentarr;
		        $ismailed = $this->common_lib->sendMail($settings);

				$responseStr =  1;
			}else{
				$responseStr =  'email';
			}
		}
		echo $responseStr;
	}
	public function checkLogin($role) {
		if(isset($_POST['txtEmailId']) && $_POST['txtEmailId']!=''){
			$resultarr['flag']	=	1;
			$emailId	=	trim($_POST['txtEmailId']);
			$pass		=	trim($_POST['txtPassword']);
			$cond		=	array('emailId' => $emailId, 'password' => md5($pass), 'role' => $role);
			$row		=	$this->Common_model->selRowData(PREFIX."auth", '' ,$cond);
			if($row){

				$resultarr['flag']	=	2;

				if ($role == 'restaurant') {
					$restaurantStatus = $this->Common_model->getSelectedField(PREFIX."restaurant", 'status' ,array('email' => $emailId));
					if ($restaurantStatus > 0)
						$resultarr['flag']	=	3;
					
				}else if ($row->status = 1) {
					$resultarr['flag']	=	3;
				}

				if ($resultarr['flag']	==	2) {
					
					$this->session->set_userdata(PREFIX.'sessAuthId', $row->authId);
					$this->session->set_userdata(PREFIX.'sessEmail', $row->emailId);
					$this->session->set_userdata(PREFIX.'sessRoleId', $row->roleId);
					$this->session->set_userdata(PREFIX.'sessRole', $role);
				}


						
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
	public function forgot($role = "admin"){
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

}
