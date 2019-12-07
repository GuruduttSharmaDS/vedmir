<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**

 * Created by Dream Steps Pvt Ltd

 * Created on 30 Nov 2019

 * Vedmir -  module

**/

class Commonajax extends CI_Controller {



	public $outputdata 	= array();

	public $langSuffix = '';

	

	public function __construct(){

		parent::__construct();

		//Check login authentication & set public veriables

		$this->session->set_userdata(PREFIX.'sessRole', "admin");

		$this->common_lib->setSessionVariables();

		$this->langSuffix = $this->lang->line('langSuffix');

	}

	

	// Vedmir - Ajax landing page

	public function index(){

		$action='';

		$action=$_POST['action'];

		//$data=$this->sanitize($_POST);

		if($action=="Change_Status"){

			$return['valid']=$this->change_status($_POST);

		}

		else if($action=="Delete_Record"){

			$return['valid']=$this->delete_record($_POST);

		}

		else if($action=="Get_Product_Category_List"){

			$return=$this->product_category_list();

			

		}

		else if($action=="Get_Restaurant_Category_List"){

			$return=$this->restaurant_category_list();

		}

		else if($action=="Get_Product_Subcategory_List"){

			$return=$this->product_subcategory_list();

			

		}

		else if($action=="Get_Product_Subcategoryitem_List"){

			$return=$this->product_subcategoryitem_list();

		}

		else if($action=="getProductSubcategory"){

			$return['optionData']=$this->getProductSubcategory();

			$return['valid']=true;

		}


		else if($action=="getProductSubcategoryitem"){

			$return['optionData']=$this->getProductSubcategoryitem();

			$return['valid']=true;

		}

		else if($action=="Get_Product_List"){

			$return=$this->product_list();

		}

		else if($action=="Get_Restaurant_List"){

			$return=$this->restaurant_list();			

		}

		else if($action=="Get_Blocked_Restaurant_List"){

			$return=$this->blocked_restaurant_list();			

		}

		else if($action=="Get_New_Restaurant_List"){

			$return=$this->new_restaurant_list();

		}

		else if($action=="Get_User_List"){

			$return=$this->user_list();

		}

		else if($action=="Get_Blocked_User_List"){

			$return=$this->Get_Blocked_User_List();

		}

		else if($action=="Get_Enquiry_List"){

			$return=$this->enquiry_list();

		}

		else if($action=="Get_Blog_List"){

			$return=$this->blog_list();

		}

		else if($action=="Get_Blog_Category_List"){

			$return=$this->blog_category_list();

		}

		else if($action=="Get_Inactive_Comment_List"){

			$return=$this->inactive_comment_list();

		}

		else if($action=="Get_Order_List"){

			$return=$this->order_list();

		}

		else if( $action == "getAdminNotification" ){

			$return=$this->getAdminNotification();

			$return['valid']=true;

		}

		else if( $action == "add_plan" ){

			$return=$this->addEventPlan($_POST);

			$return['valid']=true;

		}

		else if($action=="Get_Event_List"){

			$return=$this->GetEventList();

		}

		else if($action=="Get_Subscription_List"){

			$return=$this->GetSubscriptionList();

		}

		else if($action=="UserRatingList"){

			$return=$this->GetUserRatingList();

		}

		else if($action=="Get_Product_Variable_List"){

			$return=$this->product_variable_list();

			$return['valid']=true;

		}

		else if($action=="uploadstripedocument")

			$return=$this->common_lib->uploadstripedocument($_POST['restaurantId'],$_POST,$_FILES);

		else if($action=="deletestripbankaccount")

			$return=$this->common_lib->deletestripbankaccount($_POST['restaurantId']);

		else if($action=="Get_Restaurant_Payout_List"){

			$restaurantId=(isset($_POST['restaurantId']) && !empty($_POST['restaurantId']))?$_POST['restaurantId']:0;

			$return=$this->restaurant_payout_list($restaurantId);

		}
		else if($action=="getHappyhourProductData")
			$return=$this->getHappyhourProductData();		
		else if($action=="Get_Happyhour_List")
			$return=$this->Get_Happyhour_List();		
		else if($action=="Get_WalletHistory_List")
			$return=$this->Get_WalletHistory_List();
		else if($action=="addCoupon")
			$return=$this->addCoupon($_POST);
		else if($action=="Get_Coupon_List")
			$return=$this->GetCouponList();
		else if($action=="Get_Bartender_List")
			$return=$this->GetBartenderList();
		else if($action=="sendpayountmail")
			$return=$this->sendpayountmail();
		else if($action=="Get_Image_Category_List")
			$return=$this->image_category_list();
		else if($action=="Get_Image_Subcategory_List")
			$return=$this->image_subcategory_list();
		else if($action=="getImageCategory"){
			$return['optionData']=$this->getImageCategory();
			$return['valid']=true;
		}
		else if($action=="getImageSubcategory"){
			$return['optionData']=$this->getImageSubcategory();
			$return['valid']=true;
		}
		else if($action=="Get_MenuCanvas_Category_List")
			$return=$this->menuCanvas_category_list();
		else if($action=="Get_MenuCanvas_Subcategory_List")
			$return=$this->menuCanvas_subcategory_list();
		else if($action=="getCanvasCategory"){
			$return['optionData']=$this->getCanvasCategory();
			$return['valid']=true;
		}
		else if($action=="Get_Image_List")
			$return=$this->image_list();	
		else if($action=="getFilterdImages")
			$return=$this->getFilterdImages();
		else if($action=="sendMassNotification")
			$return=$this->sendMassNotification();
        else if($action=="massNotificationlist")
            $return=$this->massNotificationlist();
        else if($action == "getMembershipPlan")
        	 $return=$this->getMembershipPlan($_POST);
       	else if( $action == "getCouponDetails" )
       		 $return=$this->getCouponDetails($_POST['couponId']);
   		else if($action=="getUserCouponList")
			$return=$this->getUserCouponList($_POST);
		else if($action=="getUserOrderList")
			$return=$this->getUserOrderList($_POST);
        /*--------------------------- Update Stripe Business Name----------------------*/
       	else if($action == "updateBusinessName") 
       		$return = $this->updateBusinessName($_POST);
       	/*--------------------------- Ambassador Section ------------------------------*/
       	else if($action == "user_as_ambassdor")
       		$return = $this->userAsAmbassdor($_POST);
       	else if($action == "setUpAmbassadorAccount")
       		$return = $this->setUpAmbassadorAccount($_POST);
       	else if($action == "Get_ambassador_plan_List")
       		$return = $this->GetAmbasssadorPlanList($_POST);
       	else if($action == "getConvertedUserList")
       		$return = $this->getConvertedUserList(@$_POST['ambassadorId']);
       /*--------------------------- get user membership log -----------------*/
       else if($action == "getUserMembershipLog")
            $return = $this->getUserMembershipLog($_POST);
		$this->output->set_content_type('application/json')->set_output(json_encode($return));



	}



	// admin change status of records

	public function change_status($data){

		

	$tab=$data['tab'];

	$id=$data['id'];

	$act=$data['status'];

	$updateStatus =0;

	if($tab=='restaurant' || $tab=='user' || $tab=='bartender' || $tab=='image'){

		$updateStatus = $this->Common_model->update("vm_".$tab, array('status' => $act), $tab."Id =".$id);
		if($updateStatus){
			$cond = ($tab == 'bartender')?"role IN ('drink', 'food','both') and roleId =".$id:"role = '".$tab."' and roleId =".$id;
			$this->Common_model->update("vm_auth", array('status' => $act), $cond);
		}

	}
    else if($tab == 'image_category' ) 
	    $updateStatus = $this->Common_model->update("vm_".$tab, array('status' => $act), "categoryId =".$id);
	
	else if($tab == 'image_subcategory') 
	    $updateStatus = $this->Common_model->update("vm_".$tab, array('status' => $act), "subcategoryId =".$id);
	else if($tab == 'canvas_category' ) 
	    $updateStatus = $this->Common_model->update("vm_".$tab, array('status' => $act), "categoryId =".$id);
	
	else if($tab == 'canvas_subcategory') 
	    $updateStatus = $this->Common_model->update("vm_".$tab, array('status' => $act), "subcategoryId =".$id);
	else if($tab=='blog' ||  $tab=='happyhour')

		$updateStatus = $this->Common_model->update("vm_".$tab, array('status' => $act), $tab."Id =".$id);

	else if($tab=='product') {

		$this->common_lib->reArrangeProductOrder($id); 

		$deleteStatus = $this->Common_model->update("vm_".$tab, array('status' => 2),"productId =".$id." and restaurantId =".$this->sessRoleId);

	}

	else if($tab=='blog_category'|| $tab=='product_category'|| $tab=='restaurant_category')

		$updateStatus = $this->Common_model->update("vm_".$tab, array('status' => $act), "categoryId =".$id);

	else if($tab=='product_subcategory')

		$updateStatus = $this->Common_model->update("vm_".$tab, array('status' => $act), "subcategoryId =".$id);

	else if($tab=='product_subcategoryitem')

		$updateStatus = $this->Common_model->update("vm_".$tab, array('status' => $act), "subcategoryitemId =".$id);

	else if($tab=='coupons')

		$updateStatus = $this->Common_model->update("vm_".$tab, array('status' => $act), "couponId =".$id);

	else if($tab=='variable_product'){

		$updateStatus = $this->Common_model->update("vm_".$tab, array('status' => $act), "variableId =".$id);

		$this->updateProductType($id);

	}

	else if($tab=='blog_comment')

		$updateStatus = $this->Common_model->update("vm_".$tab, array('status' => $act), "commentId =".$id);

	else if($tab=='resturant_rating')

		$updateStatus = $this->Common_model->update("vm_".$tab, array('status' => $act), "ratingId =".$id);

	else if($tab=='subscription_plan')

		$updateStatus = $this->Common_model->update("vm_".$tab, array('status' => $act), "Id =".$id);

	else if($tab=='subscription_plan')

		$updateStatus = $this->Common_model->update("vm_".$tab, array('status' => $act), "id =".$id);

	else if($tab=='auth')

		$updateStatus = $this->activateRestaurant($id);
	else if($tab=='product_stock')
		$updateStatus = $this->Common_model->update("vm_product", array('isStockAvailable' => $act), "productId =".$id);



	return $updateStatus;



	}

	// admin delete of records

	public function delete_record($data){

		

	$tab=$data['tab'];

	$id=$data['id'];
	$deleteStatus = 0;
	if($tab=='restaurant' || $tab=='user' ||  $tab=='bartender' ||  $tab=='image'){
       
		$deleteStatus = $this->Common_model->update("vm_".$tab, array('status' => 2), $tab."Id =".$id);
		if($deleteStatus){
			$cond = ($tab == 'bartender')?"role IN ('drink', 'food','both') and roleId =".$id:"role = '".$tab."' and roleId =".$id;
			$this->Common_model->update("vm_auth", array('status' => '2'), $cond);
		}

	}
	else if($tab == 'image_category' ) 
	    $deleteStatus = $this->Common_model->update("vm_".$tab, array('status' => 2), "categoryId =".$id);
	
	else if($tab == 'image_subcategory') 
	    $deleteStatus = $this->Common_model->update("vm_".$tab, array('status' => 2), "subcategoryId =".$id);
	else if($tab == 'canvas_category' ) 
	    $deleteStatus = $this->Common_model->update("vm_".$tab, array('status' => 2), "categoryId =".$id);
	
	else if($tab == 'canvas_subcategory') 
	    $deleteStatus = $this->Common_model->update("vm_".$tab, array('status' => 2), "subcategoryId =".$id);
	else if($tab=='product' || $tab=='blog' ||  $tab=='happyhour')

		$deleteStatus = $this->Common_model->update("vm_".$tab, array('status' => 2), $tab."Id =".$id);

	else if($tab=='blog_category'|| $tab=='product_category'|| $tab=='restaurant_category')

		$deleteStatus = $this->Common_model->update("vm_".$tab, array('status' => 2),"categoryId =".$id);

	else if($tab=='product_subcategory')

		$deleteStatus = $this->Common_model->update("vm_".$tab, array('status' => 2),"subcategoryId =".$id);

	else if($tab=='product_subcategoryitem')

		$deleteStatus = $this->Common_model->update("vm_".$tab, array('status' => 2),"subcategoryitemId =".$id);
	else if($tab=='coupons')

		$deleteStatus = $this->Common_model->update("vm_".$tab, array('status' => 2),"couponId =".$id);

	else if($tab=='variable_product'){

		$deleteStatus = $this->Common_model->update("vm_".$tab, array('status' => 2),"variableId =".$id);

		$this->updateProductType($id);

	}

	else if($tab=='blog_comment')

		$deleteStatus = $this->Common_model->update("vm_".$tab, array('status' => 2),"commentId =".$id);

	else if($tab=='resturant_rating')

		$deleteStatus = $this->Common_model->del("vm_".$tab, "ratingId =".$id);

	else if($tab=='new_restaurant')

		$deleteStatus = $this->deleteRestaurant($id);

	else if($tab=='delete_user')

		$deleteStatus = $this->deleteUser($id);
	else if($tab=='product_addons_category')
		$deleteStatus = $this->Common_model->update("vm_".$tab, array('status' => 2),"addonsCatId =".$id);



	return $deleteStatus;



	}



	// update restaurant as activated

	public function activateRestaurant($restaurantId){

		$updateStatus= false;

		$restaurantData = $this->Common_model->selRowData("vm_restaurant","email","restaurantId =".$restaurantId);

		if (isset($restaurantData->email) && !empty($restaurantData->email)) {

			$updateStatus = $this->Common_model->update("vm_auth", array('status' => 0), "role = 'restaurant'  and roleId =".$restaurantId);

			if ($updateStatus) {

				$updateData = array('status' => 0);

				$insertStripeData = array();

				try{

					$accData = \Stripe\Account::create([

						"country" => "CH",

						"type" => "custom",

						"payout_schedule"=> [ 

							"delay_days"=> 7,

							"interval"=> "monthly",

							"monthly_anchor" => 30,

						],

						"tos_acceptance" => [

							"date" => time(),

							"ip" => $_SERVER['REMOTE_ADDR'],

						],

					]);

				}catch (\Exception $e) {

					$settings = array();

	                $settings["template"]               =   "message_default_tpl.html";

	                $settings["email"]                  =   ADMINMAIL;

	                $settings["subject"]                =   "Error in VEDMIR";

	                $contentarr['[[[TITLE]]]']          =   'STRIPE error with '.$restaurantData->email;

	                $contentarr['[[[MESSAGE]]]']       	=   $e->getMessage();

	                $contentarr['[[[MESSAGEBOLD]]]']    =   'Please contact to developer.';

	                $settings["contentarr"]             =   $contentarr;

	                $this->common_lib->sendMail($settings);

				 }



				if (isset($accData->id) && !empty($accData->id)) {

					$updateData['stripeAccId'] = $accData->id;

					$insertStripeData['restaurantId'] = $restaurantId;

					$insertStripeData['stripeAccId'] = $accData->id;

					$insertStripeData['payout_schedule_delay_days'] = $accData->payout_schedule->delay_days;

					$insertStripeData['payout_schedule_interval'] = $accData->payout_schedule->interval;

					$insertStripeData['tos_acceptance_date'] = $accData->tos_acceptance->date;

					$insertStripeData['tos_acceptance_ip'] = $accData->tos_acceptance->ip;

					$insertStripeData['addedOn'] = date('Y-m-d H:i:s');

					$insertStripeData['updatedOn'] = date('Y-m-d H:i:s');

				}



				$isUpdated = $this->Common_model->update("vm_restaurant", $updateData, "restaurantId =".$restaurantId);

				if(!empty($insertStripeData) && $isUpdated)

					$this->Common_model->insert("vm_restaurant_stripe_details", $insertStripeData);



                $settings = array();

                $settings["template"]               =   "restaurant_activated_tpl.html";

                $settings["email"]                  =   $restaurantData->email;

                $settings["subject"]                =   "Welcome to Vedmir";

                $contentarr['[[[ROLE]]]']           =  	'Restaurant';

                $contentarr['[[[USERNAME]]]']       =   $restaurantData->email;

                $contentarr['[[[LOGINURL]]]']       =   BASEURL."/dashboard/restaurant/login";

                $settings["contentarr"]             =   $contentarr;

                $this->common_lib->sendMail($settings);

			}		

		}

		return $updateStatus;



	}



	// delete restaurant

	public function deleteRestaurant($restaurantId){



		$updateStatus = $this->Common_model->del("vm_auth", "role = 'restaurant'  and roleId =".$restaurantId);



		if ($updateStatus)

			$this->Common_model->del("vm_restaurant", "restaurantId =".$restaurantId);		

		

		return $updateStatus;



	}

	// delete user

	public function deleteUser($userId){



		$updateStatus = $this->Common_model->del("vm_auth", "role = 'user'  and roleId =".$userId);



		if ($updateStatus)

			$this->Common_model->del("vm_user", "userId =".$userId);		

		

		return $updateStatus;



	}





	// update product as simple or variable

	public function updateProductType($variableId){



		$variableData = $this->Common_model->selRowData("vm_variable_product","productId,price","variableId =".$variableId);

		if (isset($variableData->productId) && $variableData->productId > 0) {

			

			$checkData =	$this->Common_model->exequery("SELECT variableId from vm_variable_product where status = 0 and productId=".$variableData->productId,1);

			if (valResultSet($checkData)) 

				$this->Common_model->update("vm_product", array('productType' => 1), "productType = 0  and productId =".$variableData->productId);

			else

				$this->Common_model->update("vm_product", array('productType' => 0,'price' => $variableData->price), "productType = 1  and productId =".$variableData->productId);			

		}



	}



	// admin product category list view

	public function product_category_list(){



		$columns = array( 0 => "categoryName$this->langSuffix",1 => 'addedOn', 2 => 'status', 3 => 'categoryId');



        $limit = $this->input->post('length');

        $start = $this->input->post('start');

        $order = $columns[$this->input->post('order')[0]['column']];

        $dir = $this->input->post('order')[0]['dir'];



        $cond = " order by $order $dir LIMIT $start, $limit ";

  

        $totalDataCount = $this->Common_model->exequery("SELECT count(*) as total from vm_product_category ",1);

        $totalData = (isset($totalDataCount->total)  && $totalDataCount->total > 0)?$totalDataCount->total:0;

            

        $totalFiltered = $totalData; 

        $qry = "SELECT categoryId, categoryName$this->langSuffix as categoryName,addedOn,case when status='0' then 'Active' else 'DeActive' end as status, case when status='0' then 'act fa fa-circle' else 'dct fa fa-circle' end as class from vm_product_category where status != 2 "; 

        if(empty($this->input->post('search')['value']))

        {            

            $queryData = $this->Common_model->exequery($qry.$cond);

        }else {

            $search = $this->input->post('search')['value']; 

            if (!empty($search)) {             	

            	$search = str_replace("'", '', $search); 

            	$search = str_replace('"', '', $search); 

             }

            $searchCond = " AND (categoryName$this->langSuffix LIKE  '%".$search."%' OR addedOn LIKE  '%".$search."%' OR status LIKE  '%".$search."%'  ) ";

            $cond = $searchCond.$cond;

            $queryData = $this->Common_model->exequery($qry.$cond);



            $totalDataCount = $this->Common_model->exequery("SELECT count(*) as total from vm_product_category where categoryId > 0 ".$searchCond,1);

            $totalFiltered = (isset($totalDataCount->total)  && $totalDataCount->total > 0)?$totalDataCount->total:0;

        }

        $data = array();

        if(!empty($queryData))

        {

            foreach ($queryData as $row)

            {

                $nestedData['name'] = $row->categoryName;

                $nestedData['currency'] = $row->addedOn;

                $nestedData['type'] = $this->lang->line($category->status);

                $nestedData['action'] = '<a href="'.DASHURL.'/admin/product/add-category/'.$category->categoryId .'"  class="btn btn-warning btn-rounded btn-sm  "><i class="fa fa-pencil"></i></a><button onclick="ActivateDeActivateThisRecord(this,\'product_category\','.$category->categoryId.');" class="btn btn-danger btn-rounded btn-sm active " title="Active/DeActive" data-status="'.$category->status.'"><span class="'.$category->class.'"></span></button></button><button onclick="delete_row(this,\'product_category\','.$category->categoryId.');" class="btn btn-danger btn-rounded btn-sm del" title="Delete"><span class="fa fa-trash-o"></span></button>';

                $data[] = $nestedData;



            }

        }

          

        return $json_data = array("draw" => intval($this->input->post('draw')), "recordsTotal"    => intval($totalData), "recordsFiltered" => intval($totalFiltered), "data" => $data );



	}



	// admin product sub category list view

	public function product_subcategory_list(){



		$columns = array( 0 => "rs.restaurantName$this->langSuffix", 1 => "ps.subcategoryName$this->langSuffix", 2 => "pc.categoryName$this->langSuffix",3 => 'ps.addedOn', 4 => 'ps.status', 5 => 'subcategoryId');



        $limit = $this->input->post('length');

        $start = $this->input->post('start');

        $order = $columns[$this->input->post('order')[0]['column']];

        $dir = $this->input->post('order')[0]['dir'];



        $cond = " order by $order $dir LIMIT $start, $limit ";

  

        $totalDataCount = $this->Common_model->exequery("SELECT count(ps.subcategoryId) as total from vm_product_subcategory as ps left join vm_product_category as pc on pc.categoryId = ps.categoryId left join vm_restaurant as rs on rs.restaurantId = ps.restaurantId where ps.status != 2 ",1);

        $totalData = (isset($totalDataCount->total)  && $totalDataCount->total > 0)?$totalDataCount->total:0;

            

        $totalFiltered = $totalData; 

        $qry = "SELECT ps.subcategoryId, pc.categoryName$this->langSuffix as categoryName,ps.subcategoryName$this->langSuffix as subcategoryName,ps.addedOn, restaurantName$this->langSuffix as restaurantName, case when ps.status='0' then 'Active' else 'DeActive' end as status, case when ps.status='0' then 'act fa fa-circle' else 'dct fa fa-circle' end as class from vm_product_subcategory as ps left join vm_product_category as pc on pc.categoryId = ps.categoryId left join vm_restaurant as rs on rs.restaurantId = ps.restaurantId where ps.status != 2 "; 

        if(empty($this->input->post('search')['value']))

        {            

            $queryData = $this->Common_model->exequery($qry.$cond);

        }else {

            $search = $this->input->post('search')['value'];

            if (!empty($search)) {             	

            	$search = str_replace("'", '', $search); 

            	$search = str_replace('"', '', $search); 

             } 

            $searchCond = " AND (rs.restaurantName$this->langSuffix LIKE  '%".$search."%' OR ps.subcategoryName$this->langSuffix LIKE  '%".$search."%' OR pc.categoryName$this->langSuffix LIKE  '%".$search."%' OR ps.addedOn LIKE  '%".$search."%' OR ps.status LIKE  '%".$search."%'  ) ";

            $cond = $searchCond.$cond;

            $queryData = $this->Common_model->exequery($qry.$cond);



            $totalDataCount = $this->Common_model->exequery("SELECT count(ps.subcategoryId) as total from vm_product_subcategory as ps left join vm_product_category as pc on pc.categoryId = ps.categoryId left join vm_restaurant as rs on rs.restaurantId = ps.restaurantId where ps.status != 2 ".$searchCond,1);

            $totalFiltered = (isset($totalDataCount->total)  && $totalDataCount->total > 0)?$totalDataCount->total:0;

        }

        $data = array();

        if(!empty($queryData))

        {

            foreach ($queryData as $row)

            {

                $nestedData['restaurantName'] = $row->restaurantName;

                $nestedData['subcategoryName'] = $row->subcategoryName;

                $nestedData['categoryName'] = $row->categoryName;

                $nestedData['addedOn'] = $row->addedOn;

                $nestedData['status'] = $this->lang->line($row->status);

                $nestedData['action'] = '<a href="'.DASHURL.'/admin/product/add-subcategory/'.$row->subcategoryId .'"  class="btn btn-warning btn-rounded btn-sm  "><i class="fa fa-pencil"></i></a><button onclick="ActivateDeActivateThisRecord(this,\'product_subcategory\','.$row->subcategoryId.');" class="btn btn-danger btn-rounded btn-sm active " title="Active/DeActive" data-status="'.$row->status.'"><span class="'.$row->class.'"></span></button></button><button onclick="delete_row(this,\'product_subcategory\','.$row->subcategoryId.');" class="btn btn-danger btn-rounded btn-sm del" title="Delete"><span class="fa fa-trash-o"></span></button>';

                $data[] = $nestedData;



            }

        }

          

        return $json_data = array("draw" => intval($this->input->post('draw')), "recordsTotal"    => intval($totalData), "recordsFiltered" => intval($totalFiltered), "data" => $data );



		

	}



	// admin product sub category item list view

