<?php



defined('BASEPATH') OR exit('No direct script access allowed');



/**

 * Created by Dream Steps Pvt Ltd

 * Created on 30 Nov 2019

 * Vedmir -  module

**/



class Product extends CI_Controller {



	



	public $menu		= 1;



	public $subMenu		= 11;



	public $subSubMenu		= 0;



	public $outputdata 	= array();

	public $langSuffix = '';

	



	public function __construct(){



		parent::__construct();



		//Check login authentication & set public veriables



		$this->session->set_userdata(PREFIX.'sessRole', "admin");



		$this->common_lib->setSessionVariables();

		$this->langSuffix = $this->lang->line('langSuffix');		



	}

	public function add_category($categoryId = 0){

		$this->menu		=	1;

		$this->subMenu	=	11;

		$langSuffix = $this->lang->line('langSuffix');

		if(isset($_POST) && !empty($_POST['categoryName'.$langSuffix])) {

			$status = '';

			$insertData   =  array();



			if($categoryId > 0)

				$isExistCond = " and categoryId !=".$categoryId;

			else

				$isExistCond = '';



			$isExist = $this->Common_model->selRowData("vm_product_category","","status != '2' and categoryName".$langSuffix." = '".$_POST['categoryName'.$langSuffix]."'".$isExistCond);

			$status = 'alreadyExist';



			if(!$isExist){			

				$_POST['categoryName'] = (isset($_POST['categoryName']) && empty($_POST['categoryName']) && $this->sessLang != 'english') ? $this->common_lib->changeLanguage($_POST['categoryName'.$langSuffix],$this->lang->line('lang'),'en') : $_POST['categoryName'] ;



				$_POST['categoryName_fr'] = (isset($_POST['categoryName_fr']) && empty($_POST['categoryName_fr']) && $this->sessLang != 'french') ? $this->common_lib->changeLanguage($_POST['categoryName'.$langSuffix],$this->lang->line('lang'),'fr') : $_POST['categoryName_fr'] ; 



				$_POST['categoryName_gr'] = (isset($_POST['categoryName_gr']) && empty($_POST['categoryName_gr']) && $this->sessLang != 'german') ? $this->common_lib->changeLanguage($_POST['categoryName'.$langSuffix],$this->lang->line('lang'),'de') : $_POST['categoryName_gr'] ; 



				$_POST['categoryName_it'] = (isset($_POST['categoryName_it']) && empty($_POST['categoryName_it']) && $this->sessLang != 'italian') ? $this->common_lib->changeLanguage($_POST['categoryName'.$langSuffix],$this->lang->line('lang'),'it') : $_POST['categoryName_it'] ; 





				$insertData['categoryName']	 	=   trim($_POST['categoryName']);

				$insertData['categoryName_fr']	=   trim($_POST['categoryName_fr']);

				$insertData['categoryName_gr']	=   trim($_POST['categoryName_gr']);

				$insertData['categoryName_it']	=   trim($_POST['categoryName_it']);

				$insertData['updatedOn']	 	=   date('Y-m-d H:i:s');



				if($categoryId > 0){

					$slug = $this->common_lib->create_unique_slug(trim($_POST['categoryName']),"vm_product_category","categoryName",$categoryId,"categoryId",$counter=0);

					$insertData['slug']			 =   $slug;

					$cond 	=	"categoryId = ".$categoryId;

					$updatetStatus 		= 	$this->Common_model->update("vm_product_category", $insertData,$cond);

					if($updatetStatus)

						$status = 'updated';

				}else if($categoryId == 0 && !$isExist){

					$slug = $this->common_lib->create_unique_slug(trim($_POST['categoryName']),"vm_product_category","categoryName",0,"categoryId",$counter=0);

					$insertData['slug']			 =   $slug;

					$insertData['addedOn']	 	 =   date('Y-m-d H:i:s');

					$updatetStatus 		= 	$this->Common_model->insert("vm_product_category", $insertData);

					if($updatetStatus)

						$status = 'added';

				}

			}



			if($status == 'added')

				$this->common_lib->setSessMsg($this->lang->line('addNewRecord'), 1);

			else if($status == 'updated')

				$this->common_lib->setSessMsg($this->lang->line('editRecord'), 1);

			else if($status == 'alreadyExist')

				$this->common_lib->setSessMsg($this->lang->line('alreadyExist'), 2);

			else

				$this->common_lib->setSessMsg($this->lang->line('dbError'), 2);

		}



		if ($categoryId > 0)

			$this->outputdata['categoryData'] = $this->Common_model->selRowData("vm_product_category","","categoryId = ".$categoryId);



		$this->load->viewD('admin/product_category_add_view', $this->outputdata);

	}

		



	// category-listing view

	public function category_list(){	

		$this->menu		=	1;

		$this->subMenu	=	12;			

		$this->load->viewD('admin/product_category_list_view', $this->outputdata);

	}	

	// Add Category view

	public function add_subcategory($subcategoryId = ''){

		$this->menu		=	1;

		$this->subMenu	=	13;

			// v3print($_POST);exit;

		$langSuffix = $this->lang->line('langSuffix');

		if(isset($_POST) && !empty($_POST['subcategoryName'.$langSuffix])) {



			$status = '';

			$insertData   =  array();



			if($subcategoryId > 0)

                $this->db->where('subcategoryId !=', $subcategoryId);

            

            $this->db->where(array("status !="=>"2", "restaurantId ="=>trim($_POST['selRestaurant']), "categoryId ="=>trim($_POST['categoryId']), "subcategoryName$langSuffix" =>$_POST['subcategoryName'.$langSuffix]));

            $qry = $this->db->get('vm_product_subcategory');

            $isExist = $qry->row();



			$status = 'alreadyExist';



			if(!$isExist){	

				$_POST['subcategoryName'] = (isset($_POST['subcategoryName']) && empty($_POST['subcategoryName']) && $this->sessLang != 'english') ? $this->common_lib->changeLanguage($_POST['subcategoryName'.$langSuffix],$this->lang->line('lang'),'en') : $_POST['subcategoryName'] ;



				$_POST['subcategoryName_fr'] = (isset($_POST['subcategoryName_fr']) && empty($_POST['subcategoryName_fr']) && $this->sessLang != 'french') ? $this->common_lib->changeLanguage($_POST['subcategoryName'.$langSuffix],$this->lang->line('lang'),'fr') : $_POST['subcategoryName_fr'] ; 



				$_POST['subcategoryName_gr'] = (isset($_POST['subcategoryName_gr']) && empty($_POST['subcategoryName_gr']) && $this->sessLang != 'german') ? $this->common_lib->changeLanguage($_POST['subcategoryName'.$langSuffix],$this->lang->line('lang'),'de') : $_POST['subcategoryName_gr'] ; 



				$_POST['subcategoryName_it'] = (isset($_POST['subcategoryName_it']) && empty($_POST['subcategoryName_it']) && $this->sessLang != 'italian') ? $this->common_lib->changeLanguage($_POST['subcategoryName'.$langSuffix],$this->lang->line('lang'),'it') : $_POST['subcategoryName_it'] ; 



				// v3print($_POST);exit;

				$insertData   =  array();

				$insertData['categoryId']	 		=   trim($_POST['categoryId']);

				$insertData['subcategoryName']		=   trim($_POST['subcategoryName']);

				$insertData['subcategoryName_fr']	=   trim($_POST['subcategoryName_fr']);

				$insertData['subcategoryName_gr']	=   trim($_POST['subcategoryName_gr']);

				$insertData['subcategoryName_it']	=   trim($_POST['subcategoryName_it']);

				$insertData['updatedOn']	 		=   date('Y-m-d H:i:s');





				if($subcategoryId > 0 && !$isExist){



					$slug = $this->common_lib->create_unique_slug(trim($_POST['subcategoryName']),"vm_product_subcategory","subcategoryName",$subcategoryId,"subcategoryId",$counter=0);

					$insertData['slug']			 =   $slug;

					$cond 	=	"subcategoryId = ".$subcategoryId;

					$updatetStatus 		= 	$this->Common_model->update("vm_product_subcategory", $insertData,$cond);

					if($updatetStatus)

						$status = 'updated';



				}else if($subcategoryId == 0 && !$isExist){

					$slug = $this->common_lib->create_unique_slug(trim($_POST['subcategoryName']),"vm_product_subcategory","subcategoryName",0,"subcategoryId",$counter=0);

					$insertData['slug']			 =   $slug;

					$insertData['restaurantId']	 =   trim($_POST['selRestaurant']);

					$insertData['addedOn']	 	 =   date('Y-m-d H:i:s');

					$updatetStatus 		= 	$this->Common_model->insert("vm_product_subcategory", $insertData);

					if($updatetStatus)

						$status = 'added';

				}

			}



			if($status == 'added')

				$this->common_lib->setSessMsg($this->lang->line('addNewRecord'), 1);

			else if($status == 'updated')

				$this->common_lib->setSessMsg($this->lang->line('editRecord'), 1);

			else if($status == 'alreadyExist')

				$this->common_lib->setSessMsg($this->lang->line('alreadyExist'), 2);

			else

				$this->common_lib->setSessMsg($this->lang->line('dbError'), 2);

		}

			



		$this->outputdata['restaurantData'] = $this->Common_model->selTableData("vm_restaurant","restaurantId,restaurantName$this->langSuffix as restaurantName","status = 0","restaurantName");

		$this->outputdata['categoryData'] = $this->Common_model->selTableData("vm_product_category","categoryId,categoryName$this->langSuffix as categoryName","status = 0 ");

		if ($subcategoryId > 0)

			$this->outputdata['subcategoryData'] = $this->Common_model->selRowData("vm_product_subcategory","","subcategoryId = ".$subcategoryId);

		else if(isset($_POST['categoryId']) && !empty($_POST['categoryId'])){

			$newObj =  new stdClass();

			$newObj->restaurantId 		=  $_POST['selRestaurant'];

			$newObj->categoryId 		=  $_POST['categoryId'];

			// if ($langSuffix == '') {

			// 	$newObj->subcategoryName 		=  $_POST['subcategoryName'];

			// }else if($langSuffix == '_fr') {

			// 	$newObj->subcategoryName_fr 	=  $_POST['subcategoryName_fr'];



			// }else if($langSuffix == '_gr') {

			// 	$newObj->subcategoryName_gr 	=  $_POST['subcategoryName_gr'];



			// }else if($langSuffix == '_it') {

			// 	$newObj->subcategoryName_it 	=  $_POST['subcategoryName_it'];



			// }

			$this->outputdata['subcategoryData'] = $newObj;

		}



		$this->load->viewD('admin/product_subcategory_add_view', $this->outputdata);

	}

		



