<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConferenceController;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\Auth\ForgotPasswordController;
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
Route::middleware('auth:sanctum')->post('/update-profile', [ApiController::class, 'updateProfile']);
Route::middleware('auth:sanctum')->post('/update-password', [ApiController::class, 'updatePassword']);
// Property Routes
Route::middleware('auth:sanctum')->get('/get-properties', [ApiController::class, 'getProperties']);
Route::middleware('auth:sanctum')->get('/get-property/{id}', [ApiController::class, 'getProperty']);
Route::middleware('auth:sanctum')->post('/add-property', [ApiController::class, 'addProperty']);
Route::middleware('auth:sanctum')->post('/update-property/{id}', [ApiController::class, 'updateProperty']);
// Service 
Route::middleware('auth:sanctum')->post('/get-services', [ApiController::class, 'getServices']);
// Route::middleware('auth:sanctum')->get('/all-services', [ApiController::class, 'allServices']);
Route::middleware('auth:sanctum')->post('/add-service', [ApiController::class, 'storeService']);
Route::middleware('auth:sanctum')->post('/update-service/{id}',  [ApiController::class, 'updateServices']);
Route::middleware('auth:sanctum')->get('/get-service/{id}', [ApiController::class, 'getService']);
Route::middleware('auth:sanctum')->delete('/destroy-service/{id}',  [ApiController::class, 'destroyService']);

// 15 Feb 2024 

Route::middleware('auth:sanctum')->post('/add-fav-provider', [ApiController::class, 'addFavouriteProvider']);
Route::middleware('auth:sanctum')->post('/add-fav-property', [ApiController::class, 'addFavouriteProperty']);
Route::middleware('auth:sanctum')->post('/get-favourite', [ApiController::class, 'getFavourite']);
Route::middleware('auth:sanctum')->post('/all-serviceprovider', [ApiController::class, 'getServiceProviders']);
Route::middleware('auth:sanctum')->get('/get-serviceprovider/{id}', [ApiController::class, 'getServiceProvider']);

//  18 FEB 2024
Route::middleware('auth:sanctum')->get('/get-user-by-id/{id}', [ApiController::class, 'getUserById']);
Route::middleware('auth:sanctum')->get('/get-notification', [ApiController::class, 'getNotificationByUserId']);
Route::middleware('auth:sanctum')->get('/delete-notification/{id}', [ApiController::class, 'destroyNotification']);
Route::middleware('auth:sanctum')->post('/add-service-request', [ApiController::class, 'addServiceRequest']);
Route::middleware('auth:sanctum')->post('/get-service-request', [ApiController::class, 'getServiceRequest']);
Route::middleware('auth:sanctum')->post('/get-user-request', [ApiController::class, 'getUserRequest']);
Route::middleware('auth:sanctum')->get('/get-service-provider-request/{id}', [ApiController::class, 'getServiceProviderRequest']);
Route::middleware('auth:sanctum')->post('/add-service-job', [ApiController::class, 'addServiceJob']);
Route::middleware('auth:sanctum')->post('/get-service-job', [ApiController::class, 'getServiceJob']);

Route::middleware('auth:sanctum')->get('/all-propertytype', [ApiController::class, 'allPropertyType']);
Route::middleware('auth:sanctum')->get('/get-property-sub-type/{id}', [ApiController::class, 'getPropertySubType']);
// 26-02-24
Route::middleware('auth:sanctum')->post('/service-request-decline', [ApiController::class, 'addServiceRequestDecline']);
Route::middleware('auth:sanctum')->post('/mark-service-job-status', [ApiController::class, 'markServiceStatusJob']);
Route::middleware('auth:sanctum')->post('/service-job-by-status', [ApiController::class, 'serviceJobDetailWithStatus']);
Route::middleware('auth:sanctum')->get('/get-service-provider-job', [ApiController::class, 'getServiceProvidersJob']);
Route::middleware('auth:sanctum')->get('/get-job-detail/{id}', [ApiController::class, 'getJobDetails']);
Route::middleware('auth:sanctum')->get('/get-provider-rating', [ApiController::class, 'getProviderReviews']);

// 28-02-24
Route::middleware('auth:sanctum')->post('/make-service-feedback', [ApiController::class, 'markServiceReview']);
Route::middleware('auth:sanctum')->post('/get-service-feedback', [ApiController::class, 'getServiceReview']);
Route::middleware('auth:sanctum')->post('/add-fav-service', [ApiController::class, 'addFavouriteService']);
Route::middleware('auth:sanctum')->get('/delete-property/{id}', [ApiController::class, 'deleteProperty']);
Route::middleware('auth:sanctum')->post('/get-service-favourite', [ApiController::class, 'getServiceFavourite']);

// 03-03-24

Route::middleware('auth:sanctum')->get('/inbox-listing', [ApiController::class, 'inboxListing']);
Route::middleware('auth:sanctum')->post('/chat-messages', [ApiController::class, 'getChatMessages']);
Route::middleware('auth:sanctum')->post('/send-message', [ApiController::class, 'sendMessage']);

// Route::middleware('auth:sanctum')->post('/message', [ApiController::class, 'sendMessage']);
// Route::middleware('auth:sanctum')->post('/get-messages', [ApiController::class, 'getMessages']);
// Route::middleware('auth:sanctum')->get('/get-inbox-listing', [ApiController::class, 'inboxListing']);

// contract
Route::middleware('auth:sanctum')->post('/add-contract', [ApiController::class, 'storeContract']);
Route::middleware('auth:sanctum')->get('/get-contract', [ApiController::class, 'getContract']);
Route::middleware('auth:sanctum')->get('/get-tanent-contract-property', [ApiController::class, 'getTanentContractProperty']);
Route::middleware('auth:sanctum')->get('/get-landlord-contract', [ApiController::class, 'getLandlordContract']);
Route::middleware('auth:sanctum')->post('/mark-contract-status', [ApiController::class, 'markContractStatus']);
Route::middleware('auth:sanctum')->get('/get-contract-detail/{id}', [ApiController::class, 'getContractDetail']);

Route::middleware('auth:sanctum')->get('/approved-contract-property', [ApiController::class, 'approvedContractProperty']);
/////////////////////////  Stat /////////////////////////////
Route::middleware('auth:sanctum')->post('/service-provider-stat', [ApiController::class, 'serviceProviderstat']);
Route::middleware('auth:sanctum')->post('/landlord-stat', [ApiController::class, 'Landlordstat']);
Route::middleware('auth:sanctum')->post('/visitor-stat', [ApiController::class, 'Visitorstat']);
Route::middleware('auth:sanctum')->post('/tenant-stat', [ApiController::class, 'Tenantstat']);

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
