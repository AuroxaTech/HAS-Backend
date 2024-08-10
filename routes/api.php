<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConferenceController;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Api\ApproveServiceProvide;
use App\Http\Controllers\Api\UpdateServiceProviderRequest;
use App\Http\Controllers\Api\DeleteUserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::middleware('auth:sanctum')->get('/api/profile', function (Request $request) {
    return $request->user();
});
 
// Route::post('/login', ['uses' =>'App\Http\Controllers\HomeController@userLogin','as' => 'login']);
Route::get('/clear-api', function () {
    Artisan::call('optimize:clear');
    return "Api Cleared!";
});

Route::get('/dropdown', ['uses' =>'App\Http\Controllers\ApiController@getDropdownData','as' => 'getdropdowndata']);
Route::post('/register', ['uses' =>'App\Http\Controllers\ApiController@userRegister','as' => 'register']);
Route::get('/login', ['uses' =>'App\Http\Controllers\ApiController@userLogin','as' => 'login']);

Route::post('/all-properties', [ApiController::class, 'allProperties']);
Route::get('/single-properties/{id}', [ApiController::class, 'singleProperty']);
// Update Profile
Route::get('/logout', [ApiController::class, 'logout']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/service-provider/{user}/approve', ApproveServiceProvide::class);
    Route::post('/service-provider/{serviceProviderRequest}/request', UpdateServiceProviderRequest::class);
    
    Route::middleware(['is.approved.sp'])->group(function () {
        Route::post('/update-profile', [ApiController::class, 'updateProfile']);
        Route::post('/update-password', [ApiController::class, 'updatePassword']);
        Route::delete('/user/delete', DeleteUserController::class);

        // Property Routes
        Route::get('/get-properties', [ApiController::class, 'getProperties']);
        Route::get('/get-property/{id}', [ApiController::class, 'getProperty']);
        Route::post('/add-property', [ApiController::class, 'addProperty']);
        Route::post('/update-property/{id}', [ApiController::class, 'updateProperty']);
        // Service 
        Route::post('/get-services', [ApiController::class, 'getServices']);
        // Route::get('/all-services', [ApiController::class, 'allServices']);
        Route::post('/add-service', [ApiController::class, 'storeService']);
        Route::post('/update-service/{id}',  [ApiController::class, 'updateServices']);
        Route::get('/get-service/{id}', [ApiController::class, 'getService']);
        Route::delete('/destroy-service/{id}',  [ApiController::class, 'destroyService']);
    
        // 15 Feb 2024 
    
        Route::post('/add-fav-provider', [ApiController::class, 'addFavouriteProvider']);
        Route::post('/add-fav-property', [ApiController::class, 'addFavouriteProperty']);
        Route::post('/get-favourite', [ApiController::class, 'getFavourite']);
        Route::post('/all-serviceprovider', [ApiController::class, 'getServiceProviders']);
        Route::get('/get-serviceprovider/{id}', [ApiController::class, 'getServiceProvider']);
    
        //  18 FEB 2024
        Route::get('/get-user-by-id/{id}', [ApiController::class, 'getUserById']);
        Route::get('/get-notification', [ApiController::class, 'getNotificationByUserId']);
        Route::get('/delete-notification/{id}', [ApiController::class, 'destroyNotification']);
        Route::post('/add-service-request', [ApiController::class, 'addServiceRequest']);
        Route::post('/get-service-request', [ApiController::class, 'getServiceRequest']);
        Route::post('/get-user-request', [ApiController::class, 'getUserRequest']);
        Route::get('/get-service-provider-request/{id}', [ApiController::class, 'getServiceProviderRequest']);
        Route::post('/add-service-job', [ApiController::class, 'addServiceJob']);
        Route::post('/get-service-job', [ApiController::class, 'getServiceJob']);
    
        Route::get('/all-propertytype', [ApiController::class, 'allPropertyType']);
        Route::get('/get-property-sub-type/{id}', [ApiController::class, 'getPropertySubType']);
        // 26-02-24
        Route::post('/service-request-decline', [ApiController::class, 'addServiceRequestDecline']);
        Route::post('/mark-service-job-status', [ApiController::class, 'markServiceStatusJob']);
        Route::post('/service-job-by-status', [ApiController::class, 'serviceJobDetailWithStatus']);
        Route::get('/get-service-provider-job', [ApiController::class, 'getServiceProvidersJob']);
        Route::get('/get-job-detail/{id}', [ApiController::class, 'getJobDetails']);
        Route::get('/get-provider-rating', [ApiController::class, 'getProviderReviews']);
    
        // 28-02-24
        Route::post('/make-service-feedback', [ApiController::class, 'markServiceReview']);
        Route::post('/get-service-feedback', [ApiController::class, 'getServiceReview']);
        Route::post('/add-fav-service', [ApiController::class, 'addFavouriteService']);
        Route::get('/delete-property/{id}', [ApiController::class, 'deleteProperty']);
        Route::post('/get-service-favourite', [ApiController::class, 'getServiceFavourite']);
    
        // 03-03-24
    
        Route::get('/inbox-listing', [ApiController::class, 'inboxListing']);
        Route::post('/chat-messages', [ApiController::class, 'getChatMessages']);
        Route::post('/send-message', [ApiController::class, 'sendMessage']);
    
        // Route::post('/message', [ApiController::class, 'sendMessage']);
        // Route::post('/get-messages', [ApiController::class, 'getMessages']);
        // Route::get('/get-inbox-listing', [ApiController::class, 'inboxListing']);
    
        // contract
        Route::post('/add-contract', [ApiController::class, 'storeContract']);
        Route::get('/get-contract', [ApiController::class, 'getContract']);
        Route::get('/get-tanent-contract-property', [ApiController::class, 'getTanentContractProperty']);
        Route::get('/get-landlord-contract', [ApiController::class, 'getLandlordContract']);
        Route::post('/mark-contract-status', [ApiController::class, 'markContractStatus']);
        Route::get('/get-contract-detail/{id}', [ApiController::class, 'getContractDetail']);
    
        Route::get('/approved-contract-property', [ApiController::class, 'approvedContractProperty']);
        /////////////////////////  Stat /////////////////////////////
        Route::post('/service-provider-stat', [ApiController::class, 'serviceProviderstat']);
        Route::post('/landlord-stat', [ApiController::class, 'Landlordstat']);
        Route::post('/visitor-stat', [ApiController::class, 'Visitorstat']);
        Route::post('/tenant-stat', [ApiController::class, 'Tenantstat']);
    });
});