	// subcategory-listing view

	public function subcategory_list(){	

		$this->menu		=	1;

		$this->subMenu	=	14;



		$this->load->viewD('admin/product_subcategory_list_view', $this->outputdata);

	}	

	// Add subcategory item view

	public function add_subcategoryitem($subcategoryitemId = ''){

		$this->menu		=	1;

		$this->subMenu	=	15;

			// v3print($_POST);exit;

		$langSuffix = $this->lang->line('langSuffix');

		if(isset($_POST) && !empty($_POST['subcategoryitemName'.$langSuffix])) {



			$status = '';

			$insertData   =  array();



			if($subcategoryitemId > 0)

                $this->db->where('subcategoryitemId !=', $subcategoryitemId);

            

            $this->db->where(array("status !="=>"2", "restaurantId ="=>trim($_POST['selRestaurant']), "categoryId ="=>trim($_POST['categoryId']), "subcategoryId ="=>trim($_POST['subcategoryId']), "subcategoryitemName$langSuffix" =>$_POST['subcategoryitemName'.$langSuffix]));

            $qry = $this->db->get('vm_product_subcategoryitem');

            $isExist = $qry->row();

			$status = 'alreadyExist';



			if(!$isExist){

				$_POST['subcategoryitemName'] = (isset($_POST['subcategoryitemName']) && empty($_POST['subcategoryitemName']) && $this->sessLang != 'english') ? $this->common_lib->changeLanguage($_POST['subcategoryitemName'.$langSuffix],$this->lang->line('lang'),'en') : $_POST['subcategoryitemName'] ;



				$_POST['subcategoryitemName_fr'] = (isset($_POST['subcategoryitemName_fr']) && empty($_POST['subcategoryitemName_fr']) && $this->sessLang != 'french') ? $this->common_lib->changeLanguage($_POST['subcategoryitemName'.$langSuffix],$this->lang->line('lang'),'fr') : $_POST['subcategoryitemName_fr'] ; 



				$_POST['subcategoryitemName_gr'] = (isset($_POST['subcategoryitemName_gr']) && empty($_POST['subcategoryitemName_gr']) && $this->sessLang != 'german') ? $this->common_lib->changeLanguage($_POST['subcategoryitemName'.$langSuffix],$this->lang->line('lang'),'de') : $_POST['subcategoryitemName_gr'] ; 



				$_POST['subcategoryitemName_it'] = (isset($_POST['subcategoryitemName_it']) && empty($_POST['subcategoryitemName_it']) && $this->sessLang != 'italian') ? $this->common_lib->changeLanguage($_POST['subcategoryitemName'.$langSuffix],$this->lang->line('lang'),'it') : $_POST['subcategoryitemName_it'] ; 



				// v3print($_POST);exit;

				$insertData   =  array();

				$insertData['restaurantId']	 			=   trim($_POST['selRestaurant']);

				$insertData['categoryId']	 			=   trim($_POST['categoryId']);

				$insertData['subcategoryId']	 		=   trim($_POST['subcategoryId']);

				$insertData['subcategoryitemName']		=   trim($_POST['subcategoryitemName']);

				$insertData['subcategoryitemName_fr']	=   trim($_POST['subcategoryitemName_fr']);

				$insertData['subcategoryitemName_gr']	=   trim($_POST['subcategoryitemName_gr']);

				$insertData['subcategoryitemName_it']	=   trim($_POST['subcategoryitemName_it']);

				$insertData['updatedOn']	 		=   date('Y-m-d H:i:s');





				if($subcategoryitemId > 0 && !$isExist){



					$slug = $this->common_lib->create_unique_slug(trim($_POST['subcategoryitemName']),"vm_product_subcategoryitem","subcategoryitemName",$subcategoryitemId,"subcategoryitemId",$counter=0);

					$insertData['slug']			 =   $slug;

					$cond 	=	"subcategoryitemId = ".$subcategoryitemId;

					$updatetStatus 		= 	$this->Common_model->update("vm_product_subcategoryitem", $insertData,$cond);

					if($updatetStatus)

						$status = 'updated';



				}else if($subcategoryitemId == 0 && !$isExist){

					$slug = $this->common_lib->create_unique_slug(trim($_POST['subcategoryitemName']),"vm_product_subcategoryitem","subcategoryitemName",0,"subcategoryitemId",$counter=0);

					$insertData['slug']			 =   $slug;

					$insertData['addedOn']	 	 =   date('Y-m-d H:i:s');

					$updatetStatus 		= 	$this->Common_model->insert("vm_product_subcategoryitem", $insertData);

					if($updatetStatus)

						$status = 'added';

				}

			}



			if($status == 'added')

				$this->common_lib->setSessMsg($this->lang->line('addNewRecord'), 1);

			else if($status == 'updated')

				$this->common_lib->setSessMsg($this->lang->line('editRecord'), 1);

			else if($status == 'alreadyExist')

				$this->common_lib->setSessMsg($this->lang->line('alreadyExist'), 2);

			else

				$this->common_lib->setSessMsg($this->lang->line('dbError'), 2);

		}

			



		$this->outputdata['restaurantData'] = $this->Common_model->selTableData("vm_restaurant","restaurantId,restaurantName$this->langSuffix as restaurantName","status = 0","restaurantName");

		$this->outputdata['categoryData'] = $this->Common_model->selTableData("vm_product_category","categoryId,categoryName$this->langSuffix as categoryName","status = 0 ");



		if ($subcategoryitemId > 0){

			$this->outputdata['subcategoryitemData'] = $this->Common_model->selRowData("vm_product_subcategoryitem","","subcategoryitemId = ".$subcategoryitemId);



			$this->outputdata['subcategoryData'] = $this->Common_model->selTableData("vm_product_subcategory","subcategoryId,subcategoryName$this->langSuffix as subcategoryName","status = 0 and categoryId =".$this->outputdata['subcategoryitemData']->categoryId." AND restaurantId = ".$this->outputdata['subcategoryitemData']->restaurantId,"subcategoryName$this->langSuffix ASC ");

		}

		else if(isset($_POST['categoryId']) && !empty($_POST['categoryId'])){

			$newObj =  new stdClass();

			$newObj->restaurantId 		=  $_POST['selRestaurant'];

			$newObj->categoryId 		=  $_POST['categoryId'];

			$newObj->subcategoryId 		=  $_POST['subcategoryId'];

			// if ($langSuffix == '') {

			// 	$newObj->subcategoryName 		=  $_POST['subcategoryName'];

			// }else if($langSuffix == '_fr') {

			// 	$newObj->subcategoryName_fr 	=  $_POST['subcategoryName_fr'];



			// }else if($langSuffix == '_gr') {

			// 	$newObj->subcategoryName_gr 	=  $_POST['subcategoryName_gr'];



			// }else if($langSuffix == '_it') {

			// 	$newObj->subcategoryName_it 	=  $_POST['subcategoryName_it'];



			// }

			$this->outputdata['subcategoryitemData'] = $newObj;

		}



		$this->load->viewD('admin/product_subcategoryitem_add_view', $this->outputdata);

	}

		



	// subcategoryitem-listing view

	public function subcategoryitem_list(){	

		$this->menu		=	1;

		$this->subMenu	=	16;



		$this->load->viewD('admin/product_subcategoryitem_list_view', $this->outputdata);

	}

	