	public function product_subcategoryitem_list(){



		$columns = array( 0 => "rs.restaurantName$this->langSuffix", 1 => "si.subcategoryitemName$this->langSuffix", 2 => "ps.subcategoryName$this->langSuffix", 3 => "pc.categoryName$this->langSuffix",4 => 'ps.addedOn', 5 => 'ps.status', 6 => 'subcategoryitemId');



        $limit = $this->input->post('length');

        $start = $this->input->post('start');

        $order = $columns[$this->input->post('order')[0]['column']];

        $dir = $this->input->post('order')[0]['dir'];



        $cond = " order by $order $dir LIMIT $start, $limit ";

  

        $totalDataCount = $this->Common_model->exequery("SELECT count(si.subcategoryitemId) as total from vm_product_subcategoryitem as si left join vm_product_subcategory as ps on ps.subcategoryId = si.subcategoryId left join vm_product_category as pc on pc.categoryId = si.categoryId left join vm_restaurant as rs on rs.restaurantId = si.restaurantId  where si.status != 2",1);

        $totalData = (isset($totalDataCount->total)  && $totalDataCount->total > 0)?$totalDataCount->total:0;

            

        $totalFiltered = $totalData; 

        $qry = "SELECT si.subcategoryitemId, pc.categoryName$this->langSuffix as categoryName, ps.subcategoryName$this->langSuffix as subcategoryName,si.subcategoryitemName$this->langSuffix as subcategoryitemName,si.addedOn,  rs.restaurantName$this->langSuffix as restaurantName, case when si.status='0' then 'Active' else 'DeActive' end as status, case when si.status='0' then 'act fa fa-circle' else 'dct fa fa-circle' end as class from vm_product_subcategoryitem as si left join vm_product_subcategory as ps on ps.subcategoryId = si.subcategoryId left join vm_product_category as pc on pc.categoryId = si.categoryId left join vm_restaurant as rs on rs.restaurantId = si.restaurantId  where si.status != 2 "; 

        if(empty($this->input->post('search')['value']))

        {            

            $queryData = $this->Common_model->exequery($qry.$cond);

        }else {

            $search = $this->input->post('search')['value'];

            if (!empty($search)) {             	

            	$search = str_replace("'", '', $search); 

            	$search = str_replace('"', '', $search); 

             } 

            $searchCond = " AND (rs.restaurantName$this->langSuffix LIKE  '%".$search."%' OR si.subcategoryitemName$this->langSuffix LIKE  '%".$search."%' OR ps.subcategoryName$this->langSuffix LIKE  '%".$search."%' OR pc.categoryName$this->langSuffix LIKE  '%".$search."%' OR si.addedOn LIKE  '%".$search."%' OR si.status LIKE  '%".$search."%'  ) ";

            $cond = $searchCond.$cond;

            $queryData = $this->Common_model->exequery($qry.$cond);



            $totalDataCount = $this->Common_model->exequery("SELECT count(si.subcategoryitemId) as total from vm_product_subcategoryitem as si left join vm_product_subcategory as ps on ps.subcategoryId = si.subcategoryId left join vm_product_category as pc on pc.categoryId = si.categoryId left join vm_restaurant as rs on rs.restaurantId = si.restaurantId  where si.status != 2  ".$searchCond,1);

            $totalFiltered = (isset($totalDataCount->total)  && $totalDataCount->total > 0)?$totalDataCount->total:0;

        }

        $data = array();

        if(!empty($queryData))

        {

            foreach ($queryData as $row)

            {

                $nestedData['restaurantName'] = $row->restaurantName;

                $nestedData['subcategoryitemName'] = $row->subcategoryitemName;

                $nestedData['subcategoryName'] = $row->subcategoryName;

                $nestedData['categoryName'] = $row->categoryName;

                $nestedData['addedOn'] = $row->addedOn;

                $nestedData['status'] = $this->lang->line($row->status);

                $nestedData['action'] = '<a href="'.DASHURL.'/admin/product/add-subcategoryitem/'.$row->subcategoryitemId .'"  class="btn btn-warning btn-rounded btn-sm  "><i class="fa fa-pencil"></i></a><button onclick="ActivateDeActivateThisRecord(this,\'product_subcategoryitem\','.$row->subcategoryitemId.');" class="btn btn-danger btn-rounded btn-sm active " title="Active/DeActive" data-status="'.$row->status.'"><span class="'.$row->class.'"></span></button></button><button onclick="delete_row(this,\'product_subcategoryitem\','.$row->subcategoryitemId.');" class="btn btn-danger btn-rounded btn-sm del" title="Delete"><span class="fa fa-trash-o"></span></button>';

                $data[] = $nestedData;



            }

        }

          

        return $json_data = array("draw" => intval($this->input->post('draw')), "recordsTotal"    => intval($totalData), "recordsFiltered" => intval($totalFiltered), "data" => $data );



		

	}

	public function product_subcategoryitem_list_($pagesize,$pageno){

		$startp=$pageno>0?($pageno)*$pagesize:$pageno;

		$endp=$pagesize;

		$subcategoryitemData =array();

		$subcategoryitemData['id'] ='';

		$query	=	"SELECT si.subcategoryitemId,

		 pc.categoryName$this->langSuffix as categoryName,

		 ps.subcategoryName$this->langSuffix as subcategoryName,si.subcategoryitemName$this->langSuffix as subcategoryitemName,si.addedOn, (SELECT restaurantName$this->langSuffix from vm_restaurant where vm_restaurant.restaurantId = si.restaurantId) as restaurantName, case when si.status='0' then 'Active' else 'DeActive' end as status,

			case when si.status='0' then 'act fa fa-circle' else 'dct fa fa-circle' end as class from vm_product_subcategoryitem as si left join vm_product_subcategory as ps on ps.subcategoryId = si.subcategoryId left join vm_product_category as pc on pc.categoryId = si.categoryId where si.status != 2 order by si.subcategoryitemId desc limit $startp,$endp ";

		$subcategoryitems =	$this->Common_model->exequery($query);

		if (valResultSet($subcategoryitems)) {

			foreach ($subcategoryitems as $subcategoryitem) {

				$subcategoryitemData['id'] .='<tr><td>'.$subcategoryitem->restaurantName.'</td><td>'.$subcategoryitem->subcategoryitemName.'</td><td>'.$subcategoryitem->subcategoryName.'</td><td>'.$subcategoryitem->categoryName.'</td><td>'.$subcategoryitem->addedOn.'</td><td>'.$this->lang->line($subcategoryitem->status).'</td><td><a href="'.DASHURL.'/admin/product/add-subcategoryitem/'.$subcategoryitem->subcategoryitemId .'"  class="btn btn-warning btn-rounded btn-sm  "><i class="fa fa-pencil"></i></a><button onclick="ActivateDeActivateThisRecord(this,\'product_subcategoryitem\','.$subcategoryitem->subcategoryitemId.');" class="btn btn-danger btn-rounded btn-sm active " title="Active/DeActive" data-status="'.$subcategoryitem->status.'"><span class="'.$subcategoryitem->class.'"></span></button></button><button onclick="delete_row(this,\'product_subcategoryitem\','.$subcategoryitem->subcategoryitemId.');" class="btn btn-danger btn-rounded btn-sm del" title="Delete"><span class="fa fa-trash-o"></span></button></td></tr>';

			}

		}else{



			$subcategoryitemData['id'] .='<tr style="text-align:center;font-size:18px;" ><td colspan="8" class="alert-danger">Subcategory Item Not Found</td></tr>';

		}

		$query	=	"SELECT count(*) as count from vm_product_subcategoryitem where status != 2 order by subcategoryitemId desc";

		$coundata =	$this->Common_model->exequery($query,1);

		$subcategoryitemData['count'] = $coundata->count;

		return $subcategoryitemData;

	}

	

	//get Product SubCategory for selected Category

	public function getProductSubcategory(){

		$condition 		= 	"categoryId = '".$_POST["selCategoryId"]."' and status = 0 and restaurantId =".$_POST['restaurantId'];

		$resultdata		= 	$this->Common_model->selTableData(PREFIX."product_subcategory","subcategoryId,subcategoryName$this->langSuffix as subcategoryName",$condition,"","","","subcategoryName");

		$dd_options =	'<option value="">'.$this->lang->line('selectCategory').'</option>';

		if(valResultSet($resultdata)){

			foreach($resultdata as $rs)			

				$dd_options .= '<option value="'.$rs->subcategoryId.'" >'.$rs->subcategoryName.'</option>';

		}

		return $dd_options;

	}	

	//get Product SubCategoryitem for selected subcategory

	public function getProductSubcategoryitem(){

		$condition 		= 	"categoryId = '".$_POST["selCategoryId"]."' and subcategoryId = '".$_POST["selSubcategoryId"]."' and  status = 0";

		$resultdata		= 	$this->Common_model->selTableData(PREFIX."product_subcategoryitem","subcategoryitemId,subcategoryitemName$this->langSuffix as subcategoryitemName",$condition,"","","","subcategoryitemName");

		$dd_options =	'<option value="">'.$this->lang->line('selectSubCategoryLabel').'</option>';

		if(valResultSet($resultdata)){

			foreach($resultdata as $rs){

			

				$dd_options .= '<option value="'.$rs->subcategoryitemId.'" >'.$rs->subcategoryitemName.'</option>';

			}

		}

		return $dd_options;

	}



	// admin product list view



	// admin product list view
	public function product_list(){


		$columns = array( 0 => "pd.productId", 1 => "pd.productName$this->langSuffix", 2 => "pd.price", 3 => "pd.sortDescription$this->langSuffix",4 => "rs.restaurantName$this->langSuffix", 5 => "pc.categoryName$this->langSuffix", 6 => "ps.subcategoryName$this->langSuffix", 7 => "psi.subcategoryitemName$this->langSuffix", 8 => "pd.status", 9 => "pd.productId");

        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $order = $columns[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];

        $cond = " order by $order $dir LIMIT $start, $limit ";
        $filterBy = '?order='.$this->input->post('order')[0]['column'].'&dir='.$dir.'&start='.$start.'&limit='.$limit.'&search='.$this->input->post('search')['value'].'';
  
        $totalDataCount = $this->Common_model->exequery("SELECT count(pd.productId) as total from vm_product as pd left join vm_product_subcategory as ps on ps.subcategoryId = pd.subcategoryId left join vm_product_category as pc on pc.categoryId = pd.categoryId left join vm_restaurant as rs on rs.restaurantId = pd.restaurantId where pd.status != 2",1);
        $totalData = (isset($totalDataCount->total)  && $totalDataCount->total > 0)?$totalDataCount->total:0;
            
        $totalFiltered = $totalData; 
        $qry = "SELECT pd.productId, rs.restaurantName$this->langSuffix as restaurantName, pd.categoryId, pc.categoryName$this->langSuffix as categoryName, ps.subcategoryName$this->langSuffix as subcategoryName, psi.subcategoryitemName$this->langSuffix as subcategoryitemName, pd.productName$this->langSuffix as productName, (CASE WHEN productType = 1 THEN (SELECT GROUP_CONCAT(CONCAT(' ',vp.price,' (',vp.variableName$this->langSuffix  ,')') SEPARATOR  ',<br>') as price FROM vm_variable_product as vp WHERE vp.productId = pd.productId AND vp.status=0 ) ELSE pd.price END) as price, pd.sortDescription$this->langSuffix as sortDescription, pd.isStockAvailable, pd.addedOn, (CASE WHEN pd.img REGEXP ('^[0-9]+$') THEN (SELECT (CASE WHEN im.image != '' THEN CONCAT('".UPLOADPATH."','/vedmir_images/',im.image) else '' end ) FROM vm_image as im WHERE im.status = 0 and im.image != '' and im.imageId= pd.img) WHEN pd.img != '' THEN CONCAT('".UPLOADPATH."','/product_images/',pd.img) else '' end ) as img, case when pd.status='0' then 'Active' else 'DeActive' end as status, case when pd.status='0' then 'act fa fa-circle' else 'dct fa fa-circle' end as class, case when pd.isStockAvailable='1' then 'stock act fa fa-cubes' else 'stock dct fa fa-cubes' end as availableClass	from vm_product as pd left join vm_product_subcategoryitem as psi on psi.subcategoryitemId = pd.subcategoryitemId left join vm_product_subcategory as ps on ps.subcategoryId = pd.subcategoryId left join vm_product_category as pc on pc.categoryId = pd.categoryId left join vm_restaurant as rs on rs.restaurantId = pd.restaurantId where pd.status != 2"; 
        if(empty($this->input->post('search')['value']))
        {            
            $queryData = $this->Common_model->exequery($qry.$cond);
        }else {
            $search = $this->input->post('search')['value']; 
            if (!empty($search)) {             	
            	$search = str_replace("'", '', $search); 
            	$search = str_replace('"', '', $search); 
             }
            $searchCond = " AND (rs.restaurantName$this->langSuffix LIKE  '%".$search."%' OR pd.productName$this->langSuffix LIKE  '%".$search."%' OR psi.subcategoryitemName$this->langSuffix LIKE  '%".$search."%' OR ps.subcategoryName$this->langSuffix LIKE  '%".$search."%' OR pc.categoryName$this->langSuffix LIKE  '%".$search."%' OR pd.price LIKE  '%".$search."%' OR pd.sortDescription LIKE  '%".$search."%' OR pd.addedOn LIKE  '%".$search."%' OR pd.status LIKE  '%".$search."%'  ) ";
            $cond = $searchCond.$cond;
            $queryData = $this->Common_model->exequery($qry.$cond);

            $totalDataCount = $this->Common_model->exequery("SELECT count(pd.productId) as total from vm_product as pd left join vm_product_subcategoryitem as psi on psi.subcategoryitemId = pd.subcategoryitemId left join vm_product_subcategory as ps on ps.subcategoryId = pd.subcategoryId left join vm_product_category as pc on pc.categoryId = pd.categoryId left join vm_restaurant as rs on rs.restaurantId = pd.restaurantId where pd.status != 2  ".$searchCond,1);
            $totalFiltered = (isset($totalDataCount->total)  && $totalDataCount->total > 0)?$totalDataCount->total:0;
        }
        $data = array();
        if(!empty($queryData))
        {
            foreach ($queryData as $row)
            {	$img = (!empty($row->img))?$row->img:DASHSTATIC.'/restaurant/assets/img/product.png'; 
        		$btn = ($row->categoryId != 5)?'<a href="'.DASHURL.'/admin/product/addons/'.$row->productId .'"  class="btn btn-success btn-rounded btn-sm" title="Add/Update Addons"><i class="fa fa-plus"></i></a>':'';

                $nestedData['image'] = '<img src="'.$img.'" width="30px" height="30px">';
                $nestedData['productName'] = $row->productName;
                $nestedData['price'] = $row->price;
                $nestedData['description'] = $row->sortDescription;
                $nestedData['restaurantName'] = $row->restaurantName;
                $nestedData['categoryName'] = $row->categoryName;
                $nestedData['subcategoryName'] = $row->subcategoryName;
                $nestedData['subcategoryitemName'] = $row->subcategoryitemName;
                $nestedData['status'] = $this->lang->line($row->status);
                $nestedData['action'] = '<a href="'.DASHURL.'/admin/product/add-product/'.$row->productId .$filterBy.'" class="btn btn-warning btn-rounded btn-sm"><i class="fa fa-pencil" title="Edit"></i></a><a href="'.DASHURL.'/admin/product/view-product/'.$row->productId .'"  class="btn btn-primary btn-rounded btn-sm" title="View"><i class="fa fa-eye"></i></a><a href="'.DASHURL.'/admin/product/copy-product/'.$row->productId .'" class="btn btn-default btn-rounded btn-sm"><i class="fa fa-files-o" title="Copy"></i></a>'.$btn.'<button onclick="ActivateDeActivateThisRecord(this,\'product\','.$row->productId.');" class="btn btn-danger btn-rounded btn-sm active " title="Active/DeActive" data-status="'.$row->status.'"><span class="'.$row->class.'"></span></button><button onclick="delete_row(this,\'product\','.$row->productId.');" class="btn btn-danger btn-rounded btn-sm del" title="Delete"><span class="fa fa-trash-o"></span></button><button onclick="AvalabilityThisRecord(this,\'product_stock\','.$row->isStockAvailable.','.$row->productId.');" class="btn btn-info btn-rounded btn-sm active " title="In Stock/Out of Stock"><span class="'.$row->availableClass.'"></span></button>';
                $data[] = $nestedData;

            }
        }
          
        return $json_data = array("draw" => intval($this->input->post('draw')), "recordsTotal"    => intval($totalData), "recordsFiltered" => intval($totalFiltered), "data" => $data );


	}







	// restaurant product variable list view

	public function product_variable_list(){

		$productData =array();

		$productData['id'] ='';

		$productId = (isset($_POST['productId']) && is_numeric($_POST['productId']) && $_POST['productId'] > 0)?trim($_POST['productId']):0;



		$query	=	"SELECT variableId,variableName,variableName_fr,variableName_gr,variableName_it,

		variableName$this->langSuffix as name,price,case when status='0' then 'Active' else 'DeActive' end as status,

			case when status='0' then 'act fa fa-circle' else 'dct fa fa-circle' end as class from vm_variable_product where status != 2 and productId = ".$productId." order by variableId desc";

		$variables =	$this->Common_model->exequery($query);

		if (valResultSet($variables)) {

			foreach ($variables as $variable) {

				

				$productData['id'] .='<tr><td data-en="'.$variable->variableName.'" data-fr="'.$variable->variableName_fr.'" data-gr="'.$variable->variableName_gr.'" data-it="'.$variable->variableName_it.'">'.$variable->name.'</td><td>'.$variable->price.'</td><td>'.$this->lang->line($variable->status).'</td><td><a href="#" class="btn btn-warning btn-rounded btn-sm"  onclick="editProductVariable(this,event,'.$variable->variableId.');"><i class="fa fa-pencil"></i></a><a onclick="ActivateDeActivateThisRecord(this,\'variable_product\','.$variable->variableId.');" class="btn btn-danger btn-rounded btn-sm active " title="Active/DeActive" data-status="'.$variable->status.'"><span class="'.$variable->class.'"></span></a><a onclick="delete_row(this,\'variable_product\','.$variable->variableId.');" class="btn btn-danger btn-rounded btn-sm del" title="Delete"><span class="fa fa-trash-o"></span></a></td></tr>';

			}

		}else{



			$productData['id'] .='<tr style="text-align:center;font-size:18px;" ><td colspan="4" class="alert-danger">'.$this->lang->line('variableNotFound').'</td></tr>';

		}

		return $productData;

	}



	



	// admin restaurant category list view

	public function restaurant_category_list(){





		$columns = array( 0 => "categoryName$this->langSuffix", 1 => "addedOn", 2 => "status", 3 => "categoryId");



        $limit = $this->input->post('length');

        $start = $this->input->post('start');

        $order = $columns[$this->input->post('order')[0]['column']];

        $dir = $this->input->post('order')[0]['dir'];



        $cond = " order by $order $dir LIMIT $start, $limit ";

  

        $totalDataCount = $this->Common_model->exequery("SELECT count(*) as total from vm_restaurant_category where status != 2",1);

        $totalData = (isset($totalDataCount->total)  && $totalDataCount->total > 0)?$totalDataCount->total:0;

            

        $totalFiltered = $totalData; 

        $qry = "SELECT categoryId, categoryName$this->langSuffix as categoryName,addedOn,case when status='0' then 'Active' else 'DeActive' end as status, case when status='0' then 'act fa fa-circle' else 'dct fa fa-circle' end as class from vm_restaurant_category where status != 2"; 

        if(empty($this->input->post('search')['value']))

        {            

            $queryData = $this->Common_model->exequery($qry.$cond);

        }else {

            $search = $this->input->post('search')['value'];

            if (!empty($search)) {             	

            	$search = str_replace("'", '', $search); 

            	$search = str_replace('"', '', $search); 

             } 

            $searchCond = " AND (categoryName$this->langSuffix LIKE  '%".$search."%' OR addedOn LIKE  '%".$search."%' OR status LIKE  '%".$search."%' ) ";

            $cond = $searchCond.$cond;

            $queryData = $this->Common_model->exequery($qry.$cond);



            $totalDataCount = $this->Common_model->exequery("SELECT count(*) as total from vm_restaurant_category where status != 2".$searchCond,1);

            $totalFiltered = (isset($totalDataCount->total)  && $totalDataCount->total > 0)?$totalDataCount->total:0;

        }

        $data = array();

        if(!empty($queryData))

        {

            foreach ($queryData as $row)

            {	

                $nestedData['categoryName'] = $row->categoryName;

                $nestedData['addedOn'] = $row->addedOn;

                $nestedData['status'] = $this->lang->line($row->status);

                $nestedData['action'] = '<a href="'.DASHURL.'/admin/restaurant/add-category/'.$row->categoryId .'"  class="btn btn-warning btn-rounded btn-sm  "><i class="fa fa-pencil"></i></a><button onclick="ActivateDeActivateThisRecord(this,\'restaurant_category\','.$row->categoryId.');" class="btn btn-danger btn-rounded btn-sm active " title="Active/DeActive" data-status="'.$row->status.'"><span class="'.$row->class.'"></span></button></button><button onclick="delete_row(this,\'restaurant_category\','.$row->categoryId.');" class="btn btn-danger btn-rounded btn-sm del" title="Delete"><span class="fa fa-trash-o"></span></button>';

                $data[] = $nestedData;



            }

        }

          

        return $json_data = array("draw" => intval($this->input->post('draw')), "recordsTotal"    => intval($totalData), "recordsFiltered" => intval($totalFiltered), "data" => $data );



	}



	// admin restaurant list view

	public function restaurant_list(){





		$columns = array( 0 => "restaurantId", 1 => "restaurantName$this->langSuffix", 2 => "city$this->langSuffix", 3 => "addedOn", 4 => "restaurantId", 5 => "vm_restaurant.status", 6 => "restaurantId");



        $limit = $this->input->post('length');

        $start = $this->input->post('start');

        $order = $columns[$this->input->post('order')[0]['column']];

        $dir = $this->input->post('order')[0]['dir'];



        $cond = " order by $order $dir LIMIT $start, $limit ";

  

        $totalDataCount = $this->Common_model->exequery("SELECT count(*) as total from vm_restaurant where status != 2",1);

        $totalData = (isset($totalDataCount->total)  && $totalDataCount->total > 0)?$totalDataCount->total:0;

            

        $totalFiltered = $totalData; 

        if(empty($this->input->post('search')['value']))

        {            

            $queryData = $this->Common_model->exequery("SELECT * FROM (SELECT restaurantId, logo as img, restaurantName$this->langSuffix as restaurantName, city$this->langSuffix as city,(SELECT COUNT(*) FROM `vm_order_detail` WHERE isServed ='1' AND isFree = '1' and productId IN (SELECT productId from vm_product WHERE restaurantId = vm_restaurant.restaurantId) ) as totalFreeDrink, addedOn,case when vm_restaurant.status='0' then 'Active' else 'DeActive' end as status, case when vm_restaurant.status='0' then 'act fa fa-circle' else 'dct fa fa-circle' end as class,(Select legal_entity_verification_status from vm_restaurant_stripe_details where restaurantId=vm_restaurant.restaurantId) as stripeStatus,(Select business_name from vm_restaurant_stripe_details where restaurantId=vm_restaurant.restaurantId) as businessName from vm_restaurant where vm_restaurant.status != 2 ".$cond.") as t");

        }else {

            $search = $this->input->post('search')['value']; 

            if (!empty($search)) {             	

            	$search = str_replace("'", '', $search); 

            	$search = str_replace('"', '', $search); 

             }

            $searchCond = " AND (restaurantName$this->langSuffix LIKE  '%".$search."%' OR city$this->langSuffix LIKE  '%".$search."%' OR addedOn LIKE  '%".$search."%' OR vm_restaurant.status LIKE  '%".$search."%' ) ";

            $cond = $searchCond.$cond;

            $queryData = $this->Common_model->exequery("SELECT * FROM (SELECT restaurantId, logo as img, restaurantName$this->langSuffix as restaurantName, city$this->langSuffix as city,(SELECT COUNT(*) FROM `vm_order_detail` WHERE isServed ='1' AND isFree = '1' and productId IN (SELECT productId from vm_product WHERE restaurantId = vm_restaurant.restaurantId) ) as totalFreeDrink, addedOn, case when vm_restaurant.status='0' then 'Active' else 'DeActive' end as status, case when vm_restaurant.status='0' then 'act fa fa-circle' else 'dct fa fa-circle' end as class,(Select legal_entity_verification_status from vm_restaurant_stripe_details where restaurantId=vm_restaurant.restaurantId) as stripeStatus,(Select business_name from vm_restaurant_stripe_details where restaurantId=vm_restaurant.restaurantId) as businessName from vm_restaurant where vm_restaurant.status != 2 ".$cond.") as t");



            $totalDataCount = $this->Common_model->exequery("SELECT count(*) as total from vm_restaurant where status != 2".$searchCond,1);

            $totalFiltered = (isset($totalDataCount->total)  && $totalDataCount->total > 0)?$totalDataCount->total:0;

        }

        $data = array();

        if(!empty($queryData))

        {

            foreach ($queryData as $row)

            {	$img = (!empty($row->img))?UPLOADPATH.'/restaurant_images/'.$row->img:DASHSTATIC.'/restaurant/assets/img/restaurant.jpg';

        		$stripeClass=(($row->stripeStatus=='verified')?'btn-success':(($row->stripeStatus=='unverified')?'btn-danger active':(($row->stripeStatus=='pending')?'btn-warning active':'btn-danger active')));

        		$stripeTitle=(($row->stripeStatus=='verified')?'Stripe Status Verfied':(($row->stripeStatus=='unverified')?'Stripe Status Unverified':(($row->stripeStatus=='pending')?'Stripe Status Pending':'Stripe Status Unverified')));

                $nestedData['image'] = '<img src="'.$img.'" width="100px" height="100px" style="border-radius: 10px;">';

                $nestedData['restaurantName'] = $row->restaurantName;

                $nestedData['city'] = $row->city;

                $nestedData['totalFreeDrink'] = $row->totalFreeDrink;

                $nestedData['addedOn'] = $row->addedOn;

                $nestedData['status'] = $this->lang->line($row->status);

                $nestedData['action'] = '<a href="'.DASHURL.'/admin/restaurant/add-restaurant/'.$row->restaurantId .'"  class="btn btn-warning btn-rounded btn-sm  " title="Edit"><i class="fa fa-pencil"></i></a><a href="'.DASHURL.'/admin/restaurant/view-restaurant/'.$row->restaurantId .'"  class="btn btn-primary btn-rounded btn-sm  " title="View Restaurant"><i class="fa fa-eye"></i></a><a href="'.DASHURL.'/admin/event/event-list/'.$row->restaurantId .'"  class="btn btn-success btn-rounded btn-sm" title="View Events"><i class="fa fa-calendar"></i></a><a href="'.DASHURL.'/admin/restaurant/stripe-details/'.$row->restaurantId .'"  class="btn '.$stripeClass.' btn-rounded btn-sm" title="'.$stripeTitle.'">Stripe</a><a href="'.DASHURL.'/admin/restaurant/export/'.$row->restaurantId .'"  class="btn btn-info btn-rounded btn-sm" title="Export Order Summery"><span class="fa fa-download"></span></a><a href="'.DASHURL.'/admin/restaurant/payouts/'.$row->restaurantId .'"  class="btn btn-success btn-rounded btn-sm" title="View Last Month Payouts"><i class="fa fa-money"></i></a><a href="javascript:"  class="btn btn-primary btn-rounded btn-sm send-payout" title="Send Last Month Payouts" onclick="sendpayountmail(this, event, '.$row->restaurantId.')"><i class="fa fa-paper-plane-o"></i></a><a href="javascript:"  class="btn btn-primary btn-rounded btn-sm update-bussinessname" title="Update Business Name" onclick="updateBusinessName(this, event, '.$row->restaurantId.', \''.$row->businessName.'\')"><i class="fa fa-briefcase"></i></a><button onclick="ActivateDeActivateThisRecord(this,\'restaurant\','.$row->restaurantId.');" class="btn btn-danger btn-rounded btn-sm active " title="Active/DeActive" data-status="'.$row->status.'"><span class="'.$row->class.'"></span></button></button><button onclick="delete_row(this,\'restaurant\','.$row->restaurantId.');" class="btn btn-danger btn-rounded btn-sm del" title="Delete"><span class="fa fa-times-circle-o"></span></button>';

                //<button onclick="delete_row(this,\'new_restaurant\','.$row->restaurantId.');" class="btn btn-danger btn-rounded btn-sm del" title="Delete"><span class="fa fa-trash-o"></span></button>

                $data[] = $nestedData;



            }

        }

          

        return $json_data = array("draw" => intval($this->input->post('draw')), "recordsTotal"    => intval($totalData), "recordsFiltered" => intval($totalFiltered), "data" => $data );







	}





