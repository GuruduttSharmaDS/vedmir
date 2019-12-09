<?php

defined('BASEPATH') OR exit('No direct script access allowed');


class Commonajax extends CI_Controller {
	private $outputdata 	= array();
	private $langSuffix = '';

	public function __construct(){
		parent::__construct();
		$this->session->set_userdata(PREFIX.'sessRole', "admin");
		$this->common_lib->setSessionVariables();
		$this->langSuffix = $this->lang->line('langSuffix');
	}

	// Vedmir - Ajax landing page

	public function index(){

		$action = '';
		$action = @$_POST['action'];
		$return = array("valid" => false, "data" => array(), "msg" => "UnAuthorised Access!");

		if( $action == "addCategory" ) 
			$return =  $this->AddCategory( $_POST, $_FILES );

		else if($action == "addUpdateStudent" )
			$return = $this->addUpdateStudent($_POST, $_FILES);

		else if( $action == "getStudentList" ) 
			$return = $this->getStudentList($_POST);


		/******************************** Order Section *****************************/
		
		else if($action == "newOrderList" )
			$return = $this->newOrderList();
		/******************************** End Order Section *************************/
		/******************************** User Section *****************************/
		else if($action == "addUpdateUser" )
			$return = $this->addUpdateUser($_POST, $_FILES);
		else if( $action == "getUserList" ) 
			$return = $this->getUserList($_POST);
		/******************************** End User Section *************************/
		/******************************** Common Service *****************************/
		else if( $action == "changeStatus" )
			$return = $this->changeStatus($this->input->post('status'));
		else if( $action == "deleteRecord" )
			$return = $this->changeStatus(2);
		
		/******************************** End Common Service *************************/
		
		/******************************** Get Single Table Records *******************/
		else if($action == "gettabRecords")
			$return =  $this->getTabRecords( $_POST );

		/******************************** End Single Table Records *******************/

		else if($action=="update_login_password")
			$return=$this->updatePassword($_POST);		
		else if($action == "editProfile" )
			$return = $this->editProfile($_POST, $_FILES);
		/********************** End Review section *******************************/
		else if($action == "getAdminNotification" )
			$return = $this->getAdminNotification($_POST);
		else if($action == "GetNotificationList" )
			$return = $this->GetNotificationList($_POST);

		$this->output->set_content_type('application/json')->set_output(json_encode($return));
	}


	/****************************** Common Service ************************/

	function changeStatus($status = 0){

		$updateStatus = 0;
		$status = $this->input->post('event');

		$table = 'vm_'.$this->input->post('tab');
		$queryData = array('status' => $status);
		$cond = $this->input->post('tab')."Id ='".$this->input->post('id')."'";

		if ( $this->input->post('tab')  == 'category'){
			$cond = "categoryId =".$this->input->post('id');
		} elseif ( $this->input->post('tab') == 'notification'){
			$queryData = array('status' => 3);
		} 

		if(!empty($table) && !empty($queryData) && !empty($cond)){
			$updateStatus = $this->Common_model->update($table, $queryData, $cond);
		}

		if( $updateStatus ){
			return array("valid" => true, "data" => array(), "msg" => "Status updated successfully!");
		}
		else 
			return array("valid" => false, "data" => array(), "msg" => "Something Went Wrong");
	}


	
	/******************************** Get Single Table Records *******************/
	function getTabRecords($data) {
		$keys = "";
		if($data['tab'] == 'category' || $data['tab'] == 'zone')
			$keys = ", (case when tb.imageId != 0 then (SELECT concat('".UPLOADPATH."/images/', imageName) FROM "."vm_images  WHERE imageId = tb.imageId ) else '' end) as img  ";
		$singleTabRecords = $this->Common_model->exequery("SELECT tb.* $keys FROM ".$vm_data['tab']." as tb WHERE tb.".$data['key']." = '".$data['value']."'", true);
		if( $singleTabRecords ) 
			return array("valid" => true, "data" => $singleTabRecords, "msg" => "Records Info");
		else
			return array("valid" => false, "data" => array(), "msg" => "No Records Founds");
	}	
	/******************************** End Single Table Records *******************/

	/******************************** Utkarsh Review*****************************/
	public function getsubCategoryName($categoryid) {
		$categorydata = $this->Common_model->exequery("SELECT subcategoryName, subcategoryId, slug FROM "."vm_subcategory WHERE categoryId= ".$categoryid." ");
		return ( $categorydata ) ? $categorydata : array();
	}
	