	public function add_product($productId = 0){		



		$this->menu		=	1;



		$this->subMenu	=	17;

		$imgArry  =  array();

		$status = 'dbError';

		$langSuffix = $this->lang->line('langSuffix');

		if(isset($_POST) && !empty($_POST['txtProductName'.$langSuffix])) {

			



			// if (isset($_POST['selCategoryId']) && $_POST['selCategoryId'] == 5 && ( !isset($_POST['isAvailableInFree']) || empty($_POST['isAvailableInFree'] ))) {



			// 	$freeDrinkCountData = $this->Common_model->exequery("SELECT notFree * 100 / totalDrink AS percent FROM ((SELECT count(*) as totalDrink FROM `vm_product` WHERE categoryId =5 and restaurantId = $_POST[selRestaurant]) as totalDrink,(SELECT count(*) as notFree FROM `vm_product` WHERE isAvailableInFree = 0 and categoryId =5 and restaurantId = $_POST[selRestaurant]) as notFree) ",1);



			// 	$status = (valResultSet($freeDrinkCountData->percent) && $freeDrinkCountData->percent > 5)?'noMoreAllowNonFreeDrink':$status;



				

			// }





			if($productId > 0)

                $this->db->where('productId !=', $productId);

            

            $this->db->where(array("status !="=>"2", "restaurantId ="=>trim($_POST['selRestaurant']), "categoryId ="=>trim($_POST['selCategoryId']), "subcategoryId ="=>trim($_POST['selSubcategoryId']), "subcategoryitemId ="=>trim($_POST['selSubcategoryitemId']), "productName$langSuffix" =>$_POST['txtProductName'.$langSuffix]));

            $qry = $this->db->get('vm_product');

            $isExist = $qry->row();



			//$status = ($isExist)?'alreadyExist':$status;



			if ($status == 'dbError') {



				$status = '';

				$subcategory =  '';

				$insertData   =  array();

				$_POST['txtProductName'] = (isset($_POST['txtProductName']) && empty($_POST['txtProductName']) && $this->sessLang != 'english') ? $this->common_lib->changeLanguage(trim($_POST['txtProductName'.$langSuffix]),$this->lang->line('lang'),'en') : trim($_POST['txtProductName']) ;



				$_POST['txtProductName_fr'] = (isset($_POST['txtProductName_fr']) && empty($_POST['txtProductName_fr']) && $this->sessLang != 'french') ? $this->common_lib->changeLanguage(trim($_POST['txtProductName'.$langSuffix]),$this->lang->line('lang'),'fr') : trim($_POST['txtProductName_fr']) ; 



				$_POST['txtProductName_gr'] = (isset($_POST['txtProductName_gr']) && empty($_POST['txtProductName_gr']) && $this->sessLang != 'german') ? $this->common_lib->changeLanguage(trim($_POST['txtProductName'.$langSuffix]),$this->lang->line('lang'),'de') : trim($_POST['txtProductName_gr']) ; 



				$_POST['txtProductName_it'] = (isset($_POST['txtProductName_it']) && empty($_POST['txtProductName_it']) && $this->sessLang != 'italian') ? $this->common_lib->changeLanguage(trim($_POST['txtProductName'.$langSuffix]),$this->lang->line('lang'),'it') : trim($_POST['txtProductName_it']) ;



				$_POST['txtsortDescription'] = (isset($_POST['txtsortDescription']) && empty($_POST['txtsortDescription']) && $this->sessLang != 'english') ? $this->common_lib->changeLanguage(trim($_POST['txtsortDescription'.$langSuffix]),$this->lang->line('lang'),'en') : trim($_POST['txtsortDescription']) ;



				$_POST['txtsortDescription_fr'] = (isset($_POST['txtsortDescription_fr']) && empty($_POST['txtsortDescription_fr']) && $this->sessLang != 'french') ? $this->common_lib->changeLanguage(trim($_POST['txtsortDescription'.$langSuffix]),$this->lang->line('lang'),'fr') : trim($_POST['txtsortDescription_fr']) ; 



				$_POST['txtsortDescription_gr'] = (isset($_POST['txtsortDescription_gr']) && empty($_POST['txtsortDescription_gr']) && $this->sessLang != 'german') ? $this->common_lib->changeLanguage(trim($_POST['txtsortDescription'.$langSuffix]),$this->lang->line('lang'),'de') : trim($_POST['txtsortDescription_gr']) ; 



				$_POST['txtsortDescription_it'] = (isset($_POST['txtsortDescription_it']) && empty($_POST['txtsortDescription_it']) && $this->sessLang != 'italian') ? $this->common_lib->changeLanguage(trim($_POST['txtsortDescription'.$langSuffix]),$this->lang->line('lang'),'it') : trim($_POST['txtsortDescription_it']) ;



				/*$_POST['txtDescription'] = (isset($_POST['txtDescription']) && empty($_POST['txtDescription']) && $this->sessLang != 'english') ? $this->common_lib->changeLanguage(trim($_POST['txtDescription'.$langSuffix]),$this->lang->line('lang'),'en') : trim($_POST['txtDescription']) ;



				$_POST['txtDescription_fr'] = (isset($_POST['txtDescription_fr']) && empty($_POST['txtDescription_fr']) && $this->sessLang != 'french') ? $this->common_lib->changeLanguage(trim($_POST['txtDescription'.$langSuffix]),$this->lang->line('lang'),'fr') : trim($_POST['txtDescription_fr']) ; 



				$_POST['txtDescription_gr'] = (isset($_POST['txtDescription_gr']) && empty($_POST['txtDescription_gr']) && $this->sessLang != 'german') ? $this->common_lib->changeLanguage(trim($_POST['txtDescription'.$langSuffix]),$this->lang->line('lang'),'de') : trim($_POST['txtDescription_gr']) ; 



				$_POST['txtDescription_it'] = (isset($_POST['txtDescription_it']) && empty($_POST['txtDescription_it']) && $this->sessLang != 'italian') ? $this->common_lib->changeLanguage(trim($_POST['txtDescription'.$langSuffix]),$this->lang->line('lang'),'it') : trim($_POST['txtDescription_it']) ;*/



				$_POST['txtTags'] = (isset($_POST['txtTags']) && empty($_POST['txtTags']) && $this->sessLang != 'english') ? $this->common_lib->changeLanguage(trim($_POST['txtTags'.$langSuffix]),$this->lang->line('lang'),'en') : trim($_POST['txtTags']) ;



				$_POST['txtTags_fr'] = (isset($_POST['txtTags_fr']) && empty($_POST['txtTags_fr']) && $this->sessLang != 'french') ? $this->common_lib->changeLanguage(trim($_POST['txtTags'.$langSuffix]),$this->lang->line('lang'),'fr') : trim($_POST['txtTags_fr']) ; 



				$_POST['txtTags_gr'] = (isset($_POST['txtTags_gr']) && empty($_POST['txtTags_gr']) && $this->sessLang != 'german') ? $this->common_lib->changeLanguage(trim($_POST['txtTags'.$langSuffix]),$this->lang->line('lang'),'de') : trim($_POST['txtTags_gr']) ; 



				$_POST['txtTags_it'] = (isset($_POST['txtTags_it']) && empty($_POST['txtTags_it']) && $this->sessLang != 'italian') ? $this->common_lib->changeLanguage(trim($_POST['txtTags'.$langSuffix]),$this->lang->line('lang'),'it') : trim($_POST['txtTags_it']) ;



				$insertData['restaurantId']	 		=   trim($_POST['selRestaurant']);

				$insertData['productType']	 		=   trim($_POST['productType']);

				$insertData['categoryId']	 		=   trim($_POST['selCategoryId']);

				$insertData['subcategoryId']		=   (isset($_POST['selSubcategoryId']))?trim($_POST['selSubcategoryId']):0;

				$insertData['subcategoryitemId']		=   (isset($_POST['selSubcategoryitemId']))?trim($_POST['selSubcategoryitemId']):0;

				$insertData['productName']	 		=   trim($_POST['txtProductName']);

				$insertData['productName_fr']	 	=   trim($_POST['txtProductName_fr']);

				$insertData['productName_gr']	 	=   trim($_POST['txtProductName_gr']);

				$insertData['productName_it']	 	=   trim($_POST['txtProductName_it']);

				$insertData['sortDescription'] 		=   trim($_POST['txtsortDescription']);

				$insertData['sortDescription_fr'] 	=   trim($_POST['txtsortDescription_fr']);

				$insertData['sortDescription_gr'] 	=   trim($_POST['txtsortDescription_gr']);

				$insertData['sortDescription_it'] 	=   trim($_POST['txtsortDescription_it']);

				/*$insertData['description']	 		=   trim($_POST['txtDescription']);

				$insertData['description_fr']	 	=   trim($_POST['txtDescription_fr']);

				$insertData['description_gr']	 	=   trim($_POST['txtDescription_gr']);

				$insertData['description_it']	 	=   trim($_POST['txtDescription_it']);*/

				$insertData['price']	 	 		=   trim($_POST['txtPrice']);

				$insertData['tags']	 		 		=   trim($_POST['txtTags']);

				$insertData['tags_fr']	 		 	=   trim($_POST['txtTags_fr']);

				$insertData['tags_gr']	 		 	=   trim($_POST['txtTags_gr']);

				$insertData['tags_it']	 		 	=   trim($_POST['txtTags_it']);

				$insertData['isFeatured']	 		=   (isset($_POST['isFeatured']))?trim($_POST['isFeatured']):0;

				$insertData['isOnlyForGirl']	 		=   (trim($_POST['selCategoryId']) ==5 && isset($_POST['isOnlyForGirl']))?trim($_POST['isOnlyForGirl']):0;

				$insertData['isAvailableInFree']	 		=   (trim($_POST['selCategoryId']) ==5 && isset($_POST['isAvailableInFree']))?trim($_POST['isAvailableInFree']):0;

				$insertData['doNotIncludeInTheMenu']	 		=   ($insertData['isAvailableInFree'] && isset($_POST['doNotIncludeInTheMenu']))?trim($_POST['doNotIncludeInTheMenu']):0;
				if(isset($_POST['uploadImg']) && !empty($_POST['uploadImg']))
					$insertData['img']	 		=   trim($_POST['uploadImg']);
				$insertData['updatedOn']	 		=   date('Y-m-d H:i:s');







				if($productId > 0){

					$getProductDetails = $this->Common_model->exequery("SELECT * FROM vm_product WHERE productId=".$productId,1);

					$this->add_variable_items($productId);

					// $isfileUploaded = $this->upload_icon();

					// if ($isfileUploaded['status'] == 2 || $isfileUploaded['status'] == 3)

					// 	$status = 'uploadImageError';

					// else if($isfileUploaded['status'] == 1  && ($isfileUploaded['fileName'] != '' || $isfileUploaded['gallaryfileName'] != '')){

					// 	if ($isfileUploaded['fileName'] != '') {

					// 		$insertData['img']	 =   $isfileUploaded['fileName'];

					// 		$status = 'Feature image uploaded';

					// 	}



					// 	if ($isfileUploaded['gallaryfileName'] != '') {

					// 		$imgArry = array_filter(explode(',', $isfileUploaded['gallaryfileName'] ));

					// 		foreach ($imgArry as $value) {

					// 			$insertGallaryData = array();

					// 			$insertGallaryData['image']	 =   $value;

					// 			$insertGallaryData['productId']	 =   $productId;

					// 			$insertGallaryStatus 			 = 	$this->Common_model->insert("vm_product_gallary_img", $insertGallaryData);

					// 			if($insertGallaryStatus)

					// 				$status = 'Gallary images added';

					// 		}

					// 	}

					// }



					if ($status != 'uploadImageError') {

						$slug = $this->common_lib->create_unique_slug(trim($_POST['txtProductName']),"vm_product","productName",$productId,"productId",$counter=0);

						$isQRExist = $this->Common_model->selRowData("vm_product","qrcode"," productId = '".$productId."'");

						if (!valResultSet($isQRExist) || $isQRExist->qrcode == ''){

							$data = md5(100000+$productId);

							$QRname = $this->common_lib->getQRcode('product',$data,'png');

							if ($QRname !='')

								$insertData['qrcode'] =  $QRname;

						}



						$insertData['slug']			 =   $slug;

						$cond 	=	"productId = ".$productId;

						if($getProductDetails->subcategoryId != $insertData['subcategoryId']) {

                            $this->common_lib->reArrangeProductOrder($getProductDetails->productId, "subcategory");

                            $orderNo = ($insertData['subcategoryId'] > 0) ? $this->Common_model->getSelectedField("vm_product","max(orderNo)", "status !=2 and restaurantId=".$insertData['restaurantId']." AND subcategoryId=".$insertData['subcategoryId']) : 0;

                            $insertData['orderNo']  =   ($orderNo)?++$orderNo:1;



                        }

                        if($getProductDetails->subcategoryitemId != $insertData['subcategoryitemId']) {

                            $this->common_lib->reArrangeProductOrder($getProductDetails->productId, "subcategoryItem");

                           $subOrderNo = ($insertData['subcategoryitemId'] > 0) ? $this->Common_model->getSelectedField("vm_product","max(subOrderNo)", "status !=2 and restaurantId=".$insertData['restaurantId']." AND subcategoryitemId=".$insertData['subcategoryitemId']) : 0;

                            $insertData['subOrderNo']  =   ($subOrderNo)?++$subOrderNo:1;



                        }

                        if($getProductDetails->isAvailableInFree != $insertData['isAvailableInFree']) {

                            $this->common_lib->reArrangeProductOrder($getProductDetails->productId, "freeDrink");

                           $welcomeDrinkOrderNo = ($insertData['isAvailableInFree'] > 0 ) ? $this->Common_model->getSelectedField("vm_product","max(welcomeDrinkOrderNo)", "status !=2 and restaurantId=".$insertData['restaurantId']." ") : 0;

                            $insertData['welcomeDrinkOrderNo']  =   ($welcomeDrinkOrderNo)?++$welcomeDrinkOrderNo:1;



                        }

						$updatetStatus 		= 	$this->Common_model->update("vm_product", $insertData,$cond);

						if($updatetStatus)

							$status = 'updated';

					}



				}else{

					// $isfileUploaded = $this->upload_icon();

					// if ($isfileUploaded['status'] == 2 || $isfileUploaded['status'] == 3) 

					// 	$status = 'uploadImageError';

					// else if($isfileUploaded['status'] == 1  && ($isfileUploaded['fileName'] != '' || $isfileUploaded['gallaryfileName'] != '')){

					// 	if ($isfileUploaded['fileName'] != '') {

					// 		$insertData['img']	 =   $isfileUploaded['fileName'];

					// 		$status = 'Feature image uploaded';

					// 	}

					// 	if ($isfileUploaded['gallaryfileName'] != '') {

					// 		$imgArry = array_filter(explode(',', $isfileUploaded['gallaryfileName'] ));

					// 	}

					// }



					if ($status != 'uploadImageError') {

						$slug = $this->common_lib->create_unique_slug(trim($_POST['txtProductName']),"vm_product","productName",0,"productId",$counter=0);

						 $orderNo = ($insertData['subcategoryId'] > 0) ? $this->Common_model->getSelectedField("vm_product","max(orderNo)", "status !=2 and restaurantId=".$insertData['restaurantId']." AND subcategoryId=".$insertData['subcategoryId']) : 0;

                        $subOrderNo = ($insertData['subcategoryitemId'] > 0) ? $this->Common_model->getSelectedField("vm_product","max(subOrderNo)", "status !=2 and restaurantId=".$insertData['restaurantId']." AND subcategoryitemId=".$insertData['subcategoryitemId']) : 0;

                        $welcomeDrinkOrderNo = ($insertData['isAvailableInFree'] > 0 ) ? $this->Common_model->getSelectedField("vm_product","max(welcomeDrinkOrderNo)", "status !=2 and restaurantId=".$insertData['restaurantId']." ") : 0;

                        $insertData['slug']          =   $slug;

                        $insertData['orderNo']  =   ($orderNo)?++$orderNo:1;

                        $insertData['subOrderNo']  =   ($subOrderNo)?++$subOrderNo:1;

                        $insertData['welcomeDrinkOrderNo']  =   ($welcomeDrinkOrderNo)?++$welcomeDrinkOrderNo:1;

						//$insertData['slug']			 =   $slug;

						$insertData['addedOn']	 	 =   date('Y-m-d H:i:s');

						$insertStatus 		= 	$this->Common_model->insertUnique("vm_product", $insertData);

						if($insertStatus){

							$updateData = array();

							$data = md5(100000+$insertStatus);

							$QRname = $this->common_lib->getQRcode('product',$data,'png');

							if ($QRname !='')

								$updateData['qrcode'] =  $QRname;

							$updateData['generatedId'] =   $data;

							$cond 	=	"productId = ".$insertStatus;

							$updatetStatus 		= 	$this->Common_model->update("vm_product",$updateData, $cond);

							// if (count($imgArry) > 0) {

							// 	foreach ($imgArry as $value) {

							// 		$insertGallaryData = array();

							// 		$insertGallaryData['image']	 =   $value;

							// 		$insertGallaryData['productId']	 =   $insertStatus;

							// 		$insertGallaryStatus 			 = 	$this->Common_model->insert("vm_product_gallary_img", $insertGallaryData);

							// 		if($insertGallaryStatus)

							// 			$status = 'Gallary images added';

							// 	}

							// }

							$this->add_variable_items($insertStatus);

							$status = 'added';

						}

					}

				}

			}







			if($status == 'added')

				$this->common_lib->setSessMsg($this->lang->line('addNewRecord'), 1);

			else if($status == 'updated'){
				$this->common_lib->setSessMsg($this->lang->line('editRecord'), 1);

				echo('<script>window.setTimeout(function() {window.location.href = "'.DASHURL.'/admin/product/product-list?'.$_SERVER['QUERY_STRING'].'";}, 3000);</script>');

			}
			else

				$this->common_lib->setSessMsg($this->lang->line($status), 2);



		}







		$this->outputdata['restaurantData'] = $this->Common_model->selTableData("vm_restaurant","restaurantId,restaurantName$this->langSuffix as restaurantName","status != 2","restaurantName");



		$this->outputdata['categoryData'] = $this->Common_model->selTableData("vm_product_category","categoryId,categoryName$this->langSuffix as categoryName","status = 0");
		if ($productId > 0){
			$this->outputdata['productData'] = $this->Common_model->selRowData("vm_product as pd","pd.*,(CASE WHEN pd.img REGEXP ('^[0-9]+$') THEN (SELECT (CASE WHEN im.image != '' THEN CONCAT('".UPLOADPATH."','/vedmir_images/',im.image) else '' end ) FROM vm_image as im WHERE im.status = 0 and im.image != '' and im.imageId= pd.img) WHEN pd.img != '' THEN CONCAT('".UPLOADPATH."','/product_images/',pd.img) else '' end ) as img ","productId = ".$productId);
			$this->outputdata['productgallaryData'] = $this->Common_model->selTableData("vm_product_gallary_img","","productId = ".$productId);
			$this->outputdata['variableProductData'] = $this->Common_model->selTableData("vm_variable_product","","status = 0 and productId = ".$productId);
			$this->outputdata['subcategoryData'] = $this->Common_model->selTableData("vm_product_subcategory","subcategoryId,subcategoryName$this->langSuffix as subcategoryName","status = 0 and categoryId=".$this->outputdata['productData']->categoryId." AND restaurantId = ".$this->outputdata['productData']->restaurantId,"subcategoryName$this->langSuffix ASC ");
			$this->outputdata['subcategoryitemData'] = $this->Common_model->selTableData("vm_product_subcategoryitem","subcategoryitemId,subcategoryitemName$this->langSuffix as subcategoryitemName","status = 0 and subcategoryId=".$this->outputdata['productData']->subcategoryId." AND restaurantId = ".$this->outputdata['productData']->restaurantId,"subcategoryitemName$this->langSuffix ASC ");

		}else if(isset($_POST['selRestaurant']) && !empty($_POST['selRestaurant'])){

			$newObj =  new stdClass();
			$newObj->restaurantId 		=  @$_POST['selRestaurant'];
			$newObj->categoryId 		=  @$_POST['selCategoryId'];
			$newObj->subcategoryId 		=  @$_POST['selSubcategoryId'];
			$newObj->subcategoryitemId 	=  @$_POST['selSubcategoryitemId'];
			$newObj->price 				=  @$_POST['txtPrice'];
			$newObj->productName 		=  @$_POST['txtProductName'];
			$newObj->sortDescription	=  @$_POST['txtsortDescription'];
			$newObj->description		=  @$_POST['txtDescription'];
			$newObj->tags				=  @$_POST['txtTags'];

			$newObj->productName_fr 	=  @$_POST['txtProductName_fr'];
			$newObj->sortDescription_fr	=  @$_POST['txtsortDescription_fr'];
			$newObj->description_fr		=  @$_POST['txtDescription_fr'];
			$newObj->tags_fr				=  @$_POST['txtTags_fr'];

			$newObj->productName_gr 	=  @$_POST['txtProductName_gr'];
			$newObj->sortDescription_gr	=  @$_POST['txtsortDescription_gr'];
			$newObj->description_gr		=  @$_POST['txtDescription_gr'];
			$newObj->tags_gr				=  @$_POST['txtTags_gr'];

			$newObj->productName_it 	=  @$_POST['txtProductName_it'];
			$newObj->sortDescription_it	=  @$_POST['txtsortDescription_it'];
			$newObj->description_it		=  @$_POST['txtDescription_it'];
			$newObj->tags_it				=  @$_POST['txtTags_it'];

			$this->outputdata['productData'] = $newObj;

		}

		$this->load->viewD('admin/product_add_view', $this->outputdata); 

	}


