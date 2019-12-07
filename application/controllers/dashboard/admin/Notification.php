<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by Dream Steps Pvt Ltd
 * Created on 30 Nov 2019
 * BULLSEYE -  module
**/
class Notification extends CI_Controller {
	
	public $menu		= 12;	
	public $subMenu		= 121;
	public $subSubMenu	= 1211;
	public $outputData 	= array();
	
	public function __construct(){
		parent::__construct();
		//Check login authentication & set public veriables
		$this->session->set_userdata(PREFIX.'sessRole', "admin");
		$this->common_lib->setSessionVariables();
	}
	
	// BULLSEYE - Admin notification page
	public function send(){
		$this->menu = 12;
		$this->subMenu = 121;

		$this->load->viewD('admin/notification_send_view',$this->outputData);		
	}


	public function notification_list(){
		$this->menu = 12;
		$this->subMenu = 122;

		$this->load->viewD('admin/notification_list_view',$this->outputData);		
	}
	
}