	// admin blocked restaurant list view

	public function blocked_restaurant_list(){





		$columns = array( 0 => "restaurantId", 1 => "restaurantName$this->langSuffix", 2 => "city$this->langSuffix", 3 => "addedOn", 4 => "restaurantId", 5 => "vm_restaurant.status", 6 => "restaurantId");



        $limit = $this->input->post('length');

        $start = $this->input->post('start');

        $order = $columns[$this->input->post('order')[0]['column']];

        $dir = $this->input->post('order')[0]['dir'];



        $cond = " order by $order $dir LIMIT $start, $limit ";

  

        $totalDataCount = $this->Common_model->exequery("SELECT count(*) as total from vm_restaurant where status = 2",1);

        $totalData = (isset($totalDataCount->total)  && $totalDataCount->total > 0)?$totalDataCount->total:0;

            

        $totalFiltered = $totalData; 

        if(empty($this->input->post('search')['value']))

        {            

            $queryData = $this->Common_model->exequery("SELECT * FROM (SELECT restaurantId, logo as img, restaurantName$this->langSuffix as restaurantName, city$this->langSuffix as city,(SELECT COUNT(*) FROM `vm_order_detail` WHERE isServed ='1' AND isFree = '1' and productId IN (SELECT productId from vm_product WHERE restaurantId = vm_restaurant.restaurantId) ) as totalFreeDrink, addedOn,case when vm_restaurant.status='0' then 'Active' else 'blocked' end as status, case when vm_restaurant.status='0' then 'act fa fa-circle' else 'dct fa fa-circle' end as class from vm_restaurant where vm_restaurant.status = 2 ".$cond.") as t");

        }else {

            $search = $this->input->post('search')['value']; 

            if (!empty($search)) {             	

            	$search = str_replace("'", '', $search); 

            	$search = str_replace('"', '', $search); 

             }

            $searchCond = " AND (restaurantName$this->langSuffix LIKE  '%".$search."%' OR city$this->langSuffix LIKE  '%".$search."%' OR addedOn LIKE  '%".$search."%' OR vm_restaurant.status LIKE  '%".$search."%' ) ";

            $cond = $searchCond.$cond;

            $queryData = $this->Common_model->exequery("SELECT * FROM (SELECT restaurantId, logo as img, restaurantName$this->langSuffix as restaurantName, city$this->langSuffix as city,(SELECT COUNT(*) FROM `vm_order_detail` WHERE isServed ='1' AND isFree = '1' and productId IN (SELECT productId from vm_product WHERE restaurantId = vm_restaurant.restaurantId) ) as totalFreeDrink, addedOn, case when vm_restaurant.status='0' then 'Active' else 'blocked' end as status, case when vm_restaurant.status='0' then 'act fa fa-circle' else 'dct fa fa-circle' end as class from vm_restaurant where vm_restaurant.status = 2 ".$cond.") as t");



            $totalDataCount = $this->Common_model->exequery("SELECT count(*) as total from vm_restaurant where status != 2".$searchCond,1);

            $totalFiltered = (isset($totalDataCount->total)  && $totalDataCount->total > 0)?$totalDataCount->total:0;

        }

        $data = array();

        if(!empty($queryData))

        {

            foreach ($queryData as $row)

            {	$img = (!empty($row->img))?UPLOADPATH.'/restaurant_images/'.$row->img:DASHSTATIC.'/restaurant/assets/img/restaurant.jpg';

                $nestedData['image'] = '<img src="'.$img.'" width="100px" height="100px" style="border-radius: 10px;">';

                $nestedData['restaurantName'] = $row->restaurantName;

                $nestedData['city'] = $row->city;

                $nestedData['totalFreeDrink'] = $row->totalFreeDrink;

                $nestedData['addedOn'] = $row->addedOn;

                $nestedData['status'] = $this->lang->line($row->status);

                $nestedData['action'] = '<a href="'.DASHURL.'/admin/restaurant/view-restaurant/'.$row->restaurantId .'"  class="btn btn-primary btn-rounded btn-sm  " title="View Restaurant"><i class="fa fa-eye"></i></a><button onclick="unblock_row(this,\'restaurant\','.$row->restaurantId.');" class="btn btn-danger btn-rounded btn-sm del" title="Delete"><span class="fa fa-times-circle-o"></span></button>';

                // <button onclick="delete_row(this,\'new_restaurant\','.$row->restaurantId.');" class="btn btn-danger btn-rounded btn-sm del" title="Delete"><span class="fa fa-trash-o"></span></button>

                $data[] = $nestedData;



            }

        }

          

        return $json_data = array("draw" => intval($this->input->post('draw')), "recordsTotal"    => intval($totalData), "recordsFiltered" => intval($totalFiltered), "data" => $data );







	}



		// admin restaurant list view

	public function new_restaurant_list(){







		$columns = array( 0 => "restaurantName$this->langSuffix", 1 => "email", 2 => "mobile", 3 => "state", 4 => "country", 5 => "addedOn", 6 => "vm_restaurant.status", 7 => "restaurantId");



        $limit = $this->input->post('length');

        $start = $this->input->post('start');

        $order = $columns[$this->input->post('order')[0]['column']];

        $dir = $this->input->post('order')[0]['dir'];



        $cond = " order by $order $dir LIMIT $start, $limit ";

  

        $totalDataCount = $this->Common_model->exequery("SELECT count(restaurantId) as total from vm_auth INNER JOIN vm_restaurant on restaurantId = roleId where vm_auth.status = 1",1);

        $totalData = (isset($totalDataCount->total)  && $totalDataCount->total > 0)?$totalDataCount->total:0;

            

        $totalFiltered = $totalData; 

        $qry = "SELECT restaurantId, email, mobile, restaurantName$this->langSuffix as restaurantName, state$this->langSuffix as state, country$this->langSuffix as country, addedOn,case when vm_restaurant.status='0' then 'Active' else 'DeActive' end as status, case when vm_restaurant.status='0' then 'act fa fa-check' else 'dct fa fa-check' end as class from vm_auth INNER JOIN vm_restaurant on restaurantId = roleId where vm_auth.status = 1"; 

        if(empty($this->input->post('search')['value']))

        {            

            $queryData = $this->Common_model->exequery($qry.$cond);

        }else {

            $search = $this->input->post('search')['value'];

            if (!empty($search)) {             	

            	$search = str_replace("'", '', $search); 

            	$search = str_replace('"', '', $search); 

             } 

            $searchCond = " AND (restaurantName$this->langSuffix LIKE  '%".$search."%' OR email LIKE  '%".$search."%' OR mobile LIKE  '%".$search."%' OR state LIKE  '%".$search."%' OR country LIKE  '%".$search."%' OR addedOn LIKE  '%".$search."%' OR vm_auth.status LIKE  '%".$search."%' ) ";

            $cond = $searchCond.$cond;

            $queryData = $this->Common_model->exequery($qry.$cond);



            $totalDataCount = $this->Common_model->exequery("SELECT count(restaurantId) as total from vm_auth INNER JOIN vm_restaurant on restaurantId = roleId where vm_auth.status = 1 ".$searchCond,1);

            $totalFiltered = (isset($totalDataCount->total)  && $totalDataCount->total > 0)?$totalDataCount->total:0;

        }

        $data = array();

        if(!empty($queryData))

        {

            foreach ($queryData as $row)

            {	

                $nestedData['restaurantName'] = $row->restaurantName;

                $nestedData['email'] = $row->email;

                $nestedData['mobile'] = $row->mobile;

                $nestedData['state'] = $row->state;

                $nestedData['country'] = $row->country;

                $nestedData['addedOn'] = $row->addedOn;

                $nestedData['status'] = $this->lang->line($row->status);

                $nestedData['action'] = '<button onclick="ActivateDeActivateThisRecord(this,\'auth\','.$row->restaurantId.');" class="btn btn-warning btn-rounded btn-sm active " title="Active/DeActive" data-status="'.$row->status.'"><span class="'.$row->class.'"></span></button><button onclick="delete_row(this,\'new_restaurant\','.$row->restaurantId.');" class="btn btn-danger btn-rounded btn-sm del" title="Delete"><span class="fa fa-trash-o"></span></button>';

                $data[] = $nestedData;



            }

        }

          

        return $json_data = array("draw" => intval($this->input->post('draw')), "recordsTotal"    => intval($totalData), "recordsFiltered" => intval($totalFiltered), "data" => $data );







	}



	// admin user list view

	public function user_list(){

		$columns = array( 0 => "vm_user.userId", 1 => "vm_user.userId", 2 => "userName", 3 => "email", 4 => "membershipName", 5 => "membership", 6 => "platform", 7 => "vm_user.addedOn", 8 => "vm_user.status", 9 => "userId");



        $limit = $this->input->post('length');

        $start = $this->input->post('start');

        $order = $columns[$this->input->post('order')[0]['column']];

        $dir = $this->input->post('order')[0]['dir'];



        $cond = " order by $order $dir LIMIT $start, $limit ";

  

        $totalDataCount = $this->Common_model->exequery("SELECT count(userId) as total from vm_user where status != 2",1);

        $totalData = (isset($totalDataCount->total)  && $totalDataCount->total > 0)?$totalDataCount->total:0;

            

        $totalFiltered = $totalData; 

        $qry = "SELECT vm_user.userId, vm_user.img, (CASE WHEN vm_user.platform != '' then vm_user.platform else 'Unknown' end) as platform ,concat(vm_user.userName, ' ', vm_user.lastName) as userName, vm_user.email, vm_user.country, vm_user.addedOn,vm_user.status,(SELECT (CASE WHEN count(*) > 0 then (CASE WHEN isUpdatedPlan = 1 THEN ( CASE WHEN couponId != 0 THEN ( SELECT (CASE WHEN offeredType = 3 THEN CONCAT('Coupon - ', couponCode) ELSE (CONCAT('Coupon - ', couponCode, ' - ', (SELECT CONCAT(sp.planName$this->langSuffix, ' (',sd.period,' ',sd.duration,')') as planName FROM vm_subscription_details sd left JOIN vm_subscription_plan as sp on sp.Id = sd.subscriptionId WHERE detailId =vm_user_memberships.planId))) end) as planName FROM vm_coupons WHERE couponId = vm_user_memberships.couponId  ) ELSE (SELECT CONCAT(sp.planName$this->langSuffix, ' (',sd.period,' ',sd.duration,')') as planName FROM vm_subscription_details sd left JOIN vm_subscription_plan as sp on sp.Id = sd.subscriptionId WHERE detailId =vm_user_memberships.planId) end)  ELSE (SELECT planName as planName FROM `vm_subscription_plan` WHERE vm_subscription_plan.id = vm_user_memberships.planId) END ) else '' end) as membership FROM vm_user_memberships WHERE startDate <= '".date('Y-m-d')."' AND endDate >= '".date('Y-m-d')."' AND userId=vm_user.userId AND subscriptionStatus ='Active' order by membershipId desc limit 0, 1) as `membershipName`, (SELECT (CASE WHEN count(*) > 0 then 'Active' else 'Not Active' end) as membership_count FROM vm_user_memberships WHERE startDate <= '".date('Y-m-d')."' AND endDate >= '".date('Y-m-d')."' AND userId=vm_user.userId AND subscriptionStatus ='Active' order by membershipId desc limit 0, 1) as `membership`, case when vm_user.status='0' then 'Active' else 'DeActive' end as status,case when vm_user.status='0' then 'act fa fa-circle' else 'dct fa fa-circle' end as class,vm_ambassador_user.status as ambassadorStatus, vm_ambassador_user.ibanNumber as ibanNumber, vm_ambassador_user.ambassadorId as ambassadorCount, vm_ambassador_user.isMasterAmbassador as isMasterAmbassador from vm_user left join vm_ambassador_user on vm_user.userId = vm_ambassador_user.userId where vm_user.status != 2"; 

        if(empty($this->input->post('search')['value']))

        {            

            $queryData = $this->Common_model->exequery($qry.$cond);

        }else {

            $search = $this->input->post('search')['value'];

            if (!empty($search)) {             	

            	$search = str_replace("'", '', $search); 

            	$search = str_replace('"', '', $search); 

             } 

            $searchCond = " AND (userName LIKE  '%".$search."%' OR email LIKE  '%".$search."%' OR lastName LIKE  '%".$search."%' OR country LIKE  '%".$search."%' OR vm_user.addedOn LIKE  '%".$search."%' OR vm_user.status LIKE  '%".$search."%' ) ";

            $cond = $searchCond.$cond;

            $queryData = $this->Common_model->exequery($qry.$cond);



            $totalDataCount = $this->Common_model->exequery("SELECT count(userId) as total from vm_user where status != 2 ".$searchCond,1);

            $totalFiltered = (isset($totalDataCount->total)  && $totalDataCount->total > 0)?$totalDataCount->total:0;

        }

        $data = array();

        if(!empty($queryData))

        {

            foreach ($queryData as $row)

            {	$img = (!empty($row->img))?UPLOADPATH.'/user_images/'.$row->img:DASHSTATIC.'/restaurant/assets/img/user.png';
        
        		$nestedData['count'] = ++$start;
                $nestedData['image'] = '<img src="'.$img.'" width="30px" height="30px">';
                $row->ambassadorStatus = ($row->ambassadorStatus == '' || is_null($row->ambassadorStatus)) ? 1 : $row->ambassadorStatus; 
                $ambassadorLink = '';
                $ambassadorIBNNumber = '';
                $row->ambassadorCount = (!is_null($row->ambassadorCount)) ? 1 : 0;
                if( $row->ambassadorCount > 0 ) {
                	if($row->ambassadorStatus == 0 )
                		$ambassadorLink = ($row->isMasterAmbassador == 1) ? '<p class="ambassador">Master Ambassador</p>' : '<p class="ambassador">Ambassador</p>';
                	else
                		$ambassadorLink = '<p class="ambassador-not">Ambassador</p>';
                	$ibanNumber = ($row->ibanNumber == '' || is_null($row->ibanNumber )) ? '' : $row->ibanNumber;
                	$ambassadorIBNNumber = '<button onclick="updateBankDetails(this,'.$row->userId.');" class="btn btn-primary btn-rounded btn-sm updateBankDetails" data-ibnnumber="'.$ibanNumber.'" data-username="'.$row->userName.'" title="Setup Bank Details"><span class="fa fa-university" aria-hidden="true"></span></button><a href="'.DASHURL.'/admin/user/view-ambassador/'.$row->userId .'" class="btn btn-success btn-rounded btn-sm" title="View Ambassador Details"><span class="fa fa-user" aria-hidden="true"></span></a>';
                }
                if( $row->ambassadorStatus == 0 ) {
                	$masterString = "Mark As Master Ambassador";
                	$masterStatus = 0;
                	$ambaString = "Mark As Ambassador";
                	$ambastatus = 0;
                	if($row->isMasterAmbassador == 1) {
                		$masterString = "Un Mark As Master Ambassador";
                		$masterStatus = 1;
                	}
                	else {
                		$ambaString = "Un Mark As Ambassador";
                		$ambastatus = 1;
                	}
                	$ambassadorStatus = '<button onclick="updateAmbassadorStatus(this,\'ambassador\','.$row->userId.',0);" class="btn btn-success btn-rounded btn-sm del updateAmbassadorStatus" data-status="'.$ambastatus.'" title="'.$masterString.'"><span class="fa fa-graduation-cap" aria-hidden="true"></span></button>';
                	$ambassadorStatus .= '<button onclick="updateAmbassadorStatus(this,\'ambassador\','.$row->userId.',1);" class="btn btn-success btn-rounded btn-sm del updateMasterAmbassadorStatus" data-status="'.$ambastatus.'" title="'.$ambaString.'"><span class="fa fa-user-md" aria-hidden="true"></span></button>';                	
                }
                else {
                	$ambassadorStatus = '<button onclick="updateAmbassadorStatus(this,\'ambassador\','.$row->userId.', 0);" class="btn btn-danger btn-rounded btn-sm active updateAmbassadorStatus" data-status="0" title="Mark As Ambassador"><span class="fa fa-graduation-cap" aria-hidden="true"></span></button>';
                	$ambassadorStatus .= '<button onclick="updateAmbassadorStatus(this,\'ambassador\','.$row->userId.', 1);" class="btn btn-danger btn-rounded btn-sm active updateMasterAmbassadorStatus" data-status="0" title="Mark As Master Ambassador"><span class="fa fa-user-md" aria-hidden="true"></span></button>';
                }

                $nestedData['userName'] = $row->userName.$ambassadorLink;

                $nestedData['email'] = $row->email;

                $nestedData['membershipName'] = $row->membershipName;

                $nestedData['membership'] = $row->membership;

                $nestedData['platform'] = $row->platform;

                $nestedData['addedOn'] = $row->addedOn;

                $nestedData['status'] = $this->lang->line($row->status);

                
                

                $nestedData['action'] = '<a href="'.DASHURL.'/admin/user/add-user/'.$row->userId .'"  class="btn btn-warning btn-rounded btn-sm  "><i class="fa fa-pencil"></i></a><a href="'.DASHURL.'/admin/user/view-user/'.$row->userId .'"  class="btn btn-primary btn-rounded btn-sm  "><i class="fa fa-eye"></i></a><button onclick="ActivateDeActivateThisRecord(this,\'user\','.$row->userId.');" class="btn btn-danger btn-rounded btn-sm active " title="Active/DeActive" data-status="'.$row->status.'"><span class="'.$row->class.'"></span></button></button>'.$ambassadorStatus.$ambassadorIBNNumber.'<button onclick="delete_row(this,\'user\','.$row->userId.');" class="btn btn-danger btn-rounded btn-sm del" title="Delete"><span class="fa fa-times-circle-o"></span></button><button onclick="delete_row(this,\'delete_user\','.$row->userId.');" class="btn btn-danger btn-rounded btn-sm del" title="Delete"><span class="fa fa-trash-o"></span></button>';

                $data[] = $nestedData;



            }

        }

          

        return $json_data = array("draw" => intval($this->input->post('draw')), "recordsTotal"    => intval($totalData), "recordsFiltered" => intval($totalFiltered), "data" => $data );



	}


	// admin user list view

	public function Get_Blocked_User_List(){

		$columns = array( 0 => "userId", 1 => "userName", 2 => "email", 3 => "country", 4 => "membership", 5 => "addedOn", 6 => "status", 7 => "userId");



        $limit = $this->input->post('length');

        $start = $this->input->post('start');

        $order = $columns[$this->input->post('order')[0]['column']];

        $dir = $this->input->post('order')[0]['dir'];



        $cond = " order by $order $dir LIMIT $start, $limit ";

  

        $totalDataCount = $this->Common_model->exequery("SELECT count(userId) as total from vm_user where status = 2",1);

        $totalData = (isset($totalDataCount->total)  && $totalDataCount->total > 0)?$totalDataCount->total:0;

            

        $totalFiltered = $totalData; 

        $qry = "SELECT userId, img, userName, email, country, addedOn,status,(SELECT (CASE WHEN count(*) > 0 then 'Active' else 'Not Active' end) as membership_count FROM vm_user_memberships WHERE startDate <= '".date('Y-m-d')."' AND endDate >= '".date('Y-m-d')."' AND userId=vm_user.userId AND subscriptionStatus ='Active' order by membershipId desc limit 0, 1) as `membership`, case when status='0' then 'Active' else 'blocked' end as status,

			case when status='0' then 'act fa fa-circle' else 'dct fa fa-circle' end as class from vm_user where status = 2"; 

        if(empty($this->input->post('search')['value']))

        {            

            $queryData = $this->Common_model->exequery($qry.$cond);

        }else {

            $search = $this->input->post('search')['value'];

            if (!empty($search)) {             	

            	$search = str_replace("'", '', $search); 

            	$search = str_replace('"', '', $search); 

             } 

            $searchCond = " AND (userName LIKE  '%".$search."%' OR email LIKE  '%".$search."%' OR country LIKE  '%".$search."%' OR addedOn LIKE  '%".$search."%' OR status LIKE  '%".$search."%' ) ";

            $cond = $searchCond.$cond;

            $queryData = $this->Common_model->exequery($qry.$cond);



            $totalDataCount = $this->Common_model->exequery("SELECT count(userId) as total from vm_user where status = 2 ".$searchCond,1);

            $totalFiltered = (isset($totalDataCount->total)  && $totalDataCount->total > 0)?$totalDataCount->total:0;

        }

        $data = array();

        if(!empty($queryData))

        {

            foreach ($queryData as $row)

            {	$img = (!empty($user->img))?UPLOADPATH.'/user_images/'.$user->img:DASHSTATIC.'/restaurant/assets/img/user.png';

                $nestedData['image'] = '<img src="'.$img.'" width="30px" height="30px">';

                $nestedData['userName'] = $row->userName;

                $nestedData['email'] = $row->email;

                $nestedData['country'] = $row->country;

                $nestedData['membership'] = $row->membership;

                $nestedData['addedOn'] = $row->addedOn;

                $nestedData['status'] = $this->lang->line($row->status);

                $nestedData['action'] = '<a href="'.DASHURL.'/admin/user/view-user/'.$row->userId .'"  class="btn btn-primary btn-rounded btn-sm  "><i class="fa fa-eye"></i></a><button onclick="unblock_row(this,\'user\','.$row->userId.');" class="btn btn-danger btn-rounded btn-sm del" title="Delete"><span class="fa fa-times-circle-o"></span></button><button onclick="delete_row(this,\'delete_user\','.$row->userId.');" class="btn btn-danger btn-rounded btn-sm del" title="Delete"><span class="fa fa-trash-o"></span></button>';

                $data[] = $nestedData;



            }

        }

          

        return $json_data = array("draw" => intval($this->input->post('draw')), "recordsTotal"    => intval($totalData), "recordsFiltered" => intval($totalFiltered), "data" => $data );



	}



	// admin user enquiry list 

	 public function enquiry_list(){





		$columns = array( 0 => "name", 1 => "email", 2 => "subject", 3 => "message", 4 => "addedOn");



        $limit = $this->input->post('length');

        $start = $this->input->post('start');

        $order = $columns[$this->input->post('order')[0]['column']];

        $dir = $this->input->post('order')[0]['dir'];



        $cond = " order by $order $dir LIMIT $start, $limit ";

  

        $totalDataCount = $this->Common_model->exequery("SELECT count(*) as total from vm_contact_mail WHERE email != ''",1);

        $totalData = (isset($totalDataCount->total)  && $totalDataCount->total > 0)?$totalDataCount->total:0;

            

        $totalFiltered = $totalData; 

        $qry = "SELECT name, email, subject, message, addedOn from vm_contact_mail WHERE email != ''"; 

        if(empty($this->input->post('search')['value']))

        {            

            $queryData = $this->Common_model->exequery($qry.$cond);

        }else {

            $search = $this->input->post('search')['value'];

            if (!empty($search)) {             	

            	$search = str_replace("'", '', $search); 

            	$search = str_replace('"', '', $search); 

             } 

            $searchCond = " AND (name LIKE  '%".$search."%' OR email LIKE  '%".$search."%' OR subject LIKE  '%".$search."%' OR message LIKE  '%".$search."%' OR addedOn LIKE  '%".$search."%' ) ";

            $cond = $searchCond.$cond;

            $queryData = $this->Common_model->exequery($qry.$cond);



            $totalDataCount = $this->Common_model->exequery("SELECT count(*) as total from vm_contact_mail WHERE email != '' ".$searchCond,1);

            $totalFiltered = (isset($totalDataCount->total)  && $totalDataCount->total > 0)?$totalDataCount->total:0;

        }

        $data = array();

        if(!empty($queryData))

        {

            foreach ($queryData as $row)

            {	

                $nestedData['name'] = $row->name;

                $nestedData['email'] = $row->email;

                $nestedData['subject'] = $row->subject;

                $nestedData['message'] = $row->message;

                $nestedData['addedOn'] = $row->addedOn;

                $data[] = $nestedData;



            }

        }

          

        return $json_data = array("draw" => intval($this->input->post('draw')), "recordsTotal"    => intval($totalData), "recordsFiltered" => intval($totalFiltered), "data" => $data );



	 }

	// admin Blog category list view

