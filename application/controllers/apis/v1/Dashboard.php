<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';

class Dashboard extends REST_Controller {

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

    // Get Popular Category
    public function getCategoryTree () {
        $items = $this->Common_model->exequery("SELECT categoryId,idParent,categoryName,slug from vm_category"); 

        $childs = $tree = [];

        if (!empty ($items)) {
            // Get all childs
            foreach($items as $item)
                $childs[$item->idParent][] = [
                    'categoryId'    => $item->categoryId,
                    'categoryName'  => $item->categoryName,
                    'slug'          => $item->slug,
                    'totalCourses'  => 0,
                    'isPopularCategory' => true
                ];

            // Set Childs into the category
            foreach($items as $item) 
                if (isset($childs[$item->categoryId]))
                    $item->childs = $childs[$item->categoryId];

            $tree = $childs[-1];
        }   
                     
        return $tree;
    }

    // Get Popular Courses
    public function getPopularCourses () {
        $query = "SELECT category.categoryName, user.userName as userName, user.img as userImg, courses.* from vm_course as courses 
                left join vm_category as category on category.categoryId = courses.categoryId
                left join vm_user as user on user.userId = courses.userId";
        $items = $this->Common_model->exequery($query); 

        $returnData = [];

        if (!empty ($items)) {

            foreach($items as $item) {
                $userImg = "";
                if (isset ($item->userImg) && !empty ($item->userImg) && file_exists($this->userProfileImageAbsolutePath.$item->userImg))
                    $userImg = $this->userProfileImageRelativePath. $item->userImg;
                else 
                    $userImg = "";

                if (isset ($item->thumbnailImage) && !empty ($item->thumbnailImage) && file_exists(RELCOURSETHUMBNAIL.$item->thumbnailImage))
                    $thumbnailImage = ABSCOURSETHUMBNAIL. $item->thumbnailImage;
                else 
                    $thumbnailImage = "";
                
                $returnData[] = [
                    'categoryId'    => $item->categoryId,
                    'categoryName'  => $item->categoryName,
                    'userName'      => $item->userName,
                    'userImg'       => $userImg,
                    'categoryName'  => $item->categoryName,
                    'courseId'      => $item->courseId,
                    'courseName'    => $item->courseName,
                    'slug'          => $item->slug,
                    'thumbnailImage'=> $thumbnailImage,
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

    public function index_get () {
        $returnData = [];
        $returnData['categories'] = $this->getCategoryTree();
        $returnData['popularCourses'] = $this->getPopularCourses();


        $this->response(['status' => TRUE,  "message" => "Dashboard Details", 'data' => $returnData], REST_Controller::HTTP_OK);
    }
}

