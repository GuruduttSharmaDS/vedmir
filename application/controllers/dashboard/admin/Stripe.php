<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by Dream Steps Pvt Ltd
 * Created on 30 Nov 2019
 * Vedmir -  module
**/
class Stripe extends CI_Controller {
	
	public $menu		= 0;
	public $subMenu		= 0;
	public $subSubMenu	= 0;
	
	public $outputdata 	= array();
	
	public function __construct(){
		parent::__construct();
		$testmode=0;
		//Check login authentication & set public veriables
		$this->session->set_userdata(PREFIX.'sessRole', "admin");
		$this->common_lib->setSessionVariables();
		$this->load->helper('file');
		$this->langSuffix = $this->lang->line('langSuffix');
		//load config
        $this->load->config('stripe', TRUE);

        //get settings from config
        $this->current_private_key = $this->config->item('current_private_key', 'stripe');
        $this->current_public_key  = $this->config->item('current_public_key', 'stripe');
        $this->testmode  =   ($this->config->item('testmode', 'stripe') == 'on')? 1 :0;

        //initialize the client
        require_once './system/static/stripe/init.php';
        \Stripe\Stripe::setApiKey($this->current_private_key);	
	}
	
	// Vedmir - Admin landing page
	public function history($filterBy = ''){
		$this->load->viewD('admin/stripe_wallet_history_view',$this->outputdata);
		
	}

	// Vedmir - Admin landing page
	public function add_money(){
		// v3print($_POST);exit;
		if (isset($_POST['amount']) && $_POST['amount'] > 0) {

			$transactionId = 0;
			try{
				//$transfer=\Stripe\Balance::retrieve();

				$result = \Stripe\Token::create(
					array(
					"card" => array(
					"name" => $_POST['card_holder_name'],
					"number" => $_POST['card_num'],
					"exp_month" => $_POST['exp_month'],
					"exp_year" => $_POST['exp_year'],
					"cvc" => $_POST['cvc']
						)
					)
				);

				$token = $transactionId = $result['id'];

				$charge = \Stripe\Charge::create(array(
				"amount" => $_POST['amount']*100,
				"currency" => "CHF",
				"card" => $token,
				"description" => "Charge for test@example.com" 
				));
				// $charge = \Stripe\Charge::retrieve($charge->id);
				// $charge->capture();

				$stripe_id=$charge->id;
				$paidamount=($charge->amount/100);
				$status=trim(strtolower($charge->status));
				$issuccess=($status==="succeeded")?"1":"0";
				$transmessage=($status==="succeeded")?"Money added successfully":"Something is wrong";
			} 
			catch(\Stripe\Error\Card $e) {
				$stripe_id="";
				$issuccess="0";
				$paidamount="0";
				$transmessage=$e->getMessage();
			}


			if (!empty($stripe_id)) {
				$isInserted = $this->Common_model->insert("vm_stripe_wallet_history", array('stripeId'=>$stripe_id, 'transactionId'=>$transactionId, 'amount'=>$paidamount,  'message'=>$transmessage, 'addedOn'=>date('Y-m-d H:i:s'), 'updatedOn'=>date('Y-m-d H:i:s') ));
				if($isInserted)
					$this->common_lib->setSessMsg(($transmessage == 'Money added successfully')?'Money added successfully and will available soon in your wallet.':$transmessage, 1);
				else
					$this->common_lib->setSessMsg($transmessage, 2);
			}else
				$this->common_lib->setSessMsg($transmessage, 2);
		}




		$this->load->viewD('admin/stripe_wallet_add_money_view',$this->outputdata);
	}



	
}