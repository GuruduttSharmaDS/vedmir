<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Created by Dream Steps Pvt Ltd
 * Created on 30 Nov 2019
 * Vedmir -  module
**/

class Staticpage extends CI_Controller {

	public $menu		= 7;
	public $subMenu		= 71;
	public $subSubMenu		= 0;
	public $outputdata 	= array();
	
	public function __construct() {
		parent::__construct();
		//Check login authentication & set public veriables
		$this->session->set_userdata(PREFIX.'sessRole', "admin");
		$this->common_lib->setSessionVariables();
		$this->langSuffix = $this->lang->line('langSuffix');
		$this->viewPath = 'admin/Staticpage/';
	}

	// user-listing view
	public function list(){	
		$this->menu		=	7;
		$this->subMenu	=	72;	
		$this->load->viewD($this->viewPath.'/list', $this->outputdata);
	}

	public function update($id = 0) {	
		$this->menu		=	7;
		$this->subMenu	=	71;
		$query = "SELECT * from vm_staticpage where staticpageId ='".$id."' "; 
			$this->outputdata['detailData'] = $this->Common_model->exequery($query,1);

		$this->load->viewD($this->viewPath.'/update', $this->outputdata);
	}
	
}