	public function blog_category_list(){





		$columns = array( 0 => "categoryName$this->langSuffix", 1 => "addedOn", 2 => "status", 3 => "categoryId");



        $limit = $this->input->post('length');

        $start = $this->input->post('start');

        $order = $columns[$this->input->post('order')[0]['column']];

        $dir = $this->input->post('order')[0]['dir'];



        $cond = " order by $order $dir LIMIT $start, $limit ";

  

        $totalDataCount = $this->Common_model->exequery("SELECT count(*) as total from vm_blog_category where status != 2",1);

        $totalData = (isset($totalDataCount->total)  && $totalDataCount->total > 0)?$totalDataCount->total:0;

            

        $totalFiltered = $totalData; 

        $qry = "SELECT categoryId, categoryName$this->langSuffix as categoryName,addedOn,case when status='0' then 'Active' else 'DeActive' end as status,

			case when status='0' then 'act fa fa-circle' else 'dct fa fa-circle' end as class from vm_blog_category where status != 2"; 

        if(empty($this->input->post('search')['value']))

        {            

            $queryData = $this->Common_model->exequery($qry.$cond);

        }else {

            $search = $this->input->post('search')['value']; 



            if (!empty($search)) {             	

            	$search = str_replace("'", '', $search); 

            	$search = str_replace('"', '', $search); 

             }

            $searchCond = " AND (categoryName$this->langSuffix LIKE  '%".$search."%' OR addedOn LIKE  '%".$search."%' OR status LIKE  '%".$search."%' ) ";

            $cond = $searchCond.$cond;

            $queryData = $this->Common_model->exequery($qry.$cond);



            $totalDataCount = $this->Common_model->exequery("SELECT count(*) as total from vm_blog_category where status != 2".$searchCond,1);

            $totalFiltered = (isset($totalDataCount->total)  && $totalDataCount->total > 0)?$totalDataCount->total:0;

        }

        $data = array();

        if(!empty($queryData))

        {

            foreach ($queryData as $row)

            {	

                $nestedData['categoryName'] = $row->categoryName;

                $nestedData['addedOn'] = $row->addedOn;

                $nestedData['status'] = $this->lang->line($row->status);

                $nestedData['action'] = '<a href="'.DASHURL.'/admin/blog/add-category/'.$row->categoryId .'"  class="btn btn-warning btn-rounded btn-sm  "><i class="fa fa-pencil"></i></a><a href="'.DASHURL.'/admin/blog/view-category/'.$row->categoryId .'"  class="btn btn-primary btn-rounded btn-sm  "><i class="fa fa-eye"></i></a><button onclick="ActivateDeActivateThisRecord(this,\'blog_category\','.$row->categoryId.');" class="btn btn-danger btn-rounded btn-sm active " title="Active/DeActive" data-status="'.$row->status.'"><span class="'.$row->class.'"></span></button></button><button onclick="delete_row(this,\'blog_category\','.$row->categoryId.');" class="btn btn-danger btn-rounded btn-sm del" title="Delete"><span class="fa fa-trash-o"></span></button>';

                $data[] = $nestedData;



            }

        }

          

        return $json_data = array("draw" => intval($this->input->post('draw')), "recordsTotal"    => intval($totalData), "recordsFiltered" => intval($totalFiltered), "data" => $data );





	}



	// admin inactive_comment_list list view

	public function inactive_comment_list(){



		$columns = array( 0 => "bc.name", 1 => "bc.email", 2 => "bc.comment", 3 => "bc.ip", 4 => "bc.addedOn", 5 => "bc.status", 6 => "bc.commentId");



        $limit = $this->input->post('length');

        $start = $this->input->post('start');

        $order = $columns[$this->input->post('order')[0]['column']];

        $dir = $this->input->post('order')[0]['dir'];



        $cond = " order by $order $dir LIMIT $start, $limit ";

  

        $totalDataCount = $this->Common_model->exequery("SELECT count(bc.commentId) as total from vm_blog_comment as bc left join vm_blog as bl on bc.blogId=bl.blogId where bc.status = 1",1);

        $totalData = (isset($totalDataCount->total)  && $totalDataCount->total > 0)?$totalDataCount->total:0;

            

        $totalFiltered = $totalData; 

        $qry = "SELECT bc.commentId, bc.blogId, bc.name, bc.email, bc.ip, bc.comment, bc.addedOn,case when bc.status='0' then 'Active' else 'DeActive' end as status,

			case when bc.status='0' then 'act fa fa-circle' else 'dct fa fa-circle' end as class from vm_blog_comment as bc left join vm_blog as bl on bc.blogId=bl.blogId where bc.status = 1"; 

        if(empty($this->input->post('search')['value']))

        {            

            $queryData = $this->Common_model->exequery($qry.$cond);

        }else {

            $search = $this->input->post('search')['value'];



            if (!empty($search)) {             	

            	$search = str_replace("'", '', $search); 

            	$search = str_replace('"', '', $search); 

             } 

            $searchCond = " AND (bc.name LIKE  '%".$search."%' OR bc.email LIKE  '%".$search."%' OR bc.ip LIKE  '%".$search."%' OR bc.comment LIKE  '%".$search."%' OR bc.addedOn LIKE  '%".$search."%' OR bc.status LIKE  '%".$search."%' ) ";

            $cond = $searchCond.$cond;

            $queryData = $this->Common_model->exequery($qry.$cond);



            $totalDataCount = $this->Common_model->exequery("SELECT count(bc.commentId) as total from vm_blog_comment as bc left join vm_blog as bl on bc.blogId=bl.blogId where bc.status = 1".$searchCond,1);

            $totalFiltered = (isset($totalDataCount->total)  && $totalDataCount->total > 0)?$totalDataCount->total:0;

        }

        $data = array();

        if(!empty($queryData))

        {

            foreach ($queryData as $row)

            {	

                $nestedData['name'] = $row->name;

                $nestedData['email'] = $row->email;

                $nestedData['comment'] = $row->comment;

                $nestedData['ip'] = $row->ip;

                $nestedData['addedOn'] = $row->addedOn;

                $nestedData['status'] = $this->lang->line($row->status);

                $nestedData['action'] = '<button onclick="ActivateDeActivateThisRecord(this,\'blog_comment\','.$row->commentId.');" class="btn btn-danger btn-rounded btn-sm active " title="Active/DeActive" data-status="'.$row->status.'"><span class="'.$row->class.'"></span></button></button><button onclick="delete_row(this,\'blog_comment\','.$row->commentId.');" class="btn btn-danger btn-rounded btn-sm del" title="Delete"><span class="fa fa-trash-o"></span></button>';

                $data[] = $nestedData;



            }

        }

          

        return $json_data = array("draw" => intval($this->input->post('draw')), "recordsTotal"    => intval($totalData), "recordsFiltered" => intval($totalFiltered), "data" => $data );



	}



	// admin blog list view

	public function blog_list(){





		$columns = array( 0 => "bl.blogId", 1 => "bl.title$this->langSuffix", 2 => "bl.description$this->langSuffix", 3 => "bc.categoryName$this->langSuffix", 4 => "bl.tags$this->langSuffix", 5 => "bl.addedOn", 6 => "bl.status", 7 => "bl.blogId");



        $limit = $this->input->post('length');

        $start = $this->input->post('start');

        $order = $columns[$this->input->post('order')[0]['column']];

        $dir = $this->input->post('order')[0]['dir'];



        $cond = " order by $order $dir LIMIT $start, $limit ";

  

        $totalDataCount = $this->Common_model->exequery("SELECT count(bl.blogId) as total from vm_blog as bl left join vm_blog_category as bc on bc.categoryId=bl.categoryId  where bl.status != 2",1);

        $totalData = (isset($totalDataCount->total)  && $totalDataCount->total > 0)?$totalDataCount->total:0;

            

        $totalFiltered = $totalData; 

        $qry = "SELECT bl.blogId,

		 bc.categoryName$this->langSuffix as categoryName,

		 bl.img,

		 bl.title$this->langSuffix as title,SUBSTRING(bl.description$this->langSuffix, 1, 20) as description,

		    bl.tags$this->langSuffix as tags, bl.addedOn,case when bl.status='0' then 'Active' else 'DeActive' end as status,

			case when bl.status='0' then 'act fa fa-circle' else 'dct fa fa-circle' end as class from vm_blog as bl left join vm_blog_category as bc on bc.categoryId=bl.categoryId  where bl.status != 2"; 

        if(empty($this->input->post('search')['value']))

        {            

            $queryData = $this->Common_model->exequery($qry.$cond);

        }else {

            $search = $this->input->post('search')['value']; 

            

            if (!empty($search)) {             	

            	$search = str_replace("'", '', $search); 

            	$search = str_replace('"', '', $search); 

             }

            $searchCond = " AND (bl.title$this->langSuffix LIKE  '%".$search."%' OR bl.description$this->langSuffix LIKE  '%".$search."%' OR bc.categoryName$this->langSuffix LIKE  '%".$search."%' OR bl.tags$this->langSuffix LIKE  '%".$search."%' OR bl.addedOn LIKE  '%".$search."%' OR bl.status LIKE  '%".$search."%' ) ";

            $cond = $searchCond.$cond;

            $queryData = $this->Common_model->exequery($qry.$cond);



            $totalDataCount = $this->Common_model->exequery("SELECT count(bl.blogId) as total from vm_blog as bl left join vm_blog_category as bc on bc.categoryId=bl.categoryId  where bl.status != 2 ".$searchCond,1);

            $totalFiltered = (isset($totalDataCount->total)  && $totalDataCount->total > 0)?$totalDataCount->total:0;

        }

        $data = array();

        if(!empty($queryData))

        {

            foreach ($queryData as $row)

            {	$img = (!empty($row->img))?UPLOADPATH.'/blog_images/'.$row->img:DASHSTATIC.'/restaurant/assets/img/blog.png';

                $nestedData['image'] = '<img src="'.$img.'" width="30px" height="30px">';

                $nestedData['title'] = $row->title;

                $nestedData['description'] = $row->description;

                $nestedData['categoryName'] = $row->categoryName;

                $nestedData['tags'] = $row->tags;

                $nestedData['addedOn'] = $row->addedOn;

                $nestedData['status'] = $this->lang->line($row->status);

                $nestedData['action'] = '<a href="'.DASHURL.'/admin/blog/add-blog/'.$row->blogId .'"  class="btn btn-warning btn-rounded btn-sm  "><i class="fa fa-pencil"></i></a><a href="'.DASHURL.'/admin/blog/view-blog/'.$row->blogId .'"  class="btn btn-primary btn-rounded btn-sm  "><i class="fa fa-eye"></i></a><button onclick="ActivateDeActivateThisRecord(this,\'blog\','.$row->blogId.');" class="btn btn-danger btn-rounded btn-sm active " title="Active/DeActive" data-status="'.$row->status.'"><span class="'.$row->class.'"></span></button></button><button onclick="delete_row(this,\'blog\','.$row->blogId.');" class="btn btn-danger btn-rounded btn-sm del" title="Delete"><span class="fa fa-trash-o"></span></button>';

                $data[] = $nestedData;



            }

        }

          







        return $json_data = array("draw" => intval($this->input->post('draw')), "recordsTotal"    => intval($totalData), "recordsFiltered" => intval($totalFiltered), "data" => $data );



	}



	// admin order list view

	public function order_list(){





		$columns = array( 0 => "dd.id", 1 => "ur.userName", 2 => "rs.restaurantName", 3 => "pd.productName", 4 => "dd.createdDate", 5 => "dd.servedStatus");



        $limit = $this->input->post('length');

        $start = $this->input->post('start');

        $order = $columns[$this->input->post('order')[0]['column']];

        $dir = $this->input->post('order')[0]['dir'];



        $cond = " order by $order $dir LIMIT $start, $limit ";

  

        $totalDataCount = $this->Common_model->exequery("SELECT count(*) as total from vm_user_daily_drink as dd left join vm_user as ur on ur.userId=dd.userId  left join vm_product as pd on pd.productId=dd.productId left join vm_restaurant as rs on rs.restaurantId=dd.restaurantId  where dd.id > 0",1);

        $totalData = (isset($totalDataCount->total)  && $totalDataCount->total > 0)?$totalDataCount->total:0;

            

        $totalFiltered = $totalData; 

         $qry = "SELECT 

		rs.restaurantName$this->langSuffix as restaurantName, vm_order.cancelRemark,

		(CASE WHEN dd.productType = '1' THEN CONCAT(pd.productName$this->langSuffix,' (',vd.variableName$this->langSuffix,')') ELSE pd.productName$this->langSuffix END) as productName, 
		ur.userName,dd.id, dd.userId, dd.servedStatus, dd.restaurantId, dd.productId, dd.createdDate from vm_user_daily_drink as dd left join vm_user as ur on ur.userId=dd.userId  left join vm_variable_product as vd on (dd.productId= vd.variableId and dd.productType = '1') left join vm_product as pd on (CASE WHEN dd.productType = '1' THEN (pd.productId = vd.productId) ELSE  (pd.productId = dd.productId) END) left join vm_restaurant as rs on rs.restaurantId=dd.restaurantId left join vm_order on dd.orderId = vm_order.orderId  where dd.id > 0"; 

        if(empty($this->input->post('search')['value']))

        {            

            $queryData = $this->Common_model->exequery($qry.$cond);

        }else {

            $search = $this->input->post('search')['value'];

            if (!empty($search)) {             	

            	$search = str_replace("'", '', $search); 

            	$search = str_replace('"', '', $search); 

             } 

            $searchCond = " AND (ur.userName LIKE  '%".$search."%' OR rs.restaurantName LIKE  '%".$search."%' OR pd.productName LIKE  '%".$search."%' OR dd.createdDate LIKE  '%".$search."%' OR dd.servedStatus LIKE  '%".$search."%' ) ";

            $cond = $searchCond.$cond;

            $queryData = $this->Common_model->exequery($qry.$cond);



            $totalDataCount = $this->Common_model->exequery("SELECT count(*) as total from vm_user_daily_drink as dd left join vm_user as ur on ur.userId=dd.userId  left join vm_product as pd on pd.productId=dd.productId left join vm_restaurant as rs on rs.restaurantId=dd.restaurantId  where dd.id > 0 ".$searchCond,1);

            $totalFiltered = (isset($totalDataCount->total)  && $totalDataCount->total > 0)?$totalDataCount->total:0;

        }

        $data = array();

        if(!empty($queryData))

        {

            foreach ($queryData as $row)

            {	$servedStatus ='';

            	if($row->servedStatus == 0)

	                  $servedStatus = '<b style="color:blue;">'.$this->lang->line('pending').'</b>';

	             if($row->servedStatus == 1)

	                  $servedStatus = '<b style="color:green;">'.$this->lang->line('served').'</b>';

	              else if($row->servedStatus == 2){

	              	  if(!IS_NULL($row->cancelRemark) && !empty($row->cancelRemark)) {
    	              	  if(strpos(strtolower($row->cancelRemark), 'user') > -1)
    	              	  	$servedStatus = '<b style="color:red;">'.$this->lang->line('cancelledByUser').'</b>';
    	              	  else
    	                  	$servedStatus = '<b style="color:red;">'.$this->lang->line('cancelledByRestaurant').'</b>';
	              	  }
	              	  else
    	                  	$servedStatus = '<b style="color:red;">'.$this->lang->line('cancelledByRestaurant').'</b>';
	              }

                $nestedData['orderId'] = '#'.$row->id;

                $nestedData['userName'] = '<a href="'.DASHURL.'/admin/user/view-user/'.$row->userId .'" >'.(($row->userName == 'unknown')?$this->lang->line('deletedUser'):$row->userName).'</a>';

                $nestedData['restaurantName'] = '<a href="'.DASHURL.'/admin/restaurant/view-restaurant/'.$row->restaurantId .'" >'.$row->restaurantName.'</a>';

                $nestedData['productName'] = '<a href="'.DASHURL.'/admin/product/view-product/'.$row->productId .'" >'.$row->productName.'</a>';

                $nestedData['addedOn'] = $row->createdDate;

                $nestedData['status'] = $servedStatus;

                $data[] = $nestedData;



            }

        }

          

        return $json_data = array("draw" => intval($this->input->post('draw')), "recordsTotal"    => intval($totalData), "recordsFiltered" => intval($totalFiltered), "data" => $data );







	}



	// User Rating List  view

	public function GetUserRatingList(){





		$columns = array( 0 => "rs.restaurantName$this->langSuffix", 1 => "su.userName", 2 => "rr.userMessage", 3 => "rr.priceRating", 4 => "rr.qualityRating", 5 => "rr.serviceRating", 6 => "rr.ambienceRating", 7 => "rr.status", 8 => "rr.ratingId");



        $limit = $this->input->post('length');

        $start = $this->input->post('start');

        $order = $columns[$this->input->post('order')[0]['column']];

        $dir = $this->input->post('order')[0]['dir'];



        $cond = " order by $order $dir LIMIT $start, $limit ";

  

        $totalDataCount = $this->Common_model->exequery("SELECT count(rr.ratingId) as total FROM vm_resturant_rating as rr INNER JOIN vm_restaurant as rs ON rs.restaurantId = rr.restaurantId INNER JOIN vm_user as su ON su.userId = rr.userId WHERE rr.ratingId > 0",1);

        $totalData = (isset($totalDataCount->total)  && $totalDataCount->total > 0)?$totalDataCount->total:0;

            

        $totalFiltered = $totalData; 

        $qry = "SELECT rr.*,su.userName, rs.restaurantName$this->langSuffix as restaurantName,case when rr.status='0' then 'Active' else 'DeActive' end as status,

			case when rr.status='0' then 'act fa fa-circle' else 'dct fa fa-circle' end as class FROM vm_resturant_rating as rr INNER JOIN vm_restaurant as rs ON rs.restaurantId = rr.restaurantId INNER JOIN vm_user as su ON su.userId = rr.userId WHERE rr.ratingId > 0"; 

        if(empty($this->input->post('search')['value']))

        {            

            $queryData = $this->Common_model->exequery($qry.$cond);

        }else {

            $search = $this->input->post('search')['value']; 

            if (!empty($search)) {             	

            	$search = str_replace("'", '', $search); 

            	$search = str_replace('"', '', $search); 

             }

            $searchCond = " AND (su.userName LIKE  '%".$search."%' OR rs.restaurantName$this->langSuffix LIKE  '%".$search."%' OR rr.userMessage LIKE  '%".$search."%' OR rr.priceRating LIKE  '%".$search."%' OR rr.qualityRating LIKE  '%".$search."%' OR rr.serviceRating LIKE  '%".$search."%' OR rr.ambienceRating LIKE  '%".$search."%' OR rr.status LIKE  '%".$search."%' ) ";

            $cond = $searchCond.$cond;

            $queryData = $this->Common_model->exequery($qry.$cond);



            $totalDataCount = $this->Common_model->exequery("SELECT count(rr.ratingId) as total FROM vm_resturant_rating as rr INNER JOIN vm_restaurant as rs ON rs.restaurantId = rr.restaurantId INNER JOIN vm_user as su ON su.userId = rr.userId WHERE rr.ratingId > 0".$searchCond,1);

            $totalFiltered = (isset($totalDataCount->total)  && $totalDataCount->total > 0)?$totalDataCount->total:0;

        }

        $data = array();

        if(!empty($queryData))

        {

            foreach ($queryData as $row)

            {	$status = $this->lang->line($row->status);

                $nestedData['restaurantName'] = $row->restaurantName;

                $nestedData['userName'] = $row->userName;

                $nestedData['message'] = $row->userMessage;

                $nestedData['priceRating'] = $row->priceRating;

                $nestedData['qualityRating'] = $row->qualityRating;

                $nestedData['serviceRating'] = $row->serviceRating;

                $nestedData['ambienceRating'] = $row->ambienceRating;

                $nestedData['status'] = $status;

                $nestedData['action'] = '<button onclick="ActivateDeActivateThisRecord(this,\'resturant_rating\','.$row->ratingId.');" class="btn btn-danger btn-rounded btn-sm active " title="Active/DeActive" data-status="'.$status.'"><span class="'.$row->class.'"></span></button></button></button><button onclick="delete_row(this,\'resturant_rating\','.$row->ratingId.');" class="btn btn-danger btn-rounded btn-sm del" title="Delete"><span class="fa fa-trash-o"></span></button>';

                $data[] = $nestedData;



            }

        }

          

        return $json_data = array("draw" => intval($this->input->post('draw')), "recordsTotal"    => intval($totalData), "recordsFiltered" => intval($totalFiltered), "data" => $data );

	}



	// restaurant event list view

	public function GetEventList(){

		$columns = array( 0 => "re.id", 1 => "rp.planName$this->langSuffix", 2 => "re.paidAmount", 3 => "re.transactionId", 4 => "re.createdDateTime", 5 => "re.expiredDate", 6 => "rs.restaurantName$this->langSuffix", 7 => "re.isStatus", 8 => "re.id");



        $limit = $this->input->post('length');

        $start = $this->input->post('start');

        $order = $columns[$this->input->post('order')[0]['column']];

        $dir = $this->input->post('order')[0]['dir'];



        $cond = " order by $order $dir LIMIT $start, $limit ";

  

        $totalDataCount = $this->Common_model->exequery("SELECT count(re.id) as total FROM vm_resturant_event as re INNER JOIN vm_restaurant_plan as rp ON re.planId = rp.planId LEFT JOIN vm_restaurant as rs ON rs.restaurantId = re.resturantId  WHERE re.isStatus != 0",1);

        $totalData = (isset($totalDataCount->total)  && $totalDataCount->total > 0)?$totalDataCount->total:0;



        $totalFiltered = $totalData; 

        $qry = "SELECT re.id, re.websiteUrl, re.planId, re.bannerImage, re.paidAmount, re.transactionId, rp.planName$this->langSuffix as planName, rp.period, rp.duration, re.expiredDate, re.createdDateTime, re.isStatus, rs.restaurantName$this->langSuffix as restaurantName FROM vm_resturant_event as re INNER JOIN vm_restaurant_plan as rp ON re.planId = rp.planId LEFT JOIN vm_restaurant as rs ON rs.restaurantId = re.resturantId  WHERE re.isStatus != 0"; 

        if(empty($this->input->post('search')['value']))

        {            

            $queryData = $this->Common_model->exequery($qry.$cond);

        }else {

            $search = $this->input->post('search')['value'];

            if (!empty($search)) {             	

            	$search = str_replace("'", '', $search); 

            	$search = str_replace('"', '', $search); 

             } 

            $searchCond = " AND (rp.planName$this->langSuffix LIKE  '%".$search."%' OR rs.restaurantName$this->langSuffix  LIKE  '%".$search."%' OR re.paidAmount LIKE  '%".$search."%' OR re.transactionId LIKE  '%".$search."%' OR re.createdDateTime LIKE  '%".$search."%' OR re.isStatus LIKE  '%".$search."%' ) ";

            $cond = $searchCond.$cond;

            $queryData = $this->Common_model->exequery($qry.$cond);



            $totalDataCount = $this->Common_model->exequery("SELECT count(re.id) as total FROM vm_resturant_event as re INNER JOIN vm_restaurant_plan as rp ON re.planId = rp.planId LEFT JOIN vm_restaurant as rs ON rs.restaurantId = re.resturantId  WHERE re.isStatus != 0 ".$searchCond,1);

            $totalFiltered = (isset($totalDataCount->total)  && $totalDataCount->total > 0)?$totalDataCount->total:0;

        }

        $data = array();

        if(!empty($queryData))

        {

            foreach ($queryData as $row)

            {	$img = (!empty($row->bannerImage))?UPLOADPATH.'/eventImages/'.$row->bannerImage:DASHSTATIC.'/admin/ico/html/product.png';

				$status = ($row->isStatus) ? ((strtotime(date('Y-m-d H:i:s')) <= strtotime(date($row->expiredDate))) ? 'Active' : 'Expired' ) : 'DeActive';

                $nestedData['image'] = '<img src="'.$img.'" width="30px" height="30px">';

                $nestedData['planName'] = $row->planName;

                $nestedData['amt'] = $row->paidAmount;

                $nestedData['transactionId'] = $row->transactionId;

                $nestedData['addedOn'] = $row->createdDateTime;

                $nestedData['expiredOn'] = $row->expiredDate;

                $nestedData['restaurantName'] = $row->restaurantName;

                $nestedData['status'] = $this->lang->line($status);

                $nestedData['action'] = '<a href="'.DASHURL.'/admin/event/add-event/'.$row->id .'" class="btn btn-warning btn-rounded btn-sm"><i class="fa fa-pencil"></i></a><a href="'.DASHURL.'/admin/event/view-event/'.$row->id .'"  class="btn btn-primary btn-rounded btn-sm"><i class="fa fa-eye"></i></a>';

                $data[] = $nestedData;



            }

        }

          

        return $json_data = array("draw" => intval($this->input->post('draw')), "recordsTotal"    => intval($totalData), "recordsFiltered" => intval($totalFiltered), "data" => $data );

	



	}

	// subscription list view

	public function GetSubscriptionList(){

		$columns = array( 0 => "planName$this->langSuffix", 1 => "description$this->langSuffix", 2 => "status", 3 => "id");



        $limit = $this->input->post('length');

        $start = $this->input->post('start');

        $order = $columns[$this->input->post('order')[0]['column']];

        $dir = $this->input->post('order')[0]['dir'];



        $cond = " order by $order $dir LIMIT $start, $limit ";

  

        $totalDataCount = $this->Common_model->exequery("SELECT count(*) as total from vm_subscription_plan where status != '2' AND isSubType !=2",1);

        $totalData = (isset($totalDataCount->total)  && $totalDataCount->total > 0)?$totalDataCount->total:0;



        $totalFiltered = $totalData; 

        $qry = "SELECT *,planName$this->langSuffix as planName from vm_subscription_plan where status != '2'  AND isSubType !=2"; 

        if(empty($this->input->post('search')['value']))

        {            

            $queryData = $this->Common_model->exequery($qry.$cond);

        }else {

            $search = $this->input->post('search')['value'];

            if (!empty($search)) {             	

            	$search = str_replace("'", '', $search); 

            	$search = str_replace('"', '', $search); 

             } 

            $searchCond = " AND (planName$this->langSuffix LIKE  '%".$search."%' OR description$this->langSuffix LIKE  '%".$search."%' OR status LIKE  '%".$search."%' ) ";

            $cond = $searchCond.$cond;

            $queryData = $this->Common_model->exequery($qry.$cond);



            $totalDataCount = $this->Common_model->exequery("SELECT count(*) as total from vm_subscription_plan where status != '2'".$searchCond,1);

            $totalFiltered = (isset($totalDataCount->total)  && $totalDataCount->total > 0)?$totalDataCount->total:0;

        }

        $data = array();

        if(!empty($queryData))

        {



            foreach ($queryData as $row)

            {	$status = ($row->status == 0) ? 'Active' : 'DeActive';


                $nestedData['planName'] = $row->planName;

                $nestedData['description'] = $row->description;

                $nestedData['status'] = $this->lang->line($status);

                $nestedData['action'] = '<a href="'.DASHURL.'/admin/subscription/update-subscription/'.$row->Id.'" class="btn btn-warning btn-rounded btn-sm" data-id="'.$row->planId.'"><i class="fa fa-pencil"></i></a><a href="'.DASHURL.'/admin/subscription/view/'.$row->Id.'"  class="btn btn-primary btn-rounded btn-sm"><i class="fa fa-eye"></i></a><button onclick=\'ActivateDeActivateThisRecord(this,"subscription_plan",'.$row->Id.');\' class="btn btn-danger btn-rounded btn-sm active " title="Active/DeActive" data-status="'.$status.'"><span class="act fa fa-circle"></span></button>';

                $data[] = $nestedData;



            }

        }

          

        return $json_data = array("draw" => intval($this->input->post('draw')), "recordsTotal"    => intval($totalData), "recordsFiltered" => intval($totalFiltered), "data" => $data );

	}





