<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class Test extends REST_Controller {

    function __construct()
    {
        $client = $current_private_key = $current_public_key = '';
        // Construct the parent class
        parent::__construct();
        $this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key

        //load config
        $this->load->config('stripe', TRUE);

        //get settings from config
        $this->current_private_key = $this->config->item('current_private_key', 'stripe');
        $this->current_public_key  = $this->config->item('current_public_key', 'stripe');

        require_once './system/static/stripe/init.php';
        \Stripe\Stripe::setApiKey($this->current_private_key);
        
    }

    // List OF RESTAURANTS
    public function check_get() {
        $restaurantInfo = $this->Common_model->exequery("SELECT deviceToken FROM vm_auth WHERE role='restaurant' AND roleId='22'",true);
            $restaurantInfo = ( $restaurantInfo ) ? $restaurantInfo : '';
        $this->common_lib->sendPush($this->lang->line("pushsuccessOrder"), array('type' => 'order_recievd', 'orderId' => 201), $restaurantInfo->deviceToken, false);
    }


    public function copyProduct_get($fromRestaurantId, $toRestaurantId){
        $getSubcategory = $this->Common_model->exequery("SELECT * FROM vm_product_subcategory WHERE restaurantId='".$fromRestaurantId."'");
        echo count($getSubcategory).'<br>';
	    exit;
	    if($getSubcategory) {
            echo count($getSubcategory).'<br>';
            foreach($getSubcategory as $subcategoryData) {
                $subcategoryId = $this->Common_model->insertUnique("vm_product_subcategory", array("restaurantId" => $toRestaurantId, "categoryId" => $subcategoryData->categoryId, "subcategoryName" => $subcategoryData->subcategoryName, "subcategoryName_fr" => $subcategoryData->subcategoryName_fr, "subcategoryName_gr" => $subcategoryData->subcategoryName_gr, "subcategoryName_it" => $subcategoryData->subcategoryName_it, "orderNo" => $subcategoryData->orderNo, "slug" => $subcategoryData->slug."-".$toRestaurantId,"addedOn" => date('Y-m-d H:i:s'), "updatedOn" => date('Y-m-d H:i:s'), "status" => $subcategoryData->status));

                $getSubcategoryItem = $this->Common_model->exequery("SELECT * FROM vm_product_subcategoryitem WHERE restaurantId='".$fromRestaurantId."' AND subcategoryId ='".$subcategoryData->subcategoryId."'");
                if($getSubcategoryItem) {
                    foreach($getSubcategoryItem as $getSubcategoryItemData) {
                        $this->Common_model->insert("vm_product_subcategoryitem", array("restaurantId" => $toRestaurantId, "categoryId" => $getSubcategoryItemData->categoryId, "subcategoryId" => $subcategoryId, "subcategoryitemName" => $getSubcategoryItemData->subcategoryitemName, "subcategoryitemName_fr" => $getSubcategoryItemData->subcategoryitemName_fr, "subcategoryitemName_gr" => $getSubcategoryItemData->subcategoryitemName_gr, "subcategoryitemName_it" => $getSubcategoryItemData->subcategoryitemName_it, "orderNo" => $getSubcategoryItemData->orderNo, "slug" => $getSubcategoryItemData->slug."-".$toRestaurantId,"addedOn" => date('Y-m-d H:i:s'), "updatedOn" => date('Y-m-d H:i:s'), "status" => $getSubcategoryItemData->status));
                    }
                }
            }

            $products = $this->Common_model->exequery("SELECT * FROM vm_product WHERE restaurantId='".$fromRestaurantId."'");
            if($products) {
                echo count($products).'<br>';
                foreach( $products as $productsItem) {

                    $subcategoryData = $this->Common_model->exequery("SELECT subcategoryId FROM vm_product_subcategory WHERE subcategoryName = (SELECT subcategoryName from vm_product_subcategory where subcategoryId='".$productsItem->subcategoryId."') AND restaurantId='".$toRestaurantId."'",1);
                    $subcategoryId = ($subcategoryData) ? $subcategoryData->subcategoryId : 0;
                    $subcategoryItemData = $this->Common_model->exequery("SELECT subcategoryitemId FROM vm_product_subcategoryitem WHERE subcategoryitemName = (SELECT subcategoryitemName from vm_product_subcategoryitem where subcategoryitemId='".$productsItem->subcategoryitemId."') AND restaurantId='".$toRestaurantId."'",1);
                    $subcategoryitemId = ($subcategoryItemData) ? $subcategoryItemData->subcategoryitemId : 0;
                    $productId = $this->Common_model->insertUnique("vm_product", array("restaurantId" => $toRestaurantId, "generatedId" => $productsItem->categoryId, "productName" => $productsItem->productName, "productName_fr" => $productsItem->productName_fr, "productName_gr" => $productsItem->productName_gr, "productName_it" => $productsItem->productName_it, "sortDescription" => $productsItem->sortDescription, "sortDescription_fr" => $productsItem->sortDescription_fr, "sortDescription_gr" => $productsItem->sortDescription_gr, "sortDescription_it" => $productsItem->sortDescription_it, "description" => $productsItem->description, "description_fr" => $productsItem->description_fr,"description_gr" => $productsItem->description_gr, "description_it" => $productsItem->description_it, "tags" => $productsItem->tags, "tags_fr" => $productsItem->tags_fr, "tags_gr" => $productsItem->tags_gr, "tags_it" => $productsItem->tags_it, "price" => $productsItem->price, "img" => $productsItem->img, "qrcode" => $productsItem->qrcode, "slug" => $productsItem->slug."-".$toRestaurantId,"addedOn" => date('Y-m-d H:i:s'), "updatedOn" => date('Y-m-d H:i:s'), "status" => $productsItem->status, "categoryId" => $productsItem->categoryId, "subcategoryId" => $subcategoryId, "subcategoryitemId" => $subcategoryitemId, "productType" => $productsItem->productType, "isFeatured" => $productsItem->isFeatured, "isAvailableInFree" => $productsItem->isAvailableInFree, "doNotIncludeInTheMenu" => $productsItem->doNotIncludeInTheMenu, "isOnlyForGirl" => $productsItem->isOnlyForGirl, "orderNo" => $productsItem->orderNo, "subOrderNo" => $productsItem->subOrderNo, "welcomeDrinkOrderNo" => $productsItem->welcomeDrinkOrderNo, "isStockAvailable" => $productsItem->isStockAvailable ));
                    if( $productId ) {
                        if( $productsItem->productType == 1 ) {
                            $variableproductData = $this->Common_model->exequery("SELECT * FROM vm_variable_product WHERE productId='".$productsItem->productId."'");
                            if( $variableproductData ) {
                                foreach( $variableproductData as $variableproductItem ) {
                                    $this->Common_model->insert("vm_variable_product", array("productId" => $productId, "variableName" => $variableproductItem->variableName, "variableName_fr" => $variableproductItem->variableName_fr, "variableName_gr" => $variableproductItem->variableName_gr, "variableName_it" => $variableproductItem->variableName_it, "price" => $variableproductItem->price, "status" => $variableproductItem->status, "addedOn" => date('Y-m-d H:i:s'), "updatedOn" => date('Y-m-d H:i:s')));
                                }
                            }
                        }
                        $addonsCategoryData = $this->Common_model->exequery("SELECT * FROM vm_product_addons_category WHERE productId='".$productsItem->productId."'");
                        if($addonsCategoryData) {
                            foreach( $addonsCategoryData as $addonsCategoryItem ) {
                                $addonsCatId = $this->Common_model->insertUnique("vm_product_addons_category", array("productId" => $productId, "categoryName" => $addonsCategoryItem->categoryName, "categoryName_fr" => $addonsCategoryItem->categoryName_fr, "categoryName_gr" => $addonsCategoryItem->categoryName_gr, "categoryName_it" => $addonsCategoryItem->categoryName_it, "required" => $addonsCategoryItem->required, "choice" => $addonsCategoryItem->choice, "isStockAvailable" => $addonsCategoryItem->isStockAvailable, "status" => $addonsCategoryItem->status, "addedOn" => date('Y-m-d H:i:s'), "updatedOn" => date('Y-m-d H:i:s') ));
                                if( $addonsCatId ) {
                                    $productAddonsData = $this->Common_model->exequery("SELECT * FROM vm_product_addons WHERE productId='".$productsItem->productId."' AND addonsCatId='".$addonsCategoryItem->addonsCatId."'");
                                    if( $productAddonsData ) {
                                        foreach( $productAddonsData as $productAddonsItem ) {
                                            $this->Common_model->insertUnique("vm_product_addons", array("productId" => $productId, "addonsCatId" => $addonsCatId,  "addonsName" => $productAddonsItem->addonsName, "addonsName_fr" => $productAddonsItem->addonsName_fr, "addonsName_gr" => $productAddonsItem->addonsName_gr, "addonsName_it" => $productAddonsItem->addonsName_it, "price" => $productAddonsItem->price, "isStockAvailable" => $productAddonsItem->isStockAvailable, "status" => $productAddonsItem->status, "addedOn" => date('Y-m-d H:i:s'), "updatedOn" => date('Y-m-d H:i:s') ));
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    public function timecheck_get(){
        $restaurantData =   $this->Common_model->exequery("SELECT * from vm_restaurant where status != 2");
        $date = date('Y-m-d H:i:s');        
        $days = array('sunday','monday','tuesday','wednesday','thursday','friday','saturday');
        if (!empty($restaurantData)) {
            foreach ($restaurantData as $key => $restaurant) {
                $isExit = $this->Common_model->exequery("Select timeId from vm_restaurant_time where restaurantId=".$restaurant->restaurantId." AND status !=2",1);
                if (!$isExit) {
                    $insertQry = "";
                        $closeDays = ($restaurant->closeDays)?explode(',', $restaurant->closeDays):array();
                        foreach ($days as $day) {
                            if (!in_array($day, $closeDays) && !empty($restaurant->sundayOpen) && !empty($restaurant->sundayClose)) {
                                
                                $qry = "( '".$restaurant->restaurantId."', 'regular', '', '".$day."', '".$restaurant->sundayOpen."', '".$restaurant->sundayClose."', '0', '".$date."', '".$date."')";
                                $insertQry .= ($insertQry)?','.$qry:$qry;
                            }
                        }
                        if ($insertQry) {

                            $finalQry = "INSERT INTO `vm_restaurant_time` (`restaurantId`, `openCloseType`, `week`, `day`, `open`, `close`, `status`, `addedOn`, `updatedOn`) VALUES ".$insertQry.";";
                            $this->Common_model->runquery($finalQry);
                        }
                }
            }
        }
        
        
    }
    public function downloaduser_get(){
            $userList = $this->Common_model->exequery("SELECT * FROM vm_user");
            $msg = '';
            if($userList) {
                $count = 1;
                foreach($userList as $userDetails) {
                    $msg.="<tr><td>".$count."</td><td>".$userDetails->userName." ".$userDetails->lastName."</td><td>".$userDetails->email."</td></tr>";
                    $count++;
                }
            }
            $msg1="";
            $msg1.="<tr><th>Sr No.</th><th>User Name</th><th>Email Address</th></tr>";
            
            $data="<html>";
            $data.="<meta http-equiv=\"Content-Type\" content=\"text/html; charset=Windows-1252\">";
            $data.="<body class='fixed-top'>";
            $data.="<table cellspacing='0' border='1' style='text-align:center;'><tbody>";
            $data.=$msg1.$msg;
            $data.='</table>';
            $data.="</body>";
            $data.="</html>";
            
            $filename = 'users_'. date('Y/m/d') . ".xls";
            header("Content-Disposition: attachment; filename=\"$filename\"");
            header("Content-Type: application/vnd.ms-excel");
            print "$header\n$data";
    }
}
