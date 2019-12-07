<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class Checkin extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->methods['order_get']['limit'] = 500; // 500 requests per hour per order/key
        $this->methods['order_post']['limit'] = 500; // 100 requests per hour per order/key
        $this->methods['order_delete']['limit'] = 50; // 50 requests per hour per order/key
    }


    // checkin user in restaurant

    public function checkin_post(){

        $token = $this->input->get_request_header('Authorization', TRUE);
        if($token != '' && $tokenRoleId = $this->common_lib->validateToken($token)){
            if (!empty($_POST['roleId']) && $_POST['roleId'] > 0 && !empty($_POST['role'])) {

                $restaurantId = (trim($_POST['role']) == 'user')?$tokenRoleId['roleId']:trim($_POST['roleId']);
                $userId = (trim($_POST['role']) == 'user')?trim($_POST['roleId']):$tokenRoleId['roleId'];
                $checkinData=$this->Common_model->selRowData("vm_checkin","id,restaurantId","isCheckout != 2 and userId=".$userId);

                if (valResultSet($checkinData)) {
                    $msg="Already checked in at another restaurant.";

                    if (trim($_POST['role']) == 'user' && $_POST['roleId'] == $checkinData->restaurantId) {
                        $msg="Already checked in.";          
                    }

                    $this->response(['status' => FALSE,'message' => $msg], REST_Controller::HTTP_CONFLICT);
                }

                $insertCheckinData = array();
                $insertCheckinData['restaurantId']    = $restaurantId;
                $insertCheckinData['userId']          = $userId;
                $insertCheckinData['addedBy']          = (trim($_POST['role']) == 'user')?'restaurant':'user';
                $insertCheckinData['addedOn']         = date("Y-m-d H:i:s");
                $insertCheckinData['updatedOn']       = date("Y-m-d H:i:s");
                $insertCheckinStatus=$this->Common_model->insertUnique("vm_checkin",$insertCheckinData);
                if ($insertCheckinStatus)
                    $this->response([
                        'status' => TRUE,
                        'message' => 'Checkin added'
                    ], REST_Controller::HTTP_OK); 
                else
                {
                    $this->response([
                        'status' => FALSE,
                        'message' => 'Some Internal Error'
                    ], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
                }
            }else{
                $this->response([
                    'status' => FALSE,
                    'message' => 'Role and RoleId required'
                ], REST_Controller::HTTP_FORBIDDEN);
            }

        }else{
            $this->response([
                'status' => FALSE,
                'message' => 'Unauthorized request'
            ], REST_Controller::HTTP_UNAUTHORIZED);
        }
    }

    //Checkout request

    public function checkoutrequest_post(){

        $ischeckout = 0;
        $token = $this->input->get_request_header('Authorization', TRUE);
        if($token != '' && $tokenRoleId = $this->common_lib->validateToken($token)){
            $checkinData=$this->Common_model->selTableData("vm_checkin","id,restaurantId","isCheckout = 0 and userId=".$tokenRoleId['roleId']);
            if (valResultSet($checkinData)) {
                foreach ($checkinData as $checkin) {
                    $updateCheckinData = array();
                    $updateCheckinData['isCheckout']  = 1;
                   $ischeckout = $this->Common_model->update("vm_checkin",$updateCheckinData,"id=".$checkin->id);
                    
                }

                if ($ischeckout)
                    $this->response(['status' => TRUE,'message' => 'Checkout Request Sent'], REST_Controller::HTTP_OK); 
                else
                    $this->response(['status' => FALSE,'message' => 'Some Internal Error'], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);

            }else
                $this->response(['status' => FALSE,'message' => 'Checkin Not Found'], REST_Controller::HTTP_FORBIDDEN);
  
        }else
            $this->response(['status' => FALSE,'message' => 'Unauthorized request'], REST_Controller::HTTP_UNAUTHORIZED);
        
    }
//Checkout confirm from restaurant

    public function checkout_post(){

        $ischeckout = 0;
        $token = $this->input->get_request_header('Authorization', TRUE);
        if($token != '' && $tokenRoleId = $this->common_lib->validateToken($token)){
            if (!empty($_POST['roleId']) && $_POST['roleId'] > 0 ) {

                $checkinData=$this->Common_model->selRowData("vm_checkin","","(isCheckout = 1 or isCheckout = 0) and restaurantId=".$tokenRoleId['roleId']." and userId=".trim($_POST['roleId']));
                if (valResultSet($checkinData)){

                    $this->db->trans_start();
                    $updateCheckinData = array();
                    $updateCheckinData['isCheckout']  = 2;
                    $ischeckout = $this->Common_model->update("vm_checkin",$updateCheckinData,"id=".$checkinData->id);
                    if ($ischeckout){ 
                        $updateOrderData = array();
                        $updateOrderData['status']  = 2;
                        $this->Common_model->update("vm_order",$updateOrderData,"( status  = 0 or status  = 0 ) and userId=".$checkinData->userId." and restaurantId=".$checkinData->restaurantId);
                    }
                    if ($this->db->trans_status() === FALSE){
                        $this->db->trans_rollback();
                        $this->response(['status' => FALSE, 'message' => 'Failed to checkout'], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
                    } else {
                        $this->db->trans_commit();
                        $this->response(['status' => TRUE, 'message' => 'Checkout success'], REST_Controller::HTTP_OK);
                    }
                    
                }else
                    $this->response(['status' => FALSE,'message' => 'Checkin Not Found'."(isCheckout = 1 or isCheckout = 0) and restaurantId=".$tokenRoleId['roleId']." and userId=".trim($_POST['roleId'])], REST_Controller::HTTP_FORBIDDEN);

            }else
                $this->response(['status' => FALSE,'message' => 'RoleId required'], REST_Controller::HTTP_FORBIDDEN);

        }else
            $this->response(['status' => FALSE,'message' => 'Unauthorized request'], REST_Controller::HTTP_UNAUTHORIZED);
    }


    public function getcheckin_get(){
        $token = $this->input->get_request_header('Authorization', TRUE);
        if($token != '' && $roleId = $this->common_lib->validateToken($token)){
            $token= explode(" ", $token); 
            $role=$this->Common_model->getSelectedField("vm_api_token","role","token='".$token[1]."' and roleId=".$roleId['roleId']);
            if ($role == 'restaurant')
             $qry = "SELECT ch.*,rs.restaurantName,us.userName FROM vm_checkin as ch left join vm_restaurant as rs on rs.restaurantId = ch.restaurantId left join vm_user as us on us.userId = ch.userId WHERE ch.restaurantId = ".$roleId['roleId'];
            else
             $qry = "SELECT ch.*,rs.restaurantName,us.userName FROM vm_checkin as ch left join vm_restaurant as rs on rs.restaurantId = ch.restaurantId left join vm_user as us on us.userId = ch.userId WHERE ch.userId = ".$roleId['roleId'];

            $checkinData = $this->Common_model->exequery($qry);
            // v3print($detailData);exit;
            if ($checkinData)
            {
                // Set the response and exit
                $this->response($checkinData, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            }
            else
            {
                // Set the response and exit
                $this->response([
                    'status' => FALSE,
                    'message' => 'No checkin Item were found'
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
