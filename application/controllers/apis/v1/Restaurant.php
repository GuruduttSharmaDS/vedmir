<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class Restaurant extends REST_Controller {

    function __construct()
    {
        $client = $current_private_key = $current_public_key = '';
        $testmode=0;
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
        $this->testmode  =   ($this->config->item('testmode', 'stripe') == 'on')? 1 :0;
        require_once './system/static/stripe/init.php';
        \Stripe\Stripe::setApiKey($this->current_private_key);
        
    }

    // List OF RESTAURANTS

    public function getrestaurantwithproducts_get(){
        $token = $this->input->get_request_header('Authorization', TRUE);
        $langSuffix = $this->lang->line('langSuffix');
        if($token != '' && $roleId = $this->common_lib->validateToken($token)){            
            $restaurantId = (int) $this->get('restaurantId');
            // Validate the id.
            if ($restaurantId <= 0)
                $this->response(['status' => FALSE,'message' => $this->lang->line('inValidResturantId') ], REST_Controller::HTTP_BAD_REQUEST);
            $distanceWhere = '10 as distance';
            $distanceCond = ' ORDER BY restaurantId desc';
            if(isset($_REQUEST['lat']) && !empty($_REQUEST['lat']) && isset($_REQUEST['lang']) && !empty($_REQUEST['lang'])){
                $distanceWhere = "( 111.111 * DEGREES(acos( cos( radians(".$_REQUEST['lat'].") ) * cos( radians( lat ) ) * cos( radians( lang ) - radians(".$_REQUEST['lang'].") ) + sin( radians(".$_REQUEST['lat'].") ) * sin(radians(lat)) ))) AS distance";
                $distanceCond = ' ORDER BY distance ASC';
            }
            $restaurantData['restaurantData'] = $this->Common_model->selRowData("vm_restaurant","`restaurantId`, `generatedId`, isRestaurantOpen, isKitchenOpen, acceptingOrder, acceptingFoodOrder, acceptingDrinkOrder, totalTable, openCloseType, restaurantName".$langSuffix." as restaurantName, `since`, `website`, `facebookPageUrl`, iframeUrl, `googlePageUrl`, `instagramPageUrl`, `youtubePageUrl`, contactName".$langSuffix." as contactName, `email`, `mobile`, CONCAT(`address1".$langSuffix."`,' ',`address2".$langSuffix."`) as address, `city".$langSuffix."` as city, `state".$langSuffix."` as state, `country".$langSuffix."` as country, `postalCode`, `lat`, `lang`, `about".$langSuffix."` as about, (SELECT case when  sum(overallRating) / count(*) is not null  then format(sum(overallRating) / count(*),1) else 0 end FROM vm_resturant_rating WHERE restaurantId = vm_restaurant.restaurantId AND status='0') as `rating`, (SELECT case when  sum(priceRating) / count(*) is not null  then format(sum(priceRating) / count(*),1) else 0 end FROM vm_resturant_rating WHERE restaurantId = vm_restaurant.restaurantId AND status='0') as `priceRating`, (SELECT case when  sum(qualityRating) / count(*) is not null  then format(sum(qualityRating) / count(*),1) else 0 end FROM vm_resturant_rating WHERE restaurantId = vm_restaurant.restaurantId AND status='0') as `qualityRating`, (SELECT case when  sum(serviceRating) / count(*) is not null  then format(sum(serviceRating) / count(*),1) else 0 end FROM vm_resturant_rating WHERE restaurantId = vm_restaurant.restaurantId AND status='0') as `serviceRating`,(SELECT case when  sum(ambienceRating) / count(*) is not null  then format(sum(ambienceRating) / count(*),1) else 0 end FROM vm_resturant_rating WHERE restaurantId = vm_restaurant.restaurantId AND status='0') as `ambienceRating`, `tax`, `img` , (case when logo !='' then concat('".UPLOADPATH."/restaurant_images/',logo) else '".UPLOADPATH."/default/restaurant_default.jpg' end ) as logo, (SELECT case when count(*) > 0 then 1 else 0 end FROM vm_product WHERE restaurantId = vm_restaurant.restaurantId AND categoryId!='5') as foodAvailable, ".$distanceWhere," restaurantId = '".$restaurantId."'");

            if (!empty($restaurantData['restaurantData'])){
                if (!empty($restaurantData['restaurantData']->img))
                    $restaurantData['restaurantData']->img = UPLOADPATH.'/restaurant_images/'.$restaurantData['restaurantData']->img;
                
                
                $day = strtolower(date('l'));
                $openCloseData = $this->common_lib->checkrestaurantopenclosed($restaurantId, $restaurantData['restaurantData']->openCloseType);
                $restaurantData['restaurantData']->openCloseData = $openCloseData;
                $restaurantData['restaurantData']->day = $openCloseData['currentDay'];
                $restaurantData['restaurantData']->isOpen = $openCloseData['isOpen'];
                $restaurantData['restaurantData']->nextOpenCLoseTiming = ($openCloseData['isOpen'])?$openCloseData['closeTime']:$openCloseData['nextOpenTime'];
                $restaurantData['restaurantData']->nextOpenCLoseString = $openCloseData['nextOpenCLoseString'];

                $restaurantData['restaurantData']->weekDaysTiming = $this->common_lib->weekDaysTiming($restaurantId, $restaurantData['restaurantData']->openCloseType, $openCloseData);

                $resturantgallery = $this->Common_model->exequery("SELECT CONCAT('".UPLOADPATH."/restaurant_gallary_images/'".", image) as image  FROM vm_restaurant_gallary_img  WHERE  restaurantId = '".$restaurantId."' ");
                $resturantgallery = ($resturantgallery) ? $resturantgallery : array();
                if(!empty($restaurantData['restaurantData']->img))
                    array_unshift($resturantgallery, array('image' => $restaurantData['restaurantData']->img)); 
                $restaurantData['restaurantData']->restaurantGallaryData = $resturantgallery;
                $restaurantData['productData'] =  array();
                $userDrinkAvailable = $this->Common_model->exequery("SELECT count(*) as free,(SELECT servedStatus FROM `vm_user_daily_drink` WHERE userId = ".$roleId['roleId']." AND DATE(currentTimestamp) = '".date('Y-m-d')."'  AND (servedStatus='1' OR servedStatus='0')) as servedStatusVal,(SELECT (CASE WHEN count(*) > 0 then 1 else 0 end) as membership_count FROM vm_user_memberships WHERE startDate <= '".date('Y-m-d')."' AND endDate >= '".date('Y-m-d')."' AND userId=".$roleId['roleId']." AND subscriptionStatus ='Active' ORDER BY membershipId desc limit 0,1) as `membership` FROM `vm_user_daily_drink` WHERE userId = ".$roleId['roleId']." AND DATE(currentTimestamp) = '".date('Y-m-d')."' AND (servedStatus='1' OR servedStatus='0')",true);            
                           
                if ($userDrinkAvailable->free == 0 && $userDrinkAvailable->membership != 0)
                    $restaurantData['free_drink'] = '1';
                else {
                    $restaurantData['free_drink'] = ( $userDrinkAvailable->servedStatusVal == 0 ) ? '2' : (($userDrinkAvailable->servedStatusVal == 1 ) ? '3' : '1' );
                }
                $restaurantData['membership'] = $userDrinkAvailable->membership;
                 
               
                 $getHappyhourData = $this->Common_model->exequery("SELECT happyhourId, day, startTime, endTime from vm_happyhour where status != 2 AND day = '".$day."' AND restaurantId = '".$restaurantId."'");
                $openHappyhourIds = '';
                $happyhourData = new stdClass();
                if (!empty($getHappyhourData)) {
                    foreach ($getHappyhourData as $key => $happyhour) {
                        $isOpen = $this->common_lib->getrestaurantopenclosed($happyhour->startTime.' - '.$happyhour->endTime);
                        if($isOpen)
                            $openHappyhourIds .= ($openHappyhourIds)?','.$happyhour->happyhourId:$happyhour->happyhourId;
                    }
                    
                    if($openHappyhourIds){
                        $ppCond = (isset($roleId['gender']) && strtolower($roleId['gender']) == 'female')?" ":" and pd.isOnlyForGirl = '0' ";
                        $products = $this->Common_model->exequery("SELECT * FROM (SELECT hp.happyhourProductId, hp.productId, hp.price, hp.variableId, (CASE WHEN hp.variableId > 0 then (SELECT price FROM vm_variable_product where variableId = hp.variableId) else  pd.price end) as oldPrice, pd.productName$langSuffix as productName, pd.isStockAvailable, (CASE WHEN pd.img REGEXP ('^[0-9]+$') THEN (SELECT (CASE WHEN im.image != '' THEN CONCAT('".UPLOADPATH."','/vedmir_images/',im.image) when pd.categoryId=4 then '".UPLOADPATH."/default/food_default.jpg' else '".UPLOADPATH."/default/drink_default.jpg' end ) FROM vm_image as im WHERE im.status = 0 and im.image != '' and im.imageId= pd.img) WHEN pd.img != '' THEN CONCAT('".UPLOADPATH."','/product_images/',pd.img) when pd.categoryId=4 then '".UPLOADPATH."/default/food_default.jpg' else '".UPLOADPATH."/default/drink_default.jpg' end ) as img, (SELECT categoryName$langSuffix from vm_product_category where categoryId = pd.categoryId) as type, (SELECT subcategoryName$langSuffix from vm_product_subcategory where subcategoryId = pd.subcategoryId) as categoryName FROM vm_happyhour_product as hp left join vm_product as pd on (pd.productId = hp.productId AND pd.status = 0) where hp.status = 0 AND hp.happyhourId IN (".$openHappyhourIds.")".$ppCond." order by hp.price asc  ) as tt group by productId");
                        if( $products ) {
                            foreach($products as $productItems) {
                                if( $productItems->variableId > 0 ) {
                                    $checkAllVariablesOptions = $this->Common_model->exequery("SELECT vd.variableName$langSuffix as variableName, vd.price as price, vd.price as oldPrice, vd.variableId, vd.productId  FROM  vm_variable_product as vd left join vm_product as pd on (pd.productId = vd.productId AND pd.status = 0) where pd.status = 0 AND pd.productId = ".$productItems->productId);
                                    if($checkAllVariablesOptions) {
                                        foreach($checkAllVariablesOptions as $variableProductInfo) {
                                            $happyHourInfoWithVariable = $this->Common_model->exequery("SELECT hp.happyhourProductId,  hp.price FROM vm_happyhour_product hp WHERE hp.status = 0 AND hp.variableId = ".$variableProductInfo->variableId." and productId = ".$variableProductInfo->productId." AND hp.happyhourId IN (".$openHappyhourIds.")", 1);
                                            if($happyHourInfoWithVariable) {
                                                $variableProductInfo->price = $happyHourInfoWithVariable->price;
                                                $variableProductInfo->happyhourProductId = $happyHourInfoWithVariable->happyhourProductId;
                                                $variableProductInfo->isHappyhour = true;

                                            }
                                            else
                                                $variableProductInfo->isHappyhour = false;
                                        }
                                    }
                                    else 
                                        $checkAllVariablesOptions = new stdClass();
                                    //$checkAllVariablesOptions = $this->Common_model->exequery("SELECT vd.variableName$langSuffix as variableName, hp.price, vd.price as oldPrice, hp.happyhourProductId, hp.productId, hp.price, hp.variableId  FROM vm_happyhour_product as hp left join vm_variable_product as vd on (vd.variableId = hp.variableId AND vd.status = 0) left join vm_product as pd on (pd.productId = hp.productId AND pd.status = 0) where hp.status = 0 AND hp.productId = ".$productItems->productId." AND hp.happyhourId IN (".$openHappyhourIds.")".$ppCond." order by hp.price asc");
                                    $productItems->isVariable = true;
                                    $productItems->variableProduct = $checkAllVariablesOptions;
                                }
                                else
                                    $productItems->isVariable = false;
                            }
                        }
                        $happyhourData->products = ($products) ? $products : array();
                    }else
                        $happyhourData= '';
                }
                $restaurantData['happyhourData'] = ($happyhourData) ? $happyhourData : new stdClass();

                $restaurantData['upcommingHappyhour'] = ($happyhourData) ? '' : $this->common_lib->getUpcommingHappyhour($restaurantId);


               $tax = (isset($restaurantData['restaurantData']->tax) && !empty($restaurantData['restaurantData']->tax))?$restaurantData['restaurantData']->tax:0;

               $pCond = (isset($roleId['gender']) && strtolower($roleId['gender']) == 'female')?" ":" and isOnlyForGirl = '0' ";
               if (isset($happyhourData->happyhourProductId) && !empty($happyhourData->happyhourProductId))
                    $pCond .= " AND productId NOT IN (".$happyhourData->happyhourProductId.") ";


                $productData = $this->Common_model->exequery("SELECT productId,restaurantId,productName".$langSuffix." as productName,sortDescription".$langSuffix." as sortDescription,description".$langSuffix." as description,(CASE WHEN productType=1 then (SELECT price FROM `vm_variable_product` WHERE status='0' AND  productId = vm_product.productId order by price asc limit 0,1) else price end) as price,tags".$langSuffix." as tags,(CASE WHEN vm_product.img REGEXP ('^[0-9]+$') THEN (SELECT (CASE WHEN im.image != '' THEN CONCAT('".UPLOADPATH."','/vedmir_images/',im.image) when vm_product.categoryId=4 then '".UPLOADPATH."/default/food_default.jpg' else '".UPLOADPATH."/default/drink_default.jpg' end ) FROM vm_image as im WHERE im.status = 0 and im.image != '' and im.imageId= vm_product.img) WHEN vm_product.img != '' THEN CONCAT('".UPLOADPATH."','/product_images/',vm_product.img) when vm_product.categoryId=4 then '".UPLOADPATH."/default/food_default.jpg' else '".UPLOADPATH."/default/drink_default.jpg' end ) as img,categoryId,subcategoryId,productType , (CASE WHEN productType = 1 then (SELECT (CASE WHEN count(*) > 0 then 1 else 0 end) FROM vm_variable_product WHERE isAvailableInFree = 1 AND productId = vm_product.productId) else 1 end) as isFree, doNotIncludeInTheMenu, isStockAvailable, (SELECT subcategoryName".$langSuffix." FROM vm_product_subcategory WHERE subcategoryId = vm_product.subcategoryId) as categoryName,(CASE WHEN subcategoryitemId !=0 then (SELECT subcategoryitemName".$langSuffix."  FROM vm_product_subcategoryitem WHERE subcategoryitemId = vm_product.subcategoryitemId ) else '' end )as subcategoryName FROM vm_product WHERE restaurantId = '".$restaurantId."' AND categoryId='5' AND status = '0' AND isAvailableInFree = '1'".$pCond." HAVING isFree = 1 ORDER BY welcomeDrinkOrderNo ASC");
                if(valResultSet($productData)) {
                    foreach($productData as $product) {
                      

                        $product->tax = $tax ;

                        $product->productGallaryData = $this->Common_model->exequery("SELECT CONCAT('".UPLOADPATH."/product_gallary_images/'".", image) as image  FROM vm_product_gallary_img  WHERE  productId = '".$product->productId."' ");
                        $product->productGallaryData = ($product->productGallaryData) ? $product->productGallaryData : array();
                        if(!empty($product->img))
                            array_unshift($product->productGallaryData, array('image' => $product->img));
                        if( $product->productType ) {
                            $variableproduct = $this->Common_model->exequery("SELECT variableId, variableName".$langSuffix." as variableName, price,  isAvailableInFree as isFree FROM `vm_variable_product` WHERE status='0' AND  productId ='".$product->productId."'");
                            $product->variableProduct = ( $variableproduct ) ? $variableproduct : array();
                        } 
                    }
                    
                }
                
                                
                $restaurantData['products'] = ($productData) ? $productData : array();
                
                        

                $this->set_response($restaurantData, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            }else{
                $this->response([
                    'status' => FALSE,
                    'message' => $this->lang->line('noResturant')
                ], REST_Controller::HTTP_FORBIDDEN); // HTTP_FORBIDDEN (403) being the HTTP response code
            }
        }else{
            $this->response([
                'status' => FALSE,
                'message' => $this->lang->line('unAuthorized')
            ], REST_Controller::HTTP_UNAUTHORIZED); // HTTP_UNAUTHORIZED (401) being the HTTP response code
        }
    }

    public function getrestaurantfoods_get(){
        $langSuffix = $this->lang->line('langSuffix');
        $token = $this->input->get_request_header('Authorization', TRUE);
        if($token != '' && $roleId = $this->common_lib->validateToken($token)){            
            $restaurantId = (int) $this->get('restaurantId');
            $pCond = (isset($roleId['gender']) && strtolower($roleId['gender']) == 'female')?"":" and isOnlyForGirl = '0' ";
            // Validate the id.
            if ($restaurantId <= 0)
                $this->response(['status' => FALSE,'message' => $this->lang->line('inValidResturantId') ], REST_Controller::HTTP_BAD_REQUEST);
            $distanceWhere = '10 as distance';
            $distanceCond = ' ORDER BY restaurantId desc';
            if(isset($_REQUEST['lat']) && !empty($_REQUEST['lat']) && isset($_REQUEST['lang']) && !empty($_REQUEST['lang'])){
                $distanceWhere = "( 111.111 * DEGREES(acos( cos( radians(".$_REQUEST['lat'].") ) * cos( radians( lat ) ) * cos( radians( lang ) - radians(".$_REQUEST['lang'].") ) + sin( radians(".$_REQUEST['lat'].") ) * sin(radians(lat)) ))) AS distance";
                $distanceCond = ' ORDER BY distance ASC';
            }
            $restaurantData['restaurantData'] = $this->Common_model->selRowData("vm_restaurant","`restaurantId`, `generatedId`,totalTable, isRestaurantOpen, isKitchenOpen, acceptingFoodOrder, acceptingDrinkOrder, acceptingOrder, openCloseType, restaurantName".$langSuffix." as restaurantName, `since`, `website`, `facebookPageUrl`, iframeUrl, `googlePageUrl`, `instagramPageUrl`, `youtubePageUrl`, contactName".$langSuffix." as contactName, `email`,  `mobile`, CONCAT(`address1".$langSuffix."`,' ',`address2".$langSuffix."`) as address, `city".$langSuffix."` as city, `state".$langSuffix."` as state, `country".$langSuffix."` as country, `postalCode`, `lat`, `lang`, `about".$langSuffix."` as about, (SELECT case when  sum(overallRating) / count(*) is not null  then format(sum(overallRating) / count(*),1) else 0 end FROM vm_resturant_rating WHERE restaurantId = vm_restaurant.restaurantId AND status='0') as `rating`, (SELECT case when  sum(priceRating) / count(*) is not null  then format(sum(priceRating) / count(*),1) else 0 end FROM vm_resturant_rating WHERE restaurantId = vm_restaurant.restaurantId AND status='0') as `priceRating`, (SELECT case when  sum(qualityRating) / count(*) is not null  then format(sum(qualityRating) / count(*),1) else 0 end FROM vm_resturant_rating WHERE restaurantId = vm_restaurant.restaurantId AND status='0') as `qualityRating`, (SELECT case when  sum(serviceRating) / count(*) is not null  then format(sum(serviceRating) / count(*),1) else 0 end FROM vm_resturant_rating WHERE restaurantId = vm_restaurant.restaurantId AND status='0') as `serviceRating`,(SELECT case when  sum(ambienceRating) / count(*) is not null  then format(sum(ambienceRating) / count(*),1) else 0 end FROM vm_resturant_rating WHERE restaurantId = vm_restaurant.restaurantId AND status='0') as `ambienceRating`, `tax`, `img` , (case when logo !='' then concat('".UPLOADPATH."/restaurant_images/',logo) else '".UPLOADPATH."/default/restaurant_default.jpg' end ) as logo, ".$distanceWhere," restaurantId = '".$restaurantId."'");
            if (!empty($restaurantData['restaurantData']->img))
                $restaurantData['restaurantData']->img = UPLOADPATH.'/restaurant_images/'.$restaurantData['restaurantData']->img;
            
            
            $day = strtolower(date('l'));
            $openCloseData = $this->common_lib->checkrestaurantopenclosed($restaurantId, $restaurantData['restaurantData']->openCloseType);
            $restaurantData['restaurantData']->openCloseData = $openCloseData;
            $restaurantData['restaurantData']->day = $openCloseData['currentDay'];
            $restaurantData['restaurantData']->isOpen = $openCloseData['isOpen'];
            $restaurantData['restaurantData']->nextOpenCLoseTiming = ($openCloseData['isOpen'])?$openCloseData['closeTime']:$openCloseData['nextOpenTime'];
            $restaurantData['restaurantData']->nextOpenCLoseString = $openCloseData['nextOpenCLoseString'];

            $restaurantData['restaurantData']->weekDaysTiming = $this->common_lib->weekDaysTiming($restaurantId, $restaurantData['restaurantData']->openCloseType, $openCloseData);
            
            $tax = (isset($restaurantData['restaurantData']->tax) && !empty($restaurantData['restaurantData']->tax))?$restaurantData['restaurantData']->tax:0;
            $getHappyhourData = $this->Common_model->exequery("SELECT happyhourId, day, startTime, endTime from vm_happyhour where status != 2 AND day = '".$day."' AND restaurantId = '".$restaurantId."'");
            $openHappyhourIds = '';
            $happyhourData = new stdClass();
            if (!empty($getHappyhourData)) {
                foreach ($getHappyhourData as $key => $happyhour) {
                    $isOpen = $this->common_lib->getrestaurantopenclosed($happyhour->startTime.' - '.$happyhour->endTime);
                    if($isOpen)
                        $openHappyhourIds .= ($openHappyhourIds)?','.$happyhour->happyhourId:$happyhour->happyhourId;
                }
            }
            $resturantgallery = $this->Common_model->exequery("SELECT CONCAT('".UPLOADPATH."/restaurant_gallary_images/'".", image) as image  FROM vm_restaurant_gallary_img  WHERE  restaurantId = '".$restaurantId."' ");
            $resturantgallery = ($resturantgallery) ? $resturantgallery : array();
            if(!empty($restaurantData['restaurantData']->img))
                array_unshift($resturantgallery, array('image' => $restaurantData['restaurantData']->img)); 
            $restaurantData['restaurantData']->restaurantGallaryData = $resturantgallery;
            $restaurantData['productData'] =  array();
            $restaurantsCategory = $this->Common_model->exequery("SELECT spc.subcategoryId,  spc.restaurantId, spc.categoryId, spc.subcategoryName".$langSuffix." as categoryName, (SELECT count(*) FROM vm_product_subcategoryitem WHERE subcategoryId =  spc.subcategoryId AND status = '0') as `subcatCount` FROM vm_product_subcategory spc WHERE spc.categoryId = 4 AND spc.restaurantId = '".$restaurantId."'  AND spc.status = '0' ORDER BY spc.orderNo ASC"); 


            // echo $this->db->last_query();
            // print_r($restaurantsCategory); exit;
             $restaurantsDrinksCategory = $this->Common_model->exequery("SELECT spc.subcategoryId,  spc.restaurantId, spc.categoryId, spc.subcategoryName".$langSuffix." as categoryName, (SELECT count(*) FROM vm_product_subcategoryitem WHERE subcategoryId =  spc.subcategoryId AND status = '0') as `subcatCount` FROM vm_product_subcategory spc WHERE spc.categoryId = 5 AND spc.restaurantId = '".$restaurantId."'  AND spc.status = '0' ORDER BY spc.orderNo ASC");
            
            //$restaurantData['drinkProductData'] = ($drinkProductData)?$drinkProductData:array();
            $restaurantData['productData'] = array();
            
            if( $restaurantsCategory ) {
                foreach ($restaurantsCategory as $restaurantsCategoryData) {
                    $restaurantsCategoryData->products = array();
                    $restaurantsCategoryData->subcategory = array();
                    if( $restaurantsCategoryData->subcatCount > 0 ) {
                        
                        $restaurantsSubcat = $this->Common_model->exequery("SELECT subcategoryitemId, subcategoryitemName".$langSuffix." as subcategoryName FROM vm_product_subcategoryitem WHERE subcategoryId =  '".$restaurantsCategoryData->subcategoryId."' AND status = '0' ORDER BY orderNo ASC");
                        if( $restaurantsSubcat ) {
                            foreach ( $restaurantsSubcat as $restaurantsSubcatData ) {

                                $productData = $this->Common_model->exequery("SELECT productId,restaurantId,productName".$langSuffix." as productName,sortDescription".$langSuffix." as sortDescription,description".$langSuffix." as description,(CASE WHEN productType=1 then (SELECT price FROM `vm_variable_product` WHERE status='0' AND  productId = vm_product.productId order by price asc limit 0,1) else price end) as price,tags".$langSuffix." as tags,(CASE WHEN vm_product.img REGEXP ('^[0-9]+$') THEN (SELECT (CASE WHEN im.image != '' THEN CONCAT('".UPLOADPATH."','/vedmir_images/',im.image) when vm_product.categoryId=4 then '".UPLOADPATH."/default/food_default.jpg' else '".UPLOADPATH."/default/drink_default.jpg' end ) FROM vm_image as im WHERE im.status = 0 and im.image != '' and im.imageId= vm_product.img) WHEN vm_product.img != '' THEN CONCAT('".UPLOADPATH."','/product_images/',vm_product.img) when vm_product.categoryId=4 then '".UPLOADPATH."/default/food_default.jpg' else '".UPLOADPATH."/default/drink_default.jpg' end ) as img,categoryId,subcategoryId,productType ,isAvailableInFree as isFree, doNotIncludeInTheMenu, isStockAvailable, (SELECT subcategoryName".$langSuffix." FROM vm_product_subcategory WHERE subcategoryId = vm_product.subcategoryId) as categoryName,(CASE WHEN subcategoryitemId !=0 then (SELECT subcategoryitemName".$langSuffix."  FROM vm_product_subcategoryitem WHERE subcategoryitemId = vm_product.subcategoryitemId ) else '' end ) as subcategoryName,(Select count(*) from vm_product_addons_category where status != 2 AND productId=vm_product.productId) as hasAddOn FROM vm_product WHERE restaurantId = '".$restaurantId."' AND categoryId='4' AND subcategoryId ='".$restaurantsCategoryData->subcategoryId."' AND subcategoryitemId='".$restaurantsSubcatData->subcategoryitemId."' AND status = '0'".$pCond." ORDER BY orderNo ASC, subOrderNo ASC");
                                if(valResultSet($productData)) {
                                    foreach($productData as $product) {
                                        $product->hasAddOn = ($product->hasAddOn > 0 ) ? 1 : 0;
                                        /*if (!empty($product->img))
                                            $product->img = UPLOADPATH.'/product_images/'.$product->img;*/

                                        $product->tax = $tax ;

                                        $product->productGallaryData = $this->Common_model->exequery("SELECT CONCAT('".UPLOADPATH."/product_gallary_images/'".", image) as image  FROM vm_product_gallary_img  WHERE  productId = '".$product->productId."' ");
                                        $product->productGallaryData = ($product->productGallaryData) ? $product->productGallaryData : array();
                                        if(!empty($product->img))
                                            array_unshift($product->productGallaryData, array('image' => $product->img));
                                        if( $product->productType ) {
                                            $variableproduct = $this->Common_model->exequery("SELECT variableId, variableName".$langSuffix." as variableName, price,isAvailableInFree as isFree, productId FROM `vm_variable_product` WHERE status='0' AND  productId ='".$product->productId."'");
                                            if($variableproduct) {
                                               
                                                foreach($variableproduct as $variableItem) {
                                                    if($openHappyhourIds != '') {
                                                        $variableHappyHourProduct = $this->Common_model->exequery("SELECT hp.* FROM vm_happyhour_product as hp WHERE hp.status = 0 AND hp.happyhourId IN (".$openHappyhourIds.") AND hp.productId = '".$product->productId."' AND variableId = '".$variableItem->variableId."'", true);
                                                        if($variableHappyHourProduct) {
                                                            $variableItem->oldPrice = $variableItem->price;
                                                            $variableItem->price = $variableHappyHourProduct->price;
                                                            $variableItem->isHappyhour = true;
                                                            $variableItem->happyhourProductId = $variableHappyHourProduct->happyhourProductId;
                                                        }
                                                        else
                                                            $variableItem->isHappyhour = false;
                                                    }
                                                }
                                            }
                                            $product->variableProduct = ( $variableproduct ) ? $variableproduct : array();
                                        } 
                                    }
                                    $restaurantsSubcatData->products = $productData;
                                }
                            }
                            
                            $restaurantsCategoryData->subcategory = $restaurantsSubcat;
                        }

                    }
                    
                    
                    $productData = $this->Common_model->exequery("SELECT productId,restaurantId,1 as isDrink,productName".$langSuffix." as productName,sortDescription".$langSuffix." as sortDescription,description".$langSuffix." as description,(CASE WHEN productType=1 then (SELECT price FROM `vm_variable_product` WHERE status='0' AND  productId = vm_product.productId order by price asc limit 0,1) else price end) as price,tags".$langSuffix." as tags,(CASE WHEN vm_product.img REGEXP ('^[0-9]+$') THEN (SELECT (CASE WHEN im.image != '' THEN CONCAT('".UPLOADPATH."','/vedmir_images/',im.image) when vm_product.categoryId=4 then '".UPLOADPATH."/default/food_default.jpg' else '".UPLOADPATH."/default/drink_default.jpg' end ) FROM vm_image as im WHERE im.status = 0 and im.image != '' and im.imageId= vm_product.img) WHEN vm_product.img != '' THEN CONCAT('".UPLOADPATH."','/product_images/',vm_product.img) when vm_product.categoryId=4 then '".UPLOADPATH."/default/food_default.jpg' else '".UPLOADPATH."/default/drink_default.jpg' end ) as img,categoryId,subcategoryId,productType ,isAvailableInFree as isFree, doNotIncludeInTheMenu, isStockAvailable, (SELECT subcategoryName".$langSuffix." FROM vm_product_subcategory WHERE subcategoryId = vm_product.subcategoryId) as categoryName,(CASE WHEN subcategoryitemId !=0 then (SELECT subcategoryitemName".$langSuffix."  FROM vm_product_subcategoryitem WHERE subcategoryitemId = vm_product.subcategoryitemId ) else '' end)as subcategoryName, (Select count(*) from vm_product_addons_category where status != 2 AND productId=vm_product.productId) as hasAddOn FROM vm_product WHERE restaurantId = '".$restaurantId."' AND categoryId='4' AND subcategoryId ='".$restaurantsCategoryData->subcategoryId."' AND subcategoryitemId='0' AND status = '0'".$pCond." ORDER BY orderNo ASC, subOrderNo ASC");
                    if(valResultSet($productData)) {
                        foreach($productData as $product) {
                            $product->hasAddOn = ($product->hasAddOn > 0 ) ? 1 : 0;
                            /*if (!empty($product->img))
                                $product->img = UPLOADPATH.'/product_images/'.$product->img;*/

                            $product->tax = $tax;

                            $product->productGallaryData = $this->Common_model->exequery("SELECT CONCAT('".UPLOADPATH."/product_gallary_images/'".", image) as image  FROM vm_product_gallary_img  WHERE  productId = '".$product->productId."' ");
                            $product->productGallaryData = ($product->productGallaryData) ? $product->productGallaryData : array();
                            if(!empty($product->img))
                                array_unshift($product->productGallaryData, array('image' => $product->img)); 
                            if( $product->productType ) {
                                $variableproduct = $this->Common_model->exequery("SELECT variableId, variableName".$langSuffix." as variableName, price,isAvailableInFree as isFree, productId FROM `vm_variable_product` WHERE status='0' AND  productId ='".$product->productId."'");
                                if($variableproduct) {
                                   
                                    foreach($variableproduct as $variableItem) {
                                        if($openHappyhourIds != '') {
                                            $variableHappyHourProduct = $this->Common_model->exequery("SELECT hp.* FROM vm_happyhour_product as hp WHERE hp.status = 0 AND hp.happyhourId IN (".$openHappyhourIds.") AND hp.productId = '".$product->productId."' AND variableId = '".$variableItem->variableId."'", true);
                                            if($variableHappyHourProduct) {
                                                $variableItem->oldPrice = $variableItem->price;
                                                $variableItem->price = $variableHappyHourProduct->price;
                                                $variableItem->isHappyhour = true;
                                                $variableItem->happyhourProductId = $variableHappyHourProduct->happyhourProductId;
                                            }
                                            else
                                                $variableItem->isHappyhour = false;
                                        }
                                    }
                                }
                                $product->variableProduct = ( $variableproduct ) ? $variableproduct : array();
                            }
                        }
                       
                        $restaurantsCategoryData->products = $productData;
                    }

                    
                }
            }
             if( $restaurantsDrinksCategory ) {
                foreach ($restaurantsDrinksCategory as $restaurantsCategoryData) {
                    $restaurantsCategoryData->products = array();
                    $restaurantsCategoryData->subcategory = array();
                    if( $restaurantsCategoryData->subcatCount > 0 ) {
                        //$restaurantsCategoryData->qry = "SELECT subcategoryitemId, subcategoryitemName".$langSuffix." as subcategoryName FROM vm_product_subcategoryitem WHERE subcategoryId =  '".$restaurantsCategoryData->subcategoryId."' AND status = '0'";
                        $restaurantsSubcat = $this->Common_model->exequery("SELECT subcategoryitemId, subcategoryitemName".$langSuffix." as subcategoryName FROM vm_product_subcategoryitem WHERE subcategoryId =  '".$restaurantsCategoryData->subcategoryId."' AND status = '0' ORDER BY orderNo ASC");
                        if( $restaurantsSubcat ) {
                            foreach ( $restaurantsSubcat as $restaurantsSubcatData ) {
                                $productData = $this->Common_model->exequery("SELECT productId,restaurantId,productName".$langSuffix." as productName,sortDescription".$langSuffix." as sortDescription,description".$langSuffix." as description,(CASE WHEN productType=1 then (SELECT price FROM `vm_variable_product` WHERE status='0' AND  productId = vm_product.productId order by price asc limit 0,1) else price end) as price,tags".$langSuffix." as tags,(CASE WHEN vm_product.img REGEXP ('^[0-9]+$') THEN (SELECT (CASE WHEN im.image != '' THEN CONCAT('".UPLOADPATH."','/vedmir_images/',im.image) when vm_product.categoryId=4 then '".UPLOADPATH."/default/food_default.jpg' else '".UPLOADPATH."/default/drink_default.jpg' end ) FROM vm_image as im WHERE im.status = 0 and im.image != '' and im.imageId= vm_product.img) WHEN vm_product.img != '' THEN CONCAT('".UPLOADPATH."','/product_images/',vm_product.img) when vm_product.categoryId=4 then '".UPLOADPATH."/default/food_default.jpg' else '".UPLOADPATH."/default/drink_default.jpg' end ) as img,categoryId,subcategoryId,productType ,isAvailableInFree as isFree, doNotIncludeInTheMenu, isStockAvailable, (SELECT subcategoryName".$langSuffix." FROM vm_product_subcategory WHERE subcategoryId = vm_product.subcategoryId) as categoryName,(CASE WHEN subcategoryitemId !=0 then (SELECT subcategoryitemName".$langSuffix."  FROM vm_product_subcategoryitem WHERE subcategoryitemId = vm_product.subcategoryitemId ) else '' end ) as subcategoryName, (Select count(*) from vm_product_addons_category where status != 2 AND productId=vm_product.productId) as hasAddOn FROM vm_product WHERE restaurantId = '".$restaurantId."' AND categoryId='5' AND subcategoryId ='".$restaurantsCategoryData->subcategoryId."' AND subcategoryitemId='".$restaurantsSubcatData->subcategoryitemId."' AND status = '0' AND doNotIncludeInTheMenu='0'".$pCond." ORDER BY orderNo ASC, subOrderNo ASC");
                                if(valResultSet($productData)) {
                                    foreach($productData as $product) {
                                        $product->hasAddOn = ($product->hasAddOn > 0 ) ? 1 : 0;
                                        /*if (!empty($product->img))
                                            $product->img = UPLOADPATH.'/product_images/'.$product->img;*/

                                        $product->tax = $tax ;

                                        $product->productGallaryData = $this->Common_model->exequery("SELECT CONCAT('".UPLOADPATH."/product_gallary_images/'".", image) as image  FROM vm_product_gallary_img  WHERE  productId = '".$product->productId."' ");
                                        $product->productGallaryData = ($product->productGallaryData) ? $product->productGallaryData : array();
                                        if(!empty($product->img))
                                            array_unshift($product->productGallaryData, array('image' => $product->img));
                                        if( $product->productType ) {
                                            $variableproduct = $this->Common_model->exequery("SELECT variableId, variableName".$langSuffix." as variableName, price,isAvailableInFree as isFree, productId FROM `vm_variable_product` WHERE status='0' AND  productId ='".$product->productId."'");
                                            if($variableproduct) {
                                               
                                                foreach($variableproduct as $variableItem) {
                                                    if($openHappyhourIds != '') {
                                                        $variableHappyHourProduct = $this->Common_model->exequery("SELECT hp.* FROM vm_happyhour_product as hp WHERE hp.status = 0 AND hp.happyhourId IN (".$openHappyhourIds.") AND hp.productId = '".$product->productId."' AND variableId = '".$variableItem->variableId."'", true);
                                                        if($variableHappyHourProduct) {
                                                            $variableItem->oldPrice = $variableItem->price;
                                                            $variableItem->price = $variableHappyHourProduct->price;
                                                            $variableItem->isHappyhour = true;
                                                            $variableItem->happyhourProductId = $variableHappyHourProduct->happyhourProductId;
                                                        }
                                                        else
                                                            $variableItem->isHappyhour = false;
                                                    }
                                                }
                                            }
                                            $product->variableProduct = ( $variableproduct ) ? $variableproduct : array();
                                        } 
                                    }
                                    $restaurantsSubcatData->products = $productData;
                                }
                            }
                            
                            $restaurantsCategoryData->subcategory = $restaurantsSubcat;
                        }

                    }
                    $productData = $this->Common_model->exequery("SELECT productId,restaurantId,0 as isDrink,productName".$langSuffix." as productName,sortDescription".$langSuffix." as sortDescription,description".$langSuffix." as description,(CASE WHEN productType=1 then (SELECT price FROM `vm_variable_product` WHERE status='0' AND  productId = vm_product.productId order by price asc limit 0,1) else price end) as price,tags".$langSuffix." as tags,(CASE WHEN vm_product.img REGEXP ('^[0-9]+$') THEN (SELECT (CASE WHEN im.image != '' THEN CONCAT('".UPLOADPATH."','/vedmir_images/',im.image) when vm_product.categoryId=4 then '".UPLOADPATH."/default/food_default.jpg' else '".UPLOADPATH."/default/drink_default.jpg' end ) FROM vm_image as im WHERE im.status = 0 and im.image != '' and im.imageId= vm_product.img) WHEN vm_product.img != '' THEN CONCAT('".UPLOADPATH."','/product_images/',vm_product.img) when vm_product.categoryId=4 then '".UPLOADPATH."/default/food_default.jpg' else '".UPLOADPATH."/default/drink_default.jpg' end ) as img,categoryId,subcategoryId,productType ,isAvailableInFree as isFree, doNotIncludeInTheMenu, isStockAvailable, (SELECT subcategoryName".$langSuffix." FROM vm_product_subcategory WHERE subcategoryId = vm_product.subcategoryId) as categoryName,(CASE WHEN subcategoryitemId !=0 then (SELECT subcategoryitemName".$langSuffix."  FROM vm_product_subcategoryitem WHERE subcategoryitemId = vm_product.subcategoryitemId ) else '' end)as subcategoryName, (Select count(*) from vm_product_addons_category where status != 2 AND productId=vm_product.productId) as hasAddOn FROM vm_product WHERE restaurantId = '".$restaurantId."' AND categoryId='5' AND subcategoryId ='".$restaurantsCategoryData->subcategoryId."' AND subcategoryitemId='0' AND status = '0'  AND doNotIncludeInTheMenu='0'".$pCond." ORDER BY orderNo ASC, subOrderNo ASC");
                        if(valResultSet($productData)) {
                            foreach($productData as $product) {
                                $product->hasAddOn = ($product->hasAddOn > 0 ) ? 1 : 0;
                                /*if (!empty($product->img))
                                    $product->img = UPLOADPATH.'/product_images/'.$product->img;*/

                                $product->tax = $tax;

                                $product->productGallaryData = $this->Common_model->exequery("SELECT CONCAT('".UPLOADPATH."/product_gallary_images/'".", image) as image  FROM vm_product_gallary_img  WHERE  productId = '".$product->productId."' ");
                                $product->productGallaryData = ($product->productGallaryData) ? $product->productGallaryData : array();
                                if(!empty($product->img))
                                    array_unshift($product->productGallaryData, array('image' => $product->img)); 
                                if( $product->productType ) {
                                    $variableproduct = $this->Common_model->exequery("SELECT variableId, variableName".$langSuffix." as variableName, price,isAvailableInFree as isFree, productId FROM `vm_variable_product` WHERE status='0' AND  productId ='".$product->productId."'");
                                    if($variableproduct) {
                                       
                                        foreach($variableproduct as $variableItem) {
                                            if($openHappyhourIds != '') {
                                                $variableHappyHourProduct = $this->Common_model->exequery("SELECT hp.* FROM vm_happyhour_product as hp WHERE hp.status = 0 AND hp.happyhourId IN (".$openHappyhourIds.") AND hp.productId = '".$product->productId."' AND variableId = '".$variableItem->variableId."'", true);
                                                if($variableHappyHourProduct) {
                                                    $variableItem->oldPrice = $variableItem->price;
                                                    $variableItem->price = $variableHappyHourProduct->price;
                                                    $variableItem->isHappyhour = true;
                                                    $variableItem->happyhourProductId = $variableHappyHourProduct->happyhourProductId;
                                                }
                                                else
                                                    $variableItem->isHappyhour = false;
                                            }
                                        }
                                    }
                                    $product->variableProduct = ( $variableproduct ) ? $variableproduct : array();
                                }
                            }
                           
                            $restaurantsCategoryData->products = $productData;
                        }
                }
            }
          
            $restaurantData['productData'] = ($restaurantsCategory) ? $restaurantsCategory : array();
            $restaurantData['drinkProductData'] = ($restaurantsDrinksCategory) ? $restaurantsDrinksCategory : array();
            /*$foodCategory = $this->Common_model->exequery("SELECT subcategoryId, subcategoryName".$langSuffix." as subcategoryName FROM vm_product_subcategory WHERE categoryId ='4' AND status='0'");
            if( !empty($foodCategory )) {

                foreach ($foodCategory as $foodCategoryVal) {
                    $productItem = array();
                    $productItem['productlist'] = array();
                    $productData = $this->Common_model->exequery("SELECT productId,restaurantId,productName".$langSuffix." as productName,sortDescription".$langSuffix." as sortDescription,description".$langSuffix." as description,price,tags".$langSuffix." as tags,img,categoryId,subcategoryId FROM vm_product WHERE restaurantId = '".$restaurantId."' AND categoryId!='5' AND subcategoryId='".$foodCategoryVal->subcategoryId."'");
                    $productItem['catname'] = $foodCategoryVal->subcategoryName;
                    if(valResultSet($productData)) {
                        foreach($productData as $product) {
                            if (!empty($product->img))
                                $product->img = UPLOADPATH.'/product_images/'.$product->img;

                            $product->productGallaryData = $this->Common_model->exequery("SELECT CONCAT('".UPLOADPATH."/product_gallary_images/'".", image) as image  FROM vm_product_gallary_img  WHERE  productId = '".$product->productId."' ");
                            $product->productGallaryData = ($product->productGallaryData) ? $product->productGallaryData : array();
                            if(!empty($product->img))
                                array_unshift($product->productGallaryData, array('image' => $product->img)); 
                        }
                        $productItem['productlist'] = $productData;
                    }
                    array_push($restaurantData['productData'], $productItem);
                }
                
            }*/
            if (!empty($restaurantData)){
                $this->set_response($restaurantData, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            }else{
                $this->response([
                    'status' => FALSE,
                    'message' => $this->lang->line('noResturant')
                ], REST_Controller::HTTP_FORBIDDEN); // HTTP_FORBIDDEN (403) being the HTTP response code
            }
        }else{
            $this->response([
                'status' => FALSE,
                'message' => $this->lang->line('unAuthorized')
            ], REST_Controller::HTTP_UNAUTHORIZED); // HTTP_UNAUTHORIZED (401) being the HTTP response code
        }
    }

    public function getrestaurants_get(){
        $langSuffix = $this->lang->line('langSuffix');
        $token = $this->input->get_request_header('Authorization', TRUE);
        // echo $token;exit;
        $userId = $this->common_lib->validateToken($token);
        if($token != '' && $userId){
            // Users from a data store e.g. database
            $resultset = array();
            $cond = '';
            $havingCond = '';
            $subQuery = '';
            $cond .= (isset($_REQUEST['resturantName']) && !empty($_REQUEST['resturantName'])) ? " AND vm_restaurant.restaurantName LIKE '%".$_REQUEST['resturantName']."%'" : "";
            if((isset($_REQUEST['type']) && !empty($_REQUEST['type']))){
                $categoryIdarray = explode(',',$_REQUEST['type']);
                foreach( $categoryIdarray as $catId) {
                    $cond .= " AND FIND_IN_SET ('".$catId."',category) ";
                }                 
            }
            // if(isset($_REQUEST['priceRating']) && !empty($_REQUEST['priceRating'])){
            //     $havingCond .= " HAVING priceRating <=  ( 6 - ".$_REQUEST['priceRating'].") AND priceRating >=  (6 - ".$_REQUEST['priceRating']." - 1)";
            // }
            if(isset($_REQUEST['priceRating']) && !empty($_REQUEST['priceRating']))
                $havingCond .= " HAVING rating >=  ".$_REQUEST['priceRating'];
            
            $distanceWhere = '10 as distance';
            $distanceCond = ' ORDER BY views desc';
            if(isset($_REQUEST['lat']) && !empty($_REQUEST['lat']) && isset($_REQUEST['lang']) && !empty($_REQUEST['lang'])){
                $distanceWhere = "( 111.111 * DEGREES(acos( cos( radians(".$_REQUEST['lat'].") ) * cos( radians( lat ) ) * cos( radians( lang ) - radians(".$_REQUEST['lang'].") ) + sin( radians(".$_REQUEST['lat'].") ) * sin(radians(lat)) ))) AS distance";
                if( isset($_REQUEST['location']) && !empty($_REQUEST['location']) )
                    $havingCond .= (!empty($havingCond)) ? ' AND distance < '.$_REQUEST['location'] : ' AND HAVING distance < '.$_REQUEST['location'];
                $distanceCond = ' ORDER BY distance ASC';
            }
           
            $restaurants = $this->Common_model->exequery("SELECT `restaurantId`, `generatedId`, `restaurantName".$langSuffix."` as restaurantName, totalTable,  isRestaurantOpen, isKitchenOpen, openCloseType, `since`, `website`, `facebookPageUrl`, iframeUrl, `googlePageUrl`, `instagramPageUrl`, `youtubePageUrl`, `contactName".$langSuffix."` as contactName, `email`, `mobile`, CONCAT(`address1".$langSuffix."`,' ',`address2".$langSuffix."`) as address, `city".$langSuffix."` as city, `state".$langSuffix."` as state, `country".$langSuffix."` as country, `postalCode`, `lat`, `lang`, `about".$langSuffix."` as about, (SELECT case when  sum(overallRating) / count(*) is not null  then format(sum(overallRating) / count(*),1) else 0 end FROM vm_resturant_rating WHERE restaurantId = vm_restaurant.restaurantId AND status='0') as `rating`, (SELECT case when  sum(priceRating) / count(*) is not null  then format(sum(priceRating) / count(*),1) else 0 end FROM vm_resturant_rating WHERE restaurantId = vm_restaurant.restaurantId AND status='0') as `priceRating`, `tax`, `img`, (case when logo !='' then concat('".UPLOADPATH."/restaurant_images/',logo) else '".UPLOADPATH."/default/restaurant_default.jpg' end ) as logo,(SELECT case when count(*) > 0 then 1 else 0 end FROM vm_product WHERE restaurantId = vm_restaurant.restaurantId AND categoryId!='5') as foodAvailable, ".$distanceWhere." FROM vm_restaurant WHERE status = '0' ".$cond.$havingCond.$distanceCond);
            // if(!$restaurants) {
            //     $restaurants = $this->Common_model->exequery("SELECT `restaurantId`, `generatedId`, `restaurantName".$langSuffix."` as restaurantName, totalTable, `since`,CONCAT(mondayOpen,' - ', mondayClose) as monday,  CONCAT(tuesdayOpen,' - ', tuesdayClose) as tuesday,  CONCAT(wednesdayOpen,' - ', wednesdayClose) as wednesday,  CONCAT(thursdayOpen,' - ', thursdayClose) as thursday,  CONCAT(fridayOpen,' - ', fridayClose) as friday,  CONCAT(saturdayOpen,' - ', saturdayClose) as saturday,  CONCAT(sundayOpen,' - ', sundayClose) as sunday, `website`, `facebookPageUrl`, `googlePageUrl`, `instagramPageUrl`, `youtubePageUrl`, `contactName".$langSuffix."` as contactName, `email`, `mobile`, CONCAT(`address1".$langSuffix."`,' ',`address2".$langSuffix."`) as address, `city".$langSuffix."` as city, `state".$langSuffix."` as state, `country".$langSuffix."` as country, `postalCode`, `lat`, `lang`, `about".$langSuffix."` as about, (SELECT case when  sum(overallRating) / count(*) is not null  then format(sum(overallRating) / count(*),1) else 0 end FROM vm_resturant_rating WHERE restaurantId = vm_restaurant.restaurantId AND status='0') as `rating`, (SELECT case when  sum(priceRating) / count(*) is not null  then format(sum(priceRating) / count(*),1) else 0 end FROM vm_resturant_rating WHERE restaurantId = vm_restaurant.restaurantId AND status='0') as `priceRating`, `tax`, `img`,(SELECT case when count(*) > 0 then 1 else 0 end FROM vm_product WHERE restaurantId = vm_restaurant.restaurantId AND categoryId!='5') as foodAvailable  FROM vm_restaurant WHERE status = '0' ");
            // }
            if($restaurants) {
                $day = strtolower(date('l'));
                foreach ($restaurants as $restaurant) {
                    $openCloseData = $this->common_lib->checkrestaurantopenclosed($restaurant->restaurantId, $restaurant->openCloseType);
                    $restaurant->openCloseData = $openCloseData;
                    $restaurant->day = $openCloseData['currentDay'];
                    $restaurant->isOpen = $openCloseData['isOpen'];
                    $restaurant->nextOpenCLoseTiming = ($openCloseData['isOpen'])?$openCloseData['closeTime']:$openCloseData['nextOpenTime'];
                    $restaurant->nextOpenCLoseString = $openCloseData['nextOpenCLoseString'];

                    $restaurant->weekDaysTiming = $this->common_lib->weekDaysTiming($restaurant->restaurantId, $restaurant->openCloseType, $openCloseData); 
                    if (!empty($restaurant->img)){
                        $restaurant->img = UPLOADPATH.'/restaurant_images/'.$restaurant->img;
                   }
                   $restaurant->restaurantGallaryData = $this->Common_model->exequery("SELECT CONCAT('".UPLOADPATH."/restaurant_gallary_images/'".", image) as image  FROM vm_restaurant_gallary_img  WHERE  restaurantId = '".$restaurant->restaurantId."' ");
                   $restaurant->restaurantGallaryData = ($restaurant->restaurantGallaryData) ? $restaurant->restaurantGallaryData : array();
                   if(!empty($restaurant->img))
                    array_unshift($restaurant->restaurantGallaryData, array('image' => $restaurant->img));
                   $participantUser = $this->Common_model->exequery("SELECT su.userName as fullName,su.userId, (case when su.oauth_provider = 'facebook' then su.picture_url when su.img !='' then concat('".UPLOADPATH."/user_images/',su.img) else '".UPLOADPATH."/user_images/default.jpg' end ) as `profile_image` FROM `vm_daily_user_participant` INNER JOIN vm_user su ON su.userId = vm_daily_user_participant.userId  WHERE vm_daily_user_participant.`restaurantId` = ".$restaurant->restaurantId." AND DATE(vm_daily_user_participant.eventDate) = '".date('Y-m-d')."'");
                   $restaurant->participantUser = ( $participantUser ) ? $participantUser : array();
                   $myParticipant = $this->Common_model->exequery("SELECT count(*) as nums FROM `vm_daily_user_participant` WHERE vm_daily_user_participant.`restaurantId` = ".$restaurant->restaurantId." AND vm_daily_user_participant.`userId` = ".$userId['roleId']." AND DATE(vm_daily_user_participant.eventDate) = '".date('Y-m-d')."'",true);
                   $restaurant->myParticipant = ($myParticipant->nums == 0) ? false : true;

                }
            }
            
            // If the id parameter doesn't exist return all the users
            //$userDrinkAvailable = $this->Common_model->exequery("SELECT count(*) as free,(SELECT id FROM `vm_user_daily_drink` WHERE userId = ".$userId['roleId']." AND DATE(currentTimestamp) = '".date('Y-m-d')."'  AND (servedStatus='1' OR servedStatus='0')) as servedStatusVal,(SELECT (CASE WHEN count(*) > 0 then 1 else 0 end) as membership_count FROM vm_user_memberships WHERE startDate <= '".date('Y-m-d')."' AND endDate >= '".date('Y-m-d')."' AND userId=".$userId['roleId']." AND subscriptionStatus ='Active' ORDER BY membershipId desc limit 0,1) as `membership` FROM `vm_user_daily_drink` WHERE userId = ".$userId['roleId']." AND DATE(currentTimestamp) = '".date('Y-m-d')."'  AND (servedStatus='1' OR servedStatus='0')",true);
            
            //$resultset['qry'] = "SELECT count(*) as free,(SELECT id FROM `vm_user_daily_drink` WHERE userId = ".$userId['roleId']." AND DATE(currentTimestamp) = '".date('Y-m-d')."'  AND (servedStatus='1' OR servedStatus='0')) as servedStatusVal,(SELECT (CASE WHEN count(*) > 0 then 1 else 0 end) as membership_count FROM vm_user_memberships WHERE startDate <= '".date('Y-m-d')."' AND endDate >= '".date('Y-m-d')."' AND userId=".$userId['roleId']." AND subscriptionStatus ='Active' ORDER BY membershipId desc limit 0,1) as `membership` FROM `vm_user_daily_drink` WHERE userId = ".$userId['roleId']." AND DATE(currentTimestamp) = '".date('Y-m-d')."'  AND (servedStatus='1' OR servedStatus='0')";
            $userDrinkAvailable = $this->common_lib->getUserFreeDrinkAndMembership($userId['roleId']);
            $resultset['restaurants_list'] = ($restaurants) ? $restaurants : array();
            $resultset['free_drink'] = $userDrinkAvailable['free_drink'];
            //$resultset['request'] = $_REQUEST;
            /*if ($userDrinkAvailable->free == 0 && $userDrinkAvailable->membership != 0)
                $resultset['free_drink'] = '1';
            else {                
                $userdrink = $this->Common_model->exequery("SELECT id,servedStatus,resturantUpdatedTime FROM vm_user_daily_drink WHERE id = '".$userDrinkAvailable->servedStatusVal."' AND userId='".$userId['roleId']."'",true); 
                if ($userdrink){
                    if($userdrink->servedStatus == 0) 
                        $resultset['free_drink'] = '2';
                    else {
                        $from_time = strtotime(date('Y-m-d H:i:s'));
                        $to_time = strtotime(date('Y-m-d H:i:s',strtotime('+15 minutes' ,strtotime($userdrink->resturantUpdatedTime))));
                        $minutes = round(($to_time - $from_time) / 60);
                        $seconds = ($to_time - $from_time) % 60;                        
                        if($minutes > 15 || $minutes < 0)
                            $resultset['free_drink'] = '4';
                        else {
                            $minutes = ($minutes < 10 ) ? '0'.$minutes : $minutes;
                            $seconds = ($seconds < 10 ) ? '0'.$seconds : $seconds;
                            $time = $minutes.':'.$seconds;
                            $resultset['free_drink'] = '3';
                            $resultset['remaining_time'] = $time;
                            $resultset['orderId'] = $userDrinkAvailable->servedStatusVal;

                        }
                    }
                }
                else
                    $resultset['free_drink'] = 4;
            }*/
            $getEventResult = $this->Common_model->exequery("SELECT concat('".UPLOADPATH."/eventImages/',bannerImage) as bannerpath, websiteUrl FROM vm_resturant_event WHERE expiredDate >= '".date('Y-m-d H:i:s')."' AND paymentStatus='Completed' AND isStatus='1'");
            $getResturantCategory = $this->Common_model->exequery("SELECT categoryId, categoryName".$langSuffix." as categoryName FROM vm_restaurant_category WHERE status='0' order by categoryName asc");
            $resultset['eventlist'] = ($getEventResult) ? $getEventResult : array();
            $resultset['membership'] = $userDrinkAvailable['membership'];
            $resultset['remainingDays'] = $userDrinkAvailable['remainingDays'];
            $resultset['restaurantType'] = ($getResturantCategory) ? $getResturantCategory : array();
            if ($restaurants) 
                // Set the response and exit
                $this->response($resultset, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            else    
                // Set the response and exit
                $this->response([
                    'status' => FALSE,
                    'message' => $this->lang->line('noResturant')
                ], REST_Controller::HTTP_FORBIDDEN); // HTTP_FORBIDDEN (403) being the HTTP response code 

        }else{
                $this->response([
                    'status' => FALSE,
                    'message' => $this->lang->line('unAuthorized')
                ], REST_Controller::HTTP_UNAUTHORIZED); // HTTP_UNAUTHORIZED (401) being the HTTP response code
            }
    }
    public function getproduct_get(){
        $langSuffix = $this->lang->line('langSuffix');
        $token = $this->input->get_request_header('Authorization', TRUE);
        if($token != '' && $roleId = $this->common_lib->validateToken($token)){            
            $productId = (int) $this->get('productId');
            // $pCond = (isset($roleId['gender']) && strtolower($roleId['gender']) == 'female')?" ":" and vm_product.isOnlyForGirl = '0' ";
            // Validate the id.
            if ($productId <= 0)
                $this->response(['status' => FALSE,'message' => $this->lang->line('inValidProductId') ], REST_Controller::HTTP_BAD_REQUEST);
            $restaurantData = array();
            $restaurantData['productData'] =  array();
            /*$userDrinkAvailable = $this->Common_model->exequery("SELECT count(*) as free,(SELECT servedStatus FROM `vm_user_daily_drink` WHERE userId = ".$roleId['roleId']." AND DATE(currentTimestamp) = '".date('Y-m-d')."'  AND (servedStatus='1' OR servedStatus='0')) as servedStatusVal,(SELECT (CASE WHEN count(*) > 0 then 1 else 0 end) as membership_count FROM vm_user_memberships WHERE startDate <= '".date('Y-m-d')."' AND endDate >= '".date('Y-m-d')."' AND userId=".$roleId['roleId']." AND subscriptionStatus ='Active' ORDER BY membershipId desc limit 0,1) as `membership` FROM `vm_user_daily_drink` WHERE userId = ".$roleId['roleId']." AND DATE(currentTimestamp) = '".date('Y-m-d')."' AND (servedStatus='1' OR servedStatus='0')",true);            
            
            if ($userDrinkAvailable->free == 0 && $userDrinkAvailable->membership != 0)
                $restaurantData['free_drink'] = '1';
            else {
                $restaurantData['free_drink'] = ( $userDrinkAvailable->servedStatusVal == 0 ) ? '2' : (($userDrinkAvailable->servedStatusVal == 1 ) ? '3' : '1' );
            }*/
            $userDrinkAvailable = $this->common_lib->getUserFreeDrinkAndMembership($roleId['roleId']);
            $restaurantData['free_drink'] = $userDrinkAvailable['free_drink'];
            $restaurantData['membership'] = $userDrinkAvailable['membership'];
            $productData = $this->Common_model->exequery("SELECT vm_product.productId,vm_product.restaurantId,vm_product.productName".$langSuffix." as productName,vm_product.sortDescription".$langSuffix." as sortDescription,vm_product.isAvailableInFree as isFree, vm_product.doNotIncludeInTheMenu, vm_product.isStockAvailable, vm_product.description".$langSuffix." as description,(CASE WHEN vm_product.productType=1 then (SELECT price FROM `vm_variable_product` WHERE status='0' AND  productId = vm_product.productId order by price asc limit 0,1) else vm_product.price end) as price,vm_product.tags".$langSuffix." as tags,(CASE WHEN vm_product.img REGEXP ('^[0-9]+$') THEN (SELECT (CASE WHEN im.image != '' THEN CONCAT('".UPLOADPATH."','/vedmir_images/',im.image) when vm_product.categoryId=4 then '".UPLOADPATH."/default/food_default.jpg' else '".UPLOADPATH."/default/drink_default.jpg' end ) FROM vm_image as im WHERE im.status = 0 and im.image != '' and im.imageId= vm_product.img) WHEN vm_product.img != '' THEN CONCAT('".UPLOADPATH."','/product_images/',vm_product.img) when vm_product.categoryId=4 then '".UPLOADPATH."/default/food_default.jpg' else '".UPLOADPATH."/default/drink_default.jpg' end ) as img,vm_product.categoryId,vm_product.subcategoryId,vm_product.productType , (SELECT subcategoryName".$langSuffix." FROM vm_product_subcategory  WHERE subcategoryId = vm_product.subcategoryId) as categoryName,(CASE WHEN subcategoryitemId !=0 then (SELECT subcategoryitemName".$langSuffix."  FROM vm_product_subcategoryitem  WHERE subcategoryitemId = vm_product.subcategoryitemId ) else '' end) as subcategoryName FROM vm_product WHERE vm_product.productId = '".$productId."' ORDER BY vm_product.orderNo ASC, vm_product.subOrderNo ASC",1);
            if($productData) {
                /*if (!empty($productData->img))
                    $productData->img = UPLOADPATH.'/product_images/'.$productData->img;
                else {

                }*/
                $productData->productGallaryData = $this->Common_model->exequery("SELECT CONCAT('".UPLOADPATH."/product_gallary_images/'".", image) as image  FROM vm_product_gallary_img  WHERE  productId = '".$productData->productId."' ");
                $productData->productGallaryData = ($productData->productGallaryData) ? $productData->productGallaryData : array();
                    if(!empty($productData->img))
                        array_unshift($productData->productGallaryData, array('image' => $productData->img));
                if( $productData->productType ) {
                    $variableproduct = $this->Common_model->exequery("SELECT variableId, variableName".$langSuffix." as variableName, price FROM `vm_variable_product` WHERE status='0' AND productId ='".$productData->productId."'");
                    $productData->variableProduct = ( $variableproduct ) ? $variableproduct : array();
                }
                /*if ($userDrinkAvailable->free == 0 && $userDrinkAvailable->membership != 0)
                    $productData->free_drink = '1';
                else {
                    $productData->free_drink = ( $userDrinkAvailable->servedStatusVal == 0 ) ? '2' : (($userDrinkAvailable->servedStatusVal == 1 ) ? '3' : '1' );
                }*/
                $productData->free_drink = $userDrinkAvailable['free_drink'];

                $productData->membership= $userDrinkAvailable['membership'];

                $addonData=$this->Common_model->exequery("Select *, categoryName$langSuffix as categoryName from vm_product_addons_category where status != 2 AND productId=".$productId);
                if ($addonData) {
                    foreach ($addonData as $row){
                        $row->addonsItem = $this->Common_model->exequery("Select *, addonsName$langSuffix as addonsName from vm_product_addons where status != 2 AND productId=".$productId." AND addonsCatId=".$row->addonsCatId);
                    }
                }
                $productData->addonData = ($addonData) ? $addonData : array();
 
                $this->set_response($productData, REST_Controller::HTTP_OK);
            }
            else
               $this->response([
                    'status' => FALSE,
                    'message' => $this->lang->line('noProduct')
                ], REST_Controller::HTTP_FORBIDDEN); // HTTP_FORBIDDEN (403) being the HTTP response code            
                        

            
        }else{
            $this->response([
                'status' => FALSE,
                'message' => $this->lang->line('unAuthorized')
            ], REST_Controller::HTTP_UNAUTHORIZED); // HTTP_UNAUTHORIZED (401) being the HTTP response code
        }
    }

    public function orderFood_post(){
        $langSuffix = $this->lang->line('langSuffix');
        $insertDetailStatus = 0;
        $drinkCount = $foodCount = 0;
        $token = $this->input->get_request_header('Authorization', TRUE);
        if($token != '' && $roleData = $this->common_lib->validateToken($token)){
            if(!isset($_POST['restaurantId']) || empty($_POST['restaurantId']))
                $this->response(['status' => FALSE,'message' => $this->lang->line('restaurantIdRequired')], REST_Controller::HTTP_BAD_REQUEST);
            if(!isset($_POST['tableNo']) || empty($_POST['tableNo']))
                $this->response(['status' => FALSE,'message' => $this->lang->line('tableRequired')], REST_Controller::HTTP_BAD_REQUEST);
            if(!isset($_POST['stripeToken']) || empty($_POST['stripeToken']))
                $this->response(['status' => FALSE,'message' => $this->lang->line('stripeTokenRequired')], REST_Controller::HTTP_BAD_REQUEST);
            if(!isset($_POST['productList']) || empty($_POST['productList']))
                $this->response(['status' => FALSE,'message' => $this->lang->line('productListRequired')], REST_Controller::HTTP_BAD_REQUEST);
            $restaurantInfo = $this->Common_model->exequery("SELECT vm_auth.deviceToken, vm_auth.language, vm_restaurant.stripeAccId, vm_restaurant.restaurantName, vm_restaurant.isKitchenOpen FROM vm_auth left join vm_restaurant on vm_auth.roleId = vm_restaurant.restaurantId WHERE vm_auth.role='restaurant' AND vm_auth.roleId='".$_POST['restaurantId']."'",true);
            $restaurantInfo = ( $restaurantInfo ) ? $restaurantInfo : '';
            $productDetails = '';
            if (isset($_POST['productList']) && count($_POST['productList']) > 0) {
               
                $productList = json_decode($_POST['productList']);
                if( empty($productList) ) 
                    $this->response(['status' => FALSE,'message' => $this->lang->line('productListRequired')], REST_Controller::HTTP_BAD_REQUEST);
                if( !isset($productList[0]->productId) || empty($productList[0]->productId) || !isset($productList[0]->quantity) || empty($productList[0]->quantity)) 
                    $this->response(['status' => FALSE,'message' => $this->lang->line('productEmpty')], REST_Controller::HTTP_BAD_REQUEST);
                if($restaurantInfo) {
                    if($restaurantInfo->stripeAccId =='' || $restaurantInfo->stripeAccId == NULL) 
                        $this->response(['status' => FALSE,'message' => $this->lang->line('restaurantOrderError')], REST_Controller::HTTP_FORBIDDEN);
                }
                else
                    $this->response(['status' => FALSE,'message' => $this->lang->line('restaurantOrderError')], REST_Controller::HTTP_FORBIDDEN);
                $orderId = 0;
                $drinkitem = 0;
                $orderTotalAmount = 0;
                $lowestProductPrice = array();
                $isFreeDrinkId = 0;
                foreach ($productList as $product){

                    if ($orderId == 0) {                       
                        $insertOrderData = array();
                        $insertOrderData['restaurantId']    = $_POST['restaurantId'];
                        $insertOrderData['userId']          = $roleData['roleId'];
                        $insertOrderData['addedOn']         = date("Y-m-d H:i:s");
                        $insertOrderData['tableNo']         = $_POST['tableNo'];
                        $insertOrderData['orderDescription'] = (isset($_POST['description']) && !empty($_POST['description'])) ? $_POST['description'] : "";
                        $insertOrderData['isTrail']          = $this->testmode;
                        $orderId=$this->Common_model->insertUnique("vm_order",$insertOrderData);

                        if(!$orderId)
                            $this->response(['status' => FALSE,'message' => $this->lang->line('internalError')], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
                    }

                    $isVariable = (isset($product->productType) && !empty($product->productType)) ? (($product->productType == 1 ) ? 1 : 0 ): 0;

                    /*$userDrinkAvailable = $this->Common_model->exequery("SELECT count(*) as free, (SELECT servedStatus FROM `vm_user_daily_drink` WHERE userId = ".$roleData['roleId']." AND DATE(currentTimestamp) = '".date('Y-m-d')."'  AND (servedStatus='1' OR servedStatus='0')) as servedStatusVal,(SELECT count(*) FROM vm_user_memberships WHERE startDate <= '".date('Y-m-d')."' AND endDate >= '".date('Y-m-d')."' AND userId=".$roleData['roleId']." AND subscriptionStatus ='Active') as `membership`, (SELECT userName FROM vm_user WHERE userId='".$roleData['roleId']."') as userName FROM `vm_user_daily_drink` WHERE userId = ".$roleData['roleId']." AND DATE(currentTimestamp) = '".date('Y-m-d')."'  AND (servedStatus='1' OR servedStatus='0')",true);*/
                    $userDrinkAvailable = $this->common_lib->getUserFreeDrinkAndMembership($roleData['roleId']);
                    if( $isVariable )
                           $productData=$this->Common_model->exequery("SELECT vp.price, vp.productId, vp.isAvailableInFree, pd.categoryId, CONCAT(pd.productName,' ( ',vp.variableName, ' )') as productName FROM vm_variable_product as vp left join vm_product as pd on vp.productId = pd.productId WHERE vp.variableId=".$product->productId,1);
                    else
                        $productData=$this->Common_model->selRowData("vm_product","price,categoryId,productId,isAvailableInFree,productName","productId=".$product->productId);
                    
                    $product->quantity = (isset($product->quantity) && !empty($product->quantity)) ? $product->quantity : 1;

                    
                    // if ($userDrinkAvailable->free == 0 && $userDrinkAvailable->membership != 0){
                    //     if($productData->isAvailableInFree) {
                    //         if(!empty($lowestProductPrice)){

                    //         }
                    //         else {

                    //         }
                    //     }
                        
                    // }

                    if ($orderId > 0 && valResultSet($productData)) {                       

                        if ($productData->categoryId == 5 || $restaurantInfo->isKitchenOpen == '1') {
                            $insertDetailData = array();
                            $insertDetailData['orderId']        = $orderId;
                            $insertDetailData['isVariable']     = $isVariable;
                            $insertDetailData['productId']      = $product->productId;
                            $insertDetailData['quantity']       = $product->quantity;
                            $insertDetailData['price']          = $productData->price;

                            $insertDetailData['itemType']       = ($productData->categoryId == 5) ? '1' : '0';
                            $insertDetailData['discount']       = 0;
                            $insertDetailData['subtotal']       = $productData->price * $product->quantity;
                            $itemPrice = $productData->price * $product->quantity;
                            $isFreeItem = (isset($product->isFree) && !empty($product->isFree)) ? $product->isFree : 0;
                            if ($userDrinkAvailable['free_drink'] == 1 && $userDrinkAvailable['membership'] == 1 && $productData->categoryId == 5 && $isFreeDrinkId == 0 && $isFreeItem == 1){
                                if($productData->isAvailableInFree == 1 ) {
                                    $insertDetailData['subtotal']       = $itemPrice - $productData->price;
                                    $itemPrice = $insertDetailData['subtotal'];
                                    $insertDetailData['isFree'] = 1; 
                                    $this->Common_model->insert('vm_user_daily_drink', array('userId' => $roleData['roleId'], 'restaurantId' => $_POST['restaurantId'],'productId' => $product->productId,'productType' => $isVariable,'orderId' => $orderId,'createdDate' => date('Y-m-d H:i:s')));
                                    $isFreeDrinkId = $orderId;
                                    
                                }
                                
                            }
                            if ($productData->categoryId == 5)
                                $drinkCount++;
                            else
                                $foodCount++;

                            if(isset($product->happyhourProductId) && !empty($product->happyhourProductId)) {
                                $today = strtolower(date('l'));
                                $varibaleCond = ($product->productType == 1) ? " AND shp.variableId='".$product->productId."'": " AND shp.productId='".$product->productId."'";
                                $happyhourData = $this->Common_model->exequery("SELECT shp.*, sh.startTime, sh.endTime FROM vm_happyhour_product shp left JOIN vm_happyhour sh ON shp.happyhourId = sh.happyhourId where   shp.happyhourProductId='".$product->happyhourProductId."' AND shp.status='0' AND sh.status='0' AND sh.restaurantId='".$_POST['restaurantId']."' ".$varibaleCond,1);
                                $checkHappyHour = false;
                                if($happyhourData)
                                    $checkHappyHour = $this->common_lib->getrestaurantopenclosed($happyhourData->startTime.' - '.$happyhourData->endTime );
                                if($checkHappyHour) {
                                    $insertDetailData['discount']       = $happyhourData->price;
                                    $insertDetailData['subtotal']       = $happyhourData->price * $product->quantity;
                                    $itemPrice = $happyhourData->price * $product->quantity;
                                    if( $happyhourData->variableId > 0 ) {
                                        $insertDetailData['isVariable']     = 1;
                                        $insertDetailData['productId']     = $happyhourData->variableId;
                                    }     
                                }
                                else {
                                    $this->Common_model->runquery("DELETE FROM vm_order_detail WHERE orderId='".$orderId."'");
                                    $this->Common_model->runquery("DELETE FROM vm_order_addons WHERE orderId='".$orderId."'");
                                    $this->Common_model->runquery("DELETE FROM vm_order WHERE orderId='".$orderId."'");
                                    $this->response(['status' => FALSE,'message' => '', 'happyhour' => false], REST_Controller::HTTP_PRECONDITION_FAILED);
                                }
                            }
                            $productDetails .= ($productDetails !='') ? ','.$productData->productName : $productData->productName;
                            
                            
                            
                            $insertDetailStatus=$this->Common_model->insertUnique("vm_order_detail",$insertDetailData);
                            if(isset($product->addonsItem) && !empty($product->addonsItem)) {
                                if($product->addonsItem !='') {
                                    $addonsItem = $product->addonsItem;
                                    if($addonsItem !="") {                                        
                                        $addonsDeatils = $this->Common_model->exequery("SELECT * FROM vm_product_addons WHERE productId='".$productData->productId."' AND addonsId IN (".$addonsItem.")");
                                        $addonsPrice = 0;
                                        if($addonsDeatils) {
                                            $addonsItemDetails = '';
                                            foreach($addonsDeatils as $keyItem => $addonsValue) {
                                                if( $addonsValue->status == 0 && $addonsValue->isStockAvailable == 1 ) {
                                                    $addonsPrice = $addonsPrice + $addonsValue->price * $product->quantity;
                                                    $addonsItemDetails .= ($addonsItemDetails != '') ? ','.$addonsValue->addonsName : $addonsValue->addonsName;
                                                    $this->Common_model->insert("vm_order_addons", array("detailId" => $insertDetailStatus, "addonId" => $addonsValue->addonsId, "orderId" => $orderId, "price" => $addonsValue->price * $product->quantity, "productId" => $product->productId, "addedOn" => date('Y-m-d H:i:s')));
                                                }
                                                else {
                                                    $this->Common_model->runquery("DELETE FROM vm_order_detail WHERE orderId='".$orderId."'");
                                                    $this->Common_model->runquery("DELETE FROM vm_order_addons WHERE orderId='".$orderId."'");
                                                    $this->Common_model->runquery("DELETE FROM vm_order WHERE orderId='".$orderId."'");
                                                    $this->response(['status' => FALSE,'message' => $this->lang->line('productUnAvailable')], REST_Controller::HTTP_BAD_REQUEST);
                                                }
                                            }
                                            $this->Common_model->runquery("UPDATE vm_order_detail SET subtotal = subtotal + ".$addonsPrice." WHERE detailId = '".$insertDetailStatus."'");
                                            $itemPrice = $itemPrice + $addonsPrice;
                                            $productDetails .= '('.$addonsItemDetails.')';
                                        }else {
                                            $this->Common_model->runquery("DELETE FROM vm_order_detail WHERE orderId='".$orderId."'");
                                            $this->Common_model->runquery("DELETE FROM vm_order_addons WHERE orderId='".$orderId."'");
                                            $this->Common_model->runquery("DELETE FROM vm_order WHERE orderId='".$orderId."'");
                                            $this->response(['status' => FALSE,'message' => $this->lang->line('invalidRequrest'), 'happyhour' => false], REST_Controller::HTTP_PRECONDITION_FAILED);
                                        }
                                        
                                    }
                                }
                            }
                            $orderTotalAmount = $orderTotalAmount + $itemPrice;
                            
                            
                        }
                    } 
                }


                if ($insertDetailStatus){ 
                     
                    
                    $error_message = array();
                    $userinfo = $this->Common_model->exequery("SELECT userName, lastName, email, stripe_customer_id, test_stripe_customer_id FROM vm_user WHERE userId=".$roleData['roleId'], true);
                    if($orderTotalAmount > 0 ){
                        $this->Common_model->update('vm_order', array('initialAmount' => $orderTotalAmount), "orderId =".$orderId);

                        try {
                            /*$customer = \Stripe\Customer::create(array(
                                'email' => $userinfo->email,
                                'source'  => $_POST['stripeToken'],
                                'metadata' => array('First Name' => $userinfo->userName, 'Last Name' =>$userinfo->lastName ),
                            ));*/
                            $default_source = '';
                                $customer_id = '';
                            $userStripeId = ($this->testmode == 1) ? $userinfo->test_stripe_customer_id : $userinfo->stripe_customer_id;
                            if($userStripeId !='') {
                                try { $customer = \Stripe\Customer::retrieve($userStripeId);
                                    $cardsData = $customer->sources->create(["source" => $_POST['stripeToken']]);
                                    $default_source = $cardsData->id;
                                    $customer_id = $cardsData->customer;
                                }
                                catch(Exception $e) {
                                    $error_message['message'] = $e->getMessage();
                                }
                            }
                            else {
                                try { $customer = \Stripe\Customer::create(array(

                                        'email' => $userinfo->email,

                                        'source'  => $_POST['stripeToken'],

                                        'metadata' => array('First Name' => $userinfo->userName, 'Last Name' =>$userinfo->lastName ),

                                    ));
                                    $customer_id =  $customer->id;
                                    $default_source = $customer->default_source;
                               }
                               catch(Exception $e) {
                                    $error_message['message'] = $e->getMessage();
                                }
                            }
                            if(empty($error_message)) {
                                if($this->testmode == 1)
                                    $update_customer_id = $this->Common_model->update("vm_user", array('test_stripe_customer_id' => $customer_id),"userId=".$roleData['roleId']);
                                else
                                    $update_customer_id = $this->Common_model->update("vm_user", array('stripe_customer_id' => $customer_id),"userId=".$roleData['roleId']);
                                $restaurantAmount = $orderTotalAmount - (($orderTotalAmount * 5 )/100);
                                $VedmirCommission = $orderTotalAmount - $restaurantAmount - (($orderTotalAmount * 3.07 )/100);
                                $orderItems =array(        
                                  "amount" => round($orderTotalAmount,2) * 100,
                                  "currency" => "CHF",
                                  "description" => $productDetails,
                                  "source" => $default_source,           
                                  "destination" => [
                                    "amount" => round($restaurantAmount,2) * 100,
                                    "account" => $restaurantInfo->stripeAccId,
                                  ],
                                  "capture" => false,
                                  "customer" => $customer->id,
                                  'metadata' => ['order_id' => $orderId, "First Name" => $userinfo->userName, "Last Name" => $userinfo->lastName, "email" => $userinfo->email, "Venue Name" => $restaurantInfo->restaurantName, "Vedmir Commission" => $VedmirCommission, "Restataurant Amount" => $restaurantAmount]
                                );
                              /*if($this->testmode == 1 && $userinfo->test_stripe_customer_id != '')
                                    $orderItems['customer'] = $userinfo->test_stripe_customer_id;
                                else if($userinfo->stripe_customer_id != '')
                                    $orderItems['customer'] = $userinfo->stripe_customer_id;*/
                                
                                $chargePayment = \Stripe\Charge::create( $orderItems ); 
                                $this->Common_model->update('vm_order', array('paymentStatus' => 'Completed', 'orderStatus' => 'Pending','payment_method' => 'Stripe', 'amt' => $chargePayment->amount / 100, 'transactionId' => $chargePayment->balance_transaction, 'chargeId' => $chargePayment->id, 'restaturantAmount' => ($chargePayment->amount / 100) - ($chargePayment->amount / 100) * (5)/100), "orderId =".$orderId);
                                
                                $pushMsg = $this->common_lib->translate('pushsuccessOrder',$restaurantInfo->language);
                                if( isset($restaurantInfo->deviceToken) && !empty($restaurantInfo->deviceToken) && !empty($pushMsg))
                                    $this->common_lib->sendPush($pushMsg, array('type' => 'order_recievd', 'orderId' => $orderId), $restaurantInfo->deviceToken, false);
                                if(isset($_POST['cardNo']) && !empty($_POST['cardNo']) && isset($_POST['expMonth']) && !empty($_POST['expMonth']) && is_numeric($_POST['expMonth']) && isset($_POST['expYear']) && !empty($_POST['expYear']) && is_numeric($_POST['expYear']) && isset($_POST['cvv']) && !empty($_POST['cvv']) && is_numeric($_POST['cvv'])) {
                                    $checkUserCard = $this->Common_model->exequery( "SELECT * FROM vm_user_card_details  WHERE cardNo='".$_POST['cardNo']."' AND expMonth='".$_POST['expMonth']."'  AND ( expYear='".$_POST['expYear']."' OR expYear='".substr($_POST['expYear'], -2)."')  AND userId='".$roleData['roleId']."'",true );
                                    $holderName = (isset($_POST['cardHolderName']) && !empty($_POST['cardHolderName'])) ? $_POST['cardHolderName'] : '';
                                    if( ! $checkUserCard ){
                                        $this->Common_model->insertUnique('vm_user_card_details' , array('cardNo' => $_POST['cardNo'], 'expMonth' => $_POST['expMonth'], 'expYear' => $_POST['expYear'], 'userId' => $roleData['roleId'], 'cvv' => $this->common_lib->encrypt_ccv($_POST['cvv']), 'currentDateTime' => date('Y-m-d H:i:s'),'cardHolderName' => $holderName));
                                    }
                                }
                                if( $isFreeDrinkId > 0 ) {
                                    $userRestaurantData = $this->Common_model->exequery("SELECT sa.deviceToken,sa.language,sr.restaurantName,(SELECT userName FROM `vm_user` WHERE userId = $roleData[roleId]) as userName FROM vm_restaurant sr INNER JOIN vm_auth sa ON sr.restaurantId=sa.roleId WHERE sa.roleId='".$_POST['restaurantId']."' AND sa.role='restaurant'",true);
                                    if (isset($userRestaurantData->deviceToken) && !empty($userRestaurantData->deviceToken))
                                            $this->common_lib->sendPush($userRestaurantData->userName." ".$this->common_lib->translate('claimFreeDrink',$userRestaurantData->language), array('type' => 'claim_drink', 'restaurantId' => $_POST['restaurantId'], 'userId' => $roleData['roleId']),$userRestaurantData->deviceToken, false);
                                }
                                $this->notifyBartender($drinkCount, $foodCount ,$_POST['restaurantId'], $orderId);
                                $this->response(array('status' => true, 'message' => $this->lang->line('successOrder'),'orderId' => $orderId,  'postdata' => $_POST), REST_Controller::HTTP_OK);
                            }
                        }
                        catch(\Stripe\Error\Card $e) {
                          // Since it's a decline, \Stripe\Error\Card will be caught
                           $error_message['card_error'] = $e->getMessage();
                           $error_message['message'] = $e->getMessage();
                          
                        } catch (\Stripe\Error\RateLimit $e) {
                            $error_message['rate_limit'] = $e->getMessage();
                            $error_message['message'] = $e->getMessage();
                          // Too many requests made to the API too quickly
                        } catch (\Stripe\Error\InvalidRequest $e) {
                             $error_message['invalid_request'] = $e->getMessage();
                             $error_message['message'] = $e->getMessage();
                          // Invalid parameters were supplied to Stripe's API
                        } catch (\Stripe\Error\Authentication $e) {
                            $error_message['auth_error'] = $e->getMessage();
                            $error_message['message'] = $e->getMessage();
                          // Authentication with Stripe's API failed
                          // (maybe you changed API keys recently)
                        } catch (\Stripe\Error\ApiConnection $e) {
                            $error_message['connection_error'] = $e->getMessage();
                            $error_message['message'] = $e->getMessage();
                          // Network communication with Stripe failed
                        } catch (\Stripe\Error\Base $e) {
                            $error_message['genric_error'] = $e->getMessage();
                            $error_message['message'] = $e->getMessage();
                          // Display a very generic error to the user, and maybe send
                          // yourself an email
                        } catch (Exception $e) {
                            $error_message['message'] = $e->getMessage();
                          // Something else happened, completely unrelated to Stripe
                        }
                        if(!empty($error_message)){
                            /*$this->Common_model->update('vm_user_daily_drink', array('paymentStatus' => 'Failed', 'orderStatus' => 'Failed', 'cancelRemark' =>  $error_message['message']), "orderId =".$orderId);*/
                            $this->Common_model->runquery("DELETE FROM vm_user_daily_drink WHERE orderId=".$orderId);
                            $this->Common_model->update('vm_order', array('paymentStatus' => 'Failed', 'orderStatus' => 'Failed', 'cancelRemark' =>  $error_message['message']), "orderId =".$orderId);
                            $this->response(array('status' => false, 'message' => $error_message['message'],'error' => $error_message), REST_Controller::HTTP_PAYMENT_REQUIRED);  
                        }
                    }  
                    else {
                        $this->Common_model->update('vm_order', array('paymentStatus' => 'Completed', 'orderStatus' => 'Pending','payment_method' => 'Stripe', 'amt' => 0, 'restaturantAmount' => 0, 'restaurantSettlement' => 2), "orderId =".$orderId);
                        if(isset($_POST['cardNo']) && !empty($_POST['cardNo']) && isset($_POST['expMonth']) && !empty($_POST['expMonth']) && is_numeric($_POST['expMonth']) && isset($_POST['expYear']) && !empty($_POST['expYear']) && is_numeric($_POST['expYear']) && isset($_POST['cvv']) && !empty($_POST['cvv']) && is_numeric($_POST['cvv'])) {
                            $checkUserCard = $this->Common_model->exequery( "SELECT * FROM vm_user_card_details  WHERE cardNo='".$_POST['cardNo']."' AND expMonth='".$_POST['expMonth']."' AND ( expYear='".$_POST['expYear']."' OR expYear='".substr($_POST['expYear'], -2)."')  AND userId='".$roleData['roleId']."'",true );
                            $holderName = (isset($_POST['cardHolderName']) && !empty($_POST['cardHolderName'])) ? $_POST['cardHolderName'] : '';
                            if( ! $checkUserCard ) 
                                $this->Common_model->insertUnique('vm_user_card_details' , array('cardNo' => $_POST['cardNo'], 'expMonth' => $_POST['expMonth'], 'expYear' => $_POST['expYear'], 'userId' => $roleData['roleId'], 'cvv' => $this->common_lib->encrypt_ccv($_POST['cvv']), 'currentDateTime' => date('Y-m-d H:i:s'),'cardHolderName' => $holderName));
                        }
                        if( $isFreeDrinkId > 0 ) {
                            $userRestaurantData = $this->Common_model->exequery("SELECT sa.deviceToken, sa.language, sr.restaurantName,(SELECT userName FROM `vm_user` WHERE userId = $roleData[roleId]) as userName FROM vm_restaurant sr INNER JOIN vm_auth sa ON sr.restaurantId=sa.roleId WHERE sa.roleId='".$_POST['restaurantId']."' AND sa.role='restaurant'",true);
                            if (isset($userRestaurantData->deviceToken) && !empty($userRestaurantData->deviceToken))
                                    $this->common_lib->sendPush($userRestaurantData->userName." ".$this->common_lib->translate('claimFreeDrink',$userRestaurantData->language), array('type' => 'claim_drink', 'restaurantId' => $_POST['restaurantId'], 'userId' => $roleData['roleId']),$userRestaurantData->deviceToken, false);
                        }
                        $this->notifyBartender($drinkCount, $foodCount ,$_POST['restaurantId'], $orderId);
                        $this->response(array('status' => true, 'message' => $this->lang->line('successOrder'),'orderId' => $orderId, 'postdata' => $_POST), REST_Controller::HTTP_OK);
                        /*$this->Common_model->update('vm_order', array('paymentStatus' => 'Failed', 'orderStatus' => 'Failed'), "orderId =".$orderId);
                            $this->response(array('status' => false, 'message' => $this->lang->line('inValidOrder'),'error' => $error_message), REST_Controller::HTTP_FORBIDDEN); */ 
                    } 
                }
                else
                {
                    $this->Common_model->runquery("DELETE FROM vm_user_daily_drink WHERE orderId=".$orderId);
                    $this->Common_model->update('vm_order', array('paymentStatus' => 'Failed', 'orderStatus' => 'Failed', 'cancelRemark' =>  'Something Went Wrong'), "orderId =".$orderId);
                    $this->response([
                        'status' => FALSE,
                        'message' => $this->lang->line('internalError')
                    ], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
                }

            }else
                $this->set_response([
                    'status' => FALSE,
                    'message' => $this->lang->line('productListRequired')
                ], REST_Controller::HTTP_FORBIDDEN);

        }else
            $this->set_response([
                'status' => FALSE,
                'message' => $this->lang->line('unAuthorized')
            ], REST_Controller::HTTP_UNAUTHORIZED);
        
    }


    public function notifyBartender($drinkCount, $foodCount, $restaurantId, $orderId, $notifyType = "pushsuccessOrder",$actionType = "order_recievd"){

        if ($drinkCount > 0 || $foodCount > 0) {
            $cond = "'both'";
            $cond = ($drinkCount > 0)?$cond.",'drink'":$cond;
            $cond = ($foodCount > 0)?$cond.",'food'":$cond;
            $deviceData = $this->Common_model->exequery("SELECT au.deviceToken, au.language FROM vm_bartender as bt left join vm_auth au on (au.role = bt.serve AND au.roleId = bt.bartenderId) WHERE bt.status = 0 and bt.restaurantId='".$restaurantId."' AND bt.serve IN ($cond) AND au.deviceToken != '' ");
            $deviceTokens = array();
            $frenchdeviceTokens = array();
            if ($deviceData) {
                foreach ($deviceData as $key => $device) {
                    if($device->language == 'english')
                        array_push($deviceTokens, $device->deviceToken);
                    else
                        array_push($frenchdeviceTokens, $device->deviceToken);
                }
            }
            $pushMsg = $this->common_lib->translate($notifyType,'english');
            $frenchpushMsg = $this->common_lib->translate($notifyType,'french');
            //$actionType = "type";
            if(!empty($deviceTokens) && !empty($pushMsg))
                $this->common_lib->sendPush($pushMsg, array('type' => $actionType, 'orderId' => $orderId), $deviceTokens, false, true);
            if(!empty($frenchdeviceTokens) && !empty($frenchpushMsg)) 
                $this->common_lib->sendPush($pushMsg, array('type' => $actionType, 'orderId' => $orderId), $frenchdeviceTokens, false, true);
        }
    }

    public function cancelOrder_post(){
        $langSuffix = $this->lang->line('langSuffix');
        $insertDetailStatus = 0;
        $token = $this->input->get_request_header('Authorization', TRUE);
        if($token != '' && $roleData = $this->common_lib->validateToken($token)){
            if(!isset($_POST['orderId']) || empty($_POST['orderId']))
                $this->response(['status' => FALSE,'message' => $this->lang->line('orderIdReq')], REST_Controller::HTTP_BAD_REQUEST);
            $checkOrder = $this->Common_model->exequery("SELECT orderId FROM vm_order WHERE userId='".$roleData['roleId']."' AND orderId='".$_POST['orderId']."'",true);
            if( $checkOrder ) {
                $checkUserOrder = $this->Common_model->exequery("SELECT orderId, userId, amt, chargeId, restaurantId, orderStatus, (SELECT deviceToken FROM vm_auth WHERE role='restaurant' AND roleId= restaurantId) as deviceToken, (SELECT language FROM vm_auth WHERE role='restaurant' AND roleId= restaurantId) as language, (SELECT count(*) FROM vm_order_detail WHERE orderId='".$_POST['orderId']."' AND itemType='0') as foodCount, (SELECT count(*) FROM vm_order_detail WHERE orderId='".$_POST['orderId']."' AND itemType='1') as drinkCount FROM vm_order WHERE userId='".$roleData['roleId']."' AND orderId='".$_POST['orderId']."' AND paymentStatus='Completed' AND (orderStatus ='Pending' OR orderStatus='Processing')",true);
                if( $checkUserOrder ) {
                    $error_message = array();
                    $cancelRemark = (isset($_POST['remark']) && !empty($_POST['remark'])) ? trim($_POST['remark']) : 'Order Cancelled By User';

                    try {
                            $refundedAmount = 0;
                            if($checkUserOrder->orderStatus == 'Pending') {
                                /*------------- Refund Amount deduct 4% of total order ---------*/
                                $refundedAmount = $checkUserOrder->amt;//$checkUserOrder->amt - ($checkUserOrder->amt * 4) /100;
                                /*------------------ Refund Order Amount -----------------*/
                                if($refundedAmount > 0){
                                    $refundPayment = \Stripe\Refund::create(array(        
                                        "charge" => $checkUserOrder->chargeId,
                                      )
                                    );
                                    /*------------------ maintain refund data -----------------*/
                                    $this->Common_model->insert('vm_refund', array('restaurantId' => $checkUserOrder->restaurantId, 'orderId' => $checkUserOrder->orderId, 'userId' => $checkUserOrder->userId, 'stripeChargeId' =>$refundPayment->charge, 'stripeRefundId' =>$refundPayment->id, 'refundAmount' =>$refundPayment->amount / 100, 'refundTransactionId' => $refundPayment->balance_transaction, 'addedOn' => date('Y-m-d H:i:s'), 'updatedOn' => date('Y-m-d H:i:s')));              
                                } 
                            }
                            /*------------------ Update Order Status -----------------*/
                            $this->Common_model->update('vm_order', array('paymentStatus' => 'Refund', 'orderStatus' => 'Cancelled', 'cancelRemark' => $cancelRemark, 'cancelledDateTime' => date('Y-m-d H:i:s'), 'refundedAmount' => $refundedAmount), " orderId=".$_POST['orderId']);
                            $isupdated = $this->Common_model->update("vm_user_daily_drink",array('servedStatus' => '2'),"orderId=".trim($_POST['orderId'])." and userId=".$roleData['roleId']." and servedStatus = '0'");
                            //$pushMsg = $this->common_lib->translate('userCancelOrder','english');
                            $this->notifyBartender($checkUserOrder->drinkCount, $checkUserOrder->foodCount, $checkUserOrder->restaurantId, $checkUserOrder->orderId, 'userCancelOrder', 'order_cancelled');
                            $pushMsg = $this->common_lib->translate('userCancelOrder',$checkUserOrder->language);
                            //$frenchpushMsg = $this->common_lib->translate('userCancelOrder','french');
                            if( isset($checkUserOrder->deviceToken) && !empty($checkUserOrder->deviceToken) && !empty($pushMsg))
                                $this->common_lib->sendPush($pushMsg, array('type' => 'order_cancelled', 'orderId' => $checkUserOrder->orderId), $checkUserOrder->deviceToken, false);
                           
                            $this->response(array('status' => true, 'message' => sprintf($this->lang->line('orderCancelled'), $_POST['orderId']),'orderId' => $checkUserOrder->orderId, 'postdata' => $_POST), REST_Controller::HTTP_OK);
                        }
                        catch (Exception $e) {
                            $error_message['message'] = $e->getMessage();
                          // Something else happened, completely unrelated to Stripe
                        }
                        if(!empty($error_message))                            
                            $this->response(array('status' => false, 'message' => $error_message['message'],'error' => $error_message), REST_Controller::HTTP_PAYMENT_REQUIRED);  
                }
                else
                     $this->set_response([
                        'status' => FALSE,
                        'message' => $this->lang->line('cancelOrderMsg')
                    ], REST_Controller::HTTP_FORBIDDEN);
            }
            else
                $this->set_response([
                'status' => FALSE,
                'message' => $this->lang->line('ownOrder')
            ], REST_Controller::HTTP_FORBIDDEN);
        }
        else
            $this->set_response([
                'status' => FALSE,
                'message' => $this->lang->line('unAuthorized')
            ], REST_Controller::HTTP_UNAUTHORIZED);
    }

    public function updateUserFreeDrink($userId) {

    }
    public function getcurrentOrder_get() {
        $langSuffix = $this->lang->line('langSuffix');
        $token = $this->input->get_request_header('Authorization', TRUE);
        if($token != '' && $roleData = $this->common_lib->validateToken($token)){
              $CurrentOrder = $this->Common_model->exequery("SELECT (CASE WHEN skd.isVariable!=0 then (SELECT concat('sp.productName".$langSuffix."',' ', '(vm_variable_product.variableName".$langSuffix.")') FROM vm_product sp INNER JOIN vm_variable_product ON sp.productId = vm_variable_product.productId WHERE vm_variable_product.productId = skd.productId) else (SELECT sp.productName".$langSuffix." FROM vm_product WHERE productId = skd.productId) end) as productName, (CASE WHEN skd.isVariable!=0 then (SELECT (CASE WHEN sp.img REGEXP ('^[0-9]+$') THEN (SELECT (CASE WHEN im.image != '' THEN CONCAT('".UPLOADPATH."','/vedmir_images/',im.image) when sp.categoryId=4 then '".UPLOADPATH."/defaultd/food_default.jpg' else '".UPLOADPATH."/defaultd/drink_default.jpg' end ) FROM vm_image as im WHERE im.status = 0 and im.image != '' and im.imageId= sp.img) WHEN sp.img != '' THEN CONCAT('".UPLOADPATH."','/product_images/',sp.img) when sp.categoryId=4 then '".UPLOADPATH."/defaultd/food_default.jpg' else '".UPLOADPATH."/defaultd/drink_default.jpg' end ) FROM vm_product sp INNER JOIN vm_variable_product ON sp.productId = vm_variable_product.productId WHERE vm_variable_product.productId = skd.productId) else (SELECT (CASE WHEN sp.img REGEXP ('^[0-9]+$') THEN (SELECT (CASE WHEN im.image != '' THEN CONCAT('".UPLOADPATH."','/vedmir_images/',im.image) when sp.categoryId=4 then '".UPLOADPATH."/defaultd/food_default.jpg' else '".UPLOADPATH."/defaudlt/drink_default.jpg' end ) FROM vm_image as im WHERE im.status = 0 and im.image != '' and im.imageId= sp.img) WHEN sp.img != '' THEN CONCAT('".UPLOADPATH."','/product_images/',sp.img) when sp.categoryId=4 then '".UPLOADPATH."/dedfault/food_default.jpg' else '".UPLOADPATH."/dedfault/drink_default.jpg' end ) FROM vm_product WHERE productId = skd.productId) end) as productImg FROM vm_order so INNER JOIN  vm_order_detail skd ON so.orderId = skd.orderId INNER JOIN vm_product sp ON sp.productId = skd.productId WHERE so.paymentStatus ='Completed' AND so.orderStatus !='Completed' AND so.orderStatus != 'Failed' AND so.userId = '".$roleData['roleId']."'");
            if( $CurrentOrder ) {
                $orderDetails = array();
                $CurrentOrderAmount  = $this->Common_model->exequery("SELECT so.orderId,so.orderStatus, so.amt as totalAmount, so.tableNo, so.transactionId, so.payment_method as paymentMethod   FROM vm_order so WHERE so.paymentStatus ='Completed' AND so.orderStatus !='Completed' AND so.orderStatus != 'Failed' AND so.userId = '".$roleData['roleId']."'", true);
                $orderDetails['orderId'] = $CurrentOrderAmount->orderId;
                $orderDetails['totalAmount'] = $CurrentOrderAmount->totalAmount;
                $orderDetails['transactionId'] = $CurrentOrderAmount->transactionId;
                $orderDetails['paymentMethod'] = $CurrentOrderAmount->paymentMethod;
                $orderDetails['tableNo'] = $CurrentOrderAmount->tableNo;
                $orderDetails['orderStatus'] = $CurrentOrderAmount->orderStatus;
                $orderDetails['orderItem'] = $CurrentOrder;
                $this->set_response(['status' => true,'message' => $this->lang->line('orderList'), 'orderDetails' => $orderDetails], REST_Controller::HTTP_OK);
            }
            else
                $this->set_response(['status' => true,'message' => $this->lang->line('noOrderList'), 'orderDetails' => array()], REST_Controller::HTTP_OK);
        }
        else
           $this->set_response([
                'status' => FALSE,
                'message' => $this->lang->line('unAuthorized')
            ], REST_Controller::HTTP_UNAUTHORIZED);
    }

    public function reviewnow_post() {
        $langSuffix = $this->lang->line('langSuffix');
        $token = $this->input->get_request_header('Authorization', TRUE);
        if($token != '' && $roleData = $this->common_lib->validateToken($token)){
            if(!isset($_POST['restaurantId']) || empty($_POST['restaurantId']))
                $this->response(['status' => FALSE,'message' => $this->lang->line('restaurantIdRequired')], REST_Controller::HTTP_BAD_REQUEST);
            if(!isset($_POST['pricerating']) || empty($_POST['pricerating']))
                $this->response(['status' => FALSE,'message' => $this->lang->line('priceRatingRequired')], REST_Controller::HTTP_BAD_REQUEST);
            if(!isset($_POST['qualityrating']) || empty($_POST['qualityrating']))
                $this->response(['status' => FALSE,'message' => $this->lang->line('qualityRatingRequired')], REST_Controller::HTTP_BAD_REQUEST);
            if(!isset($_POST['servicerating']) || empty($_POST['servicerating']))
                $this->response(['status' => FALSE,'message' => $this->lang->line('serviceRatingRequired')], REST_Controller::HTTP_BAD_REQUEST);
            if(!isset($_POST['ambiencerating']) || empty($_POST['ambiencerating']))
                $this->response(['status' => FALSE,'message' => $this->lang->line('ambienceRatingRequired')], REST_Controller::HTTP_BAD_REQUEST);
             /*if(!isset($_POST['message']) || empty($_POST['message']))
                $this->response(['status' => FALSE,'message' => $this->lang->line('messageRatingRequired')], REST_Controller::HTTP_BAD_REQUEST);*/
            $_POST['message'] = (isset($_POST['message']) && !empty($_POST['message'])) ? $_POST['message'] : '';
            /*$CheckRating = $this->Common_model->exequery("SELECT ratingId FROM vm_resturant_rating WHERE userId='".$roleData['roleId']."' AND restaurantId='".$_POST['restaurantId']."'");
            if( $CheckRating )
                $this->response(['status' => FALSE,'message' => $this->lang->line('alreadyReview')], REST_Controller::HTTP_FORBIDDEN);*/
            $userReview = $this->Common_model->insertUnique("vm_resturant_rating", array('userId' => $roleData['roleId'], 'restaurantId' => $_POST['restaurantId'], 'priceRating' => $_POST['pricerating'], 'qualityRating' => $_POST['qualityrating'], 'serviceRating' => $_POST['servicerating'], 'ambienceRating' => $_POST['ambiencerating'], 'overallRating' => ($_POST['pricerating'] + $_POST['qualityrating'] + $_POST['servicerating'] + $_POST['ambiencerating']) / 4, 'userMessage' => strip_tags($_POST['message']), 'createdDateTime' => date('Y-m-d H:i:s')));
            $this->Common_model->update("vm_order", array('isReview' => '1'), "userId = ".$roleData['roleId']." AND restaurantId='".$_POST['restaurantId']."'");
            $this->response(['status' => true,'message' => $this->lang->line('successReview')], REST_Controller::HTTP_OK);
        }
        else
           $this->set_response([
                'status' => FALSE,
                'message' => $this->lang->line('unAuthorized')
            ], REST_Controller::HTTP_UNAUTHORIZED);
    }

    public function skipreview_post() {
        $langSuffix = $this->lang->line('langSuffix');
        $token = $this->input->get_request_header('Authorization', TRUE);
        if($token != '' && $roleData = $this->common_lib->validateToken($token)){
            if(!isset($_POST['restaurantId']) || empty($_POST['restaurantId']))
                $this->response(['status' => FALSE,'message' => $this->lang->line('restaurantIdRequired')], REST_Controller::HTTP_BAD_REQUEST);
           /*$CheckRating = $this->Common_model->exequery("SELECT ratingId FROM vm_resturant_rating WHERE userId='".$roleData['roleId']."' AND restaurantId='".$_POST['restaurantId']."'");
            if( $CheckRating )
                $this->response(['status' => FALSE,'message' => $this->lang->line('alreadyReview')], REST_Controller::HTTP_FORBIDDEN);
            */
            $this->Common_model->update("vm_order", array('isReview' => '1'), "userId = ".$roleData['roleId']." AND restaurantId='".$_POST['restaurantId']."'");
            $this->response(['status' => true,'message' => ''], REST_Controller::HTTP_OK);
        }
        else
           $this->set_response([
                'status' => FALSE,
                'message' => $this->lang->line('unAuthorized')
            ], REST_Controller::HTTP_UNAUTHORIZED);
    }

    public function getrestaurantData_get(){
        $token = $this->input->get_request_header('Authorization', TRUE);
        $langSuffix = $this->lang->line('langSuffix');
        if($token != '' && $roleId = $this->common_lib->validateToken($token)){            
            $restaurantId = (int) $this->get('restaurantId');
            $pCond = (isset($roleId['gender']) && strtolower($roleId['gender']) == 'female')?" ":" and isOnlyForGirl = '0' ";
            $isFood = (isset($_REQUEST['isFood']) && !empty($_REQUEST['isFood'])) ? 4 : 5;
            // Validate the id.
            if ($restaurantId <= 0)
                $this->response(['status' => FALSE,'message' => $this->lang->line('inValidResturantId') ], REST_Controller::HTTP_BAD_REQUEST);
            $distanceWhere = '10 as distance';
            $distanceCond = ' ORDER BY restaurantId desc';
            if(isset($_REQUEST['lat']) && !empty($_REQUEST['lat']) && isset($_REQUEST['lang']) && !empty($_REQUEST['lang'])){
                $distanceWhere = "( 111.111 * DEGREES(acos( cos( radians(".$_REQUEST['lat'].") ) * cos( radians( lat ) ) * cos( radians( lang ) - radians(".$_REQUEST['lang'].") ) + sin( radians(".$_REQUEST['lat'].") ) * sin(radians(lat)) ))) AS distance";
                $distanceCond = ' ORDER BY distance ASC';
            }
            $restaurantData['restaurantData'] = $this->Common_model->selRowData("vm_restaurant","`restaurantId`, `generatedId`,totalTable,  isRestaurantOpen, isKitchenOpen, acceptingOrder, acceptingFoodOrder, acceptingDrinkOrder, openCloseType, restaurantName".$langSuffix." as restaurantName, `since`, `website`, `facebookPageUrl`, iframeUrl, `googlePageUrl`, `instagramPageUrl`, `youtubePageUrl`, contactName".$langSuffix." as contactName, `email`, `mobile`, CONCAT(`address1".$langSuffix."`,' ',`address2".$langSuffix."`) as address, `city".$langSuffix."` as city, `state".$langSuffix."` as state, `country".$langSuffix."` as country, `postalCode`, `lat`, `lang`, `about".$langSuffix."` as about, (SELECT case when  sum(overallRating) / count(*) is not null  then format(sum(overallRating) / count(*),1) else 0 end FROM vm_resturant_rating WHERE restaurantId = vm_restaurant.restaurantId AND status='0') as `rating`, (SELECT case when  sum(priceRating) / count(*) is not null  then format(sum(priceRating) / count(*),1) else 0 end FROM vm_resturant_rating WHERE restaurantId = vm_restaurant.restaurantId AND status='0') as `priceRating`, (SELECT case when  sum(qualityRating) / count(*) is not null  then format(sum(qualityRating) / count(*),1) else 0 end FROM vm_resturant_rating WHERE restaurantId = vm_restaurant.restaurantId AND status='0') as `qualityRating`, (SELECT case when  sum(serviceRating) / count(*) is not null  then format(sum(serviceRating) / count(*),1) else 0 end FROM vm_resturant_rating WHERE restaurantId = vm_restaurant.restaurantId AND status='0') as `serviceRating`,(SELECT case when  sum(ambienceRating) / count(*) is not null  then format(sum(ambienceRating) / count(*),1) else 0 end FROM vm_resturant_rating WHERE restaurantId = vm_restaurant.restaurantId AND status='0') as `ambienceRating`, `tax`, `img` , (case when logo !='' then concat('".UPLOADPATH."/restaurant_images/',logo) else '".UPLOADPATH."/default/restaurant_default.jpg' end ) as logo, (SELECT case when count(*) > 0 then 1 else 0 end FROM vm_product WHERE restaurantId = vm_restaurant.restaurantId AND categoryId!='5') as foodAvailable, ".$distanceWhere," restaurantId = '".$restaurantId."'");
            if (!empty($restaurantData['restaurantData']->img))
                $restaurantData['restaurantData']->img = UPLOADPATH.'/restaurant_images/'.$restaurantData['restaurantData']->img;
            
            $day = strtolower(date('l'));
            $openCloseData = $this->common_lib->checkrestaurantopenclosed($restaurantId, $restaurantData['restaurantData']->openCloseType);
            $restaurantData['restaurantData']->openCloseData = $openCloseData;
            $restaurantData['restaurantData']->day = $openCloseData['currentDay'];
            $restaurantData['restaurantData']->isOpen = $openCloseData['isOpen'];
            $restaurantData['restaurantData']->nextOpenCLoseTiming = ($openCloseData['isOpen'])?$openCloseData['closeTime']:$openCloseData['nextOpenTime'];
            $restaurantData['restaurantData']->nextOpenCLoseString = $openCloseData['nextOpenCLoseString'];

            $restaurantData['restaurantData']->weekDaysTiming = $this->common_lib->weekDaysTiming($restaurantId, $restaurantData['restaurantData']->openCloseType, $openCloseData);
            $resturantgallery = $this->Common_model->exequery("SELECT CONCAT('".UPLOADPATH."/restaurant_gallary_images/'".", image) as image  FROM vm_restaurant_gallary_img  WHERE  restaurantId = '".$restaurantId."' ");
            $resturantgallery = ($resturantgallery) ? $resturantgallery : array();
            if(!empty($restaurantData['restaurantData']->img))
                array_unshift($resturantgallery, array('image' => $restaurantData['restaurantData']->img)); 
            $restaurantData['restaurantData']->restaurantGallaryData = $resturantgallery;
            //$restaurantData['productData'] =  array();
            $userDrinkAvailable = $this->common_lib->getUserFreeDrinkAndMembership($roleId['roleId']);
            /*$userDrinkAvailable = $this->Common_model->exequery("SELECT count(*) as free,(SELECT servedStatus FROM `vm_user_daily_drink` WHERE userId = ".$roleId['roleId']." AND DATE(currentTimestamp) = '".date('Y-m-d')."'  AND (servedStatus='1' OR servedStatus='0')) as servedStatusVal,(SELECT (CASE WHEN count(*) > 0 then 1 else 0 end) as membership_count FROM vm_user_memberships WHERE startDate <= '".date('Y-m-d')."' AND endDate >= '".date('Y-m-d')."' AND userId=".$roleId['roleId']." AND subscriptionStatus ='Active' ORDER BY membershipId desc limit 0,1) as `membership` FROM `vm_user_daily_drink` WHERE userId = ".$roleId['roleId']." AND DATE(currentTimestamp) = '".date('Y-m-d')."' AND (servedStatus='1' OR servedStatus='0')",true);*/            
            
            /*if ($userDrinkAvailable->free == 0 && $userDrinkAvailable->membership != 0)
                $restaurantData['free_drink'] = '1';
            else {
                $restaurantData['free_drink'] = ( $userDrinkAvailable->servedStatusVal == 0 ) ? '2' : (($userDrinkAvailable->servedStatusVal == 1 ) ? '3' : '1' );
            }*/
            $restaurantData['free_drink'] = $userDrinkAvailable['free_drink'];
            $restaurantData['membership'] = $userDrinkAvailable['membership'];
            $restaurantsCategory = $this->Common_model->exequery("SELECT spc.subcategoryId as categoryId,  spc.restaurantId, spc.subcategoryName".$langSuffix." as categoryName, (SELECT count(*) FROM vm_product_subcategoryitem WHERE subcategoryId =  spc.subcategoryId AND status = '0') as `subcatCount` FROM vm_product_subcategory spc WHERE spc.categoryId = '".$isFood."' AND spc.restaurantId = '".$restaurantId."'  AND spc.status = '0' ORDER BY spc.orderNo ASC");
            $restaurantData['restaurantCategory'] = ($restaurantsCategory) ? $restaurantsCategory : array();
            $restaurantData['products'] = $restaurantData['subcategory'] = array(); 
            if( $restaurantsCategory ) {
                $firstCategory = $restaurantsCategory[0];
                if ( $firstCategory->subcatCount ) {
                    $restaurantsSubcat = $this->Common_model->exequery("SELECT subcategoryitemId, subcategoryitemName".$langSuffix." as subcategoryName FROM vm_product_subcategoryitem WHERE subcategoryId =  '".$restaurantsCategory->categoryId."' AND status = '0' ORDER BY orderNo ASC");
                    if( $restaurantsSubcat ) {
                        $restaurantData['subcategory'] = $restaurantsSubcat;
                        $firstSubCat = $restaurantsSubcat[0];
                        $productData = $this->Common_model->exequery("SELECT productId,restaurantId,productName".$langSuffix." as productName,sortDescription".$langSuffix." as sortDescription,description".$langSuffix." as description,(CASE WHEN productType=1 then (SELECT price FROM `vm_variable_product` WHERE status='0' AND  productId = vm_product.productId order by price asc limit 0,1) else price end) as price,tags".$langSuffix." as tags,(CASE WHEN vm_product.img REGEXP ('^[0-9]+$') THEN (SELECT (CASE WHEN im.image != '' THEN CONCAT('".UPLOADPATH."','/vedmir_images/',im.image) when vm_product.categoryId=4 then '".UPLOADPATH."/default/food_default.jpg' else '".UPLOADPATH."/default/drink_default.jpg' end ) FROM vm_image as im WHERE im.status = 0 and im.image != '' and im.imageId= vm_product.img) WHEN vm_product.img != '' THEN CONCAT('".UPLOADPATH."','/product_images/',vm_product.img) when vm_product.categoryId=4 then '".UPLOADPATH."/default/food_default.jpg' else '".UPLOADPATH."/default/drink_default.jpg' end ) as img,categoryId,subcategoryId,productType , isAvailableInFree as isFree, doNotIncludeInTheMenu, isStockAvailable, (SELECT subcategoryName".$langSuffix." FROM vm_product_subcategory WHERE subcategoryId = vm_product.subcategoryId) as categoryName,(CASE WHEN subcategoryitemId !=0 then (SELECT subcategoryitemName".$langSuffix."  FROM vm_product_subcategoryitem WHERE subcategoryitemId = vm_product.subcategoryitemId ) else '' end )as subcategoryName FROM vm_product WHERE restaurantId = '".$restaurantId."' AND categoryId='".$isFood."' AND subcategoryId ='".$restaurantsCategory->categoryId."' AND subcategoryitemId='".$firstSubCat->subcategoryitemId."' AND status = '0'".$pCond." ORDER BY orderNo ASC, subOrderNo ASC");
                            if(valResultSet($productData)) {
                                foreach($productData as $product) {
                                   /* if (!empty($product->img))
                                        $product->img = UPLOADPATH.'/product_images/'.$product->img;*/

                                    $product->productGallaryData = $this->Common_model->exequery("SELECT CONCAT('".UPLOADPATH."/product_gallary_images/'".", image) as image  FROM vm_product_gallary_img  WHERE  productId = '".$product->productId."' ");
                                    $product->productGallaryData = ($product->productGallaryData) ? $product->productGallaryData : array();
                                    if(!empty($product->img))
                                        array_unshift($product->productGallaryData, array('image' => $product->img));
                                    if( $product->productType ) {
                                        $variableproduct = $this->Common_model->exequery("SELECT variableId, variableName".$langSuffix." as variableName, price FROM `vm_variable_product` WHERE status='0' AND  productId ='".$product->productId."'");
                                        $product->variableProduct = ( $variableproduct ) ? $variableproduct : array();
                                    } 
                                }
                                $restaurantData['products'] = $productData;
                            }
                    }
                }
                else {
                    $productData = $this->Common_model->exequery("SELECT productId,restaurantId,productName".$langSuffix." as productName,sortDescription".$langSuffix." as sortDescription,description".$langSuffix." as description,(CASE WHEN productType=1 then (SELECT price FROM `vm_variable_product` WHERE status='0' AND  productId = vm_product.productId order by price asc limit 0,1) else price end) as price,tags".$langSuffix." as tags,(CASE WHEN vm_product.img REGEXP ('^[0-9]+$') THEN (SELECT (CASE WHEN im.image != '' THEN CONCAT('".UPLOADPATH."','/vedmir_images/',im.image) when vm_product.categoryId=4 then '".UPLOADPATH."/default/food_default.jpg' else '".UPLOADPATH."/default/drink_default.jpg' end ) FROM vm_image as im WHERE im.status = 0 and im.image != '' and im.imageId= vm_product.img) WHEN vm_product.img != '' THEN CONCAT('".UPLOADPATH."','/product_images/',vm_product.img) when vm_product.categoryId=4 then '".UPLOADPATH."/default/food_default.jpg' else '".UPLOADPATH."/default/drink_default.jpg' end ) as img,categoryId,subcategoryId,productType , isAvailableInFree as isFree, doNotIncludeInTheMenu, isStockAvailable,(SELECT subcategoryName".$langSuffix." FROM vm_product_subcategory WHERE subcategoryId = vm_product.subcategoryId) as categoryName,(CASE WHEN subcategoryitemId !=0 then (SELECT subcategoryitemName".$langSuffix."  FROM vm_product_subcategoryitem WHERE subcategoryitemId = vm_product.subcategoryitemId ) else '' end)as subcategoryName FROM vm_product WHERE restaurantId = '".$restaurantId."' AND categoryId='".$isFood."' AND subcategoryId ='".$firstCategory->categoryId."' AND subcategoryitemId='0' AND status = '0'".$pCond." ORDER BY orderNo ASC, subOrderNo ASC");
                        if(valResultSet($productData)) {
                            foreach($productData as $product) {
                                /*if (!empty($product->img))
                                    $product->img = UPLOADPATH.'/product_images/'.$product->img;*/

                                $product->productGallaryData = $this->Common_model->exequery("SELECT CONCAT('".UPLOADPATH."/product_gallary_images/'".", image) as image  FROM vm_product_gallary_img  WHERE  productId = '".$product->productId."' ");
                                $product->productGallaryData = ($product->productGallaryData) ? $product->productGallaryData : array();
                                if(!empty($product->img))
                                    array_unshift($product->productGallaryData, array('image' => $product->img)); 
                                if( $product->productType ) {
                                    $variableproduct = $this->Common_model->exequery("SELECT variableId, variableName".$langSuffix." as variableName, price FROM `vm_variable_product` WHERE status='0' AND  productId ='".$product->productId."'");
                                    $product->variableProduct = ( $variableproduct ) ? $variableproduct : array();
                                }
                            }
                            $restaurantData['products'] = $productData;
                        }
                }
            }              
            //$restaurantData['firstCategory'] = $firstCategory;
            if (!empty($restaurantData))
                $this->set_response($restaurantData, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            else{
                $this->response([
                    'status' => FALSE,
                    'message' => $this->lang->line('noResturant')
                ], REST_Controller::HTTP_FORBIDDEN); // HTTP_FORBIDDEN (403) being the HTTP response code
            }
        }else{
            $this->response([
                'status' => FALSE,
                'message' => $this->lang->line('unAuthorized')
            ], REST_Controller::HTTP_UNAUTHORIZED); // HTTP_UNAUTHORIZED (401) being the HTTP response code
        }
    }

    public function getrestaurantCategoryData_get(){
        $token = $this->input->get_request_header('Authorization', TRUE);
        $langSuffix = $this->lang->line('langSuffix');
        if($token != '' && $roleId = $this->common_lib->validateToken($token)){            
            $restaurantId = (int) $this->get('restaurantId');
            $pCond = (isset($roleId['gender']) && strtolower($roleId['gender']) == 'female')?" ":" and isOnlyForGirl = '0' ";
            $categoryId = (isset($_REQUEST['categoryId']) && !empty($_REQUEST['categoryId'])) ? $_REQUEST['categoryId'] : 0;
            // Validate the id.
            if ($restaurantId <= 0)
                $this->response(['status' => FALSE,'message' => $this->lang->line('inValidResturantId') ], REST_Controller::HTTP_BAD_REQUEST);
            if ($categoryId <= 0)
                $this->response(['status' => FALSE,'message' => $this->lang->line('InvalidRequest') ], REST_Controller::HTTP_BAD_REQUEST);
            $subcategoryData = (isset($_REQUEST['subCategoryId']) && !empty($_REQUEST['subCategoryId'])) ? array_filter(explode(',',$_REQUEST['subCategoryId'])) : '';
            //$condProduct = ( $subcategoryData ) ? : '';
            $distanceWhere = '10 as distance';
            $distanceCond = ' ORDER BY restaurantId desc';
            if(isset($_REQUEST['lat']) && !empty($_REQUEST['lat']) && isset($_REQUEST['lang']) && !empty($_REQUEST['lang'])){
                $distanceWhere = "( 111.111 * DEGREES(acos( cos( radians(".$_REQUEST['lat'].") ) * cos( radians( lat ) ) * cos( radians( lang ) - radians(".$_REQUEST['lang'].") ) + sin( radians(".$_REQUEST['lat'].") ) * sin(radians(lat)) ))) AS distance";
                $distanceCond = ' ORDER BY distance ASC';
            }
            $restaurantData['restaurantData'] = $this->Common_model->selRowData("vm_restaurant","`restaurantId`, `generatedId`,totalTable, isRestaurantOpen, isKitchenOpen, openCloseType, restaurantName".$langSuffix." as restaurantName, `since`,`website`, `facebookPageUrl`, iframeUrl, `googlePageUrl`, `instagramPageUrl`, `youtubePageUrl`, contactName".$langSuffix." as contactName, `email`, `mobile`, CONCAT(`address1".$langSuffix."`,' ',`address2".$langSuffix."`) as address, `city".$langSuffix."` as city, `state".$langSuffix."` as state, `country".$langSuffix."` as country, `postalCode`, `lat`, `lang`, `about".$langSuffix."` as about, (SELECT case when  sum(overallRating) / count(*) is not null  then format(sum(overallRating) / count(*),1) else 0 end FROM vm_resturant_rating WHERE restaurantId = vm_restaurant.restaurantId AND status='0') as `rating`, (SELECT case when  sum(priceRating) / count(*) is not null  then format(sum(priceRating) / count(*),1) else 0 end FROM vm_resturant_rating WHERE restaurantId = vm_restaurant.restaurantId AND status='0') as `priceRating`, (SELECT case when  sum(qualityRating) / count(*) is not null  then format(sum(qualityRating) / count(*),1) else 0 end FROM vm_resturant_rating WHERE restaurantId = vm_restaurant.restaurantId AND status='0') as `qualityRating`, (SELECT case when  sum(serviceRating) / count(*) is not null  then format(sum(serviceRating) / count(*),1) else 0 end FROM vm_resturant_rating WHERE restaurantId = vm_restaurant.restaurantId AND status='0') as `serviceRating`,(SELECT case when  sum(ambienceRating) / count(*) is not null  then format(sum(ambienceRating) / count(*),1) else 0 end FROM vm_resturant_rating WHERE restaurantId = vm_restaurant.restaurantId AND status='0') as `ambienceRating`, `tax`, `img` , (case when logo !='' then concat('".UPLOADPATH."/restaurant_images/',logo) else '".UPLOADPATH."/default/restaurant_default.jpg' end ) as logo, (SELECT case when count(*) > 0 then 1 else 0 end FROM vm_product WHERE restaurantId = vm_restaurant.restaurantId AND categoryId!='5') as foodAvailable, ".$distanceWhere," restaurantId = '".$restaurantId."'");
            if (!empty($restaurantData['restaurantData']->img))
                $restaurantData['restaurantData']->img = UPLOADPATH.'/restaurant_images/'.$restaurantData['restaurantData']->img;
            
           
            $day = strtolower(date('l'));
            $openCloseData = $this->common_lib->checkrestaurantopenclosed($restaurantId, $restaurantData['restaurantData']->openCloseType);
            $restaurantData['restaurantData']->openCloseData = $openCloseData;
            $restaurantData['restaurantData']->day = $openCloseData['currentDay'];
            $restaurantData['restaurantData']->isOpen = $openCloseData['isOpen'];
            $restaurantData['restaurantData']->nextOpenCLoseTiming = ($openCloseData['isOpen'])?$openCloseData['closeTime']:$openCloseData['nextOpenTime'];
            $restaurantData['restaurantData']->nextOpenCLoseString = $openCloseData['nextOpenCLoseString'];

            $restaurantData['restaurantData']->weekDaysTiming = $this->common_lib->weekDaysTiming($restaurantId, $restaurantData['restaurantData']->openCloseType, $openCloseData);

            $resturantgallery = $this->Common_model->exequery("SELECT CONCAT('".UPLOADPATH."/restaurant_gallary_images/'".", image) as image  FROM vm_restaurant_gallary_img  WHERE  restaurantId = '".$restaurantId."' ");
            $resturantgallery = ($resturantgallery) ? $resturantgallery : array();
            if(!empty($restaurantData['restaurantData']->img))
                array_unshift($resturantgallery, array('image' => $restaurantData['restaurantData']->img)); 
            $restaurantData['restaurantData']->restaurantGallaryData = $resturantgallery;
            //$restaurantData['productData'] =  array();
            $userDrinkAvailable = $this->common_lib->getUserFreeDrinkAndMembership($roleId['roleId']);
            /*$userDrinkAvailable = $this->Common_model->exequery("SELECT count(*) as free,(SELECT servedStatus FROM `vm_user_daily_drink` WHERE userId = ".$roleId['roleId']." AND DATE(currentTimestamp) = '".date('Y-m-d')."'  AND (servedStatus='1' OR servedStatus='0')) as servedStatusVal,(SELECT (CASE WHEN count(*) > 0 then 1 else 0 end) as membership_count FROM vm_user_memberships WHERE startDate <= '".date('Y-m-d')."' AND endDate >= '".date('Y-m-d')."' AND userId=".$roleId['roleId']." AND subscriptionStatus ='Active' ORDER BY membershipId desc limit 0,1) as `membership` FROM `vm_user_daily_drink` WHERE userId = ".$roleId['roleId']." AND DATE(currentTimestamp) = '".date('Y-m-d')."' AND (servedStatus='1' OR servedStatus='0')",true);*/            
            
            /*if ($userDrinkAvailable->free == 0 && $userDrinkAvailable->membership != 0)
                $restaurantData['free_drink'] = '1';
            else {
                $restaurantData['free_drink'] = ( $userDrinkAvailable->servedStatusVal == 0 ) ? '2' : (($userDrinkAvailable->servedStatusVal == 1 ) ? '3' : '1' );
            }*/
            $restaurantData['free_drink'] = $userDrinkAvailable['free_drink'];
            $restaurantData['membership'] = $userDrinkAvailable['membership'];
            $restaurantsCategory = $this->Common_model->exequery("SELECT count(*) as `subcatCount` FROM vm_product_subcategoryitem WHERE subcategoryId =  '".$categoryId."' AND status = '0'",true);
            //$restaurantData['restaurantCategory'] = ($restaurantsCategory) ? $restaurantsCategory : array();
            $restaurantData['products'] = $restaurantData['subcategory'] = array(); 
            if( $restaurantsCategory ) {
                
                if ( $restaurantsCategory->subcatCount ) {
                    $restaurantsSubcat = $this->Common_model->exequery("SELECT subcategoryitemId, subcategoryitemName".$langSuffix." as subcategoryName FROM vm_product_subcategoryitem WHERE subcategoryId =  '".$categoryId."' AND status = '0' ORDER BY orderNo ASC");
                    if( $restaurantsSubcat ) {
                        $restaurantData['subcategory'] = $restaurantsSubcat;
                        $firstSubCat = $restaurantsSubcat[0];
                        $subcategoryqry = ($subcategoryData ) ? " AND subcategoryitemId IN (".implode(',',$subcategoryData).")": " AND subcategoryitemId='".$firstSubCat->subcategoryitemId."'";
                        
                        $productData = $this->Common_model->exequery("SELECT productId,restaurantId,productName".$langSuffix." as productName,sortDescription".$langSuffix." as sortDescription,description".$langSuffix." as description,(CASE WHEN productType=1 then (SELECT price FROM `vm_variable_product` WHERE status='0' AND  productId = vm_product.productId order by price asc limit 0,1) else price end) as price,tags".$langSuffix." as tags,(CASE WHEN vm_product.img REGEXP ('^[0-9]+$') THEN (SELECT (CASE WHEN im.image != '' THEN CONCAT('".UPLOADPATH."','/vedmir_images/',im.image) when vm_product.categoryId=4 then '".UPLOADPATH."/default/food_default.jpg' else '".UPLOADPATH."/default/drink_default.jpg' end ) FROM vm_image as im WHERE im.status = 0 and im.image != '' and im.imageId= vm_product.img) WHEN vm_product.img != '' THEN CONCAT('".UPLOADPATH."','/product_images/',vm_product.img) when vm_product.categoryId=4 then '".UPLOADPATH."/default/food_default.jpg' else '".UPLOADPATH."/default/drink_default.jpg' end ) as img,categoryId,subcategoryId,productType ,isAvailableInFree as isFree, doNotIncludeInTheMenu, isStockAvailable, (SELECT subcategoryName".$langSuffix." FROM vm_product_subcategory WHERE subcategoryId = vm_product.subcategoryId) as categoryName,(CASE WHEN subcategoryitemId !=0 then (SELECT subcategoryitemName".$langSuffix."  FROM vm_product_subcategoryitem WHERE subcategoryitemId = vm_product.subcategoryitemId ) else '' end )as subcategoryName FROM vm_product WHERE restaurantId = '".$restaurantId."' AND subcategoryId ='".$categoryId."' AND status = '0' ".$subcategoryqry.$pCond." ORDER BY orderNo ASC, subOrderNo ASC");
                            if(valResultSet($productData)) {
                                foreach($productData as $product) {
                                   /* if (!empty($product->img))
                                        $product->img = UPLOADPATH.'/product_images/'.$product->img;*/

                                    $product->productGallaryData = $this->Common_model->exequery("SELECT CONCAT('".UPLOADPATH."/product_gallary_images/'".", image) as image  FROM vm_product_gallary_img  WHERE  productId = '".$product->productId."' ");
                                    $product->productGallaryData = ($product->productGallaryData) ? $product->productGallaryData : array();
                                    if(!empty($product->img))
                                        array_unshift($product->productGallaryData, array('image' => $product->img));
                                    if( $product->productType ) {
                                        $variableproduct = $this->Common_model->exequery("SELECT variableId, variableName".$langSuffix." as variableName, price FROM `vm_variable_product` WHERE status='0' AND  productId ='".$product->productId."'");
                                        $product->variableProduct = ( $variableproduct ) ? $variableproduct : array();
                                    } 
                                }
                                $restaurantData['products'] = $productData;
                            }
                    }
                }
                else {
                    $subcategoryqry = ($subcategoryData ) ? " AND subcategoryitemId IN (".implode(',',$subcategoryData).")": " AND subcategoryitemId='0'";
                    $productData = $this->Common_model->exequery("SELECT productId,restaurantId,productName".$langSuffix." as productName,sortDescription".$langSuffix." as sortDescription,description".$langSuffix." as description,(CASE WHEN productType=1 then (SELECT price FROM `vm_variable_product` WHERE status='0' AND  productId = vm_product.productId order by price asc limit 0,1) else price end) as price,tags".$langSuffix." as tags,(CASE WHEN vm_product.img REGEXP ('^[0-9]+$') THEN (SELECT (CASE WHEN im.image != '' THEN CONCAT('".UPLOADPATH."','/vedmir_images/',im.image) when vm_product.categoryId=4 then '".UPLOADPATH."/default/food_default.jpg' else '".UPLOADPATH."/default/drink_default.jpg' end ) FROM vm_image as im WHERE im.status = 0 and im.image != '' and im.imageId= vm_product.img) WHEN vm_product.img != '' THEN CONCAT('".UPLOADPATH."','/product_images/',vm_product.img) when vm_product.categoryId=4 then '".UPLOADPATH."/default/food_default.jpg' else '".UPLOADPATH."/default/drink_default.jpg' end ) as img,categoryId,subcategoryId,productType ,isAvailableInFree as isFree, doNotIncludeInTheMenu, isStockAvailable, (SELECT subcategoryName".$langSuffix." FROM vm_product_subcategory WHERE subcategoryId = vm_product.subcategoryId) as categoryName,(CASE WHEN subcategoryitemId !=0 then (SELECT subcategoryitemName".$langSuffix."  FROM vm_product_subcategoryitem WHERE subcategoryitemId = vm_product.subcategoryitemId ) else '' end)as subcategoryName FROM vm_product WHERE restaurantId = '".$restaurantId."' AND subcategoryId ='".$categoryId."' AND status = '0' ".$subcategoryqry.$pCond." ORDER BY orderNo ASC, subOrderNo ASC");
                        if(valResultSet($productData)) {
                            foreach($productData as $product) {
                                /*if (!empty($product->img))
                                    $product->img = UPLOADPATH.'/product_images/'.$product->img;*/

                                $product->productGallaryData = $this->Common_model->exequery("SELECT CONCAT('".UPLOADPATH."/product_gallary_images/'".", image) as image  FROM vm_product_gallary_img  WHERE  productId = '".$product->productId."' ");
                                $product->productGallaryData = ($product->productGallaryData) ? $product->productGallaryData : array();
                                if(!empty($product->img))
                                    array_unshift($product->productGallaryData, array('image' => $product->img)); 
                                if( $product->productType ) {
                                    $variableproduct = $this->Common_model->exequery("SELECT variableId, variableName".$langSuffix." as variableName, price FROM `vm_variable_product` WHERE status='0' AND  productId ='".$product->productId."'");
                                    $product->variableProduct = ( $variableproduct ) ? $variableproduct : array();
                                }
                            }
                            $restaurantData['products'] = $productData;
                        }
                }
            }   

            //$restaurantData['firstCategory'] = $firstCategory;
            if (!empty($restaurantData))
                $this->set_response($restaurantData, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            else{
                $this->response([
                    'status' => FALSE,
                    'message' => $this->lang->line('noCategory')
                ], REST_Controller::HTTP_FORBIDDEN); // HTTP_FORBIDDEN (403) being the HTTP response code
            }
        }else{
            $this->response([
                'status' => FALSE,
                'message' => $this->lang->line('unAuthorized')
            ], REST_Controller::HTTP_UNAUTHORIZED); // HTTP_UNAUTHORIZED (401) being the HTTP response code
        }
    }
    public function isKitchenStatus_get($restaurantId = 0){
        $token = $this->input->get_request_header('Authorization', TRUE);
        $langSuffix = $this->lang->line('langSuffix');
        if($token != '' && $roleId = $this->common_lib->validateToken($token)){            
            
            
            if ($restaurantId <= 0)
                $this->response(['status' => FALSE,'message' => $this->lang->line('inValidResturantId') ], REST_Controller::HTTP_BAD_REQUEST);
            $getRestaurantInfo = $this->Common_model->exequery("SELECT * FROM vm_restaurant WHERE restaurantId ='".$restaurantId."' and status = '0'", true);
            if($getRestaurantInfo) {
                if($getRestaurantInfo->isKitchenOpen == 1)
                    $this->response([
                        'status' => TRUE,
                        'message' => ''
                    ], REST_Controller::HTTP_OK);
                else
                    $this->response([
                        'status' => FALSE,
                        'message' => ''
                    ], REST_Controller::HTTP_FORBIDDEN);
            }
            else
                $this->response([
                'status' => FALSE,
                'message' => ''
            ], REST_Controller::HTTP_FORBIDDEN);
            
        }else{
            $this->response([
                'status' => FALSE,
                'message' => $this->lang->line('unAuthorized')
            ], REST_Controller::HTTP_UNAUTHORIZED); // HTTP_UNAUTHORIZED (401) being the HTTP response code
        }
    }
    public function setting_get($restaurantId = 0){
        $token = $this->input->get_request_header('Authorization', TRUE);
        $langSuffix = $this->lang->line('langSuffix');
        if($token != '' && $roleId = $this->common_lib->validateToken($token)){            
            
            
            if ($restaurantId <= 0)
                $this->response(['status' => FALSE,'message' => $this->lang->line('inValidResturantId') ], REST_Controller::HTTP_BAD_REQUEST);
            $getRestaurantInfo = $this->Common_model->exequery("SELECT isRestaurantOpen, isKitchenOpen, acceptingOrder, acceptingFoodOrder, acceptingDrinkOrder, openCloseType,isShowOrderPopup FROM vm_restaurant WHERE restaurantId ='".$restaurantId."' and status = '0'", true);
            if($getRestaurantInfo) {  
                $day = strtolower(date('l'));
                $openCloseData = $this->common_lib->checkrestaurantopenclosed($restaurantId, $getRestaurantInfo->openCloseType);
                unset($getRestaurantInfo->isRestaurantOpen);
                //$getRestaurantInfo->openCloseData = $openCloseData;
                $getRestaurantInfo->day = $openCloseData['currentDay'];
                $getRestaurantInfo->isOpen = $openCloseData['isOpen'];
                $getRestaurantInfo->nextOpenCLoseTiming = ($openCloseData['isOpen'])?$openCloseData['closeTime']:$openCloseData['nextOpenTime'];
                $getRestaurantInfo->nextOpenCLoseString = $openCloseData['nextOpenCLoseString'];              
                $this->response([
                    'status' => TRUE,
                    'data' => $getRestaurantInfo,
                    'message' => ''
                ], REST_Controller::HTTP_OK);
                
            }
            else
                $this->response([
                'status' => FALSE,
                'message' => ''
            ], REST_Controller::HTTP_FORBIDDEN);
            
        }else{
            $this->response([
                'status' => FALSE,
                'message' => $this->lang->line('unAuthorized')
            ], REST_Controller::HTTP_UNAUTHORIZED); // HTTP_UNAUTHORIZED (401) being the HTTP response code
        }
    }

}
