<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Created by Dream Steps Pvt Ltd
 * Created on 30 Nov 2019
 * Vedmir -  module
**/

class Courses extends CI_Controller {

	public $menu		= 5;
	public $subMenu		= 51;
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
		$this->menu		=	5;
		$this->subMenu	=	52;	
		$this->load->viewD($this->viewPath.'list', $this->outputdata);
	}


	public function add($courseId = 0){		
		$this->menu		=	5;
		$this->subMenu	=	51;

		if ($courseId > 0){
			$subscriptionData = $this->Common_model->selRowData("vm_course","*, (case when thumbnailImage != '' then concat('".UPLOADPATH."/course_images/', thumbnailImage) else '' end) as thumbnailImage","courseId = ".$courseId);


			$this->outputdata['coursesData'] =	$subscriptionData;
		}
		$query = "SELECT * from vm_category where status !=2 "; 


			$categoryInfo = $this->Common_model->exequery($query);
			$this->outputdata['categoryData'] =	($categoryInfo) ? $categoryInfo  : array();
			//$this->outputdata['categoryData'] =	$categoryInfo;
		$this->load->viewD($this->viewPath.'/add', $this->outputdata);
	}

	// Detail Page
	public function detail($courseId) {		
		if($courseId == 0 )
			redirect(DASHURL. '/admin/courses/list');

		$this->menu		=	5;
		$this->subMenu	=	53;
		

		$query = "SELECT cau.*, cat.categoryName,cat.categoryId FROM vm_course cau left join vm_category as cat on cat.categoryId= cau.categoryId where cat.categoryId = cau.categoryId and cau.courseId= ".$courseId."";
		
		//$query ="SELECT * FROM `vm_course` where courseId = ".$courseId."";


		$coursesData = $this->Common_model->exequery($query,1);
		$this->outputdata['coursesData'] =	($coursesData) ? $coursesData  : array();
		$this->load->viewD($this->viewPath.'view',$this->outputdata);
	}

}