<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class Order extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->methods['order_get']['limit'] = 500; // 500 requests per hour per order/key
        $this->methods['order_post']['limit'] = 500; // 100 requests per hour per order/key
        $this->methods['order_delete']['limit'] = 50; // 50 requests per hour per order/key
    }


    // List OF ORDERS

    public function getorders_get(){
        $token = $this->input->get_request_header('Authorization', TRUE);
        if($token != '' && $restaurantId = $this->common_lib->validateToken($token)){
            
            // Orders from a data store e.g. database
            $qry = "SELECT od.*,
             (SELECT us.userName FROM vm_user as us WHERE us.userId= od.userId) as userName,
             (SELECT rs.restaurantName FROM vm_restaurant as rs WHERE rs.restaurantId= od.restaurantId) as restaurantName,
             (SELECT count(de.detailId) FROM vm_order_detail as de WHERE de.orderId= od.orderId) as totalItem 
             from vm_order as od WHERE od.restaurantId = ".$restaurantId['roleId'];
            $orders = $this->Common_model->exequery($qry);

                if ($orders)
                    $this->response($orders, REST_Controller::HTTP_OK); 
                else
                {
                    $this->response([
                        'status' => FALSE,
                        'message' => 'No orders were found'
                    ], REST_Controller::HTTP_FORBIDDEN);
                }

        }else{
            $this->set_response([
                'status' => FALSE,
                'message' => 'Unauthorized request'
            ], REST_Controller::HTTP_UNAUTHORIZED);
        }
    }


    // List OF ORDER WITH DETAILS

    public function getorderdetails_get($id){
        $token = $this->input->get_request_header('Authorization', TRUE);
        if($token != '' && $restaurantId = $this->common_lib->validateToken($token)){
            
            $orderId = $id;
            // Orders from a data store e.g. database
            $qry = "SELECT de.*,
             (SELECT us.userName FROM vm_user as us WHERE us.userId= od.userId) as userName,
             (SELECT pd.productName FROM vm_product as pd WHERE pd.productId= de.productId) as productName 
             from vm_order_detail as de left join vm_order as od on de.orderId= od.orderId WHERE de.orderId = ".$orderId;
            $orders = $this->Common_model->exequery($qry);
            
            
            if ($orders)
            {
                // Set the response and exit
                $this->response($orders, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            }
            else
            {
                // Set the response and exit
                $this->response([
                    'status' => FALSE,
                    'message' => 'No orders were found'
                ], REST_Controller::HTTP_FORBIDDEN); // HTTP_FORBIDDEN (403) being the HTTP response code
            }



        }else{
            $this->set_response([
                'status' => FALSE,
                'message' => 'Unauthorized request'
                ], REST_Controller::HTTP_UNAUTHORIZED); // HTTP_UNAUTHORIZED (401) being the HTTP response code
        }
    }

    public function makeserved_get($detailId){
        $token = $this->input->get_request_header('Authorization', TRUE);
        if($token != '' && $this->common_lib->validateToken($token) && $detailId > 0){

            $updateDetailData = array();
            $updateDetailData['isServed']        = 1;
            $updateStatus=$this->Common_model->update("vm_order_detail",$updateDetailData,"detailId=".$detailId);

            if ($updateStatus)
                $this->response(['status' => TRUE, 'message' => 'Order Updated'], REST_Controller::HTTP_OK);
            else
                $this->response(['status' => FALSE, 'message' => 'Internal Server Error'], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);

        }else{
            $this->set_response([
                'status' => FALSE,
                'message' => 'Unauthorized request'
                ], REST_Controller::HTTP_UNAUTHORIZED); // HTTP_UNAUTHORIZED (401) being the HTTP response code
        }
    }

    public function getserved_get($orderId){
        $token = $this->input->get_request_header('Authorization', TRUE);
        if($token != '' && $this->common_lib->validateToken($token) && $orderId > 0){
              $qry = "SELECT de.*,
             (SELECT us.userName FROM vm_user as us WHERE us.userId= od.userId) as userName,
             (SELECT pd.productName FROM vm_product as pd WHERE pd.productId= de.productId) as productName 
             from vm_order_detail as de left join vm_order as od on de.orderId= od.orderId WHERE de.isServed = 1 and  de.orderId = ".$orderId;
            $servedData = $this->Common_model->exequery($qry);
            // v3print($detailData);exit;
            if (valResultSet($servedData))
            {
                // Set the response and exit
                $this->response($servedData, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            }
            else
            {
                // Set the response and exit
                $this->response([
                    'status' => FALSE,
                    'message' => 'No served Item were found'
                ], REST_Controller::HTTP_FORBIDDEN); // HTTP_FORBIDDEN (403) being the HTTP response code
            }
            

        }else{
            $this->response([
                'status' => FALSE,
                'message' => 'Unauthorized request'
                ], REST_Controller::HTTP_UNAUTHORIZED); // HTTP_UNAUTHORIZED (401) being the HTTP response code
        }
    }



}
