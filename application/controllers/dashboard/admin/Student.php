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
	public $subSubMenu	= 0;
	public $outputdata 	= array();
	public $langSuffix 	= '';

	public function __construct() {
		parent::__construct();

		//Check login authentication & set public veriables

		$this->session->set_userdata(PREFIX.'sessRole', "admin");
		$this->common_lib->setSessionVariables();
		$this->langSuffix = $this->lang->line('langSuffix');
		$this->viewPath = 'admin/Student/';
	}	

	// Listing
	public function list() {	
		$this->menu		=	2;
		$this->subMenu	=	22;	
		$this->load->viewD($this->viewPath.'list', $this->outputdata);
	}

	// Add Item
	public function add($itemId = 0) {		
		$this->menu		=	2;
		$this->subMenu	=	21;

		if ($itemId > 0) {
			$userData = $this->Common_model->selRowData("vm_user","*, (case when img != '' then concat('".UPLOADPATH."/user_images/', img) else '' end) as img","userId = ".$itemId);	
			$this->outputdata['userData'] =	$userData;
		}
		$this->load->viewD($this->viewPath.'add_update', $this->outputdata);
	}

	// Detail Page
	public function detail($userId) {		
		if ($userId == 0 )
			redirect(DASHURL.$this->viewPath.'list');
		$this->menu		=	2;
		$this->subMenu	=	22;
		$query	=	"SELECT *, concat(userName, ' ', lastName) as userName from vm_user where userId = ".$userId;
		$profileInfo = $this->Common_model->exequery($query,1);
		$this->outputdata['profile'] =	($profileInfo) ? $profileInfo  : array();

		$this->load->viewD($this->viewPath.'view',$this->outputdata);
	}	

	// user membership view
	public function view_membership($userId = 0) {
		if($userId == 0 )
			redirect(DASHURL.'/admin/user/list');		
		$this->menu		=	2;
		$this->subMenu	=	22;
		$query	=	"SELECT vm_user.* from vm_user where userId = ".$userId;
		$profileInfo = $this->Common_model->exequery($query,1);
		$this->outputdata['profile'] =	($profileInfo) ? $profileInfo  : array();
		$this->load->viewD($this->sessRole.'/user_membership_info',$this->outputdata);
	}
}