
<?php

defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by Dream Steps Pvt Ltd
 * Created on 30 Nov 2019
 * Vedmir -  module
**/
class Rating extends CI_Controller {
	
	public $menu		= 8;
	public $subMenu		= 81;
	public $outputdata 	= array();
	
	public function __construct(){
		parent::__construct();
		//Check login authentication & set public veriables
		$this->session->set_userdata(PREFIX.'sessRole', "admin");
		$this->common_lib->setSessionVariables();
		//	echo $this->sessName;

		//load config
        $this->load->config('stripe', TRUE);

        //get settings from config
        
	}

	


	// restaurant event list  view
	public function review_list($restaurantId = 0){
		$this->menu = 8;
		$this->subMenu = 81;
		
		$this->load->viewD($this->sessRole.'/rating_list_view',$this->outputdata);
	}
}