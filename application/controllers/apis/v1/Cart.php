<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class Cart extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->methods['order_get']['limit'] = 500; // 500 requests per hour per order/key
        $this->methods['order_post']['limit'] = 500; // 100 requests per hour per order/key
        $this->methods['order_delete']['limit'] = 50; // 50 requests per hour per order/key
    }


// List OF CART

    public function addproducttocart_post(){
        $insertDetailStatus = 0;
        $token = $this->input->get_request_header('Authorization', TRUE);
        if($token != '' && $roleId = $this->common_lib->validateToken($token)){
            $checkinData=$this->Common_model->exequery("SELECT ch.restaurantId, rs.tax FROM vm_checkin as ch left join vm_restaurant as rs on rs.restaurantId = ch.restaurantId WHERE ch.isCheckout = 0 and ch.userId=".$roleId['roleId'],1);
            if (!valResultSet($checkinData)) {
               $this->response(['status' => FALSE,'message' => 'User checkin Required','xx'=>$this->db->last_query()], REST_Controller::HTTP_FORBIDDEN);
            }
            

            $orderId=$this->Common_model->getSelectedField("vm_order","orderId","orderStatus = 'Pending' and restaurantId=".$checkinData->restaurantId." and userId=".$roleId['roleId']);
            
            if (!valResultSet($orderId)) {
                $insertOrderData = array();
                $insertOrderData['restaurantId']    = $checkinData->restaurantId;
                $insertOrderData['tax']             = $checkinData->tax;
                $insertOrderData['userId']          = $roleId['roleId'];
                $insertOrderData['addedOn']         = date("Y-m-d H:i:s");
                $orderId=$this->Common_model->insertUnique("vm_order",$insertOrderData);
            }

            if (isset($_POST['productList']) && count($_POST['productList']) > 0) {
               
                $productList = json_decode($_POST['productList']);

                foreach ($productList as $product){
                    $productData=$this->Common_model->selRowData("vm_product","price","productId=".$product->pId);
                    if ($orderId > 0 && valResultSet($productData)) {

                        $detailQuantity=$this->Common_model->getSelectedField("vm_order_detail","quantity","orderId=".$orderId." and productId=".$product->pId);
                    
                        if (valResultSet($detailQuantity)) {
                            $updateDetailData = array();
                            $updateDetailData['quantity']        = $product->quantity+$detailQuantity;
                            $updateDetailData['price']          = ($product->quantity+$detailQuantity)*$productData->price;
                            $updateDetailData['subtotal']       = ($product->quantity+$detailQuantity)*$productData->price;
                            $updateDetailData['isServed']       = 0;
                            $insertDetailStatus=$this->Common_model->update("vm_order_detail",$updateDetailData,"orderId=".$orderId." and productId=".$product->pId);
                        }else{

                            $insertDetailData = array();
                            $insertDetailData['orderId']        = $orderId;
                            $insertDetailData['productId']      = $product->pId;
                            $insertDetailData['quantity']       = $product->quantity;
                            $insertDetailData['price']          = $productData->price;
                            $insertDetailData['subtotal']       = $productData->price;

                            $insertDetailStatus=$this->Common_model->insert("vm_order_detail",$insertDetailData);
                        }
                    }else{
                        $this->response([
                        'status' => FALSE,
                        'message' => 'Product not found.'
                        ], REST_Controller::HTTP_BAD_REQUEST);
                    }
                }


                if ($insertDetailStatus){
                    $detailData=$this->Common_model->exequery("SELECT sum(subtotal) as amt FROM vm_order_detail WHERE orderId=".$orderId,1);
                    if(isset($detailData->amt)){
                        $taxAmt = ($detailData->amt*$checkinData->tax)/100;
                        $detailData->amt = $taxAmt+$detailData->amt;
                        $this->Common_model->update("vm_order",array('amt'=>$detailData->amt),"orderId=".$orderId);
                    }

                    $this->response([
                        'status' => TRUE,
                        'message' => 'Cart updated'
                    ], REST_Controller::HTTP_OK); 
                }else
                {
                    $this->response([
                        'status' => FALSE,
                        'message' => 'Some Internal Error'
                    ], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
                }

            }else
                $this->response([
                    'status' => FALSE,
                    'message' => 'ProductList Required'
                ], REST_Controller::HTTP_FORBIDDEN);

        }else
            $this->response([
                'status' => FALSE,
                'message' => 'Unauthorized request'
            ], REST_Controller::HTTP_UNAUTHORIZED);
        
    }

    public function getmyorder_get(){
        $token = $this->input->get_request_header('Authorization', TRUE);
        if($token != '' && $roleId=$this->common_lib->validateToken($token)){
            // Orders from a data store e.g. database
            $qry = "SELECT t.*,  (case when (t.totalFood > 0 AND t.totalDrink > 0) then 2 when (t.totalDrink > 0) then 1 else 0 end) as orderType FROM (SELECT od.orderId,od.`restaurantId`,od.`userId`,od.`discount`,od.`tax`,od.`initialAmount`,od.`amt`,od.`payment_method`,od.`transactionId`,od.`paymentStatus`,od.`orderDescription`,od.`chargeId`, od.`cardExpMonth`, od.`cardExpyear`,od.`last4`,od.`brand`,od.`cancelRemark`,od.`cancelledDateTime`,od.`confirmOrderDateTime`,od.`deliverDateTime`, od.`refundedAmount`,od.`restaturantAmount`,od.`restaurantSettlement`,od.`isCapture`,od.`tableNo`,od.`addedOn`,od.`isTrail`,od.`isRead`,od.`isReview`, (CASE WHEN orderStatus = 'Pending' THEN 0 WHEN orderStatus = 'Processing' THEN 1 WHEN orderStatus = 'Completed' THEN 2 WHEN orderStatus = 'Failed' THEN 3 WHEN orderStatus = 'Cancelled' THEN 4 WHEN orderStatus = 'Refund' THEN 5 ELSE 6 END) as orderStatusKey,
              (CASE WHEN od.orderStatus='Processing' then '".$this->lang->line('Processing')."' when od.orderStatus = 'Pending' then '".$this->lang->line('Pending')."'  else od.orderStatus end) as orderStatus,
             (SELECT us.userName FROM vm_user as us WHERE us.userId= od.userId) as userName,
             rs.restaurantName,
             (CASE WHEN rs.logo != '' THEN CONCAT('".UPLOADPATH."','/restaurant_images/',rs.logo) else '' end ) as img ,
             (SELECT sum(de.quantity) FROM vm_order_detail as de WHERE de.orderId= od.orderId) as totalItem,
             (SELECT count(de.detailId) FROM vm_order_detail as de WHERE de.orderId= od.orderId AND itemType = '0') as totalFood,
             (SELECT count(de.detailId) FROM vm_order_detail as de WHERE de.orderId= od.orderId AND itemType = '1') as totalDrink             
             from vm_order as od LEFT JOIN vm_restaurant as rs on rs.restaurantId = od.restaurantId WHERE od.userId = ".$roleId['roleId']." order by od.orderId DESC) as t order by t.orderId DESC";
            $orders = $this->Common_model->exequery($qry);

                if ($orders)
                    $this->response($orders, REST_Controller::HTTP_OK); 
                else
                {
                    $this->response([
                        'status' => FALSE,
                        'message' => 'No orders were found'
                    ], REST_Controller::HTTP_FORBIDDEN);
                }

        }else{
            $this->set_response([
                'status' => FALSE,
                'message' => 'Unauthorized request'
            ], REST_Controller::HTTP_UNAUTHORIZED);
        }
    }


// List OF ORDER WITH DETAILS

    public function getmyorderdetails_get($orderId = 0){
        $langSuffix=$this->lang->line('langSuffix');
        $token = $this->input->get_request_header('Authorization', TRUE);
        if($token != '' && $roleId=$this->common_lib->validateToken($token)){
            // Orders from a data store e.g. database
            if ($orderId == 0 ) {
                $orderData=$this->Common_model->selRowData("vm_order","restaurantId,orderId, (CASE WHEN orderStatus = 'Pending' THEN 0 WHEN orderStatus = 'Processing' THEN 1 WHEN orderStatus = 'Completed' THEN 2 WHEN orderStatus = 'Failed' THEN 3 WHEN orderStatus = 'Cancelled' THEN 4 WHEN orderStatus = 'Refund' THEN 5 ELSE 6 END) as orderStatus","status = 0 and userId=".$roleId['roleId']);
                $orderId = $orderData->orderId;
            }else{

                $orderData=$this->Common_model->selRowData("vm_order","restaurantId,orderId, (CASE WHEN orderStatus = 'Pending' THEN 0 WHEN orderStatus = 'Processing' THEN 1 WHEN orderStatus = 'Completed' THEN 2 WHEN orderStatus = 'Failed' THEN 3 WHEN orderStatus = 'Cancelled' THEN 4 WHEN orderStatus = 'Refund' THEN 5 ELSE 6 END) as orderStatus","userId=".$roleId['roleId']." and orderId=".$orderId);
            }
            $qry = "SELECT de.*,rs.tax as tax ,
             (SELECT us.userName FROM vm_user as us WHERE us.userId= od.userId) as userName,
             (CASE WHEN de.isVariable = '1' THEN CONCAT(pd.productName$langSuffix,' (',vd.variableName$langSuffix, ')') ELSE  pd.productName$langSuffix END) as productName,
            (CASE WHEN pd.img REGEXP ('^[0-9]+$') THEN (SELECT (CASE WHEN im.image != '' THEN CONCAT('".UPLOADPATH."','/vedmir_images/',im.image) when pd.categoryId=4 then '".UPLOADPATH."/default/food_default.jpg' else '".UPLOADPATH."/default/drink_default.jpg' end ) FROM vm_image as im WHERE im.status = 0 and im.image != '' and im.imageId= pd.img) WHEN pd.img != '' THEN CONCAT('".UPLOADPATH."','/product_images/',pd.img) when pd.categoryId=4 then '".UPLOADPATH."/default/food_default.jpg' else '".UPLOADPATH."/default/drink_default.jpg' end ) as productImg ,
             rs.restaurantName,
             (CASE WHEN rs.logo != '' THEN CONCAT('".UPLOADPATH."','/restaurant_images/',rs.logo) else '' end ) as restaurantImg 
             from vm_order_detail as de left join vm_order as od on de.orderId= od.orderId left join vm_variable_product as vd on (de.productId= vd.variableId and de.isVariable = '1') left join vm_product as pd on (CASE WHEN de.isVariable = '1' THEN (pd.productId = vd.productId) ELSE  (pd.productId = de.productId) END) LEFT JOIN vm_restaurant as rs on rs.restaurantId = od.restaurantId WHERE de.orderId = ".$orderId;
            $orders['orderItems'] = $this->Common_model->exequery($qry);

            $orders['taxfinalpercent'] = $this->Common_model->getSelectedField("vm_restaurant","tax","restaurantId=".$orderData->restaurantId);
            $orders['taxfinalamount'] = 0;
            $orders['orderStatus'] = $orderData->orderStatus;
            $orders['discount'] = 0;
            $orders['subtotal'] = 0;
            $orders['total'] = 0;
            foreach ($orders['orderItems'] as $orderedproduct) {
                $orders['subtotal'] = $orders['subtotal'] + $orderedproduct->subtotal;
                $orders['discount'] = $orders['discount'] + $orderedproduct->discount;
                $addonsData = $this->Common_model->exequery("SELECT vm_product_addons.addonsName$langSuffix as addonsName, ".$orderedproduct->quantity." as addonsQuantity, vm_product_addons.price as addonsPrice, vm_order_addons.price as addonsSubtotal  FROM vm_order_addons left join vm_product_addons on vm_order_addons.addonId = vm_product_addons.addonsId WHERE vm_order_addons.detailId='".$orderedproduct->detailId."'");
                $orderedproduct->addonsData = ($addonsData) ? $addonsData : array();
            }
                $orders['total'] = $orders['subtotal']/(1+$orders['taxfinalpercent']/100);
                $orders['taxfinalamount'] =  $orders['subtotal'] - $orders['total'];
            
            
            if ($orders)
                $this->response($orders, REST_Controller::HTTP_OK);
            else
                $this->response(['status' => FALSE,'message' => 'No orders were found'], REST_Controller::HTTP_FORBIDDEN);
           
        }else
            $this->response(['status' => FALSE,'message' => 'Unauthorized request'], REST_Controller::HTTP_UNAUTHORIZED); 
        
    }


    public function getmyserved_get($orderId){
        $token = $this->input->get_request_header('Authorization', TRUE);
        if($token != '' && $this->common_lib->validateToken($token) && $orderId > 0){
             $qry = "SELECT de.*,
             (SELECT us.userName FROM vm_user as us WHERE us.userId= od.userId) as userName,
             (SELECT pd.productName FROM vm_product as pd WHERE pd.productId= de.productId) as productName 
             from vm_order_detail as de left join vm_order as od on de.orderId= od.orderId WHERE de.isServed = 1 and  de.orderId = ".$orderId;
            $servedData = $this->Common_model->exequery($qry);
            if ($servedData)
                $this->response($servedData, REST_Controller::HTTP_OK);
            else
                $this->response(['status' => FALSE,'message' => 'No served Item were found'], REST_Controller::HTTP_FORBIDDEN);

        }else
            $this->response(['status' => FALSE,'message' => 'Unauthorized request'], REST_Controller::HTTP_UNAUTHORIZED);
        
    }


    public function getorderinvoice_get($orderId){
        $langSuffix=$this->lang->line('langSuffix');
        $token = $this->input->get_request_header('Authorization', TRUE);
        if($token != '' && $this->common_lib->validateToken($token) && $orderId > 0){
            $orderData = $this->Common_model->exequery("SELECT DATE_FORMAT(od.addedOn,'%d.%m.%Y') as addedDate, DATE_FORMAT(od.addedOn,'%H.%i') as addedTime, CONCAT(us.userName,' ',us.lastName) as userName, us.email, rs.restaurantName, rs.tax, rs.address1$langSuffix as address1, rs.address2$langSuffix as address2, rs.city$langSuffix as city, rs.state$langSuffix as state, rs.country$langSuffix as country, rsd.legal_entity_business_tax_id from vm_order as od LEFT JOIN vm_restaurant as rs on rs.restaurantId = od.restaurantId LEFT JOIN vm_user as us on us.userId = od.userId  LEFT JOIN vm_restaurant_stripe_details as rsd on rsd.restaurantId = od.restaurantId WHERE od.orderId = ".$orderId,1);

            $orderDetailData = $this->Common_model->exequery("SELECT de.*, (CASE WHEN de.isVariable = '1' THEN CONCAT(pd.productName$langSuffix,' (',vd.variableName$langSuffix, ')') ELSE  pd.productName$langSuffix END) as productName, pd.price from vm_order_detail as de left join vm_variable_product as vd on (de.productId= vd.variableId and de.isVariable = '1') left join vm_product as pd on (CASE WHEN de.isVariable = '1' THEN (pd.productId = vd.productId) ELSE  (pd.productId = de.productId) END) WHERE de.orderId = ".$orderId);

            if (!empty($orderData) && !empty($orderDetailData)) {
                $taxAmount= 0;
                $subtotal = 0;
                $total = 0;
                $currency ='CHF';  $content = '';
                $address ='';
                $orderData->tax = 7.7;

                if($orderData->address1)
                    $address .= $orderData->address1;
                if($orderData->address2)
                    $address .= ', '.$orderData->address2;
                if($orderData->city)
                    $address .= ',<br>'.$orderData->city;
                if($orderData->state)
                    $address .= ', '.$orderData->state;
                if($orderData->country)
                    $address .= ',<br>'.$orderData->country;

                foreach ($orderDetailData as $orderedProduct) {
                    /*if($orderedProduct->isFree == '0'){
                        $tax = ($orderedProduct->subtotal*$orderData->tax)/100;
                        $amt =  $orderedProduct->subtotal - $tax;
                        $subtotal = $subtotal + $amt;
                        $taxAmount = $taxAmount+$tax;
                    }else
                        $amt = 0.00;*/
                    /*$tax = ($orderedProduct->subtotal*$orderData->tax)/100;
                    $amt =  $orderedProduct->subtotal - $tax;
                    $subtotal = $subtotal + $amt;
                    $taxAmount = $taxAmount+$tax;*/
                    $subtotal = $subtotal + $orderedProduct->subtotal;                    
                    
                    $amt = ($orderedProduct->subtotal)/(1+$orderData->tax/100);
                    
                    $content .= '<tr>
                                <td width="300">
                                    <table cellspacing="0" cellpadding="0" border="0" align="center" width="100%">
                                        <tbody>
                                            <tr>
                                                <td align="left" style="border-bottom: 1px solid #d7d7d7; padding: 15px 0;font-weight:400;">'.$orderedProduct->productName.'</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                                <td width="300">
                                    <table cellspacing="0" cellpadding="0" border="0" align="center" width="100%">
                                        <tbody>
                                            <tr>
                                                <td align="right" style="border-bottom: 1px solid #d7d7d7; padding: 15px 0;font-weight:400;">'.$currency.' '.number_format($amt,2).'</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>';
                }
               // $total = number_format(($subtotal + $taxAmount),2);
                $total = number_format($subtotal/(1+$orderData->tax/100), 2);
                $taxAmount =  $subtotal - $total;
                $settings = array();
                $settings["template"]               =  "order_invoice_tpl".$langSuffix.".html";
                $settings["email"]                  =  $orderData->email; 
                $settings["subject"]                =  "VEDMIR Order Invoice - $orderId";
                $contentarr['[[[USERNAME]]]']       =   $orderData->userName;
                $contentarr['[[[RESTAURANTNAME]]]'] =   $orderData->restaurantName;
                $contentarr['[[[ADDEDDATE]]]']      =   $orderData->addedDate;
                $contentarr['[[[ADDEDTIME]]]']      =   $orderData->addedTime;
                $contentarr['[[[TAX]]]']            =   $orderData->tax;
                $contentarr['[[[ORDERID]]]']        =   $orderId;
                $contentarr['[[[SUBTOTAL]]]']       =   number_format($total,2);
                $contentarr['[[[CURRENCY]]]']       =   $currency;
                $contentarr['[[[TAXAMOUNT]]]']      =   number_format($taxAmount,2);
                $contentarr['[[[VAT]]]']            =   $orderData->legal_entity_business_tax_id;
                $contentarr['[[[ADDRESS]]]']        =   $address;
                $contentarr['[[[TOTALAMOUNT]]]']    =    number_format($subtotal,2);
                $contentarr['[[[CONTENT]]]']        =   $content;
                $contentarr['[[[BASEURL]]]']        =   BASEURL;
                $contentarr['[[[LOGO]]]']           =   BASEURL."/system/static/frontend/images/logo.png";
                $settings["contentarr"]             =   $contentarr;
                $isMailSent = $this->common_lib->sendMail($settings); 
                if ($isMailSent)
                    $this->response(['status' => TRUE,'message' => 'Invoice has been sent to your mail.'], REST_Controller::HTTP_OK);
                else
                    $this->response(['status' => FALSE,'message' => 'No order were found'], REST_Controller::HTTP_FORBIDDEN);

            }else
                $this->response(['status' => FALSE,'message' => 'Order details not found.'], REST_Controller::HTTP_UNAUTHORIZED);
        }else
            $this->response(['status' => FALSE,'message' => 'Unauthorized request'], REST_Controller::HTTP_UNAUTHORIZED);
        
    }

        // recruitment-invoice for company view
    public function orderinvoice_get($orderId=0){
            $langSuffix=$this->lang->line('langSuffix');
            $orderData = $this->Common_model->exequery("SELECT DATE_FORMAT(od.addedOn,'%d.%m.%Y') as addedDate, DATE_FORMAT(od.addedOn,'%i.%H') as addedTime, CONCAT(us.userName,' ',us.lastName) as userName, rs.restaurantName, rs.tax, rs.address1$langSuffix as address1, rs.address2$langSuffix as address2, rs.city$langSuffix as city, rs.state$langSuffix as state, rs.country$langSuffix as country, rsd.legal_entity_business_tax_id from vm_order as od LEFT JOIN vm_restaurant as rs on rs.restaurantId = od.restaurantId LEFT JOIN vm_user as us on us.userId = od.userId  LEFT JOIN vm_restaurant_stripe_details as rsd on rsd.restaurantId = od.restaurantId WHERE od.orderId = ".$orderId,1);

            $orderDetailData = $this->Common_model->exequery("SELECT de.*, (CASE WHEN de.isVariable = '1' THEN CONCAT(pd.productName$langSuffix,' (',vd.variableName$langSuffix, ')') ELSE  pd.productName$langSuffix END) as productName, pd.price from vm_order_detail as de left join vm_variable_product as vd on (de.productId= vd.variableId and de.isVariable = '1') left join vm_product as pd on (CASE WHEN de.isVariable = '1' THEN (pd.productId = vd.productId) ELSE  (pd.productId = de.productId) END) WHERE de.orderId = ".$orderId);

            if (!empty($orderData) && !empty($orderDetailData)) {
                $taxAmount= 0;
                $subtotal = 0;
                $total = 0;
                $currency ='CNF';
                $content = '';
                $address ='';
                $orderData->tax = 7.7;

                if($orderData->address1)
                    $address .= $orderData->address1;
                if($orderData->address2)
                    $address .= ', '.$orderData->address2;
                if($orderData->city)
                    $address .= ',<br>'.$orderData->city;
                if($orderData->state)
                    $address .= ', '.$orderData->state;
                if($orderData->country)
                    $address .= ',<br>'.$orderData->country;

                foreach ($orderDetailData as $orderedProduct) {
                    /*if($orderedProduct->isFree == '0'){
                        $tax = ($orderedProduct->subtotal*$orderData->tax)/100;
                        $amt =  $orderedProduct->subtotal - $tax;
                        $subtotal = $subtotal + $amt;
                        $taxAmount = $taxAmount+$tax;
                    }else
                        $amt = 0.00;*/
                    $subtotal = $subtotal + $orderedProduct->subtotal;                    
                    
                    $amt = ($orderedProduct->subtotal)/(1+$orderData->tax/100);

                    $content .= '<tr>
                                <td width="300">
                                    <table cellspacing="0" cellpadding="0" border="0" align="center" width="100%">
                                        <tbody>
                                            <tr>
                                                <td align="left" style="border-bottom: 1px solid #d7d7d7; padding: 15px 0;font-weight:400;">'.$orderedProduct->productName.'</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                                <td width="300">
                                    <table cellspacing="0" cellpadding="0" border="0" align="center" width="100%">
                                        <tbody>
                                            <tr>
                                                <td align="right" style="border-bottom: 1px solid #d7d7d7; padding: 15px 0;font-weight:400;">'.$currency.' '.number_format($amt,2).'</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>';
                }

                // $taxAmount = ($subtotal*$orderData->tax)/100;
                //$total = number_format(($subtotal + $taxAmount),2);
                $total = number_format($subtotal/(1+$orderData->tax/100), 2);
                $taxAmount =  $subtotal - $total;

                $logoUrl                =   BASEURL."/system/static/frontend/images/logo.png";
                $template               =   ABSSTATICPATH."/emailTemplates/order_invoice_tpl".$langSuffix.".html";
                $invoice_content        =   file_get_contents($template);


                $invoice_content        =   str_replace("[[[BASEURL]]]", BASEURL, $invoice_content);
                $invoice_content        =   str_replace("[[[LOGO]]]", $logoUrl, $invoice_content);
                $invoice_content        =   str_replace("[[[USERNAME]]]", $orderData->userName, $invoice_content);
                $invoice_content        =   str_replace("[[[RESTAURANTNAME]]]", $orderData->restaurantName, $invoice_content);
                $invoice_content        =   str_replace("[[[ORDERID]]]", $orderId, $invoice_content);
                $invoice_content        =   str_replace("[[[ADDEDDATE]]]", $orderData->addedDate, $invoice_content);
                $invoice_content        =   str_replace("[[[ADDEDTIME]]]", $orderData->addedTime, $invoice_content);
                $invoice_content        =   str_replace("[[[SUBTOTAL]]]", number_format($total,2), $invoice_content);
                $invoice_content        =   str_replace("[[[CURRENCY]]]", $currency, $invoice_content);
                $invoice_content        =   str_replace("[[[TAX]]]", $orderData->tax, $invoice_content);
                $invoice_content        =   str_replace("[[[VAT]]]", $orderData->legal_entity_business_tax_id, $invoice_content);
                $invoice_content        =   str_replace("[[[ADDRESS]]]", $address, $invoice_content);
                $invoice_content        =   str_replace("[[[TAXAMOUNT]]]", number_format($taxAmount,2), $invoice_content);
                $invoice_content        =   str_replace("[[[TOTALAMOUNT]]]", $subtotal, $invoice_content);
                $invoice_content        =   str_replace("[[[CONTENT]]]", $content, $invoice_content);
               
                echo $invoice_content;
                /*include('./system/static/mpdf60/mpdf.php');
                $mpdf=new mPDF();
                $mpdf->WriteHTML($invoice_content);
                $filename = 'invoice_'.$orderId.'.pdf';
                $uploadPath =ABSUPLOADPATH."/order_invoices/".$filename;
                // exit;
                if($mpdf->Output($uploadPath,'F')){
                    return 1;
                }else{
                    return 0;
                }*/
            }

    }



}