	// product-listing view
	public function product_list(){	
		$this->menu		=	1;
		$this->subMenu	=	17.1;	

		$this->load->viewD('admin/product_list_view', $this->outputdata);

	}	

	// restaurant profile view

	public function view_product($productId){		
		$this->menu		=	1;
		$this->subMenu	=	18;

		$query	=	"SELECT *,productName$this->langSuffix as productName,price, description$this->langSuffix as description, tags$this->langSuffix as tags,(CASE WHEN vm_product.img REGEXP ('^[0-9]+$') THEN (SELECT (CASE WHEN im.image != '' THEN CONCAT('".UPLOADPATH."','/vedmir_images/',im.image) else '' end ) FROM vm_image as im WHERE im.status = 0 and im.image != '' and im.imageId= vm_product.img) WHEN vm_product.img != '' THEN CONCAT('".UPLOADPATH."','/product_images/',vm_product.img) else '' end ) as img,
		(SELECT categoryName$this->langSuffix from vm_product_category where categoryId = vm_product.categoryId) as categoryName,
		(SELECT subcategoryName$this->langSuffix from vm_product_subcategory where subcategoryId = vm_product.subcategoryId) as subcategoryName,
		(SELECT subcategoryitemName$this->langSuffix from vm_product_subcategoryitem where subcategoryitemId = vm_product.subcategoryitemId) as subcategoryitemName from vm_product where productId = ".$productId;
		$productData =	$this->Common_model->exequery($query,1);
        if (!valResultSet($productData)) 
            redirect(DASHURL.'/admin/product/product-list');
        $this->outputdata['productInfo'] =  $productData;

        // Get addons  list
        $addonData=$this->Common_model->exequery("Select *, categoryName$this->langSuffix as categoryName from vm_product_addons_category where status != 2 AND productId=".$productData->productId);
        if ($addonData) {
            foreach ($addonData as $row){
                $row->addonsItem = $this->Common_model->exequery("Select *, addonsName$this->langSuffix as addonsName from vm_product_addons where status != 2 AND productId=".$productData->productId." AND addonsCatId=".$row->addonsCatId);
            }
        }        
        // v3print($addonData);exit;
        $this->outputdata['addonData'] = ($addonData)?$addonData:array();

		// Get gallary img  list
		$this->outputdata['productGallaryData'] = $this->Common_model->selTableData("vm_product_gallary_img","","productId = ".$productId);
		// Get variable list
		$this->outputdata['productVariableData'] = $this->Common_model->exequery("SELECT variableName$this->langSuffix as variableName,price, isAvailableInFree FROM vm_variable_product WHERE status = 0 and productId = ".$productId);
		$this->load->viewD($this->sessRole.'/view_product_info',$this->outputdata);
	}