	public function getAdminNotification() {

		$query	=	"SELECT ratingId, isRead from vm_resturant_rating where status='1' and  isRead='0'";

		$notificationData =	$this->Common_model->exequeryarray($query);

		if( $notificationData ) {

			// v3print($notificationData);

			$notificationIds = array_map('current', $notificationData);



			// v3print($dailydrink);exit;

			$this->Common_model->update("vm_resturant_rating", array('isRead' => '1'), " ratingId IN (".implode(',',$notificationIds).")");

		}

		return ( $notificationData ) ? array('notification' => $notificationData) : array('notification' => array());

	}





	public 	function sanitize($input) {

	    if (is_array($input)) {

	        foreach($input as $var=>$val) {

	            $output[$var] =$this->sanitize($val);

	        }

	    }

	    else {

	        $input  = $this->cleanString($input);

	        $output = $input;

	    }

	    return $output;

	}

	public	function cleanString($string) {

		//$detagged = strip_tags($string);

		$detagged = $string;

	    if(get_magic_quotes_gpc()) {

	        $stripped = stripslashes($detagged);

	        $escaped = mysql_real_escape_string($stripped);

	    } else 

	        $escaped = mysql_real_escape_string($detagged);	    

		

	    return $escaped;

	}

	public function addEventPlan($data) {

		if(isset($data['hiddenval']) && !empty($data['hiddenval'])){

			$eventplan 		= 	$this->Common_model->update("vm_restaurant_plan",array('planName' => $data['plan_name'],'period' => $data['period'], 'duration' => $data['duration'], 'amount' => $data['amount']), "planId = '".$data['hiddenval']."'");

			return ($eventplan) ? array('planid' => $data['hiddenval'], 'status' => true) : array('status' => false);

		}

		else { 

			$eventplan 		= 	$this->Common_model->insertUnique("vm_restaurant_plan", array('planName' => $data['plan_name'],'period' => $data['period'], 'duration' => $data['duration'], 'amount' => $data['amount'],'createdDate' => date('Y-m-d H:i:s'), 'status' => '0'));

			return ($eventplan) ? array('planid' => $eventplan, 'status' => true) : array('status' => false);

		}

	}

	public function restaurant_payout_list($restaurantId){

		if($restaurantId>0){

			$columns = array( 0 => "totalorders", 1 => "payoutAmount", 2 => "transactionId", 3 => "addedOn", 4 => "status", 5 => "payoutId");



			$limit = $this->input->post('length');

			$start = $this->input->post('start');

			$order = $columns[$this->input->post('order')[0]['column']];

			$dir = $this->input->post('order')[0]['dir'];



			$cond = " order by $order $dir LIMIT $start, $limit ";

	  

			$totalDataCount = $this->Common_model->exequery("SELECT count(*) as total from vm_restaurant_payout where restaurantId ='".$restaurantId."' AND status='Success'",1);

			$totalData = (isset($totalDataCount->total)  && $totalDataCount->total > 0)?$totalDataCount->total:0;

				

			$totalFiltered = $totalData; 

			$qry = "SELECT payoutId,totalorders,payoutAmount,transactionId,status,addedOn from vm_restaurant_payout where restaurantId ='".$restaurantId."' AND status='Success'"; 

			if(empty($this->input->post('search')['value']))

			{            

				$queryData = $this->Common_model->exequery($qry.$cond);

			}else {

				$search = $this->input->post('search')['value'];

				if (!empty($search)) {             	

					$search = str_replace("'", '', $search); 

					$search = str_replace('"', '', $search); 

				 } 

				$searchCond = " AND (addedOn LIKE  '%".$search."%' OR transactionId LIKE  '%".$search."%' OR totalorders LIKE  '%".$search."%' OR payoutAmount LIKE  '%".$search."%' OR status LIKE  '%".$search."%' ) ";

				$cond = $searchCond.$cond;

				$queryData = $this->Common_model->exequery($qry.$cond);



				$totalDataCount = $this->Common_model->exequery("SELECT count(payoutId) as total from vm_restaurant_payout where restaurantId ='".$restaurantId."' AND status='Success'  ".$searchCond,1);

				$totalFiltered = (isset($totalDataCount->total)  && $totalDataCount->total > 0)?$totalDataCount->total:0;

			}

			$data = array();

			if(!empty($queryData))

			{

				foreach ($queryData as $row)

				{	

					$nestedData['totalorders'] = $row->totalorders;

					$nestedData['payoutAmount'] = $row->payoutAmount;

					$nestedData['transactionId'] = $row->transactionId;

					$nestedData['addedOn'] = $row->addedOn;

					$imgName=($row->status=='Success')?'checked.png':'cross.png';

					$nestedData['status'] = '<img src="'.DASHSTATIC.'/restaurant/assets/img/'.$imgName.'" />';

					$nestedData['action'] = '<a href="'.DASHURL.'/admin/restaurant/payout-details/'.$restaurantId.'/'.$row->payoutId .'"  class="btn btn-warning btn-rounded btn-sm"><i class="fa fa-eye"></i></a>';

					$data[] = $nestedData;



				}

			}

			return $json_data = array("draw" => intval($this->input->post('draw')), "recordsTotal"    => intval($totalData), "recordsFiltered" => intval($totalFiltered), "data" => $data );

		}

        else

			return $json_data=array("data" => array());



	}

	public function getHappyhourProductData(){
		$json_data=array("valid" => false, 'optionData'=>'');
		if(isset($_POST['restaurantId']) && $_POST['restaurantId'] > 0){
			
			$optionData = '';
	        $productData = $this->Common_model->exequery("SELECT productId, price, productName$this->langSuffix as productName, productType, (SELECT categoryName$this->langSuffix from vm_product_category where categoryId = vm_product.categoryId) as categoryName, (SELECT subcategoryName$this->langSuffix from vm_product_subcategory where subcategoryId = vm_product.subcategoryId) as subcategoryName FROM vm_product WHERE status = 0 AND isAvailableInFree = '0' AND restaurantId = ".$_POST['restaurantId']." order by productName$this->langSuffix ASC");
			if(!empty($productData)) {
	            foreach ($productData as $product) {

	                $product->variableData = ($product->productType)?$this->Common_model->exequery("SELECT *, variableName$this->langSuffix as variableName, (SELECT price from vm_happyhour_product where vm_happyhour_product.status != 2 AND vm_happyhour_product.productId = vm_variable_product.productId AND vm_happyhour_product.variableId = vm_variable_product.variableId AND vm_happyhour_product.happyhourId = 0 limit 0,1) as discountedPrice FROM vm_variable_product WHERE status = 0 and productId = ".$product->productId." order by variableName$this->langSuffix ASC"):array();

					$html = '&lt;span  class="hide" data-name="'.$product->productName.'" data-price="'.$product->price.'" data-price2="0" data-variable=\''.((!empty($product->variableData))?json_encode($product->variableData):'').'\' &gt;Option 1&lt;/span&gt;';
	                $optionData .= '<option value="'.$product->productId.'" > '.$product->productName.$html.'  </option>';
	            }
	        }

			return array("valid" => true, 'optionData'=>$optionData);
		}
		return $json_data;

	}


	// admin Happyhour list view
	public function Get_Happyhour_List(){


		$columns = array( 0 => "rs.restaurantName", 1 => "hh.day", 2 => "hh.startTime", 3 => "hh.endTime",4 => "productCount", 5 => "hh.addedOn", 6 => "hh.status", 7 => "hh.happyhourId", 8 => "hh.happyhourId");

        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $order = $columns[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];

        $cond = " order by $order $dir LIMIT $start, $limit ";
  
        $totalDataCount = $this->Common_model->exequery("SELECT count(hh.happyhourId) as total from vm_happyhour as hh left join vm_restaurant as rs on rs.restaurantId = hh.restaurantId where hh.status != 2",1);
        $totalData = (isset($totalDataCount->total)  && $totalDataCount->total > 0)?$totalDataCount->total:0;
            
        $totalFiltered = $totalData; 
        $qry = "SELECT hh.happyhourId, rs.restaurantName$this->langSuffix as restaurantName,  hh.day, hh.startTime, hh.endTime, hh.addedOn, (SELECT COUNT(*) as num FROM vm_happyhour_product where vm_happyhour_product.happyhourId = hh.happyhourId AND vm_happyhour_product.status !=2) as productCount, (case when hh.status='0' then 'Active' else 'DeActive' end) as status, (case when hh.status='0' then 'act fa fa-circle' else 'dct fa fa-circle' end) as class from vm_happyhour as hh left join vm_restaurant as rs on rs.restaurantId = hh.restaurantId where hh.status != 2 "; 
        if(empty($this->input->post('search')['value']))
        {            
            $queryData = $this->Common_model->exequery($qry.$cond);
        }else {
            $search = $this->input->post('search')['value']; 
            if (!empty($search)) {             	
            	$search = str_replace("'", '', $search); 
            	$search = str_replace('"', '', $search); 
             }
            $searchCond = " AND (rs.restaurantName$this->langSuffix LIKE  '%".$search."%' OR hh.day LIKE  '%".$search."%' OR hh.startTime LIKE  '%".$search."%' OR hh.endTime LIKE  '%".$search."%' OR hh.addedOn LIKE  '%".$search."%' OR hh.status LIKE  '%".$search."%'  ) ";
            $cond = $searchCond.$cond;
            $queryData = $this->Common_model->exequery($qry.$cond);

            $totalDataCount = $this->Common_model->exequery("SELECT count(hh.happyhourId) as total from vm_happyhour as hh left join vm_restaurant as rs on rs.restaurantId = hh.restaurantId where hh.status != 2 ".$searchCond,1);
            $totalFiltered = (isset($totalDataCount->total)  && $totalDataCount->total > 0)?$totalDataCount->total:0;
        }
        $data = array();
        if(!empty($queryData))
        {
            foreach ($queryData as $row)
            {	

                $nestedData['restaurantName'] = $row->restaurantName;
                $nestedData['day'] = ucfirst($row->day);
                $nestedData['startTime'] = $row->startTime;
                $nestedData['endTime'] = $row->endTime;
                $nestedData['productCount'] = $row->productCount;
                $nestedData['addedOn'] = $row->addedOn;
                $nestedData['status'] = $this->lang->line($row->status);
                $nestedData['action'] = '<a href="'.DASHURL.'/admin/product/add-happyhour/'.$row->happyhourId .'" class="btn btn-warning btn-rounded btn-sm"><i class="fa fa-pencil"></i></a><a href="'.DASHURL.'/admin/product/view-happyhour/'.$row->happyhourId .'"  class="btn btn-primary btn-rounded btn-sm"><i class="fa fa-eye"></i></a><button onclick="ActivateDeActivateThisRecord(this,\'happyhour\','.$row->happyhourId.');" class="btn btn-danger btn-rounded btn-sm active " title="Active/DeActive" data-status="'.$row->status.'"><span class="'.$row->class.'"></span></button><button onclick="delete_row(this,\'happyhour\','.$row->happyhourId.');" class="btn btn-danger btn-rounded btn-sm del" title="Delete"><span class="fa fa-trash-o"></span></button>';
                $data[] = $nestedData;

            }
        }
          
        return $json_data = array("draw" => intval($this->input->post('draw')), "recordsTotal"    => intval($totalData), "recordsFiltered" => intval($totalFiltered), "data" => $data );


	}


	// admin Wallet History list view
	public function Get_WalletHistory_List(){


		$columns = array( 0 => "amount", 1 => "transactionId", 2 => "addedOn", 3 => "message",4 => "historyId", 5 => "historyId");

        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $order = $columns[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];

        $cond = " order by $order $dir LIMIT $start, $limit ";
  
        $totalDataCount = $this->Common_model->exequery("SELECT count(*) as total from vm_stripe_wallet_history where stripeId != ''",1);
        $totalData = (isset($totalDataCount->total)  && $totalDataCount->total > 0)?$totalDataCount->total:0;
            
        $totalFiltered = $totalData; 
        $qry = "SELECT historyId, stripeId, transactionId, amount, message, status, addedOn, updatedOn from vm_stripe_wallet_history where stripeId != '' "; 
        if(empty($this->input->post('search')['value']))
        {            
            $queryData = $this->Common_model->exequery($qry.$cond);
        }else {
            $search = $this->input->post('search')['value']; 
            if (!empty($search)) {             	
            	$search = str_replace("'", '', $search); 
            	$search = str_replace('"', '', $search); 
             }
            $searchCond = " AND (amount LIKE  '%".$search."%' OR transactionId LIKE  '%".$search."%' OR addedOn LIKE  '%".$search."%' OR message LIKE  '%".$search."%'  ) ";
            $cond = $searchCond.$cond;
            $queryData = $this->Common_model->exequery($qry.$cond);

            $totalDataCount = $this->Common_model->exequery("SELECT count(*) as total from vm_stripe_wallet_history where stripeId != '' ".$searchCond,1);
            $totalFiltered = (isset($totalDataCount->total)  && $totalDataCount->total > 0)?$totalDataCount->total:0;
        }
        $data = array();
        if(!empty($queryData))
        {
            foreach ($queryData as $row)
            {	

                $nestedData['transactionId'] = $row->transactionId;
                $nestedData['amount'] = ucfirst($row->amount);
                $nestedData['addedOn'] = $row->addedOn;
                $nestedData['status'] = $row->message;
                $data[] = $nestedData;

            }
        }
          
        return $json_data = array("draw" => intval($this->input->post('draw')), "recordsTotal"    => intval($totalData), "recordsFiltered" => intval($totalFiltered), "data" => $data );


	}

	/* ADd Coupon */
	public function addCoupon($data){
		if(isset($_POST['couponCode']) && !empty($_POST['couponCode']) && isset($_POST['dateranges']) && !empty($_POST['dateranges'])) {
			$cond = (isset($_POST['hiddenVal']) && $_POST['hiddenVal'] > 0) ? " AND couponId !=".$_POST['hiddenVal']: '';
			$isExists = $this->Common_model->exequery("select * from vm_coupons WHERE BINARY couponCode='".$_POST['couponCode']."' ".$cond,1);
			$daterange = explode(' - ', $_POST['dateranges']);
			if($isExists)
				return array('valid' => false, 'msg' => 'Coupon Code Already Exists');
			$couponData = array('couponCode' => $_POST['couponCode'], 'type' => $_POST['type'], 'offeredType' => $_POST['couponOffered'],'expiryDate' => $daterange[1], 'startDate' => $daterange[0], 'discountper' => 0, 'discountchf' => 0, 'planId' => 0, 'period' => 0, 'duration' => 'day', 'numberFreeDrink' => 0, 'freeDrinkPeriod' => '');
			if( $_POST['couponOffered'] == 0 ) 
				$couponData['discountper'] = $_POST['discountper'];
			else if($_POST['couponOffered'] == 1)
				$couponData['discountchf'] = $_POST['discountchf'];
			else if($_POST['couponOffered'] == 2) 
				$couponData['planId'] = $_POST['membershipplan'];
			else if($_POST['couponOffered'] == 3) {
				$couponData['period'] = $_POST['period'];
				$couponData['duration'] = $_POST['duration'];
				$couponData['numberFreeDrink'] = $_POST['freeperiod'];
				$couponData['freeDrinkPeriod'] = $_POST['freeduration'];
			}
			 
			$couponData['discountedPrice'] = ($_POST['couponOffered'] == 2 && $_POST['discountedPrice'] > 0)?$_POST['discountedPrice']:0;

			if($_POST['type'] == 1) 
				$couponData['limituse'] = $_POST['limituse'];
			else
				$couponData['limituse'] = 0;
			if(isset($_POST['hiddenVal']) && $_POST['hiddenVal'] > 0) {
				$qry = $this->Common_model->update('vm_coupons', $couponData, "couponId=".$_POST['hiddenVal']);
				return ($qry) ? array('valid' => true, 'msg' => 'SuccessFully Updated') : array('valid' => false, 'msg' => 'Validation Failed');
			}
			else {
				$couponData['addedOn'] = date('Y-m-d H:i:s');
				$qry = $this->Common_model->insertUnique('vm_coupons', $couponData);
				return ($qry) ? array('valid' => true, 'msg' => 'SuccessFully Added') : array('valid' => false, 'msg' => 'Validation Failed');
			}

		}
		else
			return array('valid' => false, 'msg' => 'Validation Failed');
	}

	/* subscription list view */

	public function GetCouponList(){





		$columns = array( 0 => "couponCode", 1 => "type", 2 => "limituse", 3 => "startDate", 4 => "expiryDate", 5 => "activeMember", 6 => "count", 7 => "status", 8 => "couponId");



        $limit = $this->input->post('length');

        $start = $this->input->post('start');

        $order = $columns[$this->input->post('order')[0]['column']];

        $dir = $this->input->post('order')[0]['dir'];


        if ($order =='limituse')
        	$cond = " order by $order $dir, limituse $dir LIMIT $start, $limit ";
        else
        	$cond = " order by $order $dir LIMIT $start, $limit ";

  

        $totalDataCount = $this->Common_model->exequery("SELECT count(*) as total from vm_coupons where status != '2' AND giftId=0",1);

        $totalData = (isset($totalDataCount->total)  && $totalDataCount->total > 0)?$totalDataCount->total:0;



        $totalFiltered = $totalData; 

        $qry = "SELECT *, (case when status='0' then 'act fa fa-circle' else 'dct fa fa-circle' end) as class, (SELECT count(*) FROM vm_coupon_redeem where couponId= vm_coupons.couponId) as count, (SELECT count(*) FROM vm_user_memberships WHERE isPrevoiusLog='0' AND couponId = vm_coupons.couponId AND startDate <= '".date('Y-m-d')."' AND endDate >= '".date('Y-m-d')."' AND subscriptionStatus ='Active') as activeMember from vm_coupons where status != '2' AND giftId=0"; 

        if(empty($this->input->post('search')['value']))

        {            

            $queryData = $this->Common_model->exequery($qry.$cond);

        }else {

            $search = $this->input->post('search')['value'];

            if (!empty($search)) {             	

            	$search = str_replace("'", '', $search); 

            	$search = str_replace('"', '', $search); 

             } 

            $searchCond = " AND (couponCode LIKE  '%".$search."%' OR type LIKE  '%".$search."%' OR startDate  LIKE  '%".$search."%' OR expiryDate LIKE  '%".$search."%' OR status LIKE  '%".$search."%' ) ";

            $cond = $searchCond.$cond;

            $queryData = $this->Common_model->exequery($qry.$cond);



            $totalDataCount = $this->Common_model->exequery("SELECT count(*) as total from vm_coupons where status != '2' AND giftId=0".$searchCond,1);

            $totalFiltered = (isset($totalDataCount->total)  && $totalDataCount->total > 0)?$totalDataCount->total:0;

        }

        $data = array();

        if(!empty($queryData))

        {



            foreach ($queryData as $row)

            {	$status = ($row->status == 0) ? 'Active' : 'DeActive';

                $nestedData['couponCode'] = $row->couponCode;
                $nestedData['type'] = (($row->type)?$this->lang->line('multiple'):$this->lang->line('single')).'<span class=hide>'.$row->type.'<span>';
                $nestedData['subscription'] = $row->limituse;
                $nestedData['startDate'] = $row->startDate;
                $nestedData['expiryDate'] = $row->expiryDate;
                $nestedData['activeMember'] = $row->activeMember;
                $nestedData['count'] = $row->count;
               
                $nestedData['status'] = $this->lang->line($status);

                $nestedData['action'] = '<a href="javascript:"  class="btn btn-warning btn-rounded btn-sm" onclick="edit_coupon(this,'.$row->couponId.');" title="Edit"><i class="fa fa-pencil"></i></a><button onclick="ActivateDeActivateThisRecord(this,\'coupons\','.$row->couponId.');" class="btn btn-danger btn-rounded btn-sm active " title="Active/DeActive" data-status="'.$status.'"><span class="'.$row->class.'"></span></button></button></button><button onclick="delete_row(this,\'coupons\','.$row->couponId.');" class="btn btn-danger btn-rounded btn-sm del" title="Delete"><span class="fa fa-trash-o"></span></button></button>';

                $data[] = $nestedData;



            }

        }

          

        return $json_data = array("draw" => intval($this->input->post('draw')), "recordsTotal"    => intval($totalData), "recordsFiltered" => intval($totalFiltered), "data" => $data );

	}

	/* bartender list view */

	public function GetBartenderList(){
		$columns = array( 0 => "bt.serve", 1 => "bt.img", 2 => "bt.bartenderName", 3 => "bt.email", 4 => "bt.mobile", 5 => "bt.gender", 6 => "rs.restaurantName", 7 => "bt.addedOn", 8 => "bt.status", 9 => "bt.bartenderId");
        $limit = $this->input->post('length');

        $start = $this->input->post('start');

        $order = $columns[$this->input->post('order')[0]['column']];

        $dir = $this->input->post('order')[0]['dir'];



        $cond = " order by $order $dir LIMIT $start, $limit ";

  

        $totalDataCount = $this->Common_model->exequery("SELECT count(*) as total from vm_bartender where status != '2'",1);

        $totalData = (isset($totalDataCount->total)  && $totalDataCount->total > 0)?$totalDataCount->total:0;



        $totalFiltered = $totalData; 

        $qry = "SELECT bt.*, rs.restaurantName, (case when bt.status='0' then 'Active' else 'DeActive' end) as status,(case when bt.status='0' then 'act fa fa-circle' else 'dct fa fa-circle' end) as class from vm_bartender as bt left join vm_restaurant as rs on rs.restaurantId = bt.restaurantId where bt.status != '2'"; 

        if(empty($this->input->post('search')['value']))

        {            

            $queryData = $this->Common_model->exequery($qry.$cond);

        }else {

            $search = $this->input->post('search')['value'];

            if (!empty($search)) {             	

            	$search = str_replace("'", '', $search); 

            	$search = str_replace('"', '', $search); 

             } 

            $searchCond = " AND (bt.serve LIKE  '%".$search."%' OR bt.bartenderName  LIKE  '%".$search."%' OR bt.email LIKE  '%".$search."%' OR bt.mobile LIKE  '%".$search."%' OR bt.gender LIKE  '%".$search."%' OR rs.restaurantName LIKE  '%".$search."%' OR bt.addedOn LIKE  '%".$search."%' OR bt.status LIKE  '%".$search."%' ) ";

            $cond = $searchCond.$cond;

            $queryData = $this->Common_model->exequery($qry.$cond);



            $totalDataCount = $this->Common_model->exequery("SELECT count(bt.bartenderId) as total from vm_bartender as bt left join vm_restaurant as rs on rs.restaurantId = bt.restaurantId where bt.status != '2'".$searchCond,1);

            $totalFiltered = (isset($totalDataCount->total)  && $totalDataCount->total > 0)?$totalDataCount->total:0;

        }

        $data = array();

        if(!empty($queryData))

        {



            foreach ($queryData as $row)

            {	$img = (!empty($row->img))?UPLOADPATH.'/bartender_images/'.$row->img:DASHSTATIC.'/restaurant/assets/img/user.png';

                $nestedData['serve'] = $this->lang->line($row->serve);
                $nestedData['img'] = '<img src="'.$img.'" width="30px" height="30px">';

                $nestedData['bartenderName'] = $row->bartenderName;

                $nestedData['email'] = $row->email;

                $nestedData['mobile'] = $row->mobile;

                $nestedData['gender'] = $this->lang->line(strtolower($row->gender));

                $nestedData['restaurantName'] = $row->restaurantName;

                $nestedData['addedOn'] = $row->addedOn;

                $nestedData['status'] = $this->lang->line($row->status);

                $nestedData['action'] = '<a href="'.DASHURL.'/admin/bartender/add-bartender/'.$row->bartenderId .'"  class="btn btn-warning btn-rounded btn-sm  "><i class="fa fa-pencil"></i></a><a href="'.DASHURL.'/admin/bartender/view-bartender/'.$row->bartenderId .'"  class="btn btn-primary btn-rounded btn-sm  "><i class="fa fa-eye"></i></a><button onclick="ActivateDeActivateThisRecord(this,\'bartender\','.$row->bartenderId.');" class="btn btn-danger btn-rounded btn-sm active " title="Active/DeActive" data-status="'.$row->status.'"><span class="'.$row->class.'"></span></button><button onclick="delete_row(this,\'bartender\','.$row->bartenderId.');" class="btn btn-danger btn-rounded btn-sm del" title="Delete"><span class="fa fa-trash-o"></span></button>';

                $data[] = $nestedData;




            }

        }

          

        return $json_data = array("draw" => intval($this->input->post('draw')), "recordsTotal"    => intval($totalData), "recordsFiltered" => intval($totalFiltered), "data" => $data );

	}