	/****************************** Product Section ************************/
	function AddCategory($data, $filedata) {

		if(isset($data['categoryName']) && !empty($data['categoryName'])) {
			$categoryId = (isset($data['hiddenval']) && !empty($data['hiddenval']) && $data['hiddenval'] > 0 ) ? $data['hiddenval'] : '';

			//cheking permission role
			$checkRolePermission = $this->common_lib->checkRolePermission(['can_manage_all_product_category',($categoryId)?'can_edit_product_category':'can_create_product_category'],0);
			if(!$checkRolePermission['valid'])
				return $checkRolePermission;

			$insertData['categoryName'] = $data['categoryName'];

			$condArray = array("status != " => "2", "categoryName" => $data['categoryName']);
			if( $categoryId > 0 )
				$condArray['categoryId != '] = $categoryId; 

			$checkExits = $this->Common_model->checkIsExitsorNot("vm_category", "categoryId", $condArray);
			if( $checkExits )
				return array("valid" => false, "data" => array(), "msg" => "Category Already Exist!");
			if(isset($data['slugName']) && !empty($data['slugName'])) {
				$insertData['slug'] = $data['slugName'];
				$condArray = array("status != " => "2", "slug" => $data['slugName']);
				if( $categoryId > 0 )
					$condArray['categoryId != '] = $categoryId; 

				$checkExits = $this->Common_model->checkIsExitsorNot("vm_category", "categoryId", $condArray);
				if( $checkExits )
					return array("valid" => false, "data" => array(), "msg" => "Slug Already Exist!");
			}
			else{
					$categorySlug = $this->common_lib->create_unique_slug(trim($data['categoryName']),"vm_category","slug",$categoryId,"categoryId",$counter=0);
					$insertData['slug']			 =   $categorySlug; 
		    }
			$insertData['isNew'] = (isset($data['isNew']) && !empty($data['isNew'])) ? 1 : 0;
			$insertData['description'] = (isset($data['description']) && !empty($data['description'])) ? $data['description'] : '';
			$insertData['extraDescription'] = (isset($data['extraDescription']) && !empty($data['extraDescription'])) ? $data['extraDescription'] : '';
			$insertData['bannerTitle'] = (isset($data['bannerTitle']) && !empty($data['bannerTitle'])) ? $data['bannerTitle'] : '';
			$insertData['bannerDescription'] = (isset($data['bannerDescription']) && !empty($data['bannerDescription'])) ? $data['bannerDescription'] : '';
			$insertData['metaTitle'] = (isset($data['metaTitle']) && !empty($data['metaTitle'])) ? $data['metaTitle'] : '';
			$insertData['metaDescription'] = (isset($data['metaDescription']) && !empty($data['metaDescription'])) ? $data['metaDescription'] : '';
			$insertData['keywords'] = (isset($data['metaKeywords']) && !empty($data['metaKeywords'])) ? $data['metaKeywords'] : '';
			$insertData['addedBY'] = $this->session->userdata(PREFIX."sessAuthId");
			$insertData['status'] = 0;
			$insertData['addedOn'] = date('Y-m-d H:i:s');

			if(isset($filedata) && !empty($filedata)) {
				$fileInfo = $this->common_lib->uploadImageFile($filedata, "images", false, "uploadIcons" );
				if(!empty($fileInfo['filename'])) {
					$imageId = $this->Common_model->insertUnique("vm_images", array("imageName" => $fileInfo['filename'], "addedBY" => $this->session->userdata(PREFIX."sessAuthId"), "metaTitle" => '', "status" => 0, "addedOn" => date('Y-m-d H:i:s')));
					$insertData['imageId'] = ($imageId) ? $imageId : 0;
				}
			}

			$imageName = $this->uploadImage("category_images", "bannerImg" );
			if($imageName) 
				$insertData['bannerImg'] = $imageName;

			if( $categoryId > 0 )
				$updateStatus = $this->Common_model->update("vm_category", $insertData, "categoryId = ".$categoryId);
			else
				$updateStatus = $this->Common_model->insert("vm_category", $insertData);

			if( $updateStatus )
				return array("valid" => true, "data" => array(), "msg" => "Category Updated Successfully!");
			else
				return array("valid" => true, "data" => array(), "msg" => "Category Added Successfully!");

		}
		else 
			return array("valid" => false, "data" => array(), "msg" => "Category name is required!");
	}