	// Rmove product image from gallary image



	public function remove_gallery_image(){



		if(isset($_POST['img_id']) && $_POST['img_id'] > 0) {



			$productgallaryData = $this->Common_model->selRowData("vm_product_gallary_img","","id = ".$_POST['img_id']);



			unlink(ABSUPLOADPATH."/product_gallary_images/".$productgallaryData->image);







			$isdeleted = $this->Common_model->del("vm_product_gallary_img","id = ".$_POST['img_id']);



			echo ($isdeleted)?'deleted':'failed';



			// echo json_encode($return);



			



		}







	}







	public function add_variable_items($productId){



		

		if (isset($_POST['variableName'.$this->langSuffix]) && count($_POST['variableName'.$this->langSuffix]) > 0 && $productId > 0) {



			foreach ($_POST['variableName'.$this->langSuffix] as $key => $value) {

				if (!empty($value) && $_POST['productType'] == 1) {

					$_POST['variableName'][$key] = (isset($_POST['variableName'][$key]) && empty($_POST['variableName'][$key]) && $this->sessLang != 'english') ? $this->common_lib->changeLanguage(trim($value),$this->lang->line('lang'),'en') : $_POST['variableName'][$key] ;



					$_POST['variableName_fr'][$key] = (isset($_POST['variableName_fr'][$key]) && empty($_POST['variableName_fr'][$key]) && $this->sessLang != 'french') ? $this->common_lib->changeLanguage(trim($value),$this->lang->line('lang'),'fr') : $_POST['variableName_fr'][$key] ; 



					$_POST['variableName_gr'][$key] = (isset($_POST['variableName_gr'][$key]) && empty($_POST['variableName_gr'][$key]) && $this->sessLang != 'german') ? $this->common_lib->changeLanguage(trim($value),$this->lang->line('lang'),'de') : $_POST['variableName_gr'][$key] ; 



					$_POST['variableName_it'][$key] = (isset($_POST['variableName_it'][$key]) && empty($_POST['variableName_it'][$key]) && $this->sessLang != 'italian') ? $this->common_lib->changeLanguage(trim($value),$this->lang->line('lang'),'it') : $_POST['variableName_it'][$key] ;



					

						$queryData = array();		

						$queryData['productId']		=   $productId;

						$queryData['variableName']	=   trim($_POST['variableName'][$key]);

						$queryData['variableName_gr']=   trim($_POST['variableName_gr'][$key]);

						$queryData['variableName_fr']=   trim($_POST['variableName_fr'][$key]);

						$queryData['variableName_it']=   trim($_POST['variableName_it'][$key]);

						$queryData['updatedOn']		=   date('Y-m-d H:i:s');

						$queryData['price']			=   $_POST['variableItemPrice'][$key];
                        $queryData['isAvailableInFree']         =   (isset($_POST['isAvailableInFree']) && isset($_POST['isAvailableInWelcome'][$key]) && !empty($_POST['isAvailableInWelcome'][$key])) ? 1 : 0;

					if ($_POST['variableItemId'][$key] > 0) {

						$queryStatus	= 	$this->Common_model->update("vm_variable_product", $queryData,"variableId =".trim($_POST['variableItemId'][$key]));

					}else{

						$queryData['addedOn']		=   date('Y-m-d H:i:s');

						$queryStatus	= 	$this->Common_model->insert("vm_variable_product", $queryData);

					}

				}

			}

		}

	}





	// Upload icon image



	public function upload_icon(){







		$uploadFlag = array();



		$uploadFlag['status'] = 0;



		$uploadFlag['fileName'] = '';



		$uploadFlag['gallaryfileName'] = '';







		if(!empty($_FILES['uploadImg']['tmp_name'])){



			if(is_uploaded_file($_FILES['uploadImg']['tmp_name']) != "") {



				 	



				$photoToUpload = 	date('Ymdhis').str_replace(' ', '_', $_FILES['uploadImg']['name']);



				



				$uploadSettings = array();



				$uploadSettings['upload_path']   	=	ABSUPLOADPATH."/product_images";



				$uploadSettings['allowed_types'] 	=	'gif|jpg|png';



				$uploadSettings['file_name']	  	= 	$photoToUpload;



				$uploadSettings['inputFieldName']  	=  	"uploadImg";



				$fileUpload = $this->common_lib->_doUpload($uploadSettings);



				if ($fileUpload) {



					$uploadFlag['fileName']		=   $photoToUpload;



					$uploadFlag['status'] = 1;



				}else{



					$uploadFlag['status'] = 2;



				}



			}



		} 



		if (!empty($_FILES['txtgallaryImgs']['tmp_name'][0])){



			$files = $_FILES;



			for($i=0;$i<count($files["txtgallaryImgs"]["name"]);$i++){







				$_FILES['userfile']['name']		= $files['txtgallaryImgs']['name'][$i];



	        	$_FILES['userfile']['type']		= $files['txtgallaryImgs']['type'][$i];



	        	$_FILES['userfile']['tmp_name'] = $files['txtgallaryImgs']['tmp_name'][$i];



	        	$_FILES['userfile']['error']	= $files['txtgallaryImgs']['error'][$i];



	        	$_FILES['userfile']['size']		= $files['txtgallaryImgs']['size'][$i];







				if(is_uploaded_file($_FILES['userfile']['tmp_name']) != "") {



					



					$photoToUpload = 	date('Ymdhis').'g'.str_replace(' ', '_', $_FILES['userfile']['name']);



					



					$uploadSettings = array();



					$uploadSettings['upload_path']   	=	ABSUPLOADPATH."/product_gallary_images";



					$uploadSettings['allowed_types'] 	=	'gif|jpg|png';



					$uploadSettings['file_name']	  	= 	$photoToUpload;



					$uploadSettings['inputFieldName']  	=  	'userfile';



					$fileUpload = $this->common_lib->_doUpload($uploadSettings);



					if ($fileUpload) {



						$uploadFlag['gallaryfileName']		.=   $photoToUpload.',';



						$uploadFlag['status'] = 1;



					}else{



					$uploadFlag['status'] = 3;



					}



				}







			}



		}



		return $uploadFlag;



	}

