<?php

namespace App\CentralLogics;

use App\Models\User;
use \MailchimpMarketing\ApiClient;

class MailchimpHelper
{
    protected $mailchimp;
    protected $listId;

    public function __construct()
    {
        $this->mailchimp = (new ApiClient())->setConfig([
            'apiKey' => config('services.mailchimp.key'),
            'server' => config('services.mailchimp.server_prefix')
        ]);
    }



    public function getUsersByCategory()
    {
        $filteredUsers = [];

        // 1. All users
        $filteredUsers['allUsers'] = User::user()->active()->get();

        // 2. All subscribed users
        $filteredUsers['subscribedUsers'] = User::user()->active()->whereHas('activeuserSubscriptions')->get();

        // 3. All unsubscribed users
        $filteredUsers['unsubscribedUsers'] = User::user()->active()->whereDoesntHave('activeuserSubscriptions')->get();

        // 4. All subscribed basic-members
        $filteredUsers['basicMembers'] = User::user()->active()->whereHas('activeuserSubscriptions', function ($query) {
            $query->where('subplan_id', '=', 1); // Replace 1 with the ID of the Basic Membership plan.
        })->get();

        // 5. All subscribed essential-members
        $filteredUsers['essentialMembers'] = User::user()->active()->whereHas('activeuserSubscriptions', function ($query) {
            $query->where('subplan_id', '=', 2); // Replace 2 with the ID of the Essential Membership plan.
        })->get();

        // 6. All subscribed premium-members
        $filteredUsers['premiumMembers'] = User::user()->active()->whereHas('activeuserSubscriptions', function ($query) {
            $query->where('subplan_id', '=', 3); // Replace 3 with the ID of the Premium Membership plan.
        })->get();

        // 7. All subscribed svip-members
        $filteredUsers['svipMembers'] = User::user()->active()->whereHas('activeuserSubscriptions', function ($query) {
            $query->where('subplan_id', '=', 4); // Replace 4 with the ID of the SVIP Membership plan.
        })->get();

        // 8. All graduate-preferred users
        $filteredUsers['graduatePreferredUsers'] = User::user()->active()->where('internshipgraduate', '=', 'graduate')->get();

        // 9. All internship-preferred users
        $filteredUsers['internshipPreferredUsers'] = User::user()->active()->where('internshipgraduate', '=', 'internship')->get();

        // 10. All users within one year
        $filteredUsers['usersWithinOneYear'] = User::user()->active()->where('created_at', '>=', now()->subYear())->get();

        return $filteredUsers;
    }


}
