<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';

class Courses extends REST_Controller {

    function __construct()  {
        // Construct the parent class
        parent::__construct();
        $this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['users_post']['limit'] = 500; // 100 requests per hour per user/key
        $this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key

        // Check User Authorization
        $this->checkUserAuthentication ();

        $this->userProfileImageRelativePath = BASEURL. "/system/static/uploads/user_images/";
        $this->userProfileImageAbsolutePath = BASEPATH . "static". DS . "uploads". DS. "user_images".DS;
    }

    // Get Popular Courses
    public function getCourseDetails ($courseId) {
        $query = "SELECT category.categoryName, user.userName as userName, user.img as userImg, courses.* from vm_course as courses 
                left join vm_category as category on category.categoryId = courses.categoryId
                left join vm_user as user on user.userId = courses.userId where courses.courseId = $courseId";
        $items = $this->Common_model->exequery($query); 

        $returnData = [];

        if (!empty ($items)) {

            foreach($items as $item) {
                $userImg = "";
                if (isset ($item->userImg) && !empty ($item->userImg) && file_exists($this->userProfileImageAbsolutePath.$item->userImg))
                    $userImg = $this->userProfileImageRelativePath. $item->userImg;
                else 
                    $userImg = "";
                
                if (isset ($item->thumbnailImage) && !empty ($item->thumbnailImage) && file_exists(ABSCOURSETHUMBNAIL.$item->thumbnailImage))
                    $thumbnailImage = ABSCOURSETHUMBNAIL. $item->thumbnailImage;
                else 
                    $thumbnailImage = "";

                $returnData = [
                    'categoryId'    => $item->categoryId,
                    'categoryName'  => $item->categoryName,
                    'userName'      => $item->userName,
                    'userAboutUs'   => "",
                    'userTotalStudent' => 0,
                    'userTotalCourses' => 0,
                    'userImg'       => $userImg,
                    'categoryName'  => $item->categoryName,
                    'courseId'      => $item->courseId,
                    'courseName'    => $item->courseName,                    
                    'courseTitle'   => $item->courseTitle,
                    'courseDescription' => $item->courseDescription,
                    'courseSlug'    => $item->slug,
                    'thumbnailImage'=> $thumbnailImage,
                    'courseVideo'   => "",
                    'coursePrice'   => $item->coursePrice,
                    'coursePriceAfterDiscount' => $item->coursePrice,
                    'updatedOn'     => $item->updatedOn,
                    'addedOn'       => $item->addedOn,
                    'totalLacture'  => 0,
                    'totalHours'    => 0
                ];
            }
        }
                     
        return $returnData;
    }

    public function index_get ($courseId = 0) {
        $returnData = $this->getCourseDetails($courseId);
        $this->response(['status' => TRUE,  "message" => "Course Details", 'data' => $returnData], REST_Controller::HTTP_OK);
    }

    // Validate Save UserViewCourse
    public function validateSaveUserViewCourse () {
        $userId     = (isset($_POST['userId']) || !empty($_POST['userId'])) ? $_POST['userId'] : "";
        $courseId   = (isset($_POST['courseId']) || !empty($_POST['courseId'])) ? $_POST['courseId'] : "";

        if ($userId == "")
            $this->response(['status' => FALSE,'message' => $this->lang->line('userIdRequired')], REST_Controller::HTTP_BAD_REQUEST);
        if ($courseId == "")
            $this->response(['status' => FALSE,'message' => $this->lang->line('courseIdRequired')], REST_Controller::HTTP_BAD_REQUEST);

        $query = "SELECT count(1) as count from  vm_user_recently_view_course where userId = $userId and courseId = $courseId";
        $items = $this->Common_model->exequery($query,true);

        if ($items->count > 0) {
            $this->Common_model->del("vm_user_recently_view_course", ["userId" => $userId, "courseId" => $courseId]);
        }

        return true;
    }

    // Parse Save UserViewCourse
    public function parseSaveUserViewCourse () {
        $queryData  =  array();                
        $queryData['userId']    =   trim($_POST['userId']);
        $queryData['courseId']  =   trim($_POST['courseId']);       
        $queryData['addedOn']   =   date('Y-m-d H:i:s');
        return $queryData;
    }

    // Save User View Course
    public function saveUserViewCourse_post() {
        // Validate Data
        $this->validateSaveUserViewCourse ();   
               
        // Insert data in "User Table"
        $queryData      = $this->parseSaveUserViewCourse ();     
        $insertStatus   =   $this->Common_model->insertUnique("vm_user_recently_view_course", $queryData);

        if ($insertStatus) {                     
            $this->response(['status' => TRUE,  "message" => "success", 'data' => $insertStatus], REST_Controller::HTTP_OK);
        } else {
            $status['status'] = false;
            $status['message'] = $this->lang->line('failedAddUser');
            $this->response($status, REST_Controller::HTTP_CONFLICT);
        }
        
    }

    // Get Popular Courses
    public function userViewCourse () {
       
        $userId     = (isset($_POST['userId']) || !empty($_POST['userId'])) ? $_POST['userId'] : "";

        $query = "SELECT course.*,  user.*
        from vm_user_recently_view_course as crvc 
        left join vm_course as course on course.courseId = crvc.courseId
        left join vm_user as user on user.userId = crvc.userId 
        where crvc.userId = $userId";
        
        $items = $this->Common_model->exequery($query); 
        $returnData = [];

        if (!empty ($items)) {

            foreach($items as $item) {
                             
                $returnData[] = [
                    'courseId'      => $item->courseId,
                    'courseName'    => $item->courseName,                    
                    'courseTitle'   => $item->courseTitle,
                    'courseDescription' => $item->courseDescription,
                    'courseSlug'    => $item->slug,
                    'thumbnailImage'=> $item->thumbnailImage,
                    'courseVideo'   => "",
                    'coursePrice'   => $item->coursePrice,
                    'coursePriceAfterDiscount' => $item->coursePrice,
                    'updatedOn'     => $item->updatedOn,
                    'addedOn'       => $item->addedOn,
                    'totalLacture'  => 0,
                    'totalHours'    => 0
                ];
            }
        }
                     
        return $returnData;
    }

    // get UserViewCourse
    public function getUserViewCourse_post() {

        $userId     = (isset($_POST['userId']) || !empty($_POST['userId'])) ? $_POST['userId'] : "";
        if ($userId == "")
            $this->response(['status' => FALSE,'message' => $this->lang->line('userIdRequired')], REST_Controller::HTTP_BAD_REQUEST);

        $userViewCourse = $this->userViewCourse ();   

        if ($userViewCourse) {                     
            $this->response(['status' => TRUE,  "message" => "success", 'data' => $userViewCourse], REST_Controller::HTTP_OK);
        } else {
            $status['status'] = false;
            $status['message'] = $this->lang->line('failedAddUser');
            $this->response($status, REST_Controller::HTTP_CONFLICT);
        }
        
    }
}