    public function sendpayountmail(){
    	// charles
    	$response = array('valid' => false, 'msg' => $this->lang->line('dbError'));
    	$restaurantId = (isset($_POST['restaurantId']) && $_POST['restaurantId'] > 0)?$_POST['restaurantId']:0;
		$lastMonthDate = date("Y-m-d", strtotime("last day of previous month"));
		$startDate = date("Y-m-d", strtotime("first day of previous month"));
		$restaurantOrder = $this->Common_model->exequery("SELECT vm_order.restaurantId, vm_restaurant_stripe_details.legal_entity_business_name as legalName, sr.restaurantName, sr.address1, sr.address2, sr.country, sr.mobile, sr.email, vm_restaurant_stripe_details.legal_entity_business_tax_id as taxDetails FROM vm_order left join vm_restaurant sr on vm_order.restaurantId = sr.restaurantId left join vm_restaurant_stripe_details on sr.restaurantId = vm_restaurant_stripe_details.restaurantId WHERE paymentStatus='Completed' AND orderStatus='Completed' AND DATE(vm_order.addedOn) >= '".$startDate."' AND DATE(vm_order.addedOn) <= '".$lastMonthDate."' AND vm_order.isTrail='0' AND vm_order.restaurantId='".$restaurantId."'  group by vm_order.restaurantId");
		
		if($restaurantOrder) {
			foreach($restaurantOrder as $restaurantOrderItem) {
				$totalDrinkAmount = $totalFoodAmount =  $freeDrinkAmount = $drinkQuantity = $totalFreeDrinkQuantity = $foodQuantity = 0;
				$drinkOrderBodyHtml = $previousCategoryItem = $foodOrderBodyHtml = $previousFoodCategoryItem = '';
				$getDrinkOrder = $this->Common_model->exequery("SELECT od.*, vm_product_subcategory.subcategoryName, (CASE WHEN od.isVariable = '1' THEN CONCAT(pd.productName,' (',vd.variableName, ')') ELSE  pd.productName END) as productName FROM vm_order_detail as od left join vm_variable_product as vd on (od.productId= vd.variableId and od.isVariable = '1') left join vm_product as pd on (CASE WHEN od.isVariable = '1' THEN (pd.productId = vd.productId) ELSE  (pd.productId = od.productId) END) left join vm_product_subcategory ON pd.subcategoryId = vm_product_subcategory.subcategoryId  WHERE orderId IN (SELECT orderId FROM vm_order WHERE restaurantId = '".$restaurantOrderItem->restaurantId."' AND paymentStatus='Completed' AND orderStatus='Completed' AND DATE(addedOn) >= '".$startDate."' AND DATE(addedOn) <= '".$lastMonthDate."' AND isTrail='0') AND itemType='1' order by subcategoryName desc");
				$drinkOrderHtml = '<table width="800" cellspacing="0" cellpadding="0" border="0" align="" style="background-color: #f2f2f2;font-size:13px;margin-top:10px;">
								<thead class="drinks" style="text-align: left;background-color:#a6a6a6;">
								  <tr class="drinks">
									<th><span style="font-size: 20px; padding-left:10px;">DRINKS</span></th>
									<th><p>Product</p></th>
									<th><p style="text-align:center;">CHF / Unit</p></th>
									<th><p style="text-align:center;">Qty Wecome Drinks</p></th>
									<th><p style="text-align:center;">Qty</p></th>
									<th><p style="text-align:center;">Total CHF</p></th>
								  </tr>
								</thead>
							<tbody>';
				if( $getDrinkOrder ) {
					//$drinkQuantity = 0;
					foreach ($getDrinkOrder as $drinkOrderItem) {
						
						if($previousCategoryItem != $drinkOrderItem->subcategoryName) {
							$previousCategoryItem = $drinkOrderItem->subcategoryName;
							$categoryNameHtml = '<td style="margin:0px;"><span style="padding-left:10px;">'.$drinkOrderItem->subcategoryName.'</span></td>';
						}
						else
							$categoryNameHtml = '<td colspan="1" style="margin:0px;"></td>';
						if($drinkOrderItem->isFree == 1 ) {
							$freeDrinkQuantity = 1;
							$freeDrinkAmount = $freeDrinkAmount + $drinkOrderItem->price;
							$totalFreeDrinkQuantity  = $totalFreeDrinkQuantity + 1;
						}
						else
							$freeDrinkQuantity = 0;
						$totalDrinkAmount = $totalDrinkAmount + $drinkOrderItem->subtotal;
						$drinkQuantity = $drinkQuantity + $drinkOrderItem->quantity;
						$drinkOrderBodyHtml .= '<tr>'.$categoryNameHtml.'<td style="margin:0px;"><p style="margin:0px; width:200px;">'.$drinkOrderItem->productName.'</p></td><td style="margin:0px;"><p style="text-align:center; margin:0px;">'.str_replace('.', ',', $drinkOrderItem->price).'</p></td><td style="margin:0px;"><p style="text-align:center; margin:0px;">'.$freeDrinkQuantity.'</p></td><td style="margin:0px;"><p style="text-align:center; margin:0px;">'.$drinkOrderItem->quantity.'</p></td><td style="margin:0px;"><p style="text-align:center; margin:0px;">'.str_replace('.', ',', $drinkOrderItem->subtotal).'</p></td></tr>';
					}
					
				}
				if($drinkOrderBodyHtml == '')
					$drinkOrderBodyHtml = '<tr><td style="margin:0px;"><span style="padding-left:10px;">NONE</span></td><td style="margin:0px;"><p style="margin:0px;">-</p></td><td style="margin:0px;"><p style="text-align:center; margin:0px;">0</p></td><td style="margin:0px;"><p style="text-align:center; margin:0px;">0</p></td><td style="margin:0px;"><p style="text-align:center; margin:0px;">0</p></td><td style="margin:0px;"><p style="text-align:center; margin:0px;">0</p></td></tr>';
				$drinkOrderHtml .= $drinkOrderBodyHtml.'<tr class="center"><td style="margin:0px;"><p style="font-weight:bold; text-align:left;padding-left:10px; margin:0px;">Sub-total </p></td><td colspan="1" style="margin:0px;"></td><td style="margin:0px;"><p style="font-weight:bold; text-align:center; margin:0px;"></p></td><td colspan="1" style="margin:0px;"><p style="font-weight:bold; text-align:center; margin:0px;">'.$totalFreeDrinkQuantity.'</p></td><td style="margin:0px;"><p style="font-weight:bold; text-align:center; margin:0px;">'.$drinkQuantity.'</p></td><td style="margin:0px;"><p style="font-weight:bold; text-align:center; margin:0px;">'.str_replace('.', ',', $totalDrinkAmount).'</p></td>
						</tr></tbody></table>';
				$getFoodOrder = $this->Common_model->exequery("SELECT od.*, vm_product_subcategory.subcategoryName, (CASE WHEN od.isVariable = '1' THEN CONCAT(pd.productName,' (',vd.variableName, ')') ELSE  pd.productName END) as productName FROM vm_order_detail as od left join vm_variable_product as vd on (od.productId= vd.variableId and od.isVariable = '1') left join vm_product as pd on (CASE WHEN od.isVariable = '1' THEN (pd.productId = vd.productId) ELSE  (pd.productId = od.productId) END) left join vm_product_subcategory ON pd.subcategoryId = vm_product_subcategory.subcategoryId  WHERE orderId IN (SELECT orderId FROM vm_order WHERE restaurantId = '".$restaurantOrderItem->restaurantId."' AND paymentStatus='Completed' AND orderStatus='Completed' AND DATE(addedOn) >= '".$startDate."' AND DATE(addedOn) <= '".$lastMonthDate."' AND isTrail='0') AND itemType='0' order by subcategoryName desc");
				$foodOrderHtml = '<table width="800" cellspacing="0" cellpadding="0" border="0" align="" style="background-color: #f2f2f2;font-size:13px; margin-top:10px;">
								<thead class="drinks" style="text-align: left; background-color:#a6a6a6;">
								  <tr class="drinks">
									<th><span style="font-size: 20px; padding-left:10px;">FOOD</span></th>
									<th><p>Product</p></th>
									<th><p style="text-align:center;">CHF / Unit</p></th>
									<th><p style="text-align:center;">Qty Wecome Drinks</p></th>
									<th><p style="text-align:center;">Qty</p></th>
									<th><p style="text-align:center;">Total CHF</p></th>
								  </tr>
								</thead>
							<tbody>';
				if( $getFoodOrder ) {
					//$drinkQuantity = 0;
					foreach ($getFoodOrder as $foodOrderItem) {
						
						if($previousFoodCategoryItem != $foodOrderItem->subcategoryName) {
							$previousFoodCategoryItem = $foodOrderItem->subcategoryName;
							$categoryNameHtml = '<td style="margin:0px;"><span style="padding-left:10px; margin:0px;">'.$foodOrderItem->subcategoryName.'</span></td>';
						}
						else
							$categoryNameHtml = '<td colspan="1" style="margin:0px;"></td>';
						
						$totalFoodAmount = $totalFoodAmount + $foodOrderItem->subtotal;
						$foodQuantity = $foodQuantity + $foodOrderItem->quantity;
						$foodOrderBodyHtml .= '<tr>'.$categoryNameHtml.'<td style="margin:0px;"><p style="margin:0px; width:200px;">'.$foodOrderItem->productName.'</p></td><td style="margin:0px;"><p style="text-align:center; margin:0px;">'.str_replace('.', ',', $foodOrderItem->price).'</p></td><td style="margin:0px;"><p style="text-align:center; margin:0px;">0</p></td><td style="margin:0px;"><p style="text-align:center; margin:0px;">'.$foodOrderItem->quantity.'</p></td><td style="margin:0px;"><p style="text-align:center; margin:0px;">'.str_replace('.', ',', $foodOrderItem->subtotal).'</p></td></tr>';
					}
					
				}
				if($foodOrderBodyHtml == '')
					$foodOrderBodyHtml = '<tr><td style="margin:0px;"><span style="padding-left:10px;">NONE</span></td><td style="margin:0px;"><p style="margin:0px;">-</p></td><tdstyle="margin:0px;"><p style="text-align:center; margin:0px;">0</p></td><td style="margin:0px;"><p style="text-align:center; margin:0px;">0</p></td><td style="margin:0px;"><p style="text-align:center; margin:0px;">0</p></td><td style="margin:0px;"><p style="text-align:center; margin:0px;">0</p></td></tr>';
				$foodOrderHtml .= $foodOrderBodyHtml.'<tr class="center"><td style="margin:0px;"><p style="font-weight:bold;text-align:left; padding-left:10px; margin:0px;">Sub-total </p></td><td colspan="1"></td><td style="margin:0px;"><p style="font-weight:bold;text-align:center; margin:0px;">0</p></td><td colspan="1"></td><td style="margin:0px;"><p style="font-weight:bold;text-align:center; margin:0px;">'.$foodQuantity.'</p></td><td style="margin:0px;"><p style="font-weight:bold;text-align:center; margin:0px;">'.str_replace('.', ',', $totalFoodAmount).'</p></td>
						</tr></tbody></table>';
				$totalInivoceAmount = $totalDrinkAmount + $totalFoodAmount;
				$vedmirCommession = 0.05 * $totalInivoceAmount;
				$taxAmount = 0.077 * $totalInivoceAmount;
				$payoutAmount = $totalInivoceAmount - $vedmirCommession;
				$invoiceContent = '<table width="800" cellspacing="0" cellpadding="0" border="0"  style="background-color: #fce4d6;font-size: 13px;margin-top:10px;padding-right:10px;"><tbody><tr class="center"><td style="margin:0px;"><p style="padding-left:10px; margin:0px;">Drinks revenue </p></td><td style="margin:0px;"><p style="margin:0px; text-align:right;">CHF '.str_replace('.', ',', $totalDrinkAmount).'</p></td></tr><tr class="center"><td style="margin:0px;"><p style="padding-left:10px; margin:0px;">Food revenue</p></td><td style="margin:0px;"><p style="margin:0px; text-align:right;">CHF '.str_replace('.', ',', $totalFoodAmount).'</p></td></tr><tr class="center"><td style="margin:0px;"><p style="font-size:17px;padding-left:10px; margin:0px;">TOTAL REVENUE</p></td><td><p style="font-size:17px; margin:0px; text-align:right;">CHF '.str_replace('.', ',', $totalInivoceAmount).'</p></td></tr><tr class="center"><td><p style="padding-left:10px; margin:0px;">Vedmir Commission (5%) </p></td><td><p style="margin:0px; text-align:right;">CHF '.str_replace('.', ',', $vedmirCommession).'</p></td></tr><tr class="center"><td><p style="padding-left:10px; margin:0px;">Including Tax 7,7%</p></td><td><p style="margin:0px; text-align:right;">CHF '.str_replace('.', ',', $taxAmount).'</p></td></tr></tbody></table><table width="800" cellspacing="0" cellpadding="0" border="0"  style="background-color: #ed7d31;font-size: 13px;margin-top:10px; padding-right:10px;"><tbody><tr class="center"><td><p style="font-size:17px; padding-left:10px; margin:0px;">TOTAL PAYOUT</p></td><td><p style="font-size:17px; text-align:right; margin:0px;">CHF '.str_replace('.', ',', $payoutAmount).'</p></td></tr></tbody></table><table width="800" cellspacing="0" cellpadding="0" border="0"  style="background-color: #f2f2f2;font-size: 13px;margin-top:10px; padding-right:10px;"><tbody><tr class="center"><td><p style="padding-left:10px; margin:0px;">Total amount of Welcome drinks</p></td><td><p style="margin:0px; text-align:right;">'.str_replace('.', ',', $freeDrinkAmount).'</p></td></tr><tr class="center"><td><p style="padding-left:10px; margin:0px;">Total amount of paid products </p></td><td><p style="margin:0px; text-align:right;">'.str_replace('.', ',', $totalInivoceAmount).'</p></td></tr></tbody></table>';
				$settings = array();
                $settings["template"]               =   "payout_tpl.html";
                $settings["email"]                  =   $restaurantOrderItem->email;
                $settings["subject"]                =   "Payout Summary ".date('F Y', strtotime($lastMonthDate));
                $contentarr['[[[VenueName]]]']           =   $restaurantOrderItem->restaurantName;
                $contentarr['[[[PayoutNumber]]]']       =   'SK'.$restaurantOrderItem->restaurantId.date('y', strtotime($startDate)).date('m', strtotime($startDate));
                $contentarr['[[[DATEOFISSUE]]]']       =   DATE('d.m.Y');
                $contentarr['[[[PAYOUTMONTH]]]']       =   DATE('F Y', strtotime($lastMonthDate));
                $contentarr['[[[PAYOUTCONTENT]]]']           =  $drinkOrderHtml.$foodOrderHtml.$invoiceContent;
                $contentarr['[[[LEGALNAME]]]']       =   $restaurantOrderItem->legalName;
                $contentarr['[[[ADDRESSLINE1]]]']       =   $restaurantOrderItem->address1;
                $contentarr['[[[ADDRESSLINE2]]]']       =   $restaurantOrderItem->address2;
                $contentarr['[[[COUNTRY]]]']           =   $restaurantOrderItem->country;
                $contentarr['[[[PHONE]]]']       =   $restaurantOrderItem->mobile;
                $contentarr['[[[TAXNUMBER]]]']       =   $restaurantOrderItem->taxDetails;
                $contentarr['[[[LOGOBLACK]]]']		=  BASEURL."/system/static/frontend/images/logo_black.png";
                $settings["contentarr"]             =   $contentarr;
                $template 			= 	ABSSTATICPATH."/emailTemplates/".$settings["template"];
				$subject			= 	(!isset($settings["subject"]))?"Subject":$settings["subject"];
				$mail_content 		= 	file_get_contents($template);
				$logoUrl				=		BASEURL."/system/static/frontend/images/logo.png";		
				$siteUrl				=		BASEURL;		
				$termUrl				=		BASEURL;
				$mail_content		= 	str_replace("[[[LOGO]]]", $logoUrl, $mail_content);
				$mail_content		= 	str_replace("[[[SITEURL]]]", $siteUrl, $mail_content);
				$mail_content		= 	str_replace("[[[TERMURL]]]", $termUrl, $mail_content);
				

				// if(array_key_exists('contentarr', $settings)){
				// 	$contentarr			=		$settings["contentarr"];
				// 	foreach($contentarr as $key=>$value){
				// 		$mail_content		= 	str_replace($key, $value, $mail_content);
				// 	}
				// }
				// echo $mail_content;
				try{

	                $ismailed = $this->common_lib->sendMail($settings);
	                $response = array('valid' => true, 'msg' => $this->lang->line('successMail'));
				}catch (\Exception $e) {
	                $response = array('valid' => false, 'msg' => $e->getMessage());
	            }
			}
		}
		return $response;
	} 



	//get Image SubCategory for selected Category
	public function getImageCategory(){

		$condition 		= 	"type = '".$_POST["type"]."' and status = 0";
		$resultdata		= 	$this->Common_model->selTableData(PREFIX."image_category","categoryId,categoryName$this->langSuffix as categoryName",$condition,"","","","categoryName$this->langSuffix");
		$dd_options =	'<option value="">'.$this->lang->line('selectCategory').'</option>';
		if(valResultSet($resultdata)){
			foreach($resultdata as $rs)
				$dd_options .= '<option value="'.$rs->categoryId.'" >'.$rs->categoryName.'</option>';
		}
		return $dd_options;
	}

	//get Image SubCategory for selected Category
	public function getImageSubcategory(){

		$condition 		= 	"type = '".$_POST["type"]."' and categoryId = '".$_POST["categoryId"]."' and status = 0";
		$resultdata		= 	$this->Common_model->selTableData(PREFIX."image_subcategory","subcategoryId,subcategoryName$this->langSuffix as subcategoryName",$condition,"","","","subcategoryName$this->langSuffix");
		$dd_options =	'<option value="">'.$this->lang->line('selectSubcategory').'</option>';
		if(valResultSet($resultdata)){
			foreach($resultdata as $rs)
				$dd_options .= '<option value="'.$rs->subcategoryId.'" >'.$rs->subcategoryName.'</option>';
		}
		return $dd_options;
	}

	//get Image filter
	public function getFilterdImages(){
		$response = array('valid'=> false, 'imageData'=> '');
		$condition 		= 	" and type = '".$_POST["type"]."'";
		$resultdata		= 	$this->Common_model->exequery("SELECT categoryId, categoryName$this->langSuffix as categoryName, (SELECT count(*) FROM vm_image WHERE status = 0 and image != '' $condition) as totalImg FROM vm_image_category WHERE status = 0 ".$condition);
		if(valResultSet($resultdata)){
		 	foreach($resultdata as $category){
		 		if ($category->totalImg) {
					$response['imageData'] .= '<div class="col-md-12"><h2>'.$category->categoryName.'</h2></div>';
		 			$newCond	= 	$condition." and categoryId = '".$category->categoryId."'";
					$newResultdata		= 	$this->Common_model->exequery("SELECT subcategoryId, subcategoryName$this->langSuffix as subcategoryName, (SELECT count(*) FROM vm_image WHERE status = 0 and image != '' $newCond) as totalImg FROM vm_image_subcategory WHERE status = 0 ".$newCond);
					if(valResultSet($newResultdata)){
					 	foreach($newResultdata as $subcategory){
					 		if ($subcategory->totalImg) {
					 			$finalCond	= 	$newCond." and subcategoryId = '".$subcategory->subcategoryId."'";
								$finaldata		= 	$this->Common_model->exequery("SELECT * FROM vm_image WHERE status = 0 and image != '' ".$finalCond);
								if(valResultSet($finaldata)){
									$response['imageData'] .= '<div class="col-md-12"><h3>'.$subcategory->subcategoryName.'</h3></div>';
									foreach($finaldata as $rs){
										$rs->image = UPLOADPATH.'/vedmir_images/'.$rs->image; 
										$response['imageData'] .= '<div class="col-md-2" onclick="setImageId(this, event)"><input id="test-'.$rs->imageId.'" name="same-group-name" type="radio" value="'.$rs->imageId.'" url="'.$rs->image.'" class="image-radio" /><label for="test-'.$rs->imageId.'"><div class="image" style="background-image: url('.$rs->image.');background-size: cover;"></div></label></div>';
									}
								}
					 		}
					 	}
					}else{
			 			
						$finaldata		= 	$this->Common_model->exequery("SELECT * FROM vm_image WHERE status = 0 and image != '' ".$newCond);
						if(valResultSet($finaldata)){
							$response['imageData'] .= '<div class="col-md-12"><h3></h3></div>';
							foreach($finaldata as $rs){
								$rs->image = UPLOADPATH.'/vedmir_images/'.$rs->image; 
								$response['imageData'] .= '<div class="col-md-2" onclick="setImageId(this, event)"><input id="test-'.$rs->imageId.'" name="same-group-name" type="radio" value="'.$rs->imageId.'" url="'.$rs->image.'" class="image-radio" /><label for="test-'.$rs->imageId.'"><div class="image" style="background-image: url('.$rs->image.');background-size: cover;"></div></label></div>';
							}
						}

					}
		 		}
		 	}
		}
		// $resultdata		= 	$this->Common_model->exequery("SELECT * FROM vm_image WHERE status = 0 and image != '' ".$condition);
		// if(valResultSet($resultdata)){
		// 	$response['valid'] = true;
		// 	foreach($resultdata as $rs){
		// 		$rs->image = UPLOADPATH.'/vedmir_images/'.$rs->image; 
		// 		$response['imageData'] .= '<div class="col-md-4" onclick="setImageId(this, event)"><input id="test-'.$rs->imageId.'" name="same-group-name" type="radio" value="'.$rs->imageId.'" url="'.$rs->image.'" class="image-radio" /><label for="test-'.$rs->imageId.'"><div class="image" style="background-image: url('.$rs->image.');background-size: cover;"></div></label></div>';
		// 	}
		// }
		return $response;
	}
	// admin product category list view

	public function image_category_list(){
		$columns = array( 0 => "categoryName$this->langSuffix",1 => 'type', 2 => 'addedOn', 3 => 'status', 4 => 'categoryId');
        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $order = $columns[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];
        $cond = " order by $order $dir LIMIT $start, $limit ";  

        $totalDataCount = $this->Common_model->exequery("SELECT count(*) as total from vm_image_category ",1);
        $totalData = (isset($totalDataCount->total)  && $totalDataCount->total > 0)?$totalDataCount->total:0;           

        $totalFiltered = $totalData; 
        $qry = "SELECT categoryId, categoryName$this->langSuffix as categoryName,addedOn, case when type='1' then 'food' when type='2' then 'drink' else 'unknown' end as type, case when status='0' then 'Active' else 'DeActive' end as status, case when status='0' then 'act fa fa-circle' else 'dct fa fa-circle' end as class from vm_image_category where status != 2 "; 

        if(empty($this->input->post('search')['value']))
        {
            $queryData = $this->Common_model->exequery($qry.$cond);
        }else {
            $search = $this->input->post('search')['value']; 
            if (!empty($search)) {
            	$search = str_replace("'", '', $search); 
            	$search = str_replace('"', '', $search);
            }
            $searchCond = " AND (categoryName$this->langSuffix LIKE  '%".$search."%' OR addedOn LIKE  '%".$search."%' OR status LIKE  '%".$search."%'  ) ";
            $cond = $searchCond.$cond;
            $queryData = $this->Common_model->exequery($qry.$cond);
            $totalDataCount = $this->Common_model->exequery("SELECT count(*) as total from vm_image_category where categoryId > 0 ".$searchCond,1);
            $totalFiltered = (isset($totalDataCount->total)  && $totalDataCount->total > 0)?$totalDataCount->total:0;
        }
        $data = array();
        if(!empty($queryData))
        {
            foreach ($queryData as $row)
            {
                $nestedData['categoryName'] = $row->categoryName;
                $nestedData['type'] = $this->lang->line($row->type);
                $nestedData['addedOn'] = $row->addedOn;
                $nestedData['status'] = $this->lang->line($row->status);
                $nestedData['action'] = '<a href="'.DASHURL.'/admin/image/add-category/'.$row->categoryId .'"  class="btn btn-warning btn-rounded btn-sm  "><i class="fa fa-pencil"></i></a><button onclick="ActivateDeActivateThisRecord(this,\'image_category\','.$row->categoryId.');" class="btn btn-danger btn-rounded btn-sm active " title="Active/DeActive" data-status="'.$row->status.'"><span class="'.$row->class.'"></span></button></button><button onclick="delete_row(this,\'image_category\','.$row->categoryId.');" class="btn btn-danger btn-rounded btn-sm del" title="Delete"><span class="fa fa-trash-o"></span></button>';
                $data[] = $nestedData;
            }
        }         

        return $json_data = array("draw" => intval($this->input->post('draw')), "recordsTotal"    => intval($totalData), "recordsFiltered" => intval($totalFiltered), "data" => $data );
	}