	function categoryList($data) {
			
		//cheking permission of user
		$checkRolePermission = $this->common_lib->checkRolePermission(['can_manage_all_product_category','can_view_product_category'],0);
		if(!$checkRolePermission['valid'])
			return array("draw" => intval($this->input->post('draw')), "recordsTotal"    => intval(0), "recordsFiltered" => intval(0), "data" => array() );

		$columns = array( 0 => "categoryId", 1 => "categoryName", 2 => "isNew", 3 => "status",4 => "categoryId");

        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $order = $columns[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];

        $cond = " order by $order $dir LIMIT $start, $limit ";
  
        $totalDataCount = $this->Common_model->exequery("SELECT count(categoryId) as total from "."vm_category as cat where cat.status != 2",1);
        $totalData = ( isset($totalDataCount->total)  && $totalDataCount->total > 0 ) ? $totalDataCount->total : 0;
            
        $totalFiltered = $totalData; 
        $qry = "SELECT cat.*,  (case when imageId != 0 then (SELECT concat('".UPLOADPATH."/images/', imageName) FROM "."vm_images  WHERE imageId = cat.imageId ) else '' end) as icons from "."vm_category as cat where cat.status != 2"; 
        if(empty($this->input->post('search')['value']))

            $queryData = $this->Common_model->exequery($qry.$cond);
        else {
            $search = $this->input->post('search')['value']; 
            if (!empty($search)) {             	
            	$search = str_replace("'", '', $search); 
            	$search = str_replace('"', '', $search); 
             }
            $searchCond = " AND (cat.categoryName LIKE  '%".$search."%' OR cat.status LIKE  '%".$search."%'  ) ";
            $cond = $searchCond.$cond;
            $queryData = $this->Common_model->exequery($qry.$cond);

            $totalDataCount = $this->Common_model->exequery("SELECT count(categoryId) as total from "."vm_category as cat where cat.status != 2 ".$searchCond,1);

            $totalFiltered = ( isset($totalDataCount->total)  && $totalDataCount->total > 0 ) ? $totalDataCount->total : 0;
        }
        $data = array();

        if(!empty($queryData))
        {
            foreach ($queryData as $row)
            {	
            		

                $nestedData['icons'] = ( $row->icons != '' ) ? '<img src="'.$row->icons.'" width="30px" height="30px">' : "";
                $nestedData['categoryName'] = $row->categoryName;
                $nestedData['isNew'] = ($row->isNew == 1 ) ? "Yes" : "No";
                if ( $row->status == 1 ) {
                	$nestedData['status'] = "DeActive";
                	$btnClass =  "text-danger";
                }
                else {
                	$nestedData['status'] =  "Active";
                	$btnClass =  "text-success";
                }
                //<button class="btn btn-info btn-custom-sm viewCategory" title="View Category" data-id="'.$row->categoryId.'"><i class="fa fa-eye"></i></button>
                $nestedData['action'] = '<button class="btn btn-info btn-custom-sm editCategory" title="Edit" data-id="'.$row->categoryId.'"><i class="fa fa-pen"></i></button><button onclick="ActivateDeActivateThisRecord(this,\'category\','.$row->categoryId.');" class="btn btn-info btn-custom-sm '.$btnClass.'" title="Active/DeActive" data-status="'.$nestedData['status'].'"><i class="fa fa-circle"></i></button><button class="btn btn-info btn-custom-sm deleteCategory" title="Delete Category" onclick="delete_row(this,\'category\','.$row->categoryId.');"><i class="fa fa-trash"></i></button>';
                $data[] = $nestedData;

            }
        }
          
        return $json_data = array("draw" => intval($this->input->post('draw')), "recordsTotal"    => intval($totalData), "recordsFiltered" => intval($totalFiltered), "data" => $data );
	}
	
