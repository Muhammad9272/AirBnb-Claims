<?php

use App\Http\Controllers\Admin\AdminController;

use App\Http\Controllers\Admin\CouponController;



use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\TicketController as AdminTicketController;


use App\Http\Controllers\Admin\SubFeaturesController;
use App\Http\Controllers\Admin\SubPlanController;

use App\Http\Controllers\Admin\Blog\BlogCategoryController;
use App\Http\Controllers\Admin\Blog\BlogController;

use App\Http\Controllers\User\TicketController as UserTicketController;

// Route::group([
//     'prefix' => 'admin/support/chat',
//     'middleware' => ['auth:admin'],
//     'namespace' => 'App\Http\Controllers\vendor\Chatify',
// ], function () {
//     Route::get('/', [MessagesController::class, 'index'])->name('user');
//     Route::get('/{id}', [MessagesController::class, 'idFetchData'])->name('idFetchData');
// });


// Include Chatify routeser;
use App\Http\Controllers\Front\CartController;
use App\Http\Controllers\Front\CheckoutController;
// use App\Http\Controllers\Vendor\Chatify\MessagesController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;




/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::prefix('management0712')->group(function() {
  //------------ ADMIN LOGIN SECTION ------------

  Route::get('/login',  [LoginController::class, 'showLoginForm'])->name('admin.login');
  Route::post('/login',[LoginController::class, 'login'])->name('admin.login.submit');
  Route::get('/forgot',[LoginController::class, 'showForgotForm'])->name('admin.forgot');
  Route::post('/forgot',[LoginController::class, 'forgot'])->name('admin.forgot.submit');
  Route::post('/logout', [LoginController::class, 'logout'])->name('admin.logout');


  Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');

  Route::get('/profile', [AdminController::class, 'profile'])->name('admin.profile');
  Route::post('/profile/update',[AdminController::class, 'profileupdate'] )->name('admin.profile.update');
  Route::get('/password/',[AdminController::class, 'passwordreset'])->name('admin.password');
  Route::post('/password/update',[AdminController::class, 'changepass'])->name('admin.password.update');


  Route::group(['middleware'=>'permissions:social_settings'],function(){
   // Vendor Social
    Route::get('/social', [AdminController::class, 'social'])->name('admin.social');
    Route::post('/social/update',[AdminController::class, 'socialupdate'])->name('admin.social.update');
  });

  Route::group(['middleware'=>'permissions:general_settings'],function(){
    Route::get('/generalsettings',[AdminController::class, 'generalsettings'])->name('admin.generalsettings');
    Route::post('/generalsettings',[AdminController::class, 'generalsettingsupdate'])->name('admin.generalsettings.update');
  });

  // Leads Management Routes
  Route::group(['prefix' => 'leads', 'as' => 'admin.leads.', 'middleware' => 'permissions:leads_management'], function () {
    Route::get('/', [App\Http\Controllers\Admin\LeadsController::class, 'index'])->name('index');
    Route::post('/{lead}/status', [App\Http\Controllers\Admin\LeadsController::class, 'updateStatus'])->name('update.status');
    Route::get('/export', [App\Http\Controllers\Admin\LeadsController::class, 'export'])->name('export');
    Route::delete('/{lead}', [App\Http\Controllers\Admin\LeadsController::class, 'destroy'])->name('destroy');
  });

  // Influencer Management Routes
  Route::group(['prefix' => 'influencers', 'as' => 'admin.influencers.', 'middleware' => 'permissions:influencers'], function () {
    Route::get('/', [App\Http\Controllers\Admin\InfluencerController::class, 'index'])->name('index');
    Route::get('/create', [App\Http\Controllers\Admin\InfluencerController::class, 'create'])->name('create');
    Route::post('/', [App\Http\Controllers\Admin\InfluencerController::class, 'store'])->name('store');
    Route::get('/{influencer}', [App\Http\Controllers\Admin\InfluencerController::class, 'show'])->name('show');
    Route::post('/{influencer}/toggle-status', [App\Http\Controllers\Admin\InfluencerController::class, 'updateStatus'])->name('update.status');
    Route::post('/{influencer}/resend-credentials', [App\Http\Controllers\Admin\InfluencerController::class, 'resendCredentials'])->name('resend.credentials');
    Route::delete('/{influencer}', [App\Http\Controllers\Admin\InfluencerController::class, 'destroy'])->name('destroy');
    
    // Commission Management
    Route::post('/commission/{commission}/update-status', [App\Http\Controllers\Admin\InfluencerController::class, 'updateCommissionStatus'])->name('commission.update.status');
    Route::post('/commission/{commission}/mark-paid', [App\Http\Controllers\Admin\InfluencerController::class, 'markCommissionPaid'])->name('commission.mark.paid');
  });

  // Secret Login for Influencers (Super Admin Only)
  Route::get('/influencers/secret/login/{id}', [App\Http\Controllers\Admin\InfluencerController::class, 'secret'])->name('admin.influencer.secret');






 Route::group(['prefix' => 'custompage', 'as' => 'admin.custompage.', 'middleware'=>'permissions:custom_page'], function () {
      Route::get('/datatables/',[PageController::class, 'datatables'])->name('datatables');
      Route::get('/',[PageController::class, 'index'])->name('index');
      Route::get('/create/', [PageController::class, 'create'])->name('create');
      Route::post('/create/',[PageController::class, 'store'])->name('store');
      Route::get('/edit/{id}',[PageController::class, 'edit'])->name('edit');
      Route::post('/edit/{id}', [PageController::class, 'update'])->name('update');
      Route::get('/delete/{id}',[PageController::class, 'destroy'])->name('delete');
      Route::get('/status/{id1}/{id2}',[PageController::class, 'status'])->name('status');
  });




  Route::group(['middleware'=>'permissions:support_tickets'],function(){
    Route::get('tickets/datatables', [AdminTicketController::class, 'datatables'])->name('admin.tickets.datatables');
    Route::get('/tickets/datatables', [AdminTicketController::class, 'datatables'])->name('admin.tickets.datatables');
    Route::get('/tickets', [AdminTicketController::class, 'index'])->name('admin.tickets.index');
    Route::get('/tickets/{id}', [AdminTicketController::class, 'show'])->name('admin.tickets.show');
    Route::post('/tickets/{id}/reply', [AdminTicketController::class, 'reply'])->name('admin.tickets.reply');
    Route::post('/tickets/{id}/status', [AdminTicketController::class, 'updateStatus'])->name('admin.tickets.status');
});





  Route::group(['middleware'=>'permissions:orders'],function(){
    //Order Routes
    Route::get('/orders/datatables',[OrderController::class, 'datatables'])->name('admin.orders.datatables');
    Route::get('/orders',[OrderController::class, 'index'])->name('admin.orders.index');
    Route::get('/orders/show/{id}',[OrderController::class, 'show'])->name('admin.orders.show');
    Route::get('/orders/invoice/{id}',[OrderController::class, 'invoice'])->name('admin.orders.invoice');
    Route::get('/orders/download-invoice/{id}',[OrderController::class, 'downloadInvoice'])->name('admin.orders.download-invoice');
    Route::post('/orders/status/',[OrderController::class, 'updateStatus'])->name('admin.orders.update-status');
    Route::get('/orders/delete/{id}',[OrderController::class, 'delete'])->name('admin.orders.delete');
  });  

  Route::group(['middleware'=>'permissions:coupon'],function(){
    //Company Routes
    Route::get('/coupon/datatables',[CouponController::class, 'datatables'])->name('admin.coupon.datatables');
    Route::get('/coupon',[CouponController::class, 'index'])->name('admin.coupon.index');
    Route::get('/coupon/create', [CouponController::class, 'create'])->name('admin.coupon.create');
    Route::post('/coupon/create',[CouponController::class, 'store'])->name('admin.coupon.store');
    Route::get('/coupon/edit/{id}',[CouponController::class, 'edit'])->name('admin.coupon.edit');
    Route::post('/coupon/edit/{id}', [CouponController::class, 'update'])->name('admin.coupon.update');
    Route::get('/coupon/delete/{id}',[CouponController::class, 'destroy'])->name('admin.coupon.delete');
    Route::get('/coupon/status/{id1}/{id2}',[CouponController::class, 'status'])->name('admin.coupon.status');
  });  





  Route::group(['middleware'=>'permissions:subscriptions'],function(){
    Route::get('/subplan/datatables',[SubPlanController::class, 'datatables'])->name('admin.subplan.datatables');
    Route::get('/subplan',[SubPlanController::class, 'index'])->name('admin.subplan.index');
    Route::get('/subplan/create', [SubPlanController::class, 'create'])->name('admin.subplan.create');
    Route::post('/subplan/create',[SubPlanController::class, 'store'])->name('admin.subplan.store');
    Route::get('/subplan/edit/{id}',[SubPlanController::class, 'edit'])->name('admin.subplan.edit');
    Route::post('/subplan/edit/{id}', [SubPlanController::class, 'update'])->name('admin.subplan.update');
    Route::get('/subplan/delete/{id}',[SubPlanController::class, 'destroy'])->name('admin.subplan.delete');
    Route::get('/subplan/status/{id1}/{id2}',[SubPlanController::class, 'status'])->name('admin.subplan.status');

    // Stripe Webhook Configuration
    Route::post('/configure-webhook', [SubPlanController::class, 'configureWebhook'])->name('admin.stripe.webhook.configure');
    Route::post('/test-webhook', [SubPlanController::class, 'testWebhookEndpoint'])->name('admin.stripe.webhook.test');

    Route::get('/subfeatures/datatables',[SubFeaturesController::class, 'datatables'])->name('admin.subfeature.datatables');
    Route::get('/subfeature',[SubFeaturesController::class, 'index'])->name('admin.subfeature.index');
    Route::get('/subfeatures/create', [SubFeaturesController::class, 'create'])->name('admin.subfeature.create');
    Route::post('/subfeatures/create',[SubFeaturesController::class, 'store'])->name('admin.subfeature.store');
    Route::get('/subfeatures/edit/{id}',[SubFeaturesController::class, 'edit'])->name('admin.subfeature.edit');
    Route::post('/subfeatures/edit/{id}', [SubFeaturesController::class, 'update'])->name('admin.subfeature.update');
    Route::get('/subfeatures/delete/{id}',[SubFeaturesController::class, 'destroy'])->name('admin.subfeature.delete');
    Route::get('/subfeatures/status/{id1}/{id2}',[SubFeaturesController::class, 'status'])->name('admin.subfeature.status');

    // Stripe Webhook Management
    Route::prefix('stripe/webhooks')->name('admin.stripe.webhooks.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\StripeWebhookManagerController::class, 'index'])->name('index');
        Route::get('/datatables', [App\Http\Controllers\Admin\StripeWebhookManagerController::class, 'datatables'])->name('datatables');
        Route::get('/create', [App\Http\Controllers\Admin\StripeWebhookManagerController::class, 'create'])->name('create');
        Route::post('/store', [App\Http\Controllers\Admin\StripeWebhookManagerController::class, 'store'])->name('store');
        Route::get('/{id}', [App\Http\Controllers\Admin\StripeWebhookManagerController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [App\Http\Controllers\Admin\StripeWebhookManagerController::class, 'edit'])->name('edit');
        Route::post('/{id}/update', [App\Http\Controllers\Admin\StripeWebhookManagerController::class, 'update'])->name('update');
        Route::post('/{id}/toggle-status', [App\Http\Controllers\Admin\StripeWebhookManagerController::class, 'toggleStatus'])->name('toggle-status');
        Route::post('/{id}/test', [App\Http\Controllers\Admin\StripeWebhookManagerController::class, 'testWebhook'])->name('test');
        Route::delete('/{id}/destroy', [App\Http\Controllers\Admin\StripeWebhookManagerController::class, 'destroy'])->name('destroy');
    });

    // Subscription Transactions
    Route::get('/subscriptions/transactions', [App\Http\Controllers\Admin\SubscriptionController::class, 'transactions'])
        ->name('admin.subscriptions.transactions');
    Route::get('/subscriptions/transactions/datatables', [App\Http\Controllers\Admin\SubscriptionController::class, 'transactionsDatatables'])
        ->name('admin.subscriptions.transactions.datatables');
    Route::get('/subscriptions/transactions/export', [App\Http\Controllers\Admin\SubscriptionController::class, 'exportTransactions'])
        ->name('admin.subscriptions.transactions.export');
    Route::get('/subscriptions/{id}/detail', [App\Http\Controllers\Admin\SubscriptionController::class, 'subscriptionDetail'])
        ->name('admin.subscriptions.detail');
  });

 


  
  Route::group(['middleware'=>'permissions:users'],function(){
    Route::get('/users/datatables',[App\Http\Controllers\Admin\UserController::class, 'usersDataTables'])->name('admin.users.datatables');
    Route::get('/users',[App\Http\Controllers\Admin\UserController::class, 'users'])->name('admin.users.index');

    Route::get('/users/show/{id}',[App\Http\Controllers\Admin\UserController::class, 'show'])->name('admin.users.show');

    Route::post('/users/update/{id}',[App\Http\Controllers\Admin\UserController::class, 'update'])->name('admin.users.update'); 

    Route::post('/users/update/membership/{id}',[App\Http\Controllers\Admin\UserController::class, 'updateMembership'])->name('admin.users.membership.update'); 

     Route::get('/users/status/{id1}/{id2}',[App\Http\Controllers\Admin\UserController::class, 'status'])->name('admin.user.status');
     
     //Email Campaign
     Route::get('/users/email/campaign',[App\Http\Controllers\Admin\UserController::class, 'emailCampaign'])->name('admin.users.email.campaign');
      Route::post('/users/email/campaign',[App\Http\Controllers\Admin\UserController::class, 'sendCampaignEmail'])->name('admin.users.email.campaign.submit');
    
    Route::get('/users/secret/login/{id}',[App\Http\Controllers\Admin\UserController::class, 'secret'])->name('admin.user.secret');
    
    // Route::get('/secret/{email}',[App\Http\Controllers\Admin\UserController::class, 'secretlogin'])->name('admin.users.secretlogin');

    Route::get('/users/delete/{id}',[App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('admin.users.destroy');

    Route::get('/subscribed/users/datatables',[App\Http\Controllers\Admin\UserController::class, 'subscribedusersDataTables'])->name('admin.users.subscribed.datatables');
    Route::get('/subscribed/users',[App\Http\Controllers\Admin\UserController::class, 'subscribedusers'])->name('admin.users.subscribed.index');
  });


  Route::group(['middleware' => 'permissions:nostalgia'], function () {
    Route::prefix('nostalgia')->name('admin.nostalgia.')->group(function () {
        // Category Routes
        Route::get('/categories/datatables', [NostalgiaCategoryController::class, 'datatables'])->name('category.datatables');
        Route::get('/categories', [NostalgiaCategoryController::class, 'index'])->name('category.index');
        Route::get('/category/create', [NostalgiaCategoryController::class, 'create'])->name('category.create');
        Route::post('/category/store', [NostalgiaCategoryController::class, 'store'])->name('category.store');
        Route::get('/category/edit/{id}', [NostalgiaCategoryController::class, 'edit'])->name('category.edit');
        Route::post('/category/update/{id}', [NostalgiaCategoryController::class, 'update'])->name('category.update');
        Route::get('/category/delete/{id}', [NostalgiaCategoryController::class, 'destroy'])->name('category.delete');
        Route::get('/category/status/{id1}/{id2}', [NostalgiaCategoryController::class, 'status'])->name('category.status');
        Route::get('/category/parents', [NostalgiaCategoryController::class, 'getParentCategories'])->name('category.parents');

        // Item Routes
        Route::get('/items/datatables', [NostalgiaItemController::class, 'datatables'])->name('item.datatables');
        Route::get('/items', [NostalgiaItemController::class, 'index'])->name('item.index');
        Route::get('/item/create', [NostalgiaItemController::class, 'create'])->name('item.create');
        Route::post('/item/store', [NostalgiaItemController::class, 'store'])->name('item.store');
        Route::get('/item/edit/{id}', [NostalgiaItemController::class, 'edit'])->name('item.edit');
        Route::post('/item/update/{id}', [NostalgiaItemController::class, 'update'])->name('item.update');
        Route::get('/item/delete/{id}', [NostalgiaItemController::class, 'destroy'])->name('item.delete');
        Route::get('/item/status/{id1}/{id2}', [NostalgiaItemController::class, 'status'])->name('item.status');
        Route::get('/get-subcategories/{category_id}', [NostalgiaItemController::class, 'getSubcategories'])->name('item.subcategories');
        Route::get('/get-childcategories/{subcategory_id}', [NostalgiaItemController::class, 'getChildcategories'])->name('item.childcategories');
    });
  });

  Route::group(['middleware' => 'permissions:services'], function () {
    // Service Category Routes
    Route::prefix('service')->name('admin.service.')->group(function () {
        // Category Routes
        Route::get('/categories/datatables', [ServiceCategoryController::class, 'datatables'])->name('category.datatables');
        Route::get('/categories', [ServiceCategoryController::class, 'index'])->name('category.index');
        Route::get('/category/create', [ServiceCategoryController::class, 'create'])->name('category.create');
        Route::post('/category/store', [ServiceCategoryController::class, 'store'])->name('category.store');
        Route::get('/category/edit/{id}', [ServiceCategoryController::class, 'edit'])->name('category.edit');
        Route::post('/category/update/{id}', [ServiceCategoryController::class, 'update'])->name('category.update');
        Route::get('/category/delete/{id}', [ServiceCategoryController::class, 'destroy'])->name('category.delete');
        Route::get('/category/status/{id1}/{id2}', [ServiceCategoryController::class, 'status'])->name('category.status');

        // Service Item Routes
        Route::get('/items/datatables', [ServiceItemController::class, 'datatables'])->name('item.datatables');
        Route::get('/items', [ServiceItemController::class, 'index'])->name('item.index');
        Route::get('/item/create', [ServiceItemController::class, 'create'])->name('item.create');
        Route::post('/item/store', [ServiceItemController::class, 'store'])->name('item.store');
        Route::get('/item/edit/{id}', [ServiceItemController::class, 'edit'])->name('item.edit');
        Route::post('/item/update/{id}', [ServiceItemController::class, 'update'])->name('item.update');
        Route::get('/item/delete/{id}', [ServiceItemController::class, 'destroy'])->name('item.delete');
        Route::get('/item/status/{id1}/{id2}', [ServiceItemController::class, 'status'])->name('item.status');
    });
  });

  Route::group(['middleware' => 'permissions:blogs'], function () {
    Route::prefix('blog')->name('admin.blog.')->group(function () {
        // Blog Category Routes
        Route::get('/categories/datatables', [BlogCategoryController::class, 'datatables'])->name('category.datatables');
        Route::get('/categories', [BlogCategoryController::class, 'index'])->name('category.index');
        Route::get('/category/create', [BlogCategoryController::class, 'create'])->name('category.create');
        Route::post('/category/store', [BlogCategoryController::class, 'store'])->name('category.store');
        Route::get('/category/edit/{id}', [BlogCategoryController::class, 'edit'])->name('category.edit');
        Route::post('/category/update/{id}', [BlogCategoryController::class, 'update'])->name('category.update');
        Route::get('/category/delete/{id}', [BlogCategoryController::class, 'destroy'])->name('category.delete');
        Route::get('/category/status/{id1}/{id2}', [BlogCategoryController::class, 'status'])->name('category.status');

        // Blog Post Routes
        Route::get('/posts/datatables', [BlogController::class, 'datatables'])->name('datatables');
        Route::get('/posts', [BlogController::class, 'index'])->name('index');
        Route::get('/post/create', [BlogController::class, 'create'])->name('create');
        Route::post('/post/store', [BlogController::class, 'store'])->name('store');
        Route::get('/post/edit/{id}', [BlogController::class, 'edit'])->name('edit');
        Route::post('/post/update/{id}', [BlogController::class, 'update'])->name('update');
        Route::get('/post/delete/{id}', [BlogController::class, 'destroy'])->name('delete');
        Route::get('/post/status/{id1}/{id2}', [BlogController::class, 'status'])->name('status');
    });
  });




  // Admin Claims Management Routes
  Route::group(['prefix' => 'claims', 'as' => 'admin.claims.', 'middleware' => 'permissions:claims_management'], function() {
      Route::get('/', [App\Http\Controllers\Admin\ClaimManagementController::class, 'index'])->name('index');
      Route::get('/datatables', [App\Http\Controllers\Admin\ClaimManagementController::class, 'datatables'])->name('datatables');
      Route::get('/{id}', [App\Http\Controllers\Admin\ClaimManagementController::class, 'show'])->name('show');
      Route::post('/{id}/status', [App\Http\Controllers\Admin\ClaimManagementController::class, 'updateStatus'])->name('update-status');
      Route::post('/{id}/comment', [App\Http\Controllers\Admin\ClaimManagementController::class, 'addComment'])->name('comment');
      Route::get('/user/{userId}', [App\Http\Controllers\Admin\ClaimManagementController::class, 'userClaims'])->name('user-claims');
      Route::get('/export', [App\Http\Controllers\Admin\ClaimManagementController::class, 'exportClaims'])->name('export');
      Route::get('/reports', [App\Http\Controllers\Admin\ClaimManagementController::class, 'reports'])->name('reports');
  });

  // Admin Notifications
  Route::get('/notifications', [App\Http\Controllers\Admin\NotificationController::class, 'index'])->name('admin.notifications.index');
  Route::post('/notifications/{id}/read', [App\Http\Controllers\Admin\NotificationController::class, 'markAsRead'])->name('admin.notifications.mark-read');
  Route::post('/notifications/mark-all-read', [App\Http\Controllers\Admin\NotificationController::class, 'markAllAsRead'])->name('admin.notifications.mark-all-read');
  Route::delete('/notifications/{id}', [App\Http\Controllers\Admin\NotificationController::class, 'delete'])->name('admin.notifications.delete');
  Route::delete('/notifications', [App\Http\Controllers\Admin\NotificationController::class, 'deleteAll'])->name('admin.notifications.delete-all');
  Route::get('/notifications/unread-count', [App\Http\Controllers\Admin\NotificationController::class, 'getUnreadCount'])->name('admin.notifications.unread-count');


    // ROLE SECTION
  Route::group(['middleware'=>'permissions:super'],function(){

    Route::get('/cache/clear', function() {
      Artisan::call('cache:clear');
      Artisan::call('config:clear');
      Artisan::call('route:clear');
      Artisan::call('view:clear');
      return redirect()->route('admin.dashboard')->with('info','System Cache Has Been Removed.');
    })->name('admin.cache.clear');

    Route::get('/role/datatables',[App\Http\Controllers\Admin\RoleController::class, 'datatables'])->name('admin.role.datatables');
    Route::get('/role',[App\Http\Controllers\Admin\RoleController::class, 'index'])->name('admin.role.index');
    Route::get('/role/create',[App\Http\Controllers\Admin\RoleController::class, 'create'] )->name('admin.role.create');
    Route::post('/role/create',[App\Http\Controllers\Admin\RoleController::class, 'store'])->name('admin.role.store');
    Route::get('/role/edit/{id}',[App\Http\Controllers\Admin\RoleController::class, 'edit'])->name('admin.role.edit');
    Route::post('/role/edit/{id}',[App\Http\Controllers\Admin\RoleController::class, 'update'])->name('admin.role.update');
    Route::get('/role/delete/{id}',[App\Http\Controllers\Admin\RoleController::class, 'destroy'])->name('admin.role.delete');
  });

    //ADMIN STAFF SECTION 

  Route::group(['middleware'=>'permissions:manage_staffs'],function(){
    Route::get('/staff/datatables',[App\Http\Controllers\Admin\StaffController::class, 'datatables'])->name('admin.staff.datatables');
    Route::get('/staff',[App\Http\Controllers\Admin\StaffController::class, 'index'])->name('admin.staff.index');
    Route::get('/staff/create',[App\Http\Controllers\Admin\StaffController::class, 'create'])->name('admin.staff.create');
    Route::post('/staff/create',[App\Http\Controllers\Admin\StaffController::class, 'store'])->name('admin.staff.store');
    Route::get('/staff/edit/{id}', [App\Http\Controllers\Admin\StaffController::class, 'edit'])->name('admin.staff.edit');
    Route::post('/staff/update/{id}',[App\Http\Controllers\Admin\StaffController::class, 'update'])->name('admin.staff.update');
    Route::get('/staff/show/{id}', [App\Http\Controllers\Admin\StaffController::class, 'show'])->name('admin.staff.show');
    Route::get('/staff/delete/{id}', [App\Http\Controllers\Admin\StaffController::class, 'destroy'])->name('admin.staff.delete');
  });


  //LiveChat Routes
  Route::group(['middleware'=>'permissions:live_chat'],function(){
     // $liveChatRoute = 'admin.live.chat';
     Route::get('/tawk/chat', [AdminController::class, 'tawk'])->name('admin.live.chat.tawk');
     Route::get('/support/chat', [MessagesController::class, 'index'])->name('admin.live.chat');
     // include __DIR__.'/chatify.php';      
  });

  // Affiliate & Lead Management Routes

 

});







// User Auth Routes
Route::group(['as' => 'user.'], function () {
  Route::get('/login',[App\Http\Controllers\User\LoginController::class, 'showLoginForm'])->name('login');
  Route::post('/login', [App\Http\Controllers\User\LoginController::class, 'login'])->name('login.submit');
  Route::post('/logout', [App\Http\Controllers\User\LoginController::class, 'logout'])->name('logout');

  Route::get('/register',[App\Http\Controllers\User\RegisterController::class, 'showRegisterForm'])->name('register');
  Route::post('/register',[App\Http\Controllers\User\RegisterController::class, 'register'])->name('register.submit');

  Route::get('/forgot',[App\Http\Controllers\User\ForgotController::class, 'showForgotForm'])->name('forgot');
  Route::post('/forgot',[App\Http\Controllers\User\ForgotController::class, 'forgot'])->name('forgot.submit');
  Route::get('/reset-password/{token}',[App\Http\Controllers\User\ForgotController::class, 'getPassword'])->name('password.reset');
  Route::post('/reset-password',[App\Http\Controllers\User\ForgotController::class, 'updatePassword'])->name('password.reset.update');

   // Route::get('/verify/email/',[App\Http\Controllers\User\LoginController::class, 'verifyEmail'])->name('verify');
  Route::post('/verify/email/',[App\Http\Controllers\User\LoginController::class, 'authenticationToken'])->name('verify.email');
  Route::get('resend/verify/email/{email}',[App\Http\Controllers\User\LoginController::class, 'newAuthenticationToken'])->name('resend.verify');
});
// User Auth Routes ends

// Social Login
Route::group(['middleware' => 'guest'], function() {
    Route::get('oauth/{provider}',[App\Http\Controllers\User\Auth\SocialAuthController::class, 'redirect'])->where('provider', '(facebook|google|twitter|discord)$');
    Route::get('oauth/{provider}/callback',[App\Http\Controllers\User\Auth\SocialAuthController::class, 'callback'])->where('provider', '(facebook|google|twitter|discord)$');
});//<--- End Group guest





// Subscription Plans Route
Route::get('/subscription/plans', [App\Http\Controllers\Front\SubscriptionController::class, 'plans'])
    ->name('user.subscription.plans');

// Subscription Checkout Routes
Route::middleware(['auth'])->group(function () {
    // Display the subscription checkout page
    Route::get('/subscription/checkout/{slug}', [App\Http\Controllers\User\SubscriptionController::class, 'showCheckout'])
         ->name('subscription.checkout.show');

    Route::post('/subscription/validate-discount', [App\Http\Controllers\User\SubscriptionController::class, 'validateDiscount'])
        ->name('subscription.validate.discount');

    // Process the subscription payment
    Route::post('/subscription/process', [App\Http\Controllers\User\SubscriptionController::class, 'processSubscription'])
         ->name('subscription.process.payment');
    
    // Subscription success page
    Route::get('/subscription/success', [App\Http\Controllers\User\SubscriptionController::class, 'success'])
         ->name('subscription.success');
         
    // Subscription cancel page
    Route::get('/subscription/cancel', [App\Http\Controllers\User\SubscriptionController::class, 'cancel'])
         ->name('subscription.cancel');
         
    // Subscription transactions history
    Route::get('/subscription/transactions', [App\Http\Controllers\User\SubscriptionController::class, 'transactions'])
         ->name('subscription.transactions');
});

// Referral & Wallet Routes
Route::middleware(['auth'])->prefix('affiliate')->name('user.affiliate.')->group(function () {
    Route::get('/', [App\Http\Controllers\User\ReferralController::class, 'index'])->name('index');
    Route::get('/wallet', [App\Http\Controllers\User\ReferralController::class, 'wallet'])->name('wallet');
});

// Influencer Dashboard Route
Route::middleware(['auth'])->prefix('influencer')->name('user.influencer.')->group(function () {
    Route::get('/', [App\Http\Controllers\User\InfluencerController::class, 'index'])->name('index');
});

// Stripe Webhook
Route::post('/stripe/webhook', [App\Http\Controllers\Webhooks\StripeWebhookController::class, 'handleWebhook'])
    ->name('stripe.webhook');
    
// Debug route for testing webhook accessibility
Route::get('/stripe/webhook-test', function() {
    return response()->json([
        'status' => 'success',
        'message' => 'Webhook endpoint is accessible',
        'timestamp' => now()->toIso8601String()
    ]);
})->name('stripe.webhook.test');

Route::group(['prefix' => 'user', 'middleware' => ['auth'] ], function() {
    Route::get('/dashboard', [App\Http\Controllers\User\DashboardController::class, 'index'])->name('user.dashboard');
    
    // User Profile
    Route::get('/profile',[App\Http\Controllers\User\DashboardController::class, 'profile'] )->name('user.profile');

    Route::get('/account-settings',[App\Http\Controllers\User\DashboardController::class, 'accountSettings'] )->name('user.account-settings');
    Route::post('/account-settings/update', [App\Http\Controllers\User\DashboardController::class, 'accountSettingsUpdate'])->name('user.account-settings.update');
    
    Route::get('/change-password', [App\Http\Controllers\User\DashboardController::class, 'changePassword'])->name('user.change-password');
    Route::post('/change-password/update', [App\Http\Controllers\User\DashboardController::class, 'changePasswordUpdate'])->name('user.change-password.update');

   


    // User Order Routes
    Route::get('/orders', [App\Http\Controllers\User\OrderController::class, 'index'])->name('user.orders.index');
    Route::get('/orders/{orderNumber}', [App\Http\Controllers\User\OrderController::class, 'show'])->name('user.orders.show');


   

      Route::get('/tickets', [UserTicketController::class, 'index'])->name('user.tickets.index');
      Route::get('/tickets/create', [UserTicketController::class, 'create'])->name('user.tickets.create');
      Route::post('/tickets', [UserTicketController::class, 'store'])->name('user.tickets.store');
      Route::get('/tickets/{id}', [UserTicketController::class, 'show'])->name('user.tickets.show');
      Route::post('/tickets/{id}/reply', [UserTicketController::class, 'reply'])->name('user.tickets.reply');



      // Subscription routes
      Route::prefix('subscription')->name('user.subscription.')->group(function () {
          Route::get('/transactions', [App\Http\Controllers\User\SubscriptionController::class, 'transactions'])->name('transactions');
      });

   
    //Favorite Controller
    Route::post('/favorite/{type}/{id}', [App\Http\Controllers\User\FavoriteController::class, 'favorite']) ->withoutMiddleware(['UserAuthenticated']);;
    Route::post('/unfavorite/{type}/{id}', [App\Http\Controllers\User\FavoriteController::class, 'unfavorite'])->withoutMiddleware(['UserAuthenticated']);

    // Claims Routes
    Route::group(['prefix' => 'claims', 'as' => 'user.claims.'], function() {
        Route::get('/', [App\Http\Controllers\User\ClaimController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\User\ClaimController::class, 'create'])->name('create');
        Route::post('/store', [App\Http\Controllers\User\ClaimController::class, 'store'])->name('store');
        Route::get('/{id}', [App\Http\Controllers\User\ClaimController::class, 'show'])->name('show');
        Route::post('/{id}/comment', [App\Http\Controllers\User\ClaimController::class, 'addComment'])->name('comment');
        Route::post('/{id}/evidence', [App\Http\Controllers\User\ClaimController::class, 'addEvidence'])->name('evidence');
    });

    // Claims Routes
    Route::group([ 'as' => 'user.'], function() {
      // Notifications routes
      Route::get('/notifications', [App\Http\Controllers\User\NotificationController::class, 'index'])->name('notifications');
      Route::post('/notifications/{id}/read', [App\Http\Controllers\User\NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
      Route::post('/notifications/mark-all-read', [App\Http\Controllers\User\NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
      Route::delete('/notifications/{id}', [App\Http\Controllers\User\NotificationController::class, 'delete'])->name('notifications.delete');
      Route::delete('/notifications', [App\Http\Controllers\User\NotificationController::class, 'deleteAll'])->name('notifications.delete-all');
    });
    // LiveChat Routes
    Route::group([], function(){

       // $liveChatRoute = 'admin.live.chat';
       Route::get('/support/chat', [MessagesController::class, 'index'])->name('user.live.chat');           
       // $liveChatRoute = 'user.live.chat';
       // include __DIR__.'/chatify.php';      
    });

 
    

});




Route::get('/', [App\Http\Controllers\Front\HomeController::class, 'index'])->name('front.index');

// Lead Capture Route
Route::post('/leads/store', [App\Http\Controllers\Front\LeadController::class, 'store'])->name('leads.store');

Route::post('/error/report', [App\Http\Controllers\Front\ErrorReportController::class, 'store'])->name('error.report');
Route::post('dropzone/media',  [App\Http\Controllers\Front\HomeController::class, 'dropzoneStoreMedia'])->name('dropzone.storeMedia');


// Blog Routes
Route::get('/blog', [App\Http\Controllers\Front\BlogController::class, 'index'])->name('front.blog.index');
Route::get('/blog/{slug}', [App\Http\Controllers\Front\BlogController::class, 'show'])->name('front.blog.show');



// Cart Routes
Route::group(['prefix' => 'cart', 'as' => 'front.cart.'], function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/add', [CartController::class, 'add'])->name('add');
    Route::post('/update/{item}', [CartController::class, 'update'])->name('update');
    Route::delete('/remove/{item}', [CartController::class, 'remove'])->name('remove');
    Route::post('/clear', [CartController::class, 'clear'])->name('clear');
});


// Checkout Routes
Route::group(['prefix' => 'checkout', 'as' => 'front.checkout.'], function () {
    Route::get('/', [App\Http\Controllers\Front\CheckoutController::class, 'index'])->name('index');
    Route::post('/process', [App\Http\Controllers\Front\CheckoutController::class, 'process'])->name('process');
    // Route::get('/success/{orderNumber}', [App\Http\Controllers\Front\CheckoutController::class, 'success'])->name('success');
    Route::get('/cancel/{orderNumber}', [App\Http\Controllers\Front\CheckoutController::class, 'cancel'])->name('cancel');
});


// Payment Routes
Route::prefix('payment')->name('front.payment.')->middleware('auth')->group(function () {

  Route::get('/stripe/success/{orderNumber}', [App\Http\Controllers\Front\StripeController::class, 'callback'])->name('stripe.success');
  Route::get('/paypal/success/{orderNumber}', [App\Http\Controllers\Front\PaypalController::class, 'callback'])->name('paypal.success');
    
});

// Auth::routes();
//Language Translation
Route::get('index/{locale}', [App\Http\Controllers\HomeController::class, 'lang']);
// Bit Task Public Ledger - accessible without login


// Route::get('/', [App\Http\Controllers\HomeController::class, 'root'])->name('root');

//Update User Details in profile section
Route::post('/update-profile/{id}', [App\Http\Controllers\HomeController::class, 'updateProfile'])->name('updateProfile');
Route::post('/update-password/{id}', [App\Http\Controllers\HomeController::class, 'updatePassword'])->name('updatePassword');




Route::view('/page-not-found', 'errors.404')->name('front.404');

// Route::get('/support',[App\Http\Controllers\vendor\Chatify\MessagesController::class, 'index']);
include __DIR__.'/chatify.php';  







// Help Center Routes
Route::prefix('help')->name('front.help.')->group(function () {
    Route::get('/', [App\Http\Controllers\Front\HelpController::class, 'index'])->name('overview');
    Route::get('/faqs', [App\Http\Controllers\Front\HelpController::class, 'faqs'])->name('faqs');
    Route::get('/guides', [App\Http\Controllers\Front\HelpController::class, 'guides'])->name('guides');
    Route::get('/terms', [App\Http\Controllers\Front\HelpController::class, 'terms'])->name('terms');
    Route::get('/privacy', [App\Http\Controllers\Front\HelpController::class, 'privacy'])->name('privacy');
    Route::get('/warranty', function () {
        return view('front.help.warranty');
    })->name('warranty');
    
    // Add missing search route
    Route::get('/search', [App\Http\Controllers\Front\HelpController::class, 'search'])->name('search');
});


// Front pages 
Route::get('/about', [App\Http\Controllers\Front\PageController::class, 'about'])->name('front.about');
Route::get('/how-it-works', [App\Http\Controllers\Front\PageController::class, 'howItWorks'])->name('front.how-it-works');
Route::get('/pricing', [App\Http\Controllers\Front\PageController::class, 'pricing'])->name('front.pricing');
Route::get('/contact', [App\Http\Controllers\Front\PageController::class, 'contact'])->name('front.contact');
Route::post('/contact', [App\Http\Controllers\Front\PageController::class, 'submitContactForm'])->name('front.contact.submit');

// Lead Funnel Routes (Public)
Route::post('/leads/store', [App\Http\Controllers\Front\LeadController::class, 'store'])->name('leads.store');


// Route::view('/test/page', 'front.test');
Route::get('{any}', [App\Http\Controllers\Front\HomeController::class, 'page'])->name('front.page');



// Route::group([
//     'prefix' => 'admin/support/chat',
//     'middleware' => ['auth:admin'],
//     'namespace' => 'App\Http\Controllers\vendor\Chatify',
// ], function () {
//     Route::get('/', [MessagesController::class, 'index'])->name('user');
//     Route::get('/{id}', [MessagesController::class, 'idFetchData'])->name('idFetchData');
// });


// Include Chatify routes






