<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	/**
	 * Payment Mode
	 **/
	$config['testmode']   = 'off';
	/**
	 * Private Live Kay
	 **/
	$config['private_live_key']    = 'vm_live_qSCSDqazcTpxRnCvntRTYonZ';
	/**
	 * public Live Kay
	 **/
	$config['public_live_key']    = 'pk_live_lIvHkShcAlELlkS1NbkZ4wo1';

	/**
	 * Private Live Kay
	 **/
	$config['private_test_key']    = 'vm_test_V762kq4w2HfjA2wh6NsvxIHl';//'vm_test_FU8udNA2bgQeiujX5SWyPU0K';
	/**
	 * public Live Kay
	 **/
	$config['public_test_key']    = 'pk_test_M32wACJPDdMo0uYUK8n3v1sc';//'pk_test_njXCUkc2mXGPJvCa8A0j6YYI';

	/**
	  * current private key
	**/
	$config['current_private_key'] = ($config['testmode'] == 'on') ? $config['private_test_key'] : $config['private_live_key'];

	/**
	  * current public key
	**/
	$config['current_public_key'] = ($config['testmode'] == 'on') ? $config['public_test_key'] : $config['public_live_key'];
	