	// create auth 
	public function createAuth($roleId, $role ='user', $email, $pass = '1', $isUpdate=0){
		$status = '';
		
		if(!empty($role) && !empty($email) && !empty($pass) && !empty($roleId)) {
			if ($isUpdate == 1) {
				$updateData = array();
				if ($email)
					$updateData['email'] = $email;
				if (!empty($pass) && $pass != 1)
					$updateData['password'] = md5($pass);

				$updateData['updatedOn']		=   date('Y-m-d H:i:s');

	            $status = $this->Common_model->update("vm_auth", $updateData,"role = '".$role."' and roleId=".$roleData->roleId);
            	if($status && isset($updateData['email']) && $role == 'user'){
            		$userData = $this->Common_model->selRowData("vm_user_memberships","payerId",array('userId =' => $roleId));
	                if (isset($userData->payerId) && !empty($userData->payerId)) {
	                    try{
	                        $cu = \Stripe\Customer::retrieve($userData->payerId);
	                        $cu->email = trim($updateData['email']);
	                        $cu->save();
	                    }catch (\Exception $e) {
	                        $stripeMsg = $e->getMessage();
	                        $status = 0;
	                     }
	                }
	            }
			}else{
				$cond = " AND emailId = '".$email."'";

				$queryData   =  array();
				$queryData['role']	 		=   $role;
				$queryData['roleId']	 	=   $roleId;
				$queryData['emailId']	 	=   $email;
				$isExist = $this->Common_model->selRowData("vm_auth","","status != 2 ".$cond);		

				if (empty($isExist)) {
					$queryData['password']		=   md5(($pass)?$pass:'123456');
					$queryData['addedOn']		=   date('Y-m-d H:i:s');
					$queryData['updatedOn']		=   date('Y-m-d H:i:s');
					$status 		= 	$this->Common_model->insertUnique("vm_auth", $queryData);
					if($status && !empty($email)){
						$settings = array();
						$settings["template"] 				= 	"welcome_email_tpl.html";
						$settings["email"] 					= 	trim($email);
						$settings["subject"] 				=	"Welcome To ONLINE CAKE";
						$contentarr['[[[LOGINURL]]]']		=	BASEURL.'/login';
						$contentarr['[[[USERNAME]]]']		=	trim($_POST['email']);
						$contentarr['[[[PASSWORD]]]']		=	trim($pass);
						$settings["contentarr"] 			= 	$contentarr;
						try{
							$this->common_lib->sendMail($settings);
						}catch(Exception $e){}
					}
					
				}
			}

		}
		return $status;
	}


	// Upload icon image
	public function uploadFile($dirname = '', $fileName = 'uploadImg', $allowed_types = 'gif|jpg|png|svg|jpeg'){
		$imageName = '';
		if(isset($_FILES[$fileName]['tmp_name']) && is_uploaded_file($_FILES[$fileName]['tmp_name']) != "") {

			$path_parts = pathinfo($_FILES[$fileName]['name']);
			$photoToUpload  = 	md5(rand(1,10)).'.'.$path_parts['extension'];
			$uploadSettings['upload_path']   	=	ABSUPLOADPATH."/".$dirname."/";
			$uploadSettings['allowed_types'] 	=	$allowed_types;
			$uploadSettings['file_name']	  	= 	$photoToUpload;
			$uploadSettings['inputFieldName']  	=  	$fileName;
			$fileUpload = $this->common_lib->_doUpload($uploadSettings);
			if ($fileUpload) {
			    $imgData = $this->upload->data();
				$imageName		=   $imgData['file_name'];
			}
		}
		return $imageName;
	}

	// Upload icon image
	public function uploadGalleryImage($files, $dirname = '', $fileName = 'galleryImage'){
		$uploaddataArray = array();

		$filesdata = isset($files[$fileName])?$files[$fileName]:array();
		if (isset($filesdata['name']) && !empty($filesdata['name'])) {
			foreach($filesdata['name'] as $key => $image) {
				$_FILES['images']['name']= $filesdata['name'][$key];
	            $_FILES['images']['type']= $filesdata['type'][$key];
	            $_FILES['images']['tmp_name']= $filesdata['tmp_name'][$key];
	            $_FILES['images']['error']= $filesdata['error'][$key];
	            $_FILES['images']['size']= $filesdata['size'][$key];

				$array 			= 	explode(' ', $_FILES['images']['name']);
				$filePhotoName 	= 	end($array);
				$photoToUpload  = 	rand(1000,100000).time().'-'.$filePhotoName;
				
				$uploadSettings['upload_path']   	=	ABSUPLOADPATH."/".$dirname."/";
				$uploadSettings['allowed_types'] 	=	'gif|jpg|png|svg|jpeg';
				$uploadSettings['file_name']	  	= 	$photoToUpload;
				$uploadSettings['inputFieldName']  	=  	'images';

			   	if(!is_dir($uploadSettings['upload_path']))
			   		mkdir($uploadSettings['upload_path'], 0777, TRUE);

				$fileUpload = $this->common_lib->_doUpload($uploadSettings);
				if ($fileUpload) {
				    $imgData = $this->upload->data();
				    array_push($uploaddataArray, $imgData['file_name']);
				}
			}
		}

		return $uploaddataArray;
	}

