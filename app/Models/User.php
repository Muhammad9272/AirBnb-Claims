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
use Illuminate\Support\Facades\Cookie;
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
        'role_type',
        'referred_by',
        'niche',
        'country_id',
        'oauth_uid',
        'oauth_provider',
        'is_email_verified',
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
   
   
    public function orders()
    {
        return $this->hasMany(Order::class)->where('payment_status','!=','pending');
    }




    public static function handleReferralTracking($user)
    {
        $gs = \App\Models\GeneralSetting::first();
        
        // Check if affiliate system is enabled
        if (!$gs || $gs->is_affiliate != 1) {
            return;
        }

        // Generate unique affiliate code for new user
        do {
            $code = strtoupper(substr(uniqid(), -8));
        } while (self::where('affiliate_code', $code)->exists());
        
        $user->affiliate_code = $code;

        // Get referrer from cookie
        $affiliateCode = Cookie::get('affiliate_code');
        if ($affiliateCode) {
            $referrer = self::where('affiliate_code', $affiliateCode)->first();
            
            // Validate referrer exists and not self-referral
            if ($referrer && $referrer->id !== $user->id) {
                $user->referred_by = $referrer->id;
                
                // Create referral transaction (pending until subscription)
                // \App\Models\ReferralTransaction::create([
                //     'referrer_user_id' => $referrer->id,
                //     'referee_user_id' => $user->id,
                //     'subscription_id' => null,
                //     'credit_amount' => 0,
                //     'status' => 'pending',
                // ]);
            }
            
            // Clear cookie after use
            Cookie::queue(Cookie::forget('affiliate_code'));
        }

        // Initialize wallet if not set
        // if ($user->wallet_balance === null) {
        //     $user->wallet_balance = 0.00;
        // }

        $user->save();
    }

    // Relationships
    public function referralsMade()
    {
        return $this->hasMany(ReferralTransaction::class, 'referrer_user_id');
    }

    public function referralsReceived()
    {
        return $this->hasMany(ReferralTransaction::class, 'referee_user_id');
    }

    public function referredBy()
    {
        return $this->belongsTo(User::class, 'referred_by');
    }

    public function walletTransactions()
    {
        return $this->hasMany(WalletTransaction::class, 'user_id')->orderBy('created_at', 'desc');
    }

     // Get all users who signed up via this user's referral link
    public function referredUsers()
    {
        return $this->hasMany(User::class, 'referred_by');
    }

    // Influencer commission relationships
    public function influencerCommissions()
    {
        return $this->hasMany(InfluencerCommission::class, 'influencer_user_id');
    }

    public function customerCommissions()
    {
        return $this->hasMany(InfluencerCommission::class, 'customer_user_id');
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
            ->orderBy('id','desc' )
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
        $activeSubscription = $this->activeuserSubscriptions()->latest()->first();
        
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
