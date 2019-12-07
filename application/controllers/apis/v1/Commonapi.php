<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class Commonapi extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['users_post']['limit'] = 500; // 100 requests per hour per user/key
        $this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key


    }


    // about us page
    public function aboutus_get() {
        $about = array();
        $about[0] = 'Are you an after work drink lover and finding it exasperating every day to explore the right bar?.';
        $about[1] = 'Are you also finding it fairly exorbitant to spend money every day in the bar?
.';
        $about[2] = 'THEN STOP WORRYING NOW!.';
        $about[3] = 'As we have come up with a unique concept “VEDMIR”; An app based platform which will not only assist you as a night lover by providing hundreds of 

options listed on our application to choose from, but will also have one drink on us each day.';
        $this->response(['status' => TRUE,'aboutus' => $about], REST_Controller::HTTP_OK);
    }

    // snd enquiry mail to admin
    public function contact_post() {
        $errors = array();
        if(!isset($_POST['email']) || empty($_POST['email']))
            $this->response(['status' => FALSE,'message' => $this->lang->line('emailRequired')], REST_Controller::HTTP_BAD_REQUEST);
        $this->load->library('form_validation'); 
        if(isset($_POST['email']) && !empty($_POST['email'])){
            $this->form_validation->set_rules('email', 'Email', 'valid_email');
            if ($this->form_validation->run() == FALSE)
                $this->response(['status' => FALSE,'message' => $this->lang->line('invalidEmail')], REST_Controller::HTTP_BAD_REQUEST);
        }
        if(!isset($_POST['name']) || empty($_POST['name']))
            $this->response(['status' => FALSE,'message' => $this->lang->line('nameRequired')], REST_Controller::HTTP_BAD_REQUEST);        
        if(!isset($_POST['message']) || empty($_POST['message']))
            $this->response(['status' => FALSE,'message' => $this->lang->line('messageRequired')], REST_Controller::HTTP_BAD_REQUEST);

        if(!empty($errors))
            $this->response(['status' => FALSE,'message' => $this->lang->line('validationFailed'),'errors' => $errors], REST_Controller::HTTP_BAD_REQUEST);

        $queryData   =  array();
        $queryData['name']      =   trim($_POST['name']);
        $queryData['email']     =   trim($_POST['email']);
        $queryData['mobile']    =   (isset($_POST['mobile']))?trim($_POST['mobile']):'';
        $queryData['subject']   =   (isset($_POST['subject']))?trim($_POST['subject']):'New Enquiry From Vedmir';
        $queryData['message']   =   trim($_POST['message']);
        $queryData['addedOn']   =   date('Y-m-d H:i:s');
        $insertStatus       =   $this->Common_model->insertUnique("vm_contact_mail", $queryData);
        if($insertStatus){
                
            $settings = array();
            $settings["template"]               =   "send_enquiry_tpl".$this->lang->line('langSuffix').".html";
            $settings["email"]                  =   "dsmail.sapalee@gmail.com";//'hello@vedmir.com';//ADMINMAIL;
            $settings["subject"]                =   "New Enquiry From Vedmir";
            $contentarr['[[[NAME]]]']           =   trim($_POST['name']);
            $contentarr['[[[EMAIL]]]']          =   trim($_POST['email']);
            $contentarr['[[[SUBJECT]]]']        =   (isset($_POST['subject']))?trim($_POST['subject']):'New Enquiry From Vedmir';
            $contentarr['[[[PHONENUMBER]]]']        =   (isset($_POST['mobile']))?trim($_POST['mobile']):'';
            $contentarr['[[[MESSAGE]]]']        =   trim($_POST['message']);
            $settings["contentarr"]             =   $contentarr;
            $ismailed = $this->common_lib->sendMail($settings); 
            $settings = array();
            $settings["template"]               =   "thank_tpl".$this->lang->line('langSuffix').".html";
            $settings["email"]                  =   $_POST['email'];//USEREMail;
            $langSuffix = $this->lang->line('langSuffix');
           $settings["subject"]                =   ($langSuffix ==  "_fr") ? "Merci de ton intérêt." : "Thank You for interest";             
            $settings["contentarr"]             =   $contentarr;
            $ismailed = $this->common_lib->sendMail($settings);

            $this->response(['status' => TRUE,'message' => $this->lang->line('succesMail')], REST_Controller::HTTP_OK);
            
            
        }else
            $this->response(['status' => false,'message' => $this->lang->line('failedMail')], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        
    }


}