	// admin change status of records
	public function updatePassword($data){	

		$response =	 array('valid'=>false, 'msg'=>'Something is wrong.');
		//Verify current password
		$condPass = "email = '".$this->sessEmail."' AND password = '". md5(trim($_POST['form_current_password']))."'";
		$authId =	$this->Common_model->getSelectedField("vm_auth", "authId", $condPass);
		
		if($authId) {	
			//Update password
			$updateData['password']	=   md5(trim($_POST['password_1']));
			$updateStatus 			= 	$this->Common_model->update("vm_auth", $updateData, $condPass);
			
			if($updateStatus)	{	
				//Send welcome email
				$settings = array();
				$settings["template"] 		=  "password_changed_tpl.html";
				$settings["email"] 			=  $this->sessEmail; 
				$settings["subject"] 		=  "ONLINECAKE Dashboard - password has been changed";
				$contentarr['[[[USERNAME]]]']		=	$this->sessEmail;
				$contentarr['[[[PASSWORD]]]']		=	trim($_POST['password_1']);
				$contentarr['[[[DASHURL]]]']		=	DASHURL."/".$this->sessDashboard."/login";
				$settings["contentarr"] 			= 	$contentarr;
				$this->common_lib->sendMail($settings);	
				$response =	 array('valid'=>true, 'msg'=>'Success! New password is set.');
			}else
				$response['msg']="Failed! Something is wrong.";
		}else
			$response['msg']="Failed! Current password is incorrect.";
		
		return $response;

	}

	public function editProfile($data, $filedata) {
		if(isset($data['employeeName']) && !empty($data['employeeName'])) {
			$employeeId = $this->sessRoleId;

			$condAuth = ""; 
			$condArray = array("status != " => "2", "vendorId" => $this->sessEmployeeAddedById, "roleId" => $this->sessEmployeeRoleId, "employeeName" => $data['employeeName']);
			if( $employeeId > 0 ){
				$condArray['employeeId != '] = $employeeId; 
				$condAuth = " AND roleId !='".$employeeId."'"; 
			}

			$checkExits = $this->Common_model->checkIsExitsorNot("vm_employee", "employeeId", $condArray);
			if( $checkExits )
				return array("valid" => false, "msg" => "Name Already Exist!");

			$isExist = $this->Common_model->exequery("SELECT * FROM vm_auth WHERE status != 2 and (email = '".$_POST['email']."' || phoneNumber = '".$_POST['mobile']."')".$condAuth,1);
			if (isset($isExist->email)){
				if ($isExist->email == $_POST['email'])
	                return array("valid" => false, "msg" => "This email is already in use, Please try with another email Id.");
				else
					return array("valid" => false, "msg" => "Mobile Number is already in use, Please try with another.");
			}

			$insertData = array();
			$insertData['employeeName'] = trim($data['employeeName']);
			$insertData['mobile']		=   trim($_POST['mobile']);
			$insertData['email']	 		=   trim($_POST['email']);
			$insertData['updatedOn']	 	=   date('Y-m-d H:i:s');

			$imageName = $this->uploadImage("employee_images", "uploadIcons" );

					
			if($imageName) 
				$insertData['img'] = $imageName;

			$updateStatus = ($employeeId > 0 )?$this->Common_model->update("vm_employee", $insertData, "employeeId = ".$employeeId):0;

			if( $updateStatus ){
				$authStatus = $this->createAuth($employeeId, $role ='employee', $insertData['email'], '',1);
				
				$this->session->set_userdata(PREFIX."sessUserName",$insertData['employeeName']);
				if(isset($insertData['img']) && !empty($insertData['img']))
						$this->session->set_userdata(PREFIX.'sessRoleImg', UPLOADPATH."/employee_images/".$insertData['img']);
				return array("valid" => true, "msg" => "Profile Updated Successfully!");
			}
			else
				return array("valid" => false, "msg" => "Something went wrong.");

		}
		else 
			return array("valid" => false, "data" => array(), "msg" => "Name is required!");
	}


