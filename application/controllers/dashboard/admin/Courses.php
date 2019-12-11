<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Created by Dream Steps Pvt Ltd
 * Created on 30 Nov 2019
 * Vedmir -  module
**/

class Courses extends CI_Controller {

	public $menu		= 9;
	public $subMenu		= 91;
	public $subSubMenu		= 0;
	public $outputdata 	= array();
	
	public function __construct() {
		parent::__construct();

		//Check login authentication & set public veriables
		$this->session->set_userdata(PREFIX.'sessRole', "admin");
		$this->common_lib->setSessionVariables();
		$this->langSuffix = $this->lang->line('langSuffix');

		$this->viewPath = 'admin/Course/';
	}

	// Listing
	public function list() {	
		$this->menu		=	9;
		$this->subMenu	=	92;	
		$this->load->viewD($this->viewPath.'list', $this->outputdata);
	}

	// Detail Page
	public function detail($categoryId) {		
		if($categoryId == 0 )
			redirect(DASHURL. '/admin/courses/list');

		$this->menu		=	9;
		$this->subMenu	=	93;
		/*$query	=	"SELECT *, concat(userName,' ', lastName) as userName,(SELECT (CASE WHEN count(*) > 0 then (CASE WHEN isUpdatedPlan = 1 THEN ( CASE WHEN couponId != 0 THEN ( SELECT (CASE WHEN offeredType = 3 THEN couponCode ELSE (CONCAT('COUPON - ', (SELECT CONCAT(sp.planName$this->langSuffix, ' (',sd.period,' ',sd.duration,')') as planName FROM vm_subscription_details sd left JOIN vm_subscription_plan as sp on sp.Id = sd.subscriptionId WHERE detailId =vm_user_memberships.planId))) end) as planName FROM vm_coupons WHERE couponId = vm_user_memberships.couponId  ) ELSE (SELECT CONCAT(sp.planName$this->langSuffix, ' (',sd.period,' ',sd.duration,')') as planName FROM vm_subscription_details sd left JOIN vm_subscription_plan as sp on sp.Id = sd.subscriptionId WHERE detailId =vm_user_memberships.planId) end)  ELSE (SELECT planName as planName FROM `vm_subscription_plan` WHERE vm_subscription_plan.id = vm_user_memberships.planId) END ) else '' end) as membership FROM vm_user_memberships WHERE startDate <= '".date('Y-m-d')."' AND endDate >= '".date('Y-m-d')."' AND userId=vm_user.userId AND subscriptionStatus ='Active' order by membershipId desc limit 0, 1) as `membershipName` from vm_user where userId = ".$userId;*/

		$query = "SELECT cau.*, cat.categoryName,cat.categoryId FROM vm_course cau left join vm_category as cat on cat.categoryId= cau.categoryId where cat.categoryId = ".$categoryId."";
		
		$coursesData = $this->Common_model->exequery($query,1);
		$this->outputdata['coursesData'] =	($coursesData) ? $coursesData  : array();
		$this->load->viewD($this->viewPath.'/view_courses_info',$this->outputdata);
	}

}