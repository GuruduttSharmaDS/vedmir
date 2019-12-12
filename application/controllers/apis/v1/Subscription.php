<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';

class Subscription extends REST_Controller {

    function __construct()  {
        // Construct the parent class
        parent::__construct();
        $this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['users_post']['limit'] = 500; // 100 requests per hour per user/key
        $this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key

        // Check User Authorization
        $this->checkUserAuthentication ();
    }

    // Get Courses Subscription
    public function getCourseSubscription ($courseId) {
        $query = "SELECT courseSubscription.courseSubscriptionId, sp.subscriptionPlanId, sp.planName, sp.description,                   sp.amount, sp.period, sp.duration
                from vm_course_subscription as courseSubscription 
                left join vm_subscription_plan as sp on sp.subscriptionPlanId = courseSubscription.subscriptionPlanId
                where courseSubscription.courseId = $courseId";
        $items = $this->Common_model->exequery($query); 

        $returnData = [];
        if (!empty ($items)) {
            foreach($items as $item) {                
                $returnData[] = [
                    'courseSubscriptionId' => $item->courseSubscriptionId,
                    'subscriptionPlanId' => $item->subscriptionPlanId,
                    'planName'    => $item->planName,
                    'planDescription'  => $item->description,
                    'planAmount'  => $item->amount,
                    'planPeriod'  => $item->period,
                    'planDuration'  => $item->duration
                ];
            }
        }
                     
        return $returnData;
    }

    public function courseSubscriptionDetails_GET ($courseId = 0) {
        $returnData = $this->getCourseSubscription ($courseId);
        $message = "Success";
        $this->successResponse($message, $returnData);
    }

}