    // Add User
    public function addUpdateUser($data, $filedata) {
    	//return $data;
		if(isset($data['userName']) && !empty($data['userName'])) {
			$userId = (isset($data['hiddenval']) && !empty($data['hiddenval']) && $data['hiddenval'] > 0 ) ? $data['hiddenval'] : '';

			$isExist = $this->Common_model->selRowData("vm_auth","emailId, status, roleId","emailId = '".$_POST['email']."'");
			if (isset($isExist->email))
				return array("valid" => false, "msg" => "This email is already in use, Please try with another email Id.");
			
			$insertData = array();
			$insertData['userName']	=   trim($_POST['userName']);
			$insertData['email']	=   trim($_POST['email']);
			$insertData['countryCode']	=   trim($_POST['countryCode']);
			$insertData['mobile']	=   trim($_POST['mobile']);
			$insertData['gender'] 	=  $data['gender']; 
			$insertData['dob'] 	=  $data['dob'];  
			$insertData['country'] 	=  $data['country']; 
			$insertData['state'] 	=  $data['state']; 
			$insertData['city'] 	=  $data['city']; 
			$insertData['address'] 	=  $data['address']; 
			$insertData['postalCode'] 	=  $data['postalCode']; 
			$insertData['updatedOn']=   date('Y-m-d H:i:s');
			
			$imageName = $this->uploadFile("user_images");
			if($imageName) 
				$insertData['img'] = $imageName;

			if( $userId > 0 ){
				$updateStatus = $this->Common_model->update("vm_user", $insertData, "userId = ".$userId);
				$userAddId = $userId;
				if( $updateStatus )
					$authStatus = $this->createAuth($userAddId, $role ='user', $insertData['email'], '',1);
			}else {

				$insertData['addedOn'] = date('Y-m-d H:i:s');
				$authStatus = '';
				$this->db->trans_start();
				$updateStatus = $this->Common_model->insertUnique("vm_user", $insertData);
				$userAddId = $updateStatus;
				if($updateStatus)
				   $authStatus = $this->createAuth($updateStatus, $role ='user', $insertData['email'], trim($_POST['password']),0);

				if ($this->db->trans_status() === FALSE || !$authStatus || !$updateStatus){
					$this->db->trans_rollback();
					$updateStatus = false;
				}else
					$this->db->trans_commit();
			}

			if($updateStatus) {
				return array("valid" => true, "msg" => ( $userId > 0 )?"User Updated Successfully!":"User Added Successfully!");
			}
			else
				return array("valid" => false, "msg" => "Something went wrong.");

		}
		else 
			return array("valid" => false, "data" => array(), "msg" => "User name is required!");
	}	
    