// Notifications
Route::post('/send-notification', [ApiController::class, 'sendNotification']);

// Forgot Password
Route::post('forgot-password', [ApiController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('reset-password/{token}', [ApiController::class, 'showResetForm'])->name('password.reset');
Route::post('reset-password', [ApiController::class, 'reset'])->name('password.update');
Route::view('/expired', 'expired')->name('expired');
Route::view('/done', 'done')->name('done');

Route::view('/socket', 'socket')->name('socket');


// /////////////////////////////////////////////////
// /////////////////////////////////////////////////
// /////////////////////////////////////////////////
// ////////// Dashboard Api  ////////////
// /////////////////////////////////////////////////
// /////////////////////////////////////////////////
// // /////////////////////////////////////////////////


// Route::get('/websocket', [WebSocketController::class, 'handleWebSocket']);


Route::post('/websocket', ['uses' =>'App\Http\Controllers\WebSocketController@handleWebSocket','as' => 'handleWebSocket']);


Route::post('/admin-register', ['uses' =>'App\Http\Controllers\DashboardController@adminRegister','as' => 'adminregister']);
Route::get('/get-users/{id}', ['uses' =>'App\Http\Controllers\DashboardController@getUsers']);
Route::get('/get-properties/{id}', ['uses' =>'App\Http\Controllers\DashboardController@getProperties','as' => 'getproperties']);
Route::delete('/delete-user/{id}', ['uses' =>'App\Http\Controllers\DashboardController@destroyUser','as' => 'destroyuser']);
Route::get('/get-all-contracts', ['uses' =>'App\Http\Controllers\DashboardController@getAllContracts','as' => 'getcontracts']);
Route::get('/get-contract/{id}', ['uses' =>'App\Http\Controllers\DashboardController@getContract','as' => 'getcontract']);
Route::delete('/delete-property/{id}', ['uses' =>'App\Http\Controllers\DashboardController@destroyProperty','as' => 'destroyproperty']);
Route::delete('/delete-contract/{id}', ['uses' =>'App\Http\Controllers\DashboardController@destroyContract','as' => 'destroycontract']);
Route::get('/get-tenant-contract/{id}', ['uses' =>'App\Http\Controllers\DashboardController@getTanentContract','as' => 'gettanentcontract']);
Route::get('/get-serviceprovider-service/{id}', ['uses' =>'App\Http\Controllers\DashboardController@getProviderServices','as' => 'getproviderservices']);