	// admin image sub category list view
	public function image_subcategory_list(){
		$columns = array( 0 => "ps.subcategoryName$this->langSuffix", 1 => "ps.type", 2 => "pc.categoryName$this->langSuffix",3 => 'ps.addedOn', 4 => 'ps.status', 5 => 'subcategoryId');
        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $order = $columns[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];
        $cond = " order by $order $dir LIMIT $start, $limit ";  

        $totalDataCount = $this->Common_model->exequery("SELECT count(ps.subcategoryId) as total from vm_image_subcategory as ps left join vm_image_category as pc on pc.categoryId = ps.categoryId where ps.status != 2 ",1);

        $totalData = (isset($totalDataCount->total)  && $totalDataCount->total > 0)?$totalDataCount->total:0;           

        $totalFiltered = $totalData; 
        $qry = "SELECT ps.subcategoryId, pc.categoryName$this->langSuffix as categoryName,ps.subcategoryName$this->langSuffix as subcategoryName,ps.addedOn, case when ps.type='1' then 'food' when ps.type='2' then 'drink' else 'unknown' end as type, case when ps.status='0' then 'Active' else 'DeActive' end as status, case when ps.status='0' then 'act fa fa-circle' else 'dct fa fa-circle' end as class from vm_image_subcategory as ps left join vm_image_category as pc on pc.categoryId = ps.categoryId where ps.status != 2 "; 

        if(empty($this->input->post('search')['value']))
        {
        	$queryData = $this->Common_model->exequery($qry.$cond);
        }else {
            $search = $this->input->post('search')['value'];
            if (!empty($search)) {
            	$search = str_replace("'", '', $search);
            	$search = str_replace('"', '', $search); 
             } 

            $searchCond = " AND (ps.subcategoryName$this->langSuffix LIKE  '%".$search."%' OR pc.categoryName$this->langSuffix LIKE  '%".$search."%' OR ps.addedOn LIKE  '%".$search."%' OR ps.status LIKE  '%".$search."%'  ) ";
            $cond = $searchCond.$cond;
            $queryData = $this->Common_model->exequery($qry.$cond);
            $totalDataCount = $this->Common_model->exequery("SELECT count(ps.subcategoryId) as total from vm_image_subcategory as ps left join vm_image_category as pc on pc.categoryId = ps.categoryId where ps.status != 2 ".$searchCond,1);

            $totalFiltered = (isset($totalDataCount->total)  && $totalDataCount->total > 0)?$totalDataCount->total:0;

        }

        $data = array();
        if(!empty($queryData))
        {
            foreach ($queryData as $row)
            {
                $nestedData['subcategoryName'] = $row->subcategoryName;
                $nestedData['type'] = $this->lang->line($row->type);
                $nestedData['categoryName'] = $row->categoryName;
                $nestedData['addedOn'] = $row->addedOn;
                $nestedData['status'] = $this->lang->line($row->status);
                $nestedData['action'] = '<a href="'.DASHURL.'/admin/image/add-subcategory/'.$row->subcategoryId .'"  class="btn btn-warning btn-rounded btn-sm  "><i class="fa fa-pencil"></i></a><button onclick="ActivateDeActivateThisRecord(this,\'image_subcategory\','.$row->subcategoryId.');" class="btn btn-danger btn-rounded btn-sm active " title="Active/DeActive" data-status="'.$row->status.'"><span class="'.$row->class.'"></span></button></button><button onclick="delete_row(this,\'image_subcategory\','.$row->subcategoryId.');" class="btn btn-danger btn-rounded btn-sm del" title="Delete"><span class="fa fa-trash-o"></span></button>';
                $data[] = $nestedData;
            }
        }         

        return $json_data = array("draw" => intval($this->input->post('draw')), "recordsTotal"    => intval($totalData), "recordsFiltered" => intval($totalFiltered), "data" => $data );

	}
	// admin image list view
	public function image_list(){


		$columns = array( 0 => "im.imageId",1 => "im.type", 2 => "pc.categoryName$this->langSuffix",3 => "ps.subcategoryName$this->langSuffix", 4 => "im.addedOn", 5 => "im.status", 6 => "im.imageId");

        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $order = $columns[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];

        $cond = " order by $order $dir LIMIT $start, $limit ";
  
        $totalDataCount = $this->Common_model->exequery("SELECT count(im.imageId) as total from vm_image as im left join vm_image_subcategory as ps on ps.subcategoryId = im.subcategoryId left join vm_image_category as pc on pc.categoryId = im.categoryId where im.status != 2",1);
        $totalData = (isset($totalDataCount->total)  && $totalDataCount->total > 0)?$totalDataCount->total:0;
            
        $totalFiltered = $totalData; 
        $qry = "SELECT im.imageId, im.image, im.categoryId, pc.categoryName$this->langSuffix as categoryName, ps.subcategoryName$this->langSuffix as subcategoryName, im.addedOn, case when im.type='1' then 'food' when im.type='2' then 'drink' else 'unknown' end as type, case when im.status='0' then 'Active' else 'DeActive' end as status, case when im.status='0' then 'act fa fa-circle' else 'dct fa fa-circle' end as class	from vm_image as im left join vm_image_subcategory as ps on ps.subcategoryId = im.subcategoryId left join vm_image_category as pc on pc.categoryId = im.categoryId where im.status != 2"; 
        if(empty($this->input->post('search')['value']))
        {            
            $queryData = $this->Common_model->exequery($qry.$cond);
        }else {
            $search = $this->input->post('search')['value']; 
            if (!empty($search)) {             	
            	$search = str_replace("'", '', $search); 
            	$search = str_replace('"', '', $search); 
             }
            $searchCond = " AND (ps.subcategoryName$this->langSuffix LIKE  '%".$search."%' OR pc.categoryName$this->langSuffix LIKE  '%".$search."%' OR im.addedOn LIKE  '%".$search."%' OR im.status LIKE  '%".$search."%'  ) ";
            $cond = $searchCond.$cond;
            $queryData = $this->Common_model->exequery($qry.$cond);

            $totalDataCount = $this->Common_model->exequery("SELECT count(im.imageId) as total from vm_image as im left join vm_image_subcategory as ps on ps.subcategoryId = im.subcategoryId left join vm_image_category as pc on pc.categoryId = im.categoryId where im.status != 2  ".$searchCond,1);
            $totalFiltered = (isset($totalDataCount->total)  && $totalDataCount->total > 0)?$totalDataCount->total:0;
        }
        $data = array();
        if(!empty($queryData))
        {
            foreach ($queryData as $row)
            {	$img = (!empty($row->image))?UPLOADPATH.'/vedmir_images/'.$row->image:DASHSTATIC.'/restaurant/assets/img/image.png'; 
                $nestedData['image'] = '<img src="'.$img.'" width="60px" height="30px">';
                $nestedData['type'] = $this->lang->line($row->type);
                $nestedData['categoryName'] = $row->categoryName;
                $nestedData['subcategoryName'] = $row->subcategoryName;
                $nestedData['addedOn'] = $row->addedOn;
                $nestedData['status'] = $this->lang->line($row->status);
                $nestedData['action'] = '<a href="'.DASHURL.'/admin/image/add-image/'.$row->imageId .'"  class="btn btn-warning btn-rounded btn-sm  "><i class="fa fa-pencil"></i></a><button onclick="ActivateDeActivateThisRecord(this,\'image\','.$row->imageId.');" class="btn btn-danger btn-rounded btn-sm active " title="Active/DeActive" data-status="'.$row->status.'"><span class="'.$row->class.'"></span></button><button onclick="delete_row(this,\'image\','.$row->imageId.');" class="btn btn-danger btn-rounded btn-sm del" title="Delete"><span class="fa fa-trash-o"></span></button>';
                $data[] = $nestedData;

            }
        }
          
        return $json_data = array("draw" => intval($this->input->post('draw')), "recordsTotal"    => intval($totalData), "recordsFiltered" => intval($totalFiltered), "data" => $data );


	}



	// Send Mass Notification
	public function sendMassNotification(){		
    	$response = array('valid' => false, 'msg' => $this->lang->line('dbError'));
		if(((isset($_POST['message']) && !empty($_POST['message'])) || (isset($_POST['message_fr']) && !empty($_POST['message_fr']))) && isset($_POST['sendTo']) && !empty($_POST['sendTo'])) {
			$fileData = $this->upload_img();
			if ($fileData['valid'] && !$fileData['fileName']) {
				return $response = array('valid' => false, 'msg' => "");
			}

			$cond = ''; 

			if ( $_POST['sendTo'] == 'all' )
				$cond = "";
			else if ( $_POST['sendTo'] == 'subscribed' )
				$cond .= " AND um.endDate >= '".date('Y-m-d')."'";
			else if ( $_POST['sendTo'] == 'non-subscribed' )
				$cond .= "AND ( um.endDate < '".date('Y-m-d')."' OR um.endDate = '')";
            //145 charles userid
			//$cond = " AND au.roleId IN (171, 24, 204, 145, 278)";
			//$cond = " AND au.roleId IN (204)";

			$userDeviceTokenData = $this->Common_model->exequery("SELECT DISTINCT au.deviceToken FROM vm_auth AS au INNER JOIN vm_user as us ON (us.userId=au.roleId AND us.status = 0) left join vm_user_memberships as um ON um.userId=au.roleId WHERE au.role='user' AND au.deviceToken != '' AND au.status = 0 AND au.language = 'english' ".$cond." ORDER BY au.roleId desc");
			$frenchUserDeviceTokenData = $this->Common_model->exequery("SELECT DISTINCT au.deviceToken FROM vm_auth AS au INNER JOIN vm_user as us ON (us.userId=au.roleId AND us.status = 0) left join vm_user_memberships as um ON um.userId=au.roleId WHERE au.role='user' AND au.deviceToken != '' AND au.status = 0 AND au.language = 'french' ".$cond." ORDER BY au.roleId desc");
				// print_r($userDeviceTokenData); echo "string"; exit;
				$error= '';
			if (!empty($userDeviceTokenData)) {
				function myfunction($v){ return($v->deviceToken);}
				$userDeviceTokenData = array_map("myfunction",$userDeviceTokenData);
                
                try{
                	$this->common_lib->sendPush(trim($_POST['message']), array('type' => 'massNotification', 'title' => ((isset($_POST['title']))?$_POST['title']:''), 'additionalMessage' => ((isset($_POST['additionalMessage']))?$_POST['additionalMessage']:''), 'imagePath' => (($fileData['fileName'])?UPLOADPATH.'/push_images/'.$fileData['fileName']:'')), $userDeviceTokenData, true, true);
				}catch (\Exception $e) {
					$error = $e->getMessage();
				}
                
            }
            if (!empty($frenchUserDeviceTokenData)) {
				function myfunction1($v){ return($v->deviceToken);}
				$frenchUserDeviceTokenData = array_map("myfunction1",$frenchUserDeviceTokenData);
                
                try{
                	$this->common_lib->sendPush(trim($_POST['message_fr']), array('type' => 'massNotification', 'title' => ((isset($_POST['title_fr']))?$_POST['title_fr']:''), 'additionalMessage' => ((isset($_POST['additionalMessage_fr']))?$_POST['additionalMessage_fr']:''), 'imagePath' => (($fileData['fileName'])?UPLOADPATH.'/push_images/'.$fileData['fileName']:'')), $frenchUserDeviceTokenData, true, true);
				}catch (\Exception $e) {
					$error = $e->getMessage();
				}
                
            }
            $this->Common_model->insert("vm_mass_notification", array('sendTo'=>$_POST['sendTo'], 'title'=>$_POST['title'], 'message'=>$_POST['message'],'additionalMessage' => ((isset($_POST['additionalMessage']))?$_POST['additionalMessage']:''),'title_fr'=>$_POST['title_fr'], 'message_fr'=>$_POST['message_fr'],'additionalMessage_fr' => ((isset($_POST['additionalMessage_fr']))?$_POST['additionalMessage_fr']:''),'title_gr'=>$_POST['title_gr'], 'message_gr'=>$_POST['message_gr'],'additionalMessage_gr' => ((isset($_POST['additionalMessage_gr']))?$_POST['additionalMessage_gr']:''),'title_it'=>$_POST['title_it'], 'message_it'=>$_POST['message_it'],'additionalMessage_it' => ((isset($_POST['additionalMessage_it']))?$_POST['additionalMessage_it']:''),'img' => $fileData['fileName'],'addedOn'=>date('Y-m-d H:i:s')));
                $response = array('valid' => true, 'msg' => ($error)?$error:$this->lang->line('notificationsSent'));
		}
		return $response;
		
	}

	// Upload push image
	public function upload_img(){

		$return['valid'] = false;
		$return['fileName'] = '';
		$return['error'] ='';


		if(!empty($_FILES['uploadImg']['tmp_name'])){
			$return['valid']=true;
			if(is_uploaded_file($_FILES['uploadImg']['tmp_name']) != "") {
				 	
				if($_FILES['uploadImg']['type']=='image/jpeg' || $_FILES['uploadImg']['type']=='image/png' || $_FILES['uploadImg']['type']=='image/jpg'){

					$photoToUpload = 	date('Ymdhis').str_replace(' ', '_', $_FILES['uploadImg']['name']);
					
					$uploadSettings = array();
					$uploadSettings['upload_path']   	=	ABSUPLOADPATH."/push_images";
					$uploadSettings['allowed_types'] 	=	'jpeg|jpg|png';
					$uploadSettings['file_name']	  	= 	$photoToUpload;
					$uploadSettings['inputFieldName']  	=  	"uploadImg";
					$this->load->library('upload', $uploadSettings);
		            if ( ! $this->upload->do_upload('uploadImg')){
		                    $return['error'] = $this->upload->display_errors();
		                    $return['fileName']=false;                  
		            }
		            else{
		                    $data = array('upload_data' => $this->upload->data());
		                    $return['fileName']=$data['upload_data']['file_name']; 
		            }

		        }
			}


		}

		return $return;

	}

	// list of massNotificationlist
	public function massNotificationlist(){
		$columns = array( 0 => 'sendTo', 1 => 'message', 2 => 'addedOn');

		$limit = $this->input->post('length');
        $start = $this->input->post('start');
        $order = $columns[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];

        $cond = " order by $order $dir LIMIT $start, $limit ";
  
        $totalDataCount = $this->Common_model->exequery("SELECT count(*) as total from vm_mass_notification ",1);
        $totalData = (isset($totalDataCount->total)  && $totalDataCount->total > 0)?$totalDataCount->total:0;
            
        $totalFiltered = $totalData; 
          
        if(empty($this->input->post('search')['value']))
        {            
            $queryData = $this->Common_model->exequery("SELECT sendTo, message, DATE_FORMAT(addedOn,'%d-%m-%Y') as addedOn from vm_mass_notification where notificationId > 0 ".$cond);
        }else {
            $search = $this->input->post('search')['value']; 
            $searchCond = " AND (sendTo LIKE  '%".$search."%' OR message LIKE  '%".$search."%' OR addedOn LIKE  '%".$search."%' ) ";
            $cond = $searchCond.$cond;
            $queryData = $this->Common_model->exequery("SELECT sendTo, message, DATE_FORMAT(addedOn,'%d-%m-%Y') as addedOn from vm_mass_notification where notificationId > 0".$cond);

            $totalDataCount = $this->Common_model->exequery("SELECT count(*) as total from vm_mass_notification where notificationId > 0 ".$searchCond,1);
        	$totalFiltered = (isset($totalDataCount->total)  && $totalDataCount->total > 0)?$totalDataCount->total:0;
        }
        $data = array();
        if(!empty($queryData))
        {
            foreach ($queryData as $row)
            {
                $nestedData['sendTo'] = $row->sendTo;
                $nestedData['message'] = $row->message;
                $nestedData['addedOn'] = $row->addedOn;
                $data[] = $nestedData;

            }
        }
          
        return $json_data = array("draw" => intval($this->input->post('draw')), "recordsTotal"    => intval($totalData), "recordsFiltered" => intval($totalFiltered), "data" => $data );
        
	}

	public function getMembershipPlan($postData){
		$subscriptionList = $this->Common_model->exequery("SELECT * FROM vm_subscription_details WHERE subscriptionId='".$postData['planId']."' AND status='0'");
		$subscriptionItem = '<option value="">'.$this->lang->line('selectDuration').'</option>';
		if($subscriptionList) {
			foreach($subscriptionList as $subscriptionVal) {
				$planName = '';
				if($subscriptionVal->period == 1 && $subscriptionVal->duration == 'year')
					$planName = '1 Year';
				else if($subscriptionVal->period == 6)
					$planName = '6 Months';
				else if($subscriptionVal->period == 3)
					$planName = '1 Quarter';
				else if($subscriptionVal->period == 1)
					$planName = '1 Month';
				$subscriptionItem .= '<option value="'.$subscriptionVal->detailId.'">'.$planName.'</option>';
			}
		}
		return array('valid' => true, 'planList' => $subscriptionItem);
	}

	public function getCouponDetails($couponId){
		if( $couponId > 0 ) {
			$couponDetail = $this->Common_model->exequery("SELECT * FROM vm_coupons WHERE couponId='".$couponId."'",1);
			if($couponDetail) {
				if( $couponDetail->offeredType == 2 ) {
					$membership = $this->Common_model->exequery("SELECT * FROM vm_subscription_details WHERE detailId = '".$couponDetail->planId."'",1);
					if($membership) {
						$membershipdetails = $this->getMembershipPlan(array('planId' => $membership->subscriptionId));
						$couponDetail->membershipId = $membership->subscriptionId;
						$couponDetail->planList = $membershipdetails['planList'];
					}
				}
				return array('valid' => true, 'msg' => '', 'couponDetail' => $couponDetail);
			}
			else
				return array('valid' => false, 'msg' => 'InValid Request');
		}
		else 
			return array('valid' => false, 'msg' => 'InValid Request');
	}
    public function updateBusinessName($data) {
		$this->load->config('stripe', TRUE);
        //get settings from config
        $current_private_key = $this->config->item('current_private_key', 'stripe');


        //initialize the client
        require_once './system/static/stripe/init.php';
        \Stripe\Stripe::setApiKey($current_private_key);

		if( $data['hiddenVal'] > 0 ) {
			$restaurantId = $data['hiddenVal'];
			$stripeData = $this->Common_model->exequery("SELECT rsd.*, rs.restaurantName FROM vm_restaurant as rs left join vm_restaurant_stripe_details as rsd on rs.restaurantId = rsd.restaurantId WHERE rs.status != '2' and rs.restaurantId = '".$restaurantId."'",1);
			if( $stripeData ) {
				if($stripeData->stripeAccId != '') {
					try{
						$account = \Stripe\Account::retrieve($stripeData->stripeAccId);
						$account->business_name = $data['business_name'];
						try {
						  if($account->save()){
						  	$this->Common_model->update("vm_restaurant_stripe_details", array("business_name" => $data['business_name']), "restaurantId =".$restaurantId);
						  	return array('valid' => true, 'msg' => $this->lang->line('updatePassword'));
						  }
						  else 
						  	return array('valid' => false, 'msg' => $this->lang->line('dbError'));
				        }
				        catch(Exception $e) {
				        	 return array('valid' => false, 'msg' => $e->getMessage());
				        }
					}
					catch( Exception $e) {
						return array('valid' => false, 'msg' => $e->getMessage());
					}
				}
				else
					return array('valid' => false, 'msg' => 'Stripe Account Not Setup');
			}
			else
				return array('valid' => false, 'msg' => 'InValid Request');
		}
		else
			return array('valid' => false, 'msg' => 'InValid Request');
	}
	/* -------------- Menu Canvas Category List */
	public function menuCanvas_category_list(){
		$columns = array( 0 => "categoryName$this->langSuffix",1 => 'type', 2 => 'addedOn', 3 => 'status', 4 => 'categoryId');
        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $order = $columns[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];
        $cond = " order by $order $dir LIMIT $start, $limit ";  

        $totalDataCount = $this->Common_model->exequery("SELECT count(*) as total from vm_canvas_category ",1);
        $totalData = (isset($totalDataCount->total)  && $totalDataCount->total > 0)?$totalDataCount->total:0;           

        $totalFiltered = $totalData; 
        $qry = "SELECT categoryId, categoryName$this->langSuffix as categoryName,addedOn, case when type='1' then 'food' when type='2' then 'drink' else 'unknown' end as type, case when status='0' then 'Active' else 'DeActive' end as status, case when status='0' then 'act fa fa-circle' else 'dct fa fa-circle' end as class from vm_canvas_category where status != 2 "; 

        if(empty($this->input->post('search')['value']))
        {
            $queryData = $this->Common_model->exequery($qry.$cond);
        }else {
            $search = $this->input->post('search')['value']; 
            if (!empty($search)) {
            	$search = str_replace("'", '', $search); 
            	$search = str_replace('"', '', $search);
            }
            $searchCond = " AND (categoryName$this->langSuffix LIKE  '%".$search."%' OR addedOn LIKE  '%".$search."%' OR status LIKE  '%".$search."%'  ) ";
            $cond = $searchCond.$cond;
            $queryData = $this->Common_model->exequery($qry.$cond);
            $totalDataCount = $this->Common_model->exequery("SELECT count(*) as total from vm_canvas_category where categoryId > 0 ".$searchCond,1);
            $totalFiltered = (isset($totalDataCount->total)  && $totalDataCount->total > 0)?$totalDataCount->total:0;
        }
        $data = array();
        if(!empty($queryData))
        {
            foreach ($queryData as $row)
            {
                $nestedData['categoryName'] = $row->categoryName;
                $nestedData['type'] = $this->lang->line($row->type);
                $nestedData['addedOn'] = $row->addedOn;
                $nestedData['status'] = $this->lang->line($row->status);
                $nestedData['action'] = '<a href="'.DASHURL.'/admin/menucanvas/add-category/'.$row->categoryId .'"  class="btn btn-warning btn-rounded btn-sm  "><i class="fa fa-pencil"></i></a><button onclick="ActivateDeActivateThisRecord(this,\'canvas_category\','.$row->categoryId.');" class="btn btn-danger btn-rounded btn-sm active " title="Active/DeActive" data-status="'.$row->status.'"><span class="'.$row->class.'"></span></button></button><button onclick="delete_row(this,\'canvas_category\','.$row->categoryId.');" class="btn btn-danger btn-rounded btn-sm del" title="Delete"><span class="fa fa-trash-o"></span></button>';
                $data[] = $nestedData;
            }
        }         

        return $json_data = array("draw" => intval($this->input->post('draw')), "recordsTotal"    => intval($totalData), "recordsFiltered" => intval($totalFiltered), "data" => $data );
	}

	// admin Menu Canvas sub category list view
	public function menuCanvas_subcategory_list(){
		$columns = array( 0 => "ps.subcategoryName$this->langSuffix", 1 => "ps.type", 2 => "pc.categoryName$this->langSuffix",3 => 'ps.addedOn', 4 => 'ps.status', 5 => 'subcategoryId');
        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $order = $columns[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];
        $cond = " order by $order $dir LIMIT $start, $limit ";  

        $totalDataCount = $this->Common_model->exequery("SELECT count(ps.subcategoryId) as total from vm_canvas_subcategory as ps left join vm_canvas_category as pc on pc.categoryId = ps.categoryId where ps.status != 2 ",1);

        $totalData = (isset($totalDataCount->total)  && $totalDataCount->total > 0)?$totalDataCount->total:0;           

        $totalFiltered = $totalData; 
        $qry = "SELECT ps.subcategoryId, pc.categoryName$this->langSuffix as categoryName,ps.subcategoryName$this->langSuffix as subcategoryName,ps.addedOn, case when ps.type='1' then 'food' when ps.type='2' then 'drink' else 'unknown' end as type, case when ps.status='0' then 'Active' else 'DeActive' end as status, case when ps.status='0' then 'act fa fa-circle' else 'dct fa fa-circle' end as class from vm_canvas_subcategory as ps left join vm_canvas_category as pc on pc.categoryId = ps.categoryId where ps.status != 2 "; 

        if(empty($this->input->post('search')['value']))
        {
        	$queryData = $this->Common_model->exequery($qry.$cond);
        }else {
            $search = $this->input->post('search')['value'];
            if (!empty($search)) {
            	$search = str_replace("'", '', $search);
            	$search = str_replace('"', '', $search); 
             } 

            $searchCond = " AND (ps.subcategoryName$this->langSuffix LIKE  '%".$search."%' OR pc.categoryName$this->langSuffix LIKE  '%".$search."%' OR ps.addedOn LIKE  '%".$search."%' OR ps.status LIKE  '%".$search."%'  ) ";
            $cond = $searchCond.$cond;
            $queryData = $this->Common_model->exequery($qry.$cond);
            $totalDataCount = $this->Common_model->exequery("SELECT count(ps.subcategoryId) as total from vm_canvas_subcategory as ps left join vm_image_category as pc on pc.categoryId = ps.categoryId where ps.status != 2 ".$searchCond,1);

            $totalFiltered = (isset($totalDataCount->total)  && $totalDataCount->total > 0)?$totalDataCount->total:0;

        }

        $data = array();
        if(!empty($queryData))
        {
            foreach ($queryData as $row)
            {
                $nestedData['subcategoryName'] = $row->subcategoryName;
                $nestedData['type'] = $this->lang->line($row->type);
                $nestedData['categoryName'] = $row->categoryName;
                $nestedData['addedOn'] = $row->addedOn;
                $nestedData['status'] = $this->lang->line($row->status);
                $nestedData['action'] = '<a href="'.DASHURL.'/admin/menucanvas/add-subcategory/'.$row->subcategoryId .'"  class="btn btn-warning btn-rounded btn-sm  "><i class="fa fa-pencil"></i></a><button onclick="ActivateDeActivateThisRecord(this,\'canvas_subcategory\','.$row->subcategoryId.');" class="btn btn-danger btn-rounded btn-sm active " title="Active/DeActive" data-status="'.$row->status.'"><span class="'.$row->class.'"></span></button></button><button onclick="delete_row(this,\'canvas_subcategory\','.$row->subcategoryId.');" class="btn btn-danger btn-rounded btn-sm del" title="Delete"><span class="fa fa-trash-o"></span></button>';
                $data[] = $nestedData;
            }
        }         

        return $json_data = array("draw" => intval($this->input->post('draw')), "recordsTotal"    => intval($totalData), "recordsFiltered" => intval($totalFiltered), "data" => $data );

	}

	//get canvas SubCategory for selected Category
	public function getCanvasCategory(){

		$condition 		= 	"type = '".$_POST["type"]."' and status = 0";
		$resultdata		= 	$this->Common_model->selTableData(PREFIX."canvas_category","categoryId,categoryName$this->langSuffix as categoryName",$condition,"","","","categoryName$this->langSuffix");
		$dd_options =	'<option value="">'.$this->lang->line('selectCategory').'</option>';
		if(valResultSet($resultdata)){
			foreach($resultdata as $rs)
				$dd_options .= '<option value="'.$rs->categoryId.'" >'.$rs->categoryName.'</option>';
		}
		return $dd_options;
	}

	/******************************** Get User Order List ******************************/
	public function getUserOrderList($data){


		$columns = array( 0 => "od.orderId", 1 => "vm_restaurant.restaurantName$this->langSuffix", 2 => "pd.ProductName$this->langSuffix", 3 => "od.amt");

        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $order = $columns[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];

        $cond = " order by $order $dir LIMIT $start, $limit ";
        $filterBy = '?order='.$this->input->post('order')[0]['column'].'&dir='.$dir.'&start='.$start.'&limit='.$limit.'&search='.$this->input->post('search')['value'].'';
  
        $totalDataCount = $this->Common_model->exequery("SELECT count(*) as total FROM `vm_order` od left join vm_restaurant on od.restaurantId = vm_restaurant.restaurantId WHERE od.userId=".$data['userId']." AND od.paymentStatus='Completed'",1);
        $totalData = (isset($totalDataCount->total)  && $totalDataCount->total > 0)?$totalDataCount->total:0;
            
        $totalFiltered = $totalData; 
        $qry = "SELECT od.amt, od.orderId, od.addedOn, vm_restaurant.restaurantName, (SELECT GROUP_CONCAT((CASE WHEN de.isVariable = '1' THEN CONCAT(pd.productName,' (',vd.variableName, ')') ELSE  pd.productName END) )  FROM vm_order_detail de left join vm_variable_product as vd on (de.productId= vd.variableId and de.isVariable = '1') left join vm_product as pd on (CASE WHEN de.isVariable = '1' THEN (pd.productId = vd.productId) ELSE  (pd.productId = de.productId) END)  WHERE orderId = od.orderId) as productName FROM `vm_order` od left join vm_restaurant on od.restaurantId = vm_restaurant.restaurantId WHERE od.userId=".$data['userId']." AND od.paymentStatus='Completed'"; 
        if(empty($this->input->post('search')['value']))
        {            
            $queryData = $this->Common_model->exequery($qry.$cond);
        }
        else {
            $search = $this->input->post('search')['value']; 
            if (!empty($search)) {             	
            	$search = str_replace("'", '', $search); 
            	$search = str_replace('"', '', $search); 
             }
            $searchCond = " AND (vm_restaurant.restaurantName$this->langSuffix LIKE  '%".$search."%' OR od.addedOn LIKE  '%".$search."%' OR od.orderStatus LIKE  '%".$search."%'  ) ";
            $cond = $searchCond.$cond;
            $queryData = $this->Common_model->exequery($qry.$cond);

            $totalDataCount = $this->Common_model->exequery("SELECT count(*) as total FROM `vm_order` od left join vm_restaurant on od.restaurantId = vm_restaurant.restaurantId WHERE od.userId=".$data['userId']." AND od.paymentStatus='Completed' ".$searchCond,1);
            $totalFiltered = (isset($totalDataCount->total)  && $totalDataCount->total > 0)?$totalDataCount->total:0;
        }
        $data = array();
        if(!empty($queryData))
        {
            foreach ($queryData as $row)
            {	

                $nestedData['date'] = date('Y-m-d H:i:s', strtotime($row->addedOn));
                $nestedData['restaurantName'] = $row->restaurantName;
                $nestedData['productName'] = $row->productName;
                $nestedData['price'] = 'CHF '.$row->amt;                
                $data[] = $nestedData;

            }
        }
          
        return $json_data = array("draw" => intval($this->input->post('draw')), "recordsTotal"    => intval($totalData), "recordsFiltered" => intval($totalFiltered), "data" => $data );


	}
	public function getUserCouponList($data){


		$columns = array( 0 => "od.orderId", 1 => "vm_restaurant.restaurantName$this->langSuffix", 2 => "pd.ProductName$this->langSuffix", 3 => "od.amt");

        $limit = $this->input->post('length');
        $start = $this->input->post('start');
       // $order = $columns[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];

        $cond = "";//" order by $order $dir LIMIT $start, $limit ";
       // $filterBy = '?order='.$this->input->post('order')[0]['column'].'&dir='.$dir.'&start='.$start.'&limit='.$limit.'&search='.$this->input->post('search')['value'].'';
  
        $totalDataCount = $this->Common_model->exequery("SELECT count(*) as total FROM vm_user_memberships WHERE vm_user_memberships.userId='".$data['userId']."' AND (vm_user_memberships.couponId != 0 OR vm_user_memberships.referalCouponId != 0)",1);
        $totalData = (isset($totalDataCount->total)  && $totalDataCount->total > 0)?$totalDataCount->total:0;
            
        $totalFiltered = $totalData; 
        $qry = "SELECT vm_user_memberships.paymentDate, (CASE WHEN vm_user_memberships.couponId != 0 then (SELECT couponCode FROM vm_coupons WHERE couponId = vm_user_memberships.couponId) else (SELECT couponCode FROM vm_user_referal_stripe_coupon WHERE referalStripeCouponId = vm_user_memberships.referalCouponId) end) as couponName, (CASE WHEN vm_user_memberships.couponId != 0 then (SELECT (CASE WHEN offeredType =2 then (SELECT CONCAT(vm_subscription_plan.planName, ' (', vm_subscription_details.period ,' ', vm_subscription_details.duration, ')') FROM vm_subscription_details left join vm_subscription_plan on vm_subscription_details.subscriptionId = vm_subscription_plan.Id WHERE vm_subscription_details.detailId = vm_coupons.planId) WHEN offeredType = 3 then (concat(vm_coupons.period,' ',vm_coupons.duration)) WHEN offeredType = 1 then (CONCAT('CHF ', vm_coupons.discountchf)) else (CONCAT(vm_coupons.discountper,'%')) end) FROM vm_coupons WHERE couponId = vm_user_memberships.couponId) else (SELECT CONCAT('CHF ', amount) FROM vm_user_referal_stripe_coupon WHERE referalStripeCouponId = vm_user_memberships.referalCouponId) end) as benefit, (CASE WHEN vm_user_memberships.couponId != 0 then 'No' else 'Yes' end) as walletDiscount FROM vm_user_memberships WHERE vm_user_memberships.userId='".$data['userId']."' AND (vm_user_memberships.couponId != 0 OR vm_user_memberships.referalCouponId != 0 )"; 
        if(empty($this->input->post('search')['value']))
        {            
            $queryData = $this->Common_model->exequery($qry.$cond);
        }
        else {
            $search = $this->input->post('search')['value']; 
            if (!empty($search)) {             	
            	$search = str_replace("'", '', $search); 
            	$search = str_replace('"', '', $search); 
             }
             $searchCond ="";
            //$searchCond = " AND (rs.restaurantName$this->langSuffix LIKE  '%".$search."%' OR pd.productName$this->langSuffix LIKE  '%".$search."%' OR psi.subcategoryitemName$this->langSuffix LIKE  '%".$search."%' OR ps.subcategoryName$this->langSuffix LIKE  '%".$search."%' OR pc.categoryName$this->langSuffix LIKE  '%".$search."%' OR pd.price LIKE  '%".$search."%' OR pd.sortDescription LIKE  '%".$search."%' OR pd.addedOn LIKE  '%".$search."%' OR pd.status LIKE  '%".$search."%'  ) ";
            $cond = $searchCond.$cond;
            $queryData = $this->Common_model->exequery($qry.$cond);

            $totalDataCount = $this->Common_model->exequery("SELECT count(*) as total FROM vm_user_memberships WHERE vm_user_memberships.userId='".$data['userId']."' AND (vm_user_memberships.couponId != 0 OR vm_user_memberships.referalCouponId != 0)",1);
            $totalFiltered = (isset($totalDataCount->total)  && $totalDataCount->total > 0)?$totalDataCount->total:0;
        }
        $data = array();
        if(!empty($queryData))
        {
            foreach ($queryData as $row)
            {	

                $nestedData['dateofUsed'] = date('Y-m-d H:i:s',strtotime($row->paymentDate));
                $nestedData['nameofCoupon'] = $row->couponName;
                $nestedData['benefit'] = $row->benefit; 
                $nestedData['walletDiscount'] = $row->walletDiscount;   
                $data[] = $nestedData;

            }
        }
          
        return $json_data = array("draw" => intval($this->input->post('draw')), "recordsTotal"    => intval($totalData), "recordsFiltered" => intval($totalFiltered), "data" => $data );


	}
	/******************* Get User Membership Log ******************************/
	public function getUserMembershipLog($data){


		$columns = array( 0 => "um.membershipId", 1 => "membershipName", 2 => "um.subscriptionLogStatus", 3 => "um.startDate");

        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $order = $columns[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];

        $cond = " order by $order $dir LIMIT $start, $limit ";
        $filterBy = '?order='.$this->input->post('order')[0]['column'].'&dir='.$dir.'&start='.$start.'&limit='.$limit.'&search='.$this->input->post('search')['value'].'';
  
        $totalDataCount = $this->Common_model->exequery("SELECT count(*) as total FROM `vm_user_memberships` um  WHERE um.userId=".$data['userId']."",1);
        $totalData = (isset($totalDataCount->total)  && $totalDataCount->total > 0)?$totalDataCount->total:0;
            
        $totalFiltered = $totalData; 
        $qry = "SELECT um.*,  (CASE WHEN um.isUpdatedPlan = 1 THEN ( CASE WHEN um.couponId != 0 THEN ( SELECT (CASE WHEN offeredType = 3 THEN couponCode ELSE (CONCAT('COUPON - ', (SELECT CONCAT(sp.planName$this->langSuffix, ' (',sd.period,' ',sd.duration,')') as planName FROM vm_subscription_details sd left JOIN vm_subscription_plan as sp on sp.Id = sd.subscriptionId WHERE detailId =um.planId))) end) as planName FROM vm_coupons WHERE couponId = um.couponId  ) ELSE (SELECT CONCAT(sp.planName$this->langSuffix, ' (',sd.period,' ',sd.duration,')') as planName FROM vm_subscription_details sd left JOIN vm_subscription_plan as sp on sp.Id = sd.subscriptionId WHERE detailId =um.planId) end)  ELSE (SELECT planName as planName FROM `vm_subscription_plan` WHERE vm_subscription_plan.id = um.planId) END ) as membershipName, (CASE WHEN um.isAmabassadarProgram != 0 then (SELECT couponCode FROM vm_ambassador_user WHERE ambassadorId = um.isAmabassadarProgram) else (CASE WHEN um.couponId != 0 then (SELECT couponCode  FROM vm_coupons WHERE couponId = um.couponId) else (SELECT couponCode FROM vm_user_referal_stripe_coupon WHERE referalStripeCouponId = um.referalCouponId) end) end)  as couponCode, (CASE WHEN um.subscriptionLogStatus = 0 then '".$this->lang->line('purchaseMembership')."' when um.subscriptionLogStatus = 1 then '".$this->lang->line('upgradeMembership')."' else '".$this->lang->line('renewMembership')."' end) as membershipStatus FROM `vm_user_memberships` um  WHERE um.userId=".$data['userId']."";  
        if(empty($this->input->post('search')['value']))
        {            
            $queryData = $this->Common_model->exequery($qry.$cond);
        }
        else {
            $search = $this->input->post('search')['value']; 
            if (!empty($search)) {             	
            	$search = str_replace("'", '', $search); 
            	$search = str_replace('"', '', $search); 
             }
            $searchCond = "";//" AND (rs.restaurantName$this->langSuffix LIKE  '%".$search."%' OR pd.productName$this->langSuffix LIKE  '%".$search."%' OR psi.subcategoryitemName$this->langSuffix LIKE  '%".$search."%' OR ps.subcategoryName$this->langSuffix LIKE  '%".$search."%' OR pc.categoryName$this->langSuffix LIKE  '%".$search."%' OR pd.price LIKE  '%".$search."%' OR pd.sortDescription LIKE  '%".$search."%' OR pd.addedOn LIKE  '%".$search."%' OR pd.status LIKE  '%".$search."%'  ) ";
            $cond = $searchCond.$cond;
            $queryData = $this->Common_model->exequery($qry.$cond);

            $totalDataCount = $this->Common_model->exequery("SELECT count(*) as total FROM `vm_user_memberships` um  WHERE um.userId=".$data['userId']."".$searchCond,1);
            $totalFiltered = (isset($totalDataCount->total)  && $totalDataCount->total > 0)?$totalDataCount->total:0;
        }
        $data = array();
        $count = 1;
        if(!empty($queryData))
        {
            foreach ($queryData as $row)
            {	

                $nestedData['count'] = $count;
                $nestedData['startDate'] = date('Y-m-d H:i:s', strtotime($row->startDate));
                $nestedData['membership'] = $row->membershipName;
                $nestedData['membershipStatus'] = $row->membershipStatus;
                $nestedData['userAppliedCoupon'] = $row->couponCode;                
                $data[] = $nestedData;
                $count++;

            }
        }
          
        return $json_data = array("draw" => intval($this->input->post('draw')), "recordsTotal"    => intval($totalData), "recordsFiltered" => intval($totalFiltered), "data" => $data );


	}
	/*********************** Mark User As Ambassdor **************************/
	public function userAsAmbassdor($data) {
		$this->load->config('stripe', TRUE);
        //get settings from config
        $current_private_key = $this->config->item('current_private_key', 'stripe');

        //initialize the client
        require_once './system/static/stripe/init.php';
        \Stripe\Stripe::setApiKey($current_private_key);

		if( @$data['userId'] > 0 && isset($data['status'])) {
			$userId = $data['userId'];
			$data['ambassadorType'] = (isset($data['ambassadorType'])) ? $data['ambassadorType'] : 0;
			$checkUserAmbassdor = $this->Common_model->exequery("SELECT vm_user.*, vm_ambassador_user.ambassadorId, vm_ambassador_user.stripeAccountId, vm_ambassador_user.status as ambassadorStatus, vm_ambassador_user.isMasterAmbassador FROM vm_user LEFT JOIN vm_ambassador_user ON vm_user.userId = vm_ambassador_user.userId WHERE vm_user.userId = '".$userId."'", true);
			if(!$checkUserAmbassdor) 
				return array('valid' => false, 'msg' => $this->lang->line('invalidRequrest'));
			if($checkUserAmbassdor->ambassadorId != '' && !is_null($checkUserAmbassdor->ambassadorId)) {
				$actionStatus = $this->Common_model->update("vm_ambassador_user", array('status' => $data['status'], "isMasterAmbassador" => $data['ambassadorType']), " userId = '".$userId."' AND ambassadorId = '".$checkUserAmbassdor->ambassadorId."'"); 
				//$isUpdatedStatus = ($checkUserAmbassdor->ambassadorStatus == $data['status']) ? $data['status'] : (($data['status'] == 0) ? 1 : 0); 
				if(($checkUserAmbassdor->stripeAccountId == '' || is_null($checkUserAmbassdor->stripeAccountId)) && $data['ambassadorType'] == 0) {
					$stripeAccId = '';
					try {
						$accData = \Stripe\Account::create([
							"country" => "CH",
							"type" => "custom",
							"payout_schedule"=> [ 
								"delay_days"=> 7,
								"interval"=> "monthly",
								"monthly_anchor" => 30,
							],			
							"tos_acceptance" => [
								"date" => time(),
								"ip" => $_SERVER['REMOTE_ADDR'],
							],
						]);

						if (isset($accData->id) && !empty($accData->id))  {						
							$stripeAccId = $accData->id; 
							try {
								$account = \Stripe\Account::retrieve($stripeAccId);
								$account->legal_entity->first_name = $checkUserAmbassdor->userName;
								if($checkUserAmbassdor->lastName != '')
									$account->legal_entity->last_name = $checkUserAmbassdor->lastName;
								$account->legal_entity->type = 'individual';
								try {
									$account->save();
								}
								catch (Exception $e) {
									return array('valid' => false, 'msg' => $e->getMessage());
								}
							}
							catch( Exception $e) {
								return array('valid' => false, 'msg' => $e->getMessage());
							}
						}
					}
					catch(Exception $e) {
						return array('valid' => false, 'msg' => $e->getMessage());
					}
				}
				return array('valid' => true, 'msg' => 'Updated Successfully');
			}
			else {
				$stripeAccId = '';
				if($data['ambassadorType'] == 0) {
					try {
						$accData = \Stripe\Account::create([
							"country" => "CH",
							"type" => "custom",
							"payout_schedule"=> [ 
								"delay_days"=> 7,
								"interval"=> "monthly",
								"monthly_anchor" => 30,
							],			
							"tos_acceptance" => [
								"date" => time(),
								"ip" => $_SERVER['REMOTE_ADDR'],
							],
						]);

						if (isset($accData->id) && !empty($accData->id))  {						
							$stripeAccId = $accData->id; 
							try {
								$account = \Stripe\Account::retrieve($stripeAccId);
								$account->legal_entity->first_name = $checkUserAmbassdor->userName;
								if($checkUserAmbassdor->lastName != '')
									$account->legal_entity->last_name = $checkUserAmbassdor->lastName;
								$account->legal_entity->type = 'individual';
								try {
									$account->save();
								}
								catch (Exception $e) {
									return array('valid' => false, 'msg' => $e->getMessage());
								}
							}
							catch( Exception $e) {
								return array('valid' => false, 'msg' => $e->getMessage());
							}
						}
					}
					catch(Exception $e) {
						return array('valid' => false, 'msg' => $e->getMessage());
					}
				}
				$referalCode = (strlen($checkUserAmbassdor->userName) >= 1 ) ? substr($checkUserAmbassdor->userName, 0, 1) : $checkUserAmbassdor->userName;
				$referalCode .= (strlen($checkUserAmbassdor->lastName) >= 3 ) ? substr($checkUserAmbassdor->lastName, 0, 2) : $checkUserAmbassdor->lastName;
				$referalCode .= rand(1000,9999);
			  	$referalCode = preg_replace('/[^A-Za-z0-9. -]/', '', $referalCode);
				if(empty($referalCode))
					$referalCode = generateStrongPassword(6, false, 'ld');
				$couponCode = $this->common_lib->create_unique_slug(trim($referalCode),"vm_ambassador_user","couponCode",0,"userId",$counter=0, '');
				$actionStatus = $this->Common_model->insert("vm_ambassador_user", array("userId" => $userId, 'couponCode' => strtoupper($couponCode), 'stripeAccountId' => $stripeAccId, "isMasterAmbassador" => $data['ambassadorType'], 'status' => 0, 'addedOn' => date('Y-m-d H:i:s'))); 
				$msg = ($data['ambassadorType'] == 0) ? 'Successfully Mark as Ambassdor' : 'Successfully Mark as Master Ambassdor';
				if($actionStatus)
					return array('valid' => true, 'msg' => 'Successfully Mark as Ambassdor');
				else
					return array('valid' => false, 'msg' => $this->lang->line('dbError'));
			}
		}
		else
			return array('valid' => false, 'msg' => $this->lang->line('invalidRequrest'));
	
	}

	public function setUpAmbassadorAccount($data) {
		$this->load->config('stripe', TRUE);
        //get settings from config
        $current_private_key = $this->config->item('current_private_key', 'stripe');

        //initialize the client
        require_once './system/static/stripe/init.php';
        \Stripe\Stripe::setApiKey($current_private_key);

        if(isset($data['hiddenVal']) && !empty($data['hiddenVal'])  && isset($data['iban_number']) && !empty($data['iban_number'])) {
        	$getAmbassadorInfo = $this->Common_model->exequery("SELECT ambassadorId, stripeAccountId, userId  FROM vm_ambassador_user WHERE userId='".$data['hiddenVal']."'", true);
        	if( $getAmbassadorInfo ) {
        		if( $getAmbassadorInfo->stripeAccountId != '' ) {
        			try{
						$account = \Stripe\Account::retrieve($getAmbassadorInfo->stripeAccountId);
						
						$account->tos_acceptance->date = time();
						$account->tos_acceptance->ip = $_SERVER['REMOTE_ADDR'];
						
						$account->external_account = [
							"object" => "bank_account",
							"country" => 'CH',
							"currency" => 'CHF',					
							"account_number" => $data['iban_number'],
						];

						try {
							if($account->save()){
								$updateStatus = $this->Common_model->update("vm_ambassador_user", array("ibanNumber" => $data['iban_number']), "ambassadorId ='".$getAmbassadorInfo->ambassadorId."' AND userId = '".$data['hiddenVal']."'");
								return array('valid' => true, 'msg' => ( $updateStatus ) ? $this->lang->line('editRecord') : $this->lang->line('dbError') );
							}
						}
						catch(Exception $e) {
							return array('valid' => false, 'msg' => $e->getMessage());
						}
					}
					catch(Exception $e) {
						return array('valid' => false, 'msg' => $e->getMessage());
					}
        		}
        		else
        			return array('valid' => false, 'msg' => 'Stripe Account not Setup');
        	}
        	else
        		return array('valid' => false, 'msg' => 'User not registered as ambassador');

        }
        else
        	return array('valid' => false, 'msg' => $this->lang->line('invalidRequrest'), 'data' => $data);
	}
	public function GetAmbasssadorPlanList(){

		$columns = array( 0 => "planName$this->langSuffix", 1 => "description$this->langSuffix", 2 => "status", 3 => "id");



        $limit = $this->input->post('length');

        $start = $this->input->post('start');

        $order = $columns[$this->input->post('order')[0]['column']];

        $dir = $this->input->post('order')[0]['dir'];



        $cond = " order by $order $dir LIMIT $start, $limit ";

  

        $totalDataCount = $this->Common_model->exequery("SELECT count(*) as total from vm_subscription_plan where status != '2' AND isSubType =2",1);

        $totalData = (isset($totalDataCount->total)  && $totalDataCount->total > 0)?$totalDataCount->total:0;



        $totalFiltered = $totalData; 

        $qry = "SELECT *,planName$this->langSuffix as planName from vm_subscription_plan where status != '2'  AND isSubType =2"; 

        if(empty($this->input->post('search')['value']))

        {            

            $queryData = $this->Common_model->exequery($qry.$cond);

        }else {

            $search = $this->input->post('search')['value'];

            if (!empty($search)) {             	

            	$search = str_replace("'", '', $search); 

            	$search = str_replace('"', '', $search); 

             } 

            $searchCond = " AND (planName$this->langSuffix LIKE  '%".$search."%' OR description$this->langSuffix LIKE  '%".$search."%' OR status LIKE  '%".$search."%' ) ";

            $cond = $searchCond.$cond;

            $queryData = $this->Common_model->exequery($qry.$cond);



            $totalDataCount = $this->Common_model->exequery("SELECT count(*) as total from vm_subscription_plan where status != '2'".$searchCond,1);

            $totalFiltered = (isset($totalDataCount->total)  && $totalDataCount->total > 0)?$totalDataCount->total:0;

        }

        $data = array();

        if(!empty($queryData))

        {



            foreach ($queryData as $row)

            {	$status = ($row->status == 0) ? 'Active' : 'DeActive';


                $nestedData['planName'] = $row->planName;

                $nestedData['description'] = $row->description;

                $nestedData['status'] = $this->lang->line($status);

                $nestedData['action'] = '<a href="'.DASHURL.'/admin/subscription/update-subscription/'.$row->Id.'" class="btn btn-warning btn-rounded btn-sm" data-id="'.$row->planId.'"><i class="fa fa-pencil"></i></a><a href="'.DASHURL.'/admin/subscription/view/'.$row->Id.'"  class="btn btn-primary btn-rounded btn-sm"><i class="fa fa-eye"></i></a><button onclick=\'ActivateDeActivateThisRecord(this,"subscription_plan",'.$row->Id.');\' class="btn btn-danger btn-rounded btn-sm active " title="Active/DeActive" data-status="'.$status.'"><span class="act fa fa-circle"></span></button>';

                $data[] = $nestedData;



            }

        }

          

        return $json_data = array("draw" => intval($this->input->post('draw')), "recordsTotal"    => intval($totalData), "recordsFiltered" => intval($totalFiltered), "data" => $data );

	}

	public function getConvertedUserList($ambassadorId = 0){
		$columns = array( 0 => "um.membershipId", 1 => "planName", 2 => "um.subscriptionLogStatus", 3 => "um.startDate");

        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $order = $columns[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];

        $cond = " order by $order $dir LIMIT $start, $limit ";
        $filterBy = '?order='.$this->input->post('order')[0]['column'].'&dir='.$dir.'&start='.$start.'&limit='.$limit.'&search='.$this->input->post('search')['value'].'';
  
        $totalDataCount = $this->Common_model->exequery("SELECT count(*) as total FROM `vm_user_memberships` um  WHERE um.isAmabassadarProgram=".$ambassadorId."",1);
        $totalData = (isset($totalDataCount->total)  && $totalDataCount->total > 0)?$totalDataCount->total:0;
            
        $totalFiltered = $totalData; 
        $qry = "SELECT um.*, vm_ambassador_commission.amount as rewardAmount , CONCAT(vm_user.userName,' ', vm_user.lastName) as userName, (SELECT CONCAT(sp.planName$this->langSuffix, ' (',sd.period,' ',sd.duration,')') as planName FROM vm_subscription_details sd left JOIN vm_subscription_plan as sp on sp.Id = sd.subscriptionId WHERE detailId =um.planId) as planName FROM `vm_user_memberships` um left join vm_ambassador_user au on um.isAmabassadarProgram = au.ambassadorId left join vm_user on um.userId = vm_user.userId left join vm_subscription_details on um.planId = vm_subscription_details.detailId left join vm_ambassador_commission on um.membershipId = vm_ambassador_commission.membershipId  WHERE um.isAmabassadarProgram='".$ambassadorId."'";  
        if(empty($this->input->post('search')['value']))
        {            
            $queryData = $this->Common_model->exequery($qry.$cond);
        }
        else {
            $search = $this->input->post('search')['value']; 
            if (!empty($search)) {             	
            	$search = str_replace("'", '', $search); 
            	$search = str_replace('"', '', $search); 
             }
            $searchCond = "";//" AND (rs.restaurantName$this->langSuffix LIKE  '%".$search."%' OR pd.productName$this->langSuffix LIKE  '%".$search."%' OR psi.subcategoryitemName$this->langSuffix LIKE  '%".$search."%' OR ps.subcategoryName$this->langSuffix LIKE  '%".$search."%' OR pc.categoryName$this->langSuffix LIKE  '%".$search."%' OR pd.price LIKE  '%".$search."%' OR pd.sortDescription LIKE  '%".$search."%' OR pd.addedOn LIKE  '%".$search."%' OR pd.status LIKE  '%".$search."%'  ) ";
            $cond = $searchCond.$cond;
            $queryData = $this->Common_model->exequery($qry.$cond);

            $totalDataCount = $this->Common_model->exequery("SELECT count(*) as total FROM `vm_user_memberships` um  WHERE um.isAmabassadarProgram=".$data['userId']."".$searchCond,1);
            $totalFiltered = (isset($totalDataCount->total)  && $totalDataCount->total > 0)?$totalDataCount->total:0;
        }
        $data = array();
        $count = 1;
        if(!empty($queryData))
        {
            foreach ($queryData as $row)
            {	

                $nestedData['name'] = $row->userName;
                $nestedData['addedOn'] = date('Y-m-d', strtotime($row->startDate));
                $nestedData['membership'] = $row->planName;
                $rewardAmount = (!is_null($row->rewardAmount))?$row->rewardAmount : 0;
                $nestedData['reward'] = 'CHF '.$rewardAmount;                
                $data[] = $nestedData;
                $count++;

            }
        }
          
        return $json_data = array("draw" => intval($this->input->post('draw')), "recordsTotal"    => intval($totalData), "recordsFiltered" => intval($totalFiltered), "data" => $data );
	}
}
