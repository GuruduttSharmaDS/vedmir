<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Created by Dream Steps Pvt Ltd
 * Created on 30 Nov 2019
 * Vedmir -  module
**/

class Category extends CI_Controller {

	public $menu		= 6;
	public $subMenu		= 61;
	public $subSubMenu		= 0;
	public $outputdata 	= array();
	
	public function __construct() {
		parent::__construct();
		//Check login authentication & set public veriables
		$this->session->set_userdata(PREFIX.'sessRole', "admin");
		$this->common_lib->setSessionVariables();
		$this->langSuffix = $this->lang->line('langSuffix');
		$this->viewPath = 'admin/Category/';
	}

	public function add_category($Id = 0) {		
		$this->menu		=	6;
		$this->subMenu	=	61;

		if ($Id > 0)
			$this->outputdata['detailData']  = $this->Common_model->selRowData("vm_category","","categoryId=".$Id);
		$this->outputdata['parentId'] = $this->Common_model->selTableData("vm_category","","categoryId !='".$Id."'");

		$this->load->viewD($this->viewPath.'/add', $this->outputdata);
	}

	// user-listing view
	public function category_list(){	
		$this->menu		=	6;
		$this->subMenu	=	62;	
		$this->load->viewD($this->viewPath.'/list', $this->outputdata);
	}
}