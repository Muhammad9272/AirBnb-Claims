<?php

namespace App\Models;

use App\CentralLogics\Helpers;
use App\CentralLogics\UserAccess;
use App\Helpers\AppHelper;
use App\Models\Appointment;
use App\Models\CareerEventRegistration;
use App\Models\Country;
use App\Models\Favorite;
use App\Models\GeneralSetting;
use App\Models\SubPlan;
use App\Models\Subscriptions;
use App\Models\Transaction;
use App\Models\UserQuizBankAccess;
use Auth;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;
use Laravel\Sanctum\HasApiTokens;
// use Illuminate\Database\Query\Builder;
class User extends Authenticatable
{ 
    use HasApiTokens, HasFactory, Notifiable, Billable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'gender',
        'photo',
        'affiliate_code',
        'role_id',
        'referred_by',
        'niche',
        'country_id',
        'oauth_uid',
        'oauth_provider',
        // 'language',
        'tags','about','coaching_services','faqs','price'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', '=', 1);
    }
    public function scopeUser( $query)
    {
        return $query->where('role_type', 'user');
    }

    /**
     * Scope a query to only include admin users.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAdmin( $query)
    {
        return $query->where('role_type', 'admin');
    }
    public function IsSuperUser(){
        if ($this->id == 2 && $this->email=='fayexxj@gmail.com') {
           return true;
        }
        return false;
    }

    public function favorites()
    {
          return $this->hasMany(Favorite::class, 'user_id');
    }

    public function country()
    {
          return $this->belongsTo(Country::class, 'country_id');
    }
   
    public function careerEventRegistrations()
    {
      return $this->hasMany(CareerEventRegistration::class,'user_id');
    }

    public function studentAppointments()
    {
      return $this->hasMany(Appointment::class,'student_id');
    }
    public function tutorAppointments()
    {
      return $this->hasMany(Appointment::class,'tutor_id');
    }

    public function UserQuizBankAccess()
    {
      return $this->hasMany(UserQuizBankAccess::class,'user_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class)->where('payment_status','!=','pending');
    }

    public function bitSubmissions()
    {
        return $this->hasMany(BitSubmission::class);
    }

    public function bitTransactions()
    {
        return $this->hasMany(BitTransaction::class);
    }

    public function addBits($amount, $sourceType, $sourceId, $description)
    {
        $this->bit_balance += $amount;
        $this->save();
        
        return BitTransaction::create([
            'user_id' => $this->id,
            'amount' => $amount,
            'balance_after' => $this->bit_balance,
            'source_type' => $sourceType,
            'source_id' => $sourceId,
            'description' => $description
        ]);
    }

    public function deductBits($amount, $sourceType, $sourceId, $description)
    {
        if ($this->bit_balance < $amount) {
            return false;
        }
        
        $this->bit_balance -= $amount;
        $this->save();
        
        return BitTransaction::create([
            'user_id' => $this->id,
            'amount' => -$amount,
            'balance_after' => $this->bit_balance,
            'source_type' => $sourceType,
            'source_id' => $sourceId,
            'description' => $description
        ]);
    }

    /**
     * Get all user subscriptions (including inactive ones)
     */
    public function userSubscriptions()
    {
        return $this->hasMany(UserSubscription::class);
    }

    /**
     * Get active user subscriptions only
     */
    public function activeuserSubscriptions()
    {
        return $this->hasMany(UserSubscription::class)
            ->where('status', 'active')
            ->where(function($query) {
                $query->whereNull('expires_at')
                      ->orWhere('expires_at', '>', now());
            });
    }

    /**
     * Get current active subscription (fixing timezone issues with ends_at comparison)
     */
    public function getActiveSubscriptionAttribute()
    {
        return $this->userSubscriptions()
            ->where('status', 'active')
            ->latest()
            ->first();
    }

    public function subscriptionsActive()
    {
        $activeSubscription = $this->userSubscriptions()
                             //->where('ends_at', '>=', Carbon::now()->subMinutes(2))
                             ->latest('id')
                             ->first();
        if($activeSubscription && $activeSubscription->ends_at>=Carbon::now()->subMinutes(2)){
              return  $activeSubscription;   
        }   else{
            return null;
        }                  
        //return $activeSubscription;
        // if ($activeSubscription) {
        //     $plan = SubPlan::find($activeSubscription->subplan_id);
        //     if ($plan && ($plan->interval === 'unlimited' || Carbon::now()->diffInDays($activeSubscription->created_at) <= UserAccess::getTimeIntervalInDays($plan->interval))) {
        //         return $plan;
        //     }
        // }

        // return '';
    }

    public function freetrial($value='')
    {
        if(auth()->user()->userSubscriptions()->exists()){
            return false;
        }else{
            return true;
        }
    }
    
    //****************User Transaction & Balance Modules****************//
    protected  function activeTransaction() {
        $gs=GeneralSetting::find(1);
        $user=Auth::user();
        $datenow=Carbon::now()->subDays($gs->withdrawl_after_days);

        return $earning_net_user=Transaction::where('referrer_link',$user->affiliate_code)
               ->where('status','active')
               ->where('created_at','<=',$datenow)
               ->where('is_cleared',0); //2<=-25
               // ->where('is_cleared',0)
               // ->sum('earning_net_user');
    }
    public  function userbalance($withCurrency='') {

        $activeTransaction=$this->activeTransaction();
        $earning_net_user=0;
        if($activeTransaction){
            $earning_net_user=$activeTransaction->sum('earning_net_user');
        }

        if($withCurrency && $withCurrency==1){
          return Helpers::setCurrency($earning_net_user);
        }else{
            return $earning_net_user;
        }                      
    }
    public function userbalancedecrement($value='')
    {  
        $activeTransaction=$this->activeTransaction();
        if($activeTransaction){
            $activeTransaction->update(['is_cleared'=>1]);
        }
    }
    //****************User Transaction & Balance Modules****************//

    public function claims()
    {
        return $this->hasMany(Claim::class);
    }

    public function activeSubscription111()
    {
        return $this->hasOne(Subscriptions::class)
            ->where('status', 'active')
            ->where('ends_at', '>=', now())
            ->latest();
    }

    /**
     * Check if the user can create a new claim
     * 
     * @return array [can_create => bool, message => string]
     */
    public function canCreateClaim()
    {
        $activeSubscription = $this->activeuserSubscriptions()->first();
        
        if (!$activeSubscription) {
            return [
                'can_create' => false,
                'message' => 'You need an active subscription to create claims.'
            ];
        }
        
        // If unlimited claims
        if (!$activeSubscription->plan->claims_limit) {
            return [
                'can_create' => true,
                'message' => ''
            ];
        }
        
        // Get billing period start date (subscription created or last renewal)
        $periodStart = $activeSubscription->updated_at ?? $activeSubscription->created_at;
        if ($activeSubscription->ends_at) {
            $periodLength = $activeSubscription->created_at->diffInDays($activeSubscription->ends_at);
            $periodStart = $activeSubscription->ends_at->subDays($periodLength);
        }
        
        // Count claims made in the current billing period
        $claimsInCurrentPeriod = $this->claims()
            ->where('created_at', '>=', $periodStart)
            ->count();
        
        // Check if user has reached their limit
        if ($claimsInCurrentPeriod >= $activeSubscription->plan->claims_limit) {
            return [
                'can_create' => false,
                'message' => 'You have reached your claims limit for this billing period. Please upgrade your plan or wait until your next billing cycle.'
            ];
        }
        
        return [
            'can_create' => true,
            'message' => ''
        ];
    }

    public function role()
    {
        return $this->belongsTo('App\Models\Role');
    }

    public function IsSuper(){
        if ($this->id == 1) {
           return true;
        }
        return false;
    }

    public function sectionCheck($value)
    {
        if (!$this->role || !$this->role->section) {
            return false;
        }

        $sections = explode(" , ", $this->role->section);
        return in_array($value, $sections);
    }

    /**
     * Get user notifications
     */
    public function notifications()
    {
        return $this->hasMany(\App\Models\Notification::class);
    }

    /**
     * Get unread notifications
     */
    public function unreadNotifications()
    {
        return $this->notifications()->where('is_read', false);
    }
}
