<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Created by Dream Steps Pvt Ltd
 * Created on 30 Nov 2019
 * Vedmir -  module
**/

class Student extends CI_Controller {

	public $menu		= 3;
	public $subMenu		= 31;
	public $subSubMenu		= 0;
	public $outputdata 	= array();
	public $langSuffix = '';

	public function __construct() {
		parent::__construct();

		//Check login authentication & set public veriables

		$this->session->set_userdata(PREFIX.'sessRole', "admin");
		$this->common_lib->setSessionVariables();
		$this->langSuffix = $this->lang->line('langSuffix');
	}	

	public function add($itemId = 0) {		
		$this->menu		=	3;
		$this->subMenu	=	31;

		if ($itemId > 0) {
			$userData = $this->Common_model->selRowData("vm_user","*, (case when img != '' then concat('".UPLOADPATH."/user_images/', img) else '' end) as img","userId = ".$itemId);	
			$this->outputdata['userData'] =	$userData;
		}
		$this->load->viewD('admin/Student/add_update', $this->outputdata);
	}

	// Listing
	public function list() {	
		$this->menu		=	3;
		$this->subMenu	=	32;	
		$this->load->viewD('admin/Student/list', $this->outputdata);
	}

	// user profile view
	public function detail($userId) {		
		if ($userId == 0 )
			redirect(DASHURL.'/admin/student/list');
		$this->menu		=	3;
		$this->subMenu	=	32;
		$query	=	"SELECT *, concat(userName, ' ', lastName) as userName,(SELECT (CASE WHEN count(*) > 0 then (CASE WHEN isUpdatedPlan = 1 THEN ( CASE WHEN couponId != 0 THEN ( SELECT (CASE WHEN offeredType = 3 THEN couponCode ELSE (CONCAT('COUPON - ', (SELECT CONCAT(sp.planName$this->langSuffix, ' (',sd.period,' ',sd.duration,')') as planName FROM vm_subscription_details sd left JOIN vm_subscription_plan as sp on sp.Id = sd.subscriptionId WHERE detailId =vm_user_memberships.planId))) end) as planName FROM vm_coupons WHERE couponId = vm_user_memberships.couponId  ) ELSE (SELECT CONCAT(sp.planName$this->langSuffix, ' (',sd.period,' ',sd.duration,')') as planName FROM vm_subscription_details sd left JOIN vm_subscription_plan as sp on sp.Id = sd.subscriptionId WHERE detailId =vm_user_memberships.planId) end)  ELSE (SELECT planName as planName FROM `vm_subscription_plan` WHERE vm_subscription_plan.id = vm_user_memberships.planId) END ) else '' end) as membership FROM vm_user_memberships WHERE startDate <= '".date('Y-m-d')."' AND endDate >= '".date('Y-m-d')."' AND userId=vm_user.userId AND subscriptionStatus ='Active' order by membershipId desc limit 0, 1) as `membershipName` from vm_user where userId = ".$userId;
		$profileInfo = $this->Common_model->exequery($query,1);
		$this->outputdata['profile'] =	($profileInfo) ? $profileInfo  : array();
		$this->load->viewD($this->sessRole.'/Student/view',$this->outputdata);
	}

	// user-listing view
	public function blocked_user_list() {	
		$this->menu		=	3;
		$this->subMenu	=	34;			

		$this->load->viewD('admin/user_blocked_list_view', $this->outputdata);
	}

	public function enquiry_list() {	
		$this->menu		=	3;
		$this->subMenu	=	33;		

		$this->load->viewD('admin/enquiry_list_view', $this->outputdata);
	}	
	
	

	// user membership view
	public function view_membership($userId = 0) {
		if($userId == 0 )
			redirect(DASHURL.'/admin/user/list');		
		$this->menu		=	3;
		$this->subMenu	=	32;
		$query	=	"SELECT vm_user.*, (SELECT (CASE WHEN count(*) > 0 then (CASE WHEN isUpdatedPlan = 1 THEN ( CASE WHEN couponId != 0 THEN ( SELECT (CASE WHEN offeredType = 3 THEN couponCode ELSE (CONCAT('COUPON - ', (SELECT CONCAT(sp.planName$this->langSuffix, ' (',sd.period,' ',sd.duration,')') as planName FROM vm_subscription_details sd left JOIN vm_subscription_plan as sp on sp.Id = sd.subscriptionId WHERE detailId =vm_user_memberships.planId))) end) as planName FROM vm_coupons WHERE couponId = vm_user_memberships.couponId  ) ELSE (SELECT CONCAT(sp.planName$this->langSuffix, ' (',sd.period,' ',sd.duration,')') as planName FROM vm_subscription_details sd left JOIN vm_subscription_plan as sp on sp.Id = sd.subscriptionId WHERE detailId =vm_user_memberships.planId) end)  ELSE (SELECT planName as planName FROM `vm_subscription_plan` WHERE vm_subscription_plan.id = vm_user_memberships.planId) END ) else '' end) as membership FROM vm_user_memberships WHERE startDate <= '".date('Y-m-d')."' AND endDate >= '".date('Y-m-d')."' AND userId=vm_user.userId AND subscriptionStatus ='Active' order by membershipId desc limit 0, 1) as `membershipName`, (SELECT (CASE WHEN count(*) > 0 then 'Active' else 'Not Active' end) as membership_count FROM vm_user_memberships WHERE startDate <= '".date('Y-m-d')."' AND endDate >= '".date('Y-m-d')."' AND userId=vm_user.userId AND subscriptionStatus ='Active' order by membershipId desc limit 0, 1) as `membership` from vm_user where userId = ".$userId;
		$profileInfo = $this->Common_model->exequery($query,1);
		$this->outputdata['profile'] =	($profileInfo) ? $profileInfo  : array();
		$this->load->viewD($this->sessRole.'/user_membership_info',$this->outputdata);
	}
}