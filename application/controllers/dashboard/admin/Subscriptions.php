<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Created by Dream Steps Pvt Ltd
 * Created on 30 Nov 2019
 * Vedmir -  module
**/

class Subscriptions extends CI_Controller {

	public $menu		= 5;
	public $subMenu		= 51;
	public $subSubMenu		= 0;
	public $outputdata 	= array();
	public $langSuffix = '';

	public function __construct() {
		parent::__construct();

		//Check login authentication & set public veriables

		$this->session->set_userdata(PREFIX.'sessRole', "admin");
		$this->common_lib->setSessionVariables();
		$this->langSuffix = $this->lang->line('langSuffix');
		$this->viewPath = 'admin/Subscriptions/';
	}	

	public function add($subscriptionPlanId = 0){		
		$this->menu		=	6;
		$this->subMenu	=	61;

		if ($subscriptionPlanId > 0){
			$subscriptionData = $this->Common_model->selRowData("vm_subscription_plan","*, (case when icon != '' then concat('".UPLOADPATH."/subscription_images/', icon) else '' end) as icon","subscriptionPlanId = ".$subscriptionPlanId);	
			$this->outputdata['subscriptionData'] =	$subscriptionData;
		}

		$query = "SELECT currencyId,currencyName,currencySymbol FROM vm_currency WHERE status != 2";
		$currency = $this->Common_model->exequeryarray($query);
		$this->outputdata['currency'] =	$currency;
		// echo "<pre>"; print_r ($currency); die;

		$this->load->viewD($this->viewPath.'/add', $this->outputdata);
	}

	// Subscriptions-listing view
	public function list() {	
		$this->menu		=	6;
		$this->subMenu	=	62;	
		$this->load->viewD($this->viewPath.'/list', $this->outputdata);
	}
	
	// Subscriptions profile view
	public function detail($subscriptionPlanId) {		
		if ($subscriptionPlanId == 0 )
			redirect(DASHURL.$this->viewPath.'/list');
		$this->menu		=	6;
		$this->subMenu	=	62;
		/*$query	=	"SELECT *, concat(userName, ' ', lastName) as userName,(SELECT (CASE WHEN count(*) > 0 then (CASE WHEN isUpdatedPlan = 1 THEN ( CASE WHEN couponId != 0 THEN ( SELECT (CASE WHEN offeredType = 3 THEN couponCode ELSE (CONCAT('COUPON - ', (SELECT CONCAT(sp.planName$this->langSuffix, ' (',sd.period,' ',sd.duration,')') as planName FROM vm_subscription_details sd left JOIN vm_subscription_plan as sp on sp.Id = sd.subscriptionId WHERE detailId =vm_user_memberships.planId))) end) as planName FROM vm_coupons WHERE couponId = vm_user_memberships.couponId  ) ELSE (SELECT CONCAT(sp.planName$this->langSuffix, ' (',sd.period,' ',sd.duration,')') as planName FROM vm_subscription_details sd left JOIN vm_subscription_plan as sp on sp.Id = sd.subscriptionId WHERE detailId =vm_user_memberships.planId) end)  ELSE (SELECT planName as planName FROM `vm_subscription_plan` WHERE vm_subscription_plan.id = vm_user_memberships.planId) END ) else '' end) as membership FROM vm_user_memberships WHERE startDate <= '".date('Y-m-d')."' AND endDate >= '".date('Y-m-d')."' AND userId=vm_user.userId AND subscriptionStatus ='Active' order by membershipId desc limit 0, 1) as `membershipName` from vm_user where userId = ".$userId;*/
		$query = "SELECT * FROM `vm_subscription_plan` WHERE  subscriptionPlanId = ".$subscriptionPlanId."";
		$subProfileInfo = $this->Common_model->exequery($query,1);
		$this->outputdata['subProfile'] =	($subProfileInfo) ? $subProfileInfo  : array();
		$this->load->viewD($this->sessRole.'/Subscriptions/view',$this->outputdata);
	}
}