    public function getUserList() {
		$columns = array( 0 => "userId", 1 => "userName", 2 => "email", 3 => "mobile", 4 => "addedOn", 5 => "userId");
        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $order = $columns[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];

        $cond = " order by $order $dir LIMIT $start, $limit ";
        $totalDataCount = $this->Common_model->exequery("SELECT count(*) as total from "."vm_user as us where us.status != 2",1);
        $totalData = ( isset($totalDataCount->total)  && $totalDataCount->total > 0 ) ? $totalDataCount->total : 0;
            
        $totalFiltered = $totalData; 
        $qry = "SELECT us.*,(case when img != '' then concat('".UPLOADPATH."/user_images/', img) else '' end) as image from "."vm_user as us where us.status != 2"; 
        if(empty($this->input->post('search')['value']))

            $queryData = $this->Common_model->exequery($qry.$cond);
        else {
            $search = $this->input->post('search')['value']; 
            if (!empty($search))
            	$search = str_replace(['"',"'"], ['', ''], $search);

            $searchCond = " AND (us.userName LIKE  '%".$search."%' OR us.email LIKE  '%".$search."%' OR us.mobile LIKE  '%".$search."%' OR us.status LIKE  '%".$search."%'  ) ";
            $cond = $searchCond.$cond;
            $queryData = $this->Common_model->exequery($qry.$cond);

            $totalDataCount = $this->Common_model->exequery("SELECT count(*) as total from "."vm_user as us where us.status != 2 ".$searchCond,1);

            $totalFiltered = ( isset($totalDataCount->total)  && $totalDataCount->total > 0 ) ? $totalDataCount->total : 0;
        }
        $data = array();

        if(!empty($queryData))
        {
            foreach ($queryData as $row)
            {	
            		
                $nestedData['img'] = ( $row->image != '' ) ? '<img src="'.$row->image.'" width="30px" height="30px">' : "";
                $nestedData['userName'] = $row->userName.''.$row->lastName;
                $nestedData['email'] = $row->email;
                $nestedData['mobile'] = $row->mobile;
                $nestedData['addedOn'] = $row->addedOn;
                if ( $row->status == 1 ) {
                	$nestedData['status'] = "DeActive";
                	$btnClass =  "text-danger";
                }
                else {
                	$nestedData['status'] =  "Active";
                	$btnClass =  "text-success";
                }


                $nestedData['action'] = '<a class="btn btn-primary btn-custom-sm" title="view" href="'.DASHURL.'/admin/user/detail/'.$row->userId.'"><i class="fa fa-eye"></i></a><a class="btn btn-info btn-custom-sm" title="Edit" href="'.DASHURL.'/admin/user/add-user/'.$row->userId.'"><i class="fa fa-pen"></i></a><button onclick="ActivateDeActivateThisRecord(this,\'user\','.$row->userId.');" class="btn btn-light btn-custom-sm '.$btnClass.'" title="Active/DeActive" data-status="'.$nestedData['status'].'"><i class="fa fa-circle"></i></button><button class="btn btn-danger btn-custom-sm" title="Delete User" onclick="delete_row(this,\'user\','.$row->userId.');"><i class="fa fa-trash"></i></button>';

                $data[] = $nestedData;

            }
        }
          
        return $json_data = array("draw" => intval($this->input->post('draw')), "recordsTotal"    => intval($totalData), "recordsFiltered" => intval($totalFiltered), "data" => $data );
	}

	// Parse Add Update Data
	public function parseStudentTeacherAdminUserData ($data) {
		$isExist = $this->Common_model->selRowData("vm_auth","emailId, status, roleId","emailId = '".$_POST['email']."'");
		if (isset($isExist->email))
			return array("valid" => false, "msg" => "This email is already in use, Please try with another email Id.");
		
		$insertData = array();
		$insertData['userName']		=   trim($data['userName']);
		$insertData['email']		=   trim($data['email']);
		$insertData['countryCode']	=   trim($data['countryCode']);
		$insertData['mobile']		=   trim($data['mobile']);
		$insertData['gender'] 		=  $data['gender']; 
		$insertData['dob'] 			=  $data['dob'];  
		$insertData['country'] 		=  $data['country']; 
		$insertData['state'] 		=  $data['state']; 
		$insertData['city'] 		=  $data['city']; 
		$insertData['address'] 		=  $data['address']; 
		$insertData['postalCode'] 	=  $data['postalCode']; 
		$insertData['updatedOn']	=   date('Y-m-d H:i:s');

		return $insertData;
	}

	// Add Student
    public function addUpdateStudent($data, $filedata) {
    	// return $data;
		if(isset($data['userName']) && !empty($data['userName'])) {
			$itemId = (isset($data['hiddenval']) && !empty($data['hiddenval']) && $data['hiddenval'] > 0 ) ? $data['hiddenval'] : '';

			$insertData = $this->parseStudentTeacherAdminUserData ($data);			
			$imageName  = $this->uploadFile("user_images");

			if ($imageName) 
				$insertData['img'] = $imageName;

			if ($itemId > 0) {
				// Update
				$updateStatus = $this->Common_model->update("vm_user", $insertData, "userId = ".$itemId);
				$userAddId 	  = $itemId;
				if ($updateStatus)
					$authStatus = $this->createAuth($userAddId, $role ='user', $insertData['email'], '',1);
			} else {
				$insertData['addedOn'] = date('Y-m-d H:i:s');
				$authStatus = '';
				$this->db->trans_start();
				$updateStatus = $this->Common_model->insertUnique("vm_user", $insertData);
				$userAddId = $updateStatus;
				
				if ($updateStatus)
				   $authStatus = $this->createAuth($updateStatus, $role ='user', $insertData['email'], trim($_POST['password']),0);

				if ($this->db->trans_status() === FALSE || !$authStatus || !$updateStatus) {
					$this->db->trans_rollback();
					$updateStatus = false;
				} else
					$this->db->trans_commit();
			}

			if ($updateStatus)
				return array("valid" => true, "msg" => ( $itemId > 0 )?"User Updated Successfully!":"User Added Successfully!");
			else
				return array("valid" => false, "msg" => "Something went wrong.");

		}
		else 
			return array("valid" => false, "data" => array(), "msg" => "User name is required!");
	}

