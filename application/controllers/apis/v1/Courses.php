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

        $this->userProfileImageRelativePath = "/system/static/uploads/user_images/";
        $this->userProfileImageAbsolutePath = BASEPATH . "static". DIRECTORY_SEPARATOR . "uploads". DIRECTORY_SEPARATOR. "user_images".DIRECTORY_SEPARATOR;
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
                
                $returnData = [
                    'categoryId'    => $item->categoryId,
                    'categoryName'  => $item->categoryName,
                    'userName'      => $item->userName,
                    'userImg'       => $userImg,
                    'categoryName'  => $item->categoryName,
                    'courseId'      => $item->courseId,
                    'courseName'    => $item->courseName,
                    'slug'          => $item->slug,
                    'thumbnailImage'=> $item->thumbnailImage,
                    'courseTitle'   => $item->courseTitle,
                    'courseDescription'          => $item->courseDescription,
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
}

