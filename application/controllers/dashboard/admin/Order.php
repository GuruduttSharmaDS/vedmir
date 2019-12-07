<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Created by Dream Steps Pvt Ltd
 * Created on 30 Nov 2019
 * Vedmir -  module
**/

class Order extends CI_Controller {

	

	public $menu		= 4;

	public $subMenu		= 41;

	public $subSubMenu	= 0;

	public $outputdata 	= array();

	

	public function __construct(){

		parent::__construct();

		//Check login authentication & set public veriables

		$this->session->set_userdata(PREFIX.'sessRole', "admin");

		$this->common_lib->setSessionVariables();

	}

	



	// order-listing view

	public function daily_drink(){	

		$this->menu		=	4;

		$this->subMenu	=	41;	
	
		$query	=	"SELECT count(dd.id) as totalOrder,
		(SELECT count(id) FROM vm_user_daily_drink where servedStatus = '0' and vm_user_daily_drink.restaurantId = dd.restaurantId) as totalOngoingOrder,
		(SELECT count(id) FROM vm_user_daily_drink where servedStatus = '1' and vm_user_daily_drink.restaurantId = dd.restaurantId) as totalServedOrder,
		(SELECT count(id) FROM vm_user_daily_drink where servedStatus = '2' and vm_user_daily_drink.restaurantId = dd.restaurantId) as totalCancelOrder
		 from vm_user_daily_drink as dd  ";
		$this->outputdata['statisticsData'] =	$this->Common_model->exequery($query,1);


		$query	=	"SELECT od.orderId,
		ur.userName,
		rs.restaurantName,
		(SELECT count(de.detailId) from vm_order_detail as de where de.orderId = od.orderId) as orderCount,
		od.discount,
		od.amt,
		od.addedOn,
		od.orderStatus	from vm_order as od left join vm_restaurant as rs on rs.restaurantId=od.restaurantId  left join vm_user as ur on ur.userId=od.userId where od.userId != 0 order by od.orderId desc";

		$orderData =	$this->Common_model->exequery($query);

			

		$newArr = array();

		if(valResultSet($orderData )) {

			foreach($orderData  as $row) {

				$newArr[] =  array_values(get_object_vars($row));

			}

		}

		$jsonData = json_encode($newArr);

		$this->outputdata['jsonData']	=	$jsonData;		

		$this->load->viewD('admin/order_daily_drink_view', $this->outputdata);

	}	



	// order-listing view

	public function order_detail($orderId = 0){	

		$this->menu		=	4;

		$this->subMenu	=	41;		



		$query	=	"SELECT od.*,

		rs.restaurantName,

		pd.productName,

		pd.img from vm_order_detail as od left join vm_product as pd on pd.productId=od.productId left join vm_restaurant as rs on pd.restaurantId=rs.restaurantId where od.orderId = ".$orderId ." order by od.detailId asc";

		$orderData =	$this->Common_model->exequery($query);

			

		$this->outputdata['orderData']	=	$orderData;		

		$this->load->viewD('admin/order_detail_view', $this->outputdata);

	}	

	

}