	// happy hour add view
	// happy hour add view
	public function add_happyhour($happyhourId = 0){
		$this->menu		=	1;
		$this->subMenu	=	18;
        $langSuffix = $this->lang->line('langSuffix');
        if(isset($_POST) && !empty($_POST['day'])) {
            $isHappyHourProductUpdated = 0;
            $this->db->trans_begin();
            foreach ($_POST['day'] as $day) {
                if ($day){
                    // echo'<br>';print_r($_POST);exit;
                    $status = $updatetStatus ='';
                    $insertData   =  array();

                    if($happyhourId > 0)
                        $isExistCond = " and happyhourId !=".$happyhourId;
                    else
                        $isExistCond = '';

                    $isExist = $this->Common_model->selRowData("vm_happyhour","","status != '2' and restaurantId = '".$_POST['restaurantId']."' and day = '".$day."' and startTime = '".$_POST['startTime']."' and endTime = '".$_POST['endTime']."'".$isExistCond);
                    $status = ($isExist)?'alreadyExist':'';
                    $status = ($status=='' && (!isset($_POST['productId']) || empty($_POST['productId'])))?'productIdRequired':$status;

                    if($status==''){
                        $insertData['restaurantId'] =   $_POST['restaurantId'];
                        $insertData['day']          =   trim($day);
                        $insertData['startTime']    =   trim($_POST['startTime']);
                        $insertData['endTime']      =   trim($_POST['endTime']);
                        $productIds =   (!empty($_POST['productId']))?implode(',', $_POST['productId']):'';
                        $insertData['updatedOn']    =   date('Y-m-d H:i:s');

                        if($happyhourId > 0){
                            $cond   =   "happyhourId = ".$happyhourId;
                            $updatetStatus      =   $this->Common_model->update("vm_happyhour", $insertData,$cond);
                            if($updatetStatus){
                                $updatetStatus = $happyhourId;
                                $status = 'updated';
                            }
                        }else if($happyhourId == 0 && !$isExist){
                            $insertData['addedOn']       =   date('Y-m-d H:i:s');
                            $updatetStatus      =   $this->Common_model->insert("vm_happyhour", $insertData);
                            if($updatetStatus)
                                $status = 'added';
                        }
                    }

                    if($updatetStatus)
                        $isHappyHourProductUpdated = $this->common_lib->updateHappyhourProduct($updatetStatus,$_POST);
                }
            }

            if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                $this->common_lib->setSessMsg($this->lang->line('dbError'), 2);
                $this->outputdata['isError'] = 1;
            }
            elseif ($isHappyHourProductUpdated && ($status == 'added' || $status == 'updated')){
                $this->db->trans_commit();
                if($status == 'added')
                    $this->common_lib->setSessMsg($this->lang->line('addNewRecord'), 1);
                else if($status == 'updated')
                    $this->common_lib->setSessMsg($this->lang->line('editRecord'), 1);
            }
            else{
                $this->db->trans_rollback();
                $this->outputdata['isError'] = 1;
                if($status == 'alreadyExist' || $status == 'productIdRequired')
                    $this->common_lib->setSessMsg($this->lang->line($status), 2);
                else
                    $this->common_lib->setSessMsg($this->lang->line('dbError'), 2);
            }
            
        }
		$this->outputdata['restaurantData'] = $this->Common_model->selTableData("vm_restaurant","restaurantId,restaurantName$this->langSuffix as restaurantName","status = 0","restaurantName");
        if ($happyhourId > 0){
            $this->outputdata['happyhourData'] = $this->Common_model->exequery("SELECT *, (SELECT GROUP_CONCAT(vm_happyhour_product.productId) from vm_happyhour_product where vm_happyhour_product.status = 0 and vm_happyhour_product.happyhourId = vm_happyhour.happyhourId) as happyhourProductId FROM vm_happyhour WHERE status != 2 AND happyhourId = ".$happyhourId,1);

            if (!isset($this->outputdata['happyhourData']->restaurantId) || empty($this->outputdata['happyhourData']->restaurantId)){
                $this->common_lib->setSessMsg($this->lang->line('noRecords'), 2);
                redirect(DASHURL.'/admin/product/happyhour-list');
            }

        }
		$restaurantId = (isset($_POST['restaurantId']))?$_POST['restaurantId']:((isset($this->outputdata['happyhourData']->restaurantId))?$this->outputdata['happyhourData']->restaurantId:0);
        $this->outputdata['productData'] = $this->Common_model->exequery("SELECT productId, price, productName$this->langSuffix as productName, productType, (SELECT categoryName$this->langSuffix from vm_product_category where categoryId = vm_product.categoryId) as categoryName, (SELECT subcategoryName$this->langSuffix from vm_product_subcategory where subcategoryId = vm_product.subcategoryId) as subcategoryName, (SELECT price from vm_happyhour_product where vm_happyhour_product.status != 2 AND vm_happyhour_product.productId = vm_product.productId AND vm_happyhour_product.happyhourId = $happyhourId limit 0,1 ) as discountedPrice FROM vm_product WHERE status = 0 AND isAvailableInFree = '0' AND restaurantId = ".$restaurantId." order by productName$this->langSuffix ASC");
        if (!empty($this->outputdata['productData'])) {
            foreach ($this->outputdata['productData'] as $product) {
                $product->variableData = ($product->productType)?$this->Common_model->exequery("SELECT *, variableName$this->langSuffix as variableName, (SELECT price from vm_happyhour_product where vm_happyhour_product.status != 2 AND vm_happyhour_product.productId = vm_variable_product.productId AND vm_happyhour_product.variableId = vm_variable_product.variableId AND vm_happyhour_product.happyhourId = $happyhourId limit 0,1) as discountedPrice FROM vm_variable_product WHERE status = 0 and productId = ".$product->productId." order by variableName$this->langSuffix ASC"):array();
            }
        }
		
		$this->load->viewD('admin/happyhour_add_view', $this->outputdata);
	}

	// happy hour list view

	public function happyhour_list(){	

		$this->menu		=	1;

		$this->subMenu	=	19;	

		$this->load->viewD('admin/happyhour_list_view', $this->outputdata);

	}

	// happy hour details view

	public function view_happyhour($happyhourId = 0){	

		$this->menu		=	1;

		$this->subMenu	=	19;

		$this->outputdata['happyhourData'] = $this->Common_model->exequery("SELECT hh.happyhourId, rs.restaurantName$this->langSuffix as restaurantName,  hh.day, hh.startTime, hh.endTime, hh.addedOn, case when hh.status='0' then 'Active' else 'DeActive' end as status, case when hh.status='0' then 'label label-success' else 'label label-warning' end as class	from vm_happyhour as hh left join vm_restaurant as rs on rs.restaurantId = hh.restaurantId where hh.status != 2 AND happyhourId = ".$happyhourId,1);
	    if (!isset($this->outputdata['happyhourData']->happyhourId) || empty($this->outputdata['happyhourData']->happyhourId)){
            $this->common_lib->setSessMsg($this->lang->line('noRecords'), 2);
            redirect(DASHURL.'/admin/product/happyhour-list');
        }
		$this->outputdata['productData'] = $this->Common_model->exequery("SELECT hp.productId, hp.price, (CASE WHEN hp.variableId > 0 THEN vd.price ELSE pd.price END) as oldPrice, (CASE WHEN hp.variableId > 0 THEN CONCAT(pd.productName$this->langSuffix,' (',vd.variableName$this->langSuffix,')') ELSE pd.productName$this->langSuffix END) as productName, (SELECT categoryName$this->langSuffix from vm_product_category where categoryId = pd.categoryId) as categoryName, (SELECT subcategoryName$this->langSuffix from vm_product_subcategory where subcategoryId = pd.subcategoryId) as subcategoryName FROM vm_happyhour_product as hp left join vm_product as pd on pd.productId = hp.productId left join vm_variable_product as vd on vd.variableId = hp.variableId where hp.status = 0  AND pd.status = 0 and hp.happyhourId = ".$this->outputdata['happyhourData']->happyhourId." ORDER BY pd.productName$this->langSuffix ASC , vd.variableName$this->langSuffix ASC ");

		$this->load->viewD('admin/happyhour_details_view', $this->outputdata);

	}	


	

	
	public function addons($productId){
		$this->menu		=	1;
		$this->subMenu	=	17;
		$imgArry  =  array();
		$status = 'dbError';
		$langSuffix = $this->lang->line('langSuffix');
		
        $productData=$this->Common_model->exequery("Select restaurantId from vm_product where status != 2 AND productId=".$productId,1);
        if (!isset($productData->restaurantId) || empty($productData->restaurantId))
            redirect(DASHURL.'/admin/product/product-list');

        if(isset($_POST['prodAddonsCategoryName'.$this->langSuffix]) && !empty($_POST['prodAddonsCategoryName'.$this->langSuffix]) && $productId>0 && $productId!="") {
        	$response = $this->add_addons_items($productId);
			if($response['status'])
				$this->common_lib->setSessMsg($this->lang->line($response['msg']), 1);
			else
				$this->common_lib->setSessMsg($this->lang->line($response['msg']), 2);
        }


		$addonProduct=$this->Common_model->exequery("Select * from vm_product_addons_category where status != 2 AND productId=".$productId);
		if ($addonProduct) {
			foreach ($addonProduct as $row){
				$row->addonsItem = $this->Common_model->exequery("Select * from vm_product_addons where status != 2 AND productId=".$productId." AND addonsCatId=".$row->addonsCatId);
			}
		}
		$this->outputdata['addonProductData']=($addonProduct)?$addonProduct:array();
		$this->load->viewD($this->sessRole.'/product_addons_view',$this->outputdata);
	}
	
	// copy product from any restaurant to any restaurant
	public function copy_product($productId = 0){
		$this->menu		=	1;
		$this->subMenu	=	1.1;

		$imgArry  =  array();

		$status = 'dbError';

		$langSuffix = $this->lang->line('langSuffix');

		if(isset($_POST) && !empty($_POST['txtProductName'.$langSuffix])) {           

            $this->db->where(array("status !="=>"2", "restaurantId ="=>trim($_POST['selRestaurant']), "categoryId ="=>trim($_POST['selCategoryId']), "subcategoryId ="=>trim($_POST['selSubcategoryId']), "subcategoryitemId ="=>trim($_POST['selSubcategoryitemId']), "productName$langSuffix" =>$_POST['txtProductName'.$langSuffix]));

            $qry = $this->db->get('vm_product');
            $isExist = $qry->row();
			//$status = ($isExist)?'alreadyExist':$status;

			if ($status == 'dbError') {
				$status = '';
				$subcategory =  '';
				$insertData   =  array();
				$_POST['txtProductName'] = (isset($_POST['txtProductName']) && empty($_POST['txtProductName']) && $this->sessLang != 'english') ? $this->common_lib->changeLanguage(trim($_POST['txtProductName'.$langSuffix]),$this->lang->line('lang'),'en') : trim($_POST['txtProductName']) ;

				$_POST['txtProductName_fr'] = (isset($_POST['txtProductName_fr']) && empty($_POST['txtProductName_fr']) && $this->sessLang != 'french') ? $this->common_lib->changeLanguage(trim($_POST['txtProductName'.$langSuffix]),$this->lang->line('lang'),'fr') : trim($_POST['txtProductName_fr']) ;

				$_POST['txtProductName_gr'] = (isset($_POST['txtProductName_gr']) && empty($_POST['txtProductName_gr']) && $this->sessLang != 'german') ? $this->common_lib->changeLanguage(trim($_POST['txtProductName'.$langSuffix]),$this->lang->line('lang'),'de') : trim($_POST['txtProductName_gr']) ; 

				$_POST['txtProductName_it'] = (isset($_POST['txtProductName_it']) && empty($_POST['txtProductName_it']) && $this->sessLang != 'italian') ? $this->common_lib->changeLanguage(trim($_POST['txtProductName'.$langSuffix]),$this->lang->line('lang'),'it') : trim($_POST['txtProductName_it']) ;

				$_POST['txtsortDescription'] = (isset($_POST['txtsortDescription']) && empty($_POST['txtsortDescription']) && $this->sessLang != 'english') ? $this->common_lib->changeLanguage(trim($_POST['txtsortDescription'.$langSuffix]),$this->lang->line('lang'),'en') : trim($_POST['txtsortDescription']) ;

				$_POST['txtsortDescription_fr'] = (isset($_POST['txtsortDescription_fr']) && empty($_POST['txtsortDescription_fr']) && $this->sessLang != 'french') ? $this->common_lib->changeLanguage(trim($_POST['txtsortDescription'.$langSuffix]),$this->lang->line('lang'),'fr') : trim($_POST['txtsortDescription_fr']) ; 

				$_POST['txtsortDescription_gr'] = (isset($_POST['txtsortDescription_gr']) && empty($_POST['txtsortDescription_gr']) && $this->sessLang != 'german') ? $this->common_lib->changeLanguage(trim($_POST['txtsortDescription'.$langSuffix]),$this->lang->line('lang'),'de') : trim($_POST['txtsortDescription_gr']) ;

				$_POST['txtsortDescription_it'] = (isset($_POST['txtsortDescription_it']) && empty($_POST['txtsortDescription_it']) && $this->sessLang != 'italian') ? $this->common_lib->changeLanguage(trim($_POST['txtsortDescription'.$langSuffix]),$this->lang->line('lang'),'it') : trim($_POST['txtsortDescription_it']) ;

				$_POST['txtTags'] = (isset($_POST['txtTags']) && empty($_POST['txtTags']) && $this->sessLang != 'english') ? $this->common_lib->changeLanguage(trim($_POST['txtTags'.$langSuffix]),$this->lang->line('lang'),'en') : trim($_POST['txtTags']) ;

				$_POST['txtTags_fr'] = (isset($_POST['txtTags_fr']) && empty($_POST['txtTags_fr']) && $this->sessLang != 'french') ? $this->common_lib->changeLanguage(trim($_POST['txtTags'.$langSuffix]),$this->lang->line('lang'),'fr') : trim($_POST['txtTags_fr']) ;

				$_POST['txtTags_gr'] = (isset($_POST['txtTags_gr']) && empty($_POST['txtTags_gr']) && $this->sessLang != 'german') ? $this->common_lib->changeLanguage(trim($_POST['txtTags'.$langSuffix]),$this->lang->line('lang'),'de') : trim($_POST['txtTags_gr']) ;

				$_POST['txtTags_it'] = (isset($_POST['txtTags_it']) && empty($_POST['txtTags_it']) && $this->sessLang != 'italian') ? $this->common_lib->changeLanguage(trim($_POST['txtTags'.$langSuffix]),$this->lang->line('lang'),'it') : trim($_POST['txtTags_it']) ;

				$insertData['restaurantId']	 		=   trim($_POST['selRestaurant']);
				$insertData['productType']	 		=   (isset($_POST['variableName'.$this->langSuffix]) && !empty(implode(',', $_POST['variableName'.$this->langSuffix])))?1:0;
				$insertData['categoryId']	 		=   trim($_POST['selCategoryId']);
				$insertData['subcategoryId']		=   (isset($_POST['selSubcategoryId']))?trim($_POST['selSubcategoryId']):0;
				$insertData['subcategoryitemId']		=   (isset($_POST['selSubcategoryitemId']))?trim($_POST['selSubcategoryitemId']):0;
				$insertData['productName']	 		=   trim($_POST['txtProductName']);
				$insertData['productName_fr']	 	=   trim($_POST['txtProductName_fr']);
				$insertData['productName_gr']	 	=   trim($_POST['txtProductName_gr']);
				$insertData['productName_it']	 	=   trim($_POST['txtProductName_it']);
				$insertData['sortDescription'] 		=   trim($_POST['txtsortDescription']);
				$insertData['sortDescription_fr'] 	=   trim($_POST['txtsortDescription_fr']);
				$insertData['sortDescription_gr'] 	=   trim($_POST['txtsortDescription_gr']);
				$insertData['sortDescription_it'] 	=   trim($_POST['txtsortDescription_it']);
				$insertData['price']	 	 		=   trim($_POST['txtPrice']);
				$insertData['tags']	 		 		=   trim($_POST['txtTags']);
				$insertData['tags_fr']	 		 	=   trim($_POST['txtTags_fr']);
				$insertData['tags_gr']	 		 	=   trim($_POST['txtTags_gr']);
				$insertData['tags_it']	 		 	=   trim($_POST['txtTags_it']);
				$insertData['isFeatured']	 		=   (isset($_POST['isFeatured']))?trim($_POST['isFeatured']):0;
				$insertData['isOnlyForGirl']	 		=   (trim($_POST['selCategoryId']) ==5 && isset($_POST['isOnlyForGirl']))?trim($_POST['isOnlyForGirl']):0;
				$insertData['isAvailableInFree']	 		=   (trim($_POST['selCategoryId']) ==5 && isset($_POST['isAvailableInFree']))?trim($_POST['isAvailableInFree']):0;
				$insertData['doNotIncludeInTheMenu']	 		=   ($insertData['isAvailableInFree'] && isset($_POST['doNotIncludeInTheMenu']))?trim($_POST['doNotIncludeInTheMenu']):0;
				if(isset($_POST['uploadImg']) && !empty($_POST['uploadImg']))
					$insertData['img']	 		=   trim($_POST['uploadImg']);
				$insertData['updatedOn']	 		=   date('Y-m-d H:i:s');



				$slug = $this->common_lib->create_unique_slug(trim($_POST['txtProductName']),"vm_product","productName",0,"productId",$counter=0);
				 $orderNo = ($insertData['subcategoryId'] > 0) ? $this->Common_model->getSelectedField("vm_product","max(orderNo)", "status !=2 and restaurantId=".$insertData['restaurantId']." AND subcategoryId=".$insertData['subcategoryId']) : 0;
                $subOrderNo = ($insertData['subcategoryitemId'] > 0) ? $this->Common_model->getSelectedField("vm_product","max(subOrderNo)", "status !=2 and restaurantId=".$insertData['restaurantId']." AND subcategoryitemId=".$insertData['subcategoryitemId']) : 0;
                $welcomeDrinkOrderNo = ($insertData['isAvailableInFree'] > 0 ) ? $this->Common_model->getSelectedField("vm_product","max(welcomeDrinkOrderNo)", "status !=2 and restaurantId=".$insertData['restaurantId']." ") : 0;

                $insertData['slug']          =   $slug;
                $insertData['orderNo']  =   ($orderNo)?++$orderNo:1;
                $insertData['subOrderNo']  =   ($subOrderNo)?++$subOrderNo:1;
                $insertData['welcomeDrinkOrderNo']  =   ($welcomeDrinkOrderNo)?++$welcomeDrinkOrderNo:1;
				$insertData['slug']			 =   $slug;
				$insertData['addedOn']	 	 =   date('Y-m-d H:i:s');
				$insertStatus 		= 	$this->Common_model->insertUnique("vm_product", $insertData);
				if($insertStatus){
					$updateData = array();
					$data = md5(100000+$insertStatus);
					$QRname = $this->common_lib->getQRcode('product',$data,'png');
					if ($QRname !='')
						$updateData['qrcode'] =  $QRname;
					$updateData['generatedId'] =   $data;
					$cond 	=	"productId = ".$insertStatus;
					$updatetStatus 		= 	$this->Common_model->update("vm_product",$updateData, $cond);
					$this->add_variable_items($insertStatus);
					if($insertData['categoryId'] == 4)
						$this->add_addons_items($insertStatus);
					$status = 'added';

				}

			}

			if($status == 'added')
				$this->common_lib->setSessMsg($this->lang->line('addNewRecord'), 1);
			else if($status == 'updated')
				$this->common_lib->setSessMsg($this->lang->line('editRecord'), 1);
			else
				$this->common_lib->setSessMsg($this->lang->line($status), 2);
		}
		

		$this->outputdata['productData'] = $this->Common_model->selRowData("vm_product as pd","pd.*, pd.img as imageId, (CASE WHEN pd.img REGEXP ('^[0-9]+$') THEN (SELECT (CASE WHEN im.image != '' THEN CONCAT('".UPLOADPATH."','/vedmir_images/',im.image) else '' end ) FROM vm_image as im WHERE im.status = 0 and im.image != '' and im.imageId= pd.img) WHEN pd.img != '' THEN CONCAT('".UPLOADPATH."','/product_images/',pd.img) else '' end ) as img ","productId = '".$productId."'");
		if (!empty($this->outputdata['productData'])){

			$this->outputdata['restaurantData'] = $this->Common_model->selTableData("vm_restaurant","restaurantId,restaurantName$this->langSuffix as restaurantName","status != 2 and restaurantId != ".$this->outputdata['productData']->restaurantId,"restaurantName");
			$this->outputdata['productgallaryData'] = $this->Common_model->selTableData("vm_product_gallary_img","","productId = ".$productId);
			$this->outputdata['variableProductData'] = $this->Common_model->selTableData("vm_variable_product","","status = 0 and productId = ".$productId);
			$this->outputdata['categoryData'] = $this->Common_model->selTableData("vm_product_category","categoryId,categoryName$this->langSuffix as categoryName","status = 0");

			$addonProduct=$this->Common_model->exequery("Select * from vm_product_addons_category where status != 2 AND productId=".$productId);
			if ($addonProduct) {
				foreach ($addonProduct as $row){
					$row->addonsItem = $this->Common_model->exequery("Select * from vm_product_addons where status != 2 AND productId=".$productId." AND addonsCatId=".$row->addonsCatId);
				}
			}
			$this->outputdata['addonProductData']=($addonProduct)?$addonProduct:array();
			// v3print($this->outputdata['addonProductData']); exit;

		}

		$this->load->viewD($this->sessRole.'/product_copy_view',$this->outputdata);
	}

	// add addons item
	public function add_addons_items($productId = 0){

		$response  =  array('status'=>false, 'msg'=>'dbError');

		try{
			if(isset($_POST['prodAddonsCategoryName'.$this->langSuffix]) && !empty($_POST['prodAddonsCategoryName'.$this->langSuffix]) && $productId>0 && $productId!="") {
				foreach ($_POST['prodAddonsCategoryName'.$this->langSuffix] as $key => $value) {
					/* Check *& Convert Data*/
					/* Convert En Language*/
					if(isset($_POST['prodAddonsCategoryName'.$this->langSuffix][$key]) && !empty($_POST['prodAddonsCategoryName'.$this->langSuffix][$key])){
						$_POST['categoryName'][$key] = (isset($_POST['prodAddonsCategoryName'][$key]) && empty($_POST['prodAddonsCategoryName'][$key]) && $this->sessLang != 'english') ? $this->common_lib->changeLanguage(trim($value),$this->lang->line('lang'),'en') : $_POST['prodAddonsCategoryName'][$key] ;
						/* Convert Fr Language*/
						$_POST['categoryName_fr'][$key] = (isset($_POST['prodAddonsCategoryName_fr'][$key]) && empty($_POST['prodAddonsCategoryName_fr'][$key]) && $this->sessLang != 'french') ? $this->common_lib->changeLanguage(trim($value),$this->lang->line('lang'),'fr') : $_POST['prodAddonsCategoryName_fr'][$key] ; 
						/* Convert Gr Language*/
						$_POST['categoryName_gr'][$key] = (isset($_POST['prodAddonsCategoryName_gr'][$key]) && empty($_POST['prodAddonsCategoryName_gr'][$key]) && $this->sessLang != 'german') ? $this->common_lib->changeLanguage(trim($value),$this->lang->line('lang'),'de') : $_POST['prodAddonsCategoryName_gr'][$key] ; 
						/* Convert It Language*/
						$_POST['categoryName_it'][$key] = (isset($_POST['prodAddonsCategoryName_it'][$key]) && empty($_POST['prodAddonsCategoryName_it'][$key]) && $this->sessLang != 'italian') ? $this->common_lib->changeLanguage(trim($value),$this->lang->line('lang'),'it') : $_POST['prodAddonsCategoryName_it'][$key] ;
					
						$queryData = array();		
						$queryData['productId']		=   $productId;
						$queryData['categoryName']	=   trim($_POST['categoryName'][$key]);
						$queryData['categoryName_fr']=   trim($_POST['categoryName_fr'][$key]);
						$queryData['categoryName_gr']=   trim($_POST['categoryName_gr'][$key]);
						$queryData['categoryName_it']=   trim($_POST['categoryName_it'][$key]);
						$queryData['required']		 =   (isset($_POST['prodAddonsCategoryRequired'][$key]) && !empty($_POST['prodAddonsCategoryRequired'][$key])?1:0);
						$queryData['choice']		 =   $_POST['prodAddonsCatChoice'][$key];
						$queryData['isStockAvailable']		 =   (isset($_POST['prodAddonsCatStatus'][$key]) && !empty($_POST['prodAddonsCatStatus'][$key])?1:0);
						$queryData['updatedOn']		=   date('Y-m-d H:i:s');
						$queryData['addedOn']		=   date('Y-m-d H:i:s');
						if ($_POST['addonsCatId'][$key] > 0) {
							$queryStatus	= 	$this->Common_model->update("vm_product_addons_category", $queryData,"addonsCatId =".trim($_POST['addonsCatId'][$key]));
							$lastId=$_POST['addonsCatId'][$key];
							$status='updated';
						}else{
							$queryData['addedOn']		=   date('Y-m-d H:i:s');
							$lastId	= 	$this->Common_model->insertUnique("vm_product_addons_category", $queryData);
							$status='added';
						}
						// echo '<pre>'; print_r($queryData); exit;
						if($lastId){
							foreach ($_POST['prodAddonsName'.$this->langSuffix][$key] as $keyAddons => $addonValue) {
								/* Check *& Convert Addons Data*/
								/* Convert En Language*/
								$_POST['addonsName'][$keyAddons] = (isset($_POST['prodAddonsName'][$key][$keyAddons]) && empty($_POST['prodAddonsName'][$key][$keyAddons]) && $this->sessLang != 'english') ? $this->common_lib->changeLanguage(trim($addonValue),$this->lang->line('lang'),'en') : $_POST['prodAddonsName'][$key][$keyAddons] ;
								/* Convert Fr Language*/
								$_POST['addonsName_fr'][$keyAddons] = (isset($_POST['prodAddonsName_fr'][$key][$keyAddons]) && empty($_POST['prodAddonsName_fr'][$key][$keyAddons]) && $this->sessLang != 'french') ? $this->common_lib->changeLanguage(trim($addonValue),$this->lang->line('lang'),'fr') : $_POST['prodAddonsName_fr'][$key][$keyAddons] ; 
								/* Convert Gr Language*/
								$_POST['addonsName_gr'][$keyAddons] = (isset($_POST['prodAddonsName_gr'][$key][$keyAddons]) && empty($_POST['prodAddonsName_gr'][$key][$keyAddons]) && $this->sessLang != 'german') ? $this->common_lib->changeLanguage(trim($keyAddons),$this->lang->line('lang'),'de') : $_POST['prodAddonsName_gr'][$key][$keyAddons] ; 
								/* Convert It Language*/
								$_POST['addonsName_it'][$keyAddons] = (isset($_POST['prodAddonsName_it'][$key][$keyAddons]) && empty($_POST['prodAddonsName_it'][$key][$keyAddons]) && $this->sessLang != 'italian') ? $this->common_lib->changeLanguage(trim($keyAddons),$this->lang->line('lang'),'it') : $_POST['prodAddonsName_it'][$key][$keyAddons] ;
								$queryAddonsData=array();
								$queryAddonsData['productId']		=   $productId;
								$queryAddonsData['addonsCatId']		=   $lastId;
								$queryAddonsData['addonsName']		=   trim($_POST['addonsName'][$keyAddons]);
								$queryAddonsData['addonsName_fr']	=   trim($_POST['addonsName_fr'][$keyAddons]);
								$queryAddonsData['addonsName_gr']	=   trim($_POST['addonsName_gr'][$keyAddons]);
								$queryAddonsData['addonsName_it']	=   trim($_POST['addonsName_it'][$keyAddons]);
								$queryAddonsData['price']			=  (isset($_POST['prodAddonsPrice'][$key][$keyAddons]) && !empty($_POST['prodAddonsPrice'][$key][$keyAddons])?$_POST['prodAddonsPrice'][$key][$keyAddons]:0);
								$queryAddonsData['isStockAvailable']		 =   (isset($_POST['prodAddonsStatus'][$key][$keyAddons]) && !empty($_POST['prodAddonsStatus'][$key][$keyAddons])?1:0);
								$queryAddonsData['updatedOn']		=   date('Y-m-d H:i:s');
								if (isset($_POST['addonsId'][$key][$keyAddons]) && $_POST['addonsId'][$key][$keyAddons] > 0) {
									$queryStatus	= 	$this->Common_model->update("vm_product_addons", $queryAddonsData,"addonsId =".trim($_POST['addonsId'][$key][$keyAddons]));
								}else{
									$queryAddonsData['addedOn']		=   date('Y-m-d H:i:s');
									$queryStatus	= 	$this->Common_model->insert("vm_product_addons", $queryAddonsData);
								}
							}
						}
					}	
				}
				if($status == 'added')
					$response  =  array('status'=>true, 'msg'=>'addNewRecord');
				else if($status == 'updated')
					$response  =  array('status'=>true, 'msg'=>'editRecord');
				else
					$response  =  array('status'=>false, 'msg'=>'nameRequired');
			}
		}
		catch(Exception $ex){
			
		}
		return $response;
	}





	



}