<?php

namespace App\CentralLogics;

use App\Models\CareerEvent;
use App\Models\GeneralSetting;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\QuizBankManagement;
use App\Models\SubPlan;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserQuizBankAccess;
use App\Models\Withdraw;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
class UserAccess
{   
    //This helper exists in User/QuizManagementController,LearningController,EventController,CartController,
    public static function hasAccess($user, $itemType, $itemId,$activatePositionCheck=null)
    {   
        if($user->IsSuperUser()){
            $message="You have access";
            return ['access' => true, 'message' => $message, 'single_purchase'=>true];
        }
        // Base query for common conditions
        $baseQuery = OrderItem::select('order_items.*')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.user_id', $user->id)
            ->latest('orders.created_at');

        // Try to fetch direct purchase
        $orderItem = (clone $baseQuery)
            ->where('order_items.item_type', $itemType)
            ->where('order_items.item_id', $itemId)
            ->first();

        // If not found, then check for subscription
        if (!$orderItem) {
            $orderItem = (clone $baseQuery)
                ->where('order_items.item_type', 'subscription_plan')
                ->first();

            if (!$orderItem) {
                $message = "You don't have any subscription or individual purchase";
                return ['access' => false, 'message' => $message];
            }
        }

        // $orderItem = OrderItem::select('order_items.*')
        //     ->join('orders', 'orders.id', '=', 'order_items.order_id')
        //     ->where('orders.user_id', $user->id)
        //     ->where('order_items.item_type', $itemType)
        //     ->where('order_items.item_id', $itemId)
        //     ->orWhere(function ($query) use ($user, $itemId) {
        //         $query->where('orders.user_id', $user->id)
        //             ->where('order_items.item_type', 'subscription_plan');
        //             // ->where('order_items.item_id', $itemId);
        //     })
        //     ->latest('orders.created_at')
        //     ->first();
        // if (!$orderItem) {
        //     $message="You dont have any subscription or individual purchase";
        //     return ['access' => false, 'message' => $message];          
        // }
        
        if ($orderItem->item_type == $itemType) {            
            $message="You have access";
            return ['access' => true, 'message' => $message, 'single_purchase'=>true];     
        }
        
        if ($orderItem->item_type == 'subscription_plan') {
            $additionalData = self::validateItem($itemType, $itemId);
            
            $activee=$user->subscriptionsActive();
            if(!$activee){
                $message="You dont have any subscription";
                return ['access' => false, 'message' => $message];
            }
            $plan = SubPlan::findOrFail($orderItem->item_id);
            $accessSections = json_decode($plan->access_sections, true);

            // validate subscription with time interval
            if ($plan->interval !== 'unlimited' && Carbon::now()->diffInDays($orderItem->created_at) > self::getTimeIntervalInDays($plan->interval)) {
                $message="Your subscription has expired";
                return ['access' => false, 'message' => $message];  
            }
            foreach ($accessSections as $section) {

                if ($itemType == 'events' && $section['name'] == 'eventsAccess' && in_array($additionalData['type'], $section['event_type'])) {
                    $message="eventsAccess found";
                    return ['access' => true, 'message' => $message];
                }
                
                if ($itemType == 'quizbank' && $section['name'] == 'quizbankAccess' && in_array($additionalData['type'], $section['question_type'])) {
                    
                    // According to scenario User can open one position in one Company, so here is the code to validate it
                    $positionCheck=self::quizBankPositionAccess($user->id,$itemId,$activatePositionCheck);
                    // $message="quizbankAccess found";
                    return $positionCheck;
                }
                
                if ($itemType == 'interview' && $section['name'] == 'interviewAccess') {
                    $message="interviewAccess found";
                    return ['access' => true, 'message' => $message];
                }
                if ($itemType == 'job_applications' && $section['name'] == 'jobPortalAccess') {
                    $message="jobPortalAccess found";
                    return ['access' => true, 'message' => $message];
                }
            }
            // $message="Access not found for this item";
            $message="Sorry, you will need to upgrade your account to access this item.";
            return ['access' => false, 'message' => $message]; 
        }
    }


    public static function validateItem(string $itemType, int $itemId)
    {  

        switch ($itemType) {
            case 'events':
                  $item =CareerEvent::where('id',$itemId)->active()->first();
                  return [
                    'name' => $item->name ?? null,
                    'type' => $item->event_type,
                    'access'=>'eventsAccess',
                  ];
            case 'quizbank':
                $item = QuizBankManagement::where('id',$itemId)->active()->first();
                return [
                    'name' => $item->name ?? null,
                    'type' => $item->assessment_type,
                    'access'=>'quizbankAccess',
                  ];
            case 'interview':
                $item = User::where('id',$itemId)->where('role_id',2)->active()->first();
                return  [
                    'name' => $item->name ?? null,
                    'type' => null,
                    'access'=>'interviewAccess',
                  ];
            case 'subscription_plan':
                $item = SubPlan::where('id',$itemId)->active()->first();
                 return [
                    'name' => $item->name ?? null,
                    'type' => null,
                  ];
            default:
                return null;
        }
    }

    public static function getTimeIntervalInDays($interval)
    {
        switch ($interval) {
            case 'weekly':
                return 7;
            case 'monthly':
                return 30;
            case 'quarterly':
                return 90;
            case 'biannually':
                return 180;
            case 'yearly':
                return 365;
            default:
                return 0;
        }
    }

    private static function quizBankPositionAccess($user_id,$quizbankmanagement_id,$activatePositionCheck=null)
    {

        $quizbankmanagement = QuizBankManagement::where('id',$quizbankmanagement_id)->active()->firstOrFail();

        $userQuizBankPosition = UserQuizBankAccess::where('user_id', $user_id)
            ->where('company_id', $quizbankmanagement->company_id)
            ->first();

        

        if (!$userQuizBankPosition) {
            if($activatePositionCheck){
                 UserQuizBankAccess::create([
                    'user_id'=>$user_id,
                    'company_id' =>  $quizbankmanagement->company_id,
                    'quiz_bank_management_id' => $quizbankmanagement->id,
                    'position' => $quizbankmanagement->position,
                ]);
                $message="You Can access that quizbankAccess Poistion";
                return ['access' => true, 'message' => $message];
            }else{
                $message="Please Activate the Position First";
                return ['access' => false, 'can_access'=>true, 'message' => $message];
            }
           
        }
        // Normalize the positions
        $normalizedUserPosition = self::normalizeString($userQuizBankPosition->position);
        $normalizedQuizPosition = self::normalizeString($quizbankmanagement->position);
        if($userQuizBankPosition && $normalizedUserPosition != $normalizedQuizPosition){
            $message=" Sorry, you have already accessed one position in ".($quizbankmanagement->company?$quizbankmanagement->company->name:'').", you are not allowed to activate ".$quizbankmanagement->position." in ".($quizbankmanagement->company?$quizbankmanagement->company->name:'');
            // $message="Sorry you have no permit of this quiz bank as you have already accessed another position in this Employer Company.";
            return ['access' => false, 'already_opened_position'=>true, 'message' => $message];
        }
        else if($userQuizBankPosition && $normalizedUserPosition == $normalizedQuizPosition){
            $message="You can access this position";
            return ['access' => true, 'message' => $message];
        }
       
    }

   // Normalize Function
    public static function normalizeString($input) {
        return trim(str_replace("\xC2\xA0", ' ', $input));
    }



    public static function activeSubscription($user, $itemId) {
        $orderItem = OrderItem::select('order_items.*')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.user_id', $user->id)
            ->where('order_items.item_type', 'subscription_plan')
            ->where('order_items.item_id', $itemId)
            ->latest('orders.created_at')
            ->first();
        if (!$orderItem) {
            $message="You dont have any subscription ";
            return ['access' => false, 'message' => $message];          
        }

        $plan = SubPlan::find($itemId);
        if(!$plan){
            return ['access' => false, 'message' => $message]; 
        }
        // validate subscription with time interval
        if ($plan->interval !== 'unlimited' && Carbon::now()->diffInDays($orderItem->created_at) > self::getTimeIntervalInDays($plan->interval)) {
            $message="Your subscription has expired";
            return ['access' => false, 'message' => $message];  
        }else{
            $message="Your have subscription";
            return ['access' => true, 'message' => $message]; 
        }

    }

    public static function getUserSubscription($user) {
           $orderItem = OrderItem::select('order_items.*')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.user_id', $user->id)
            ->where('order_items.item_type', 'subscription_plan')
            ->latest('orders.created_at')
            ->first();
        return $orderItem;
    }

    public static function findSuitablePlan($assessmentType)
    {
        $subplans = SubPlan::active()->orderBy('price','asc')->get();
        foreach ($subplans as $subplan) {
            $accessSections = json_decode($subplan->access_sections, true);

            foreach ($accessSections as $section) {
                if ($section['name'] === 'quizbankAccess') {
                    $questionTypes = $section['question_type'];

                    // if (array_intersect($questionTypes, $quizBank->assessment_types)) {
                    //     return $subplan;
                    // }
                    if (in_array($assessmentType, $questionTypes)) {
                        return $subplan->name;
                    }
                }
            }
        }

        return null; // No suitable plan found
    }
      
}