	// List Student
	public function getStudentList() {
		$columns = array( 0 => "userId", 1 => "userName", 2 => "email", 3 => "mobile", 4 => "addedOn", 5 => "userId");
        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $order = $columns[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];

        $cond = " order by $order $dir LIMIT $start, $limit ";
        $totalDataCount = $this->Common_model->exequery("SELECT count(*) as total from "."vm_user as us where us.status != 2",1);
        $totalData = ( isset($totalDataCount->total)  && $totalDataCount->total > 0 ) ? $totalDataCount->total : 0;
            
        $totalFiltered = $totalData; 
        $qry = "SELECT us.*,(case when img != '' then concat('".UPLOADPATH."/user_images/', img) else '' end) as image from "."vm_user as us where us.status != 2"; 
        if(empty($this->input->post('search')['value']))

            $queryData = $this->Common_model->exequery($qry.$cond);
        else {
            $search = $this->input->post('search')['value']; 
            if (!empty($search))
            	$search = str_replace(['"',"'"], ['', ''], $search);

            $searchCond = " AND (us.userName LIKE  '%".$search."%' OR us.email LIKE  '%".$search."%' OR us.mobile LIKE  '%".$search."%' OR us.status LIKE  '%".$search."%'  ) ";
            $cond = $searchCond.$cond;
            $queryData = $this->Common_model->exequery($qry.$cond);

            $totalDataCount = $this->Common_model->exequery("SELECT count(*) as total from "."vm_user as us where us.status != 2 ".$searchCond,1);

            $totalFiltered = ( isset($totalDataCount->total)  && $totalDataCount->total > 0 ) ? $totalDataCount->total : 0;
        }
        $data = array();

        if(!empty($queryData))
        {
            foreach ($queryData as $row)
            {	
            		
                $nestedData['img'] = ( $row->image != '' ) ? '<img src="'.$row->image.'" width="30px" height="30px">' : "";
                $nestedData['userName'] = $row->userName.''.$row->lastName;
                $nestedData['email'] = $row->email;
                $nestedData['mobile'] = $row->mobile;
				$nestedData['addedOn'] = $row->addedOn;
				$updateStatus = ($row->status == 1) ? 0 : 1;
                if ( $row->status == 1 ) {
                	$nestedData['status'] = "DeActive";
                	$btnClass =  "text-danger";
                }
                else {
                	$nestedData['status'] =  "Active";
                	$btnClass =  "text-success";
                }
				// onclick="delete_row(this,\'student\','.$row->userId.');"
				// onclick="return confirm(\'Are you sure?\')? delete_row(this,\'student\','.$row->userId.'):'';";

                $nestedData['action'] = '<a class="btn btn-primary btn-custom-sm" title="view" href="'.DASHURL.'/admin/student/detail/'.$row->userId.'"><i class="fa fa-eye"></i></a><a class="btn btn-info btn-custom-sm" title="Edit" href="'.DASHURL.'/admin/student/add/'.$row->userId.'"><i class="fa fa-pen"></i></a><button onclick="return confirm(\'Are You Sure Want To Update This Record ?\')? CallHandlerForDeleteRecord(this,\'user\','.$row->userId.','.$updateStatus.'):\'\';" class="btn btn-light btn-custom-sm '.$btnClass.'" title="Active/DeActive" data-status="'.$nestedData['status'].'"><i class="fa fa-circle"></i></button><button class="btn btn-danger btn-custom-sm" title="Delete Student"  onclick="return confirm(\'Are You Sure Want To Delete This Record ?\')? CallHandlerForDeleteRecord(this,\'user\','.$row->userId.',2):\'\';"><i class="fa fa-trash"></i></button>';

                $data[] = $nestedData;

            }
        }
          
        return $json_data = array("draw" => intval($this->input->post('draw')), "recordsTotal"    => intval($totalData), "recordsFiltered" => intval($totalFiltered), "data" => $data );
	}
	

}