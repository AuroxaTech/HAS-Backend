<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Attached;
use App\Models\User;
use App\Models\Visitor;
use App\Models\Landlord;
use App\Models\Notification;
use App\Models\ServiceProvider;
use App\Models\FavouriteService;
use App\Models\Tenant;
use App\Models\Property;
use App\Models\PropertyType;
use App\Models\PropertySubType;
use App\Models\ServiceReview;
use App\Models\Service;
use App\Models\Contract;
use App\Models\FavouriteProvider;
use App\Models\FavouriteProperty;
use App\Models\ServiceProviderRequest;
use App\Models\ServiceProviderJob;
use App\Models\Message;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Str;
use Image;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Http;
// use App\Events\ChatMessageSent;
use App\Events\MessageSent;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\URL;
use Mail;
use App\Mail\UserMail;

class ApiController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
    }

    public function getDropdownData(Request $request)
    {
        $flag = $request->flag;
        $data = $this->getDataByFlag($flag);
        return response()->json($data);
    }

    public function getDataByFlag($flag)
    {
        switch ($flag) {
            case 'role':
                return [
                    '1' => 'Landlord',
                    '2' => 'Tenant',
                    '3' => 'Service Provider',
                    '4' => 'Visitor'
                ];
            case 'no_of_property':
                return [
                    '1'   => '1',
                    '2'   => '2',
                    '3'   => '3',
                    '4'   => '4',
                    '5'   => '5',
                    '6'   => '6',
                    '7'   => '7',
                    '8'   => '8',
                    '8'   => '8',
                    '9'   => '9',
                    '9'   => '9',
                    '10'   => '10'
                ];
            case 'property_type':
                return [
                    '1'   => 'Commmercial',
                    '2'   => 'Residential'
                ];
            case 'last_status':
                return [
                    '1'   => 'Rent',
                    '2'   => 'Own House'
                ];
            case 'services':
                return [
                    '1'   => 'Electrician',
                    '2'   => 'Plumber'
                ];
            default:
                return [];
        }
    }

    public function userRegister(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fullname' => 'required',
            'email' => 'required|unique:users',
            'username' => 'required|unique:users',
            'password' => 'required|confirmed',
            'role_id' => 'required',
            'phone_number' => 'required',
            'device_token' => 'required',
            'platform' => 'required',
            'address' => 'required|string|max:255',
            'postal_code' => 'required|string|max:25|regex:/^\d{1,25}$/',
        ]);

        if ($validator->fails()) {  
            return response()->json([
                'status'=>false,
                'messages' => $validator->messages()->toArray(), 
                'error' => 'invalid_fields_data'
            ], 202);
        }

        $image_name = '';
        if ($request->file('profileimage')) {
            $profileimage = $request->file('profileimage');
            $image = 'Profile-' . uniqid() . '-' . $profileimage->getClientOriginalName();
            $filePath = public_path('/assets/profile_images');
    
            if (!File::isDirectory($filePath)) {
                File::makeDirectory($filePath, 0777, true, true);
            }
    
            $img = Image::make($profileimage->getRealPath());
            $img->save($filePath . '/' . $image);
            $image_name = $image;
        }
        if ($request->role_id == 1) {
            if ($request->type && $request->city && $request->amount && $request->address && $request->lat && $request->long && $request->area_range && $request->bedroom && $request->bathroom) {
                $bill_image_name = '';
                $comma_separated_names = '';
    
                if ($request->file('electricity_bill')) {
                    $electricity_bill = $request->file('electricity_bill');
                    $billimage = 'ElectricityBill-' . uniqid() . '-' . $electricity_bill->getClientOriginalName();
                    $filePath = public_path('/assets/electricity_bill');
    
                    if (!File::isDirectory($filePath)) {
                        File::makeDirectory($filePath, 0777, true, true);
                    }
    
                    $billimg = Image::make($electricity_bill->getRealPath());
                    $billimg->save($filePath . '/' . $billimage);
                    $bill_image_name = $billimage;
                } else {
                    return response()->json([
                        'status'=>false,
                        'messages' => 'Electricity Bill Required', 
                        'data' => []
                    ], 202);
                    // return response()->json(['message' => 'Electricity Bill Required'], 400);
                }
    
                if ($request->hasFile('property_images')) {
                    $files = $request->file('property_images');
                    $file_names = [];
    
                    foreach ($files as $file) {
                        $file_image = 'File-' . uniqid() . '-' . $file->getClientOriginalName();
                        $filefilePath = public_path('/assets/property_images');
    
                        if (!File::isDirectory($filefilePath)) {
                            File::makeDirectory($filefilePath, 0777, true, true);
                        }
    
                        $fileimg = Image::make($file->getRealPath());
                        $fileimg->save($filefilePath . '/' . $file_image);
                        $file_names[] = $file_image;
                    }
    
                    $comma_separated_names = implode(',', $file_names);
                } else {
                    return response()->json([
                        'status'=>false,
                        'messages' => 'Property Image Required', 
                        'data' => []
                    ], 202);
                    // return response()->json(['message' => 'Property Image Required'], 400);
                }
    
                $user = User::create([
                    'fullname' => $request->fullname,
                    'email' => $request->email,
                    'username' => $request->username,
                    'phone_number' => $request->phone_number,
                    'password' => Hash::make($request->password),
                    'role_id' => $request->role_id,
                    'profileimage' => $image_name,
                    'platform' => $request->platform,
                    'device_token' => $request->device_token,
                    'address' => $request->address,
                    'postal_code' => $request->postal_code,
                ]);
    
                Landlord::create([
                    'user_id' => $user->id,
                    'no_of_property' => $request->no_of_property,
                    'availability_start_time' => $request->availability_start_time,
                    'availability_end_time' => $request->availability_end_time
                ]);
    
                Property::create([
                    'user_id' => $user->id,
                    'type' => $request->type,
                    'city' => $request->city,
                    'amount' => $request->amount,
                    'address' => $request->address,
                    'lat' => $request->lat,
                    'long' => $request->long,
                    'area_range' => $request->area_range,
                    'bedroom' => $request->bedroom,
                    'bathroom' => $request->bathroom,
                    'description' => $request->description,
                    'images' => $comma_separated_names,
                    'electricity_bill' => $bill_image_name,
                    'property_type' => $request->property_type,
                    'property_sub_type' => $request->property_sub_type,
                ]);
            } else {
                return response()->json([
                    'status'=>false,
                    'messages' => 'Fill Required Fields', 
                    'data' => []
                ], 202);
                // return response()->json(['message' => 'Required'], 400);
            }
        } elseif ($request->role_id == 2) {
            if ($request->last_status == 1) {
                // if ($request->last_landlord_name && $request->last_tenancy && $request->last_landlord_contact && $request->occupation && $request->leased_duration && $request->no_of_occupants) {
                if ($request->occupation && $request->leased_duration && $request->no_of_occupants) {
                    $user = User::create([
                        'fullname' => $request->fullname,
                        'email' => $request->email,
                        'username' => $request->username,
                        'phone_number' => $request->phone_number,
                        'password' => Hash::make($request->password),
                        'role_id' => $request->role_id,
                        'profileimage' => $image_name,
                        'platform' => $request->platform,
                        'device_token' => $request->device_token,
                        'address' => $request->address,
                        'postal_code' => $request->postal_code,
                    ]);
    
                    Tenant::create([
                        'user_id' => $user->id,
                        'last_status' => $request->last_status,
                        'last_tenancy' => $request->last_tenancy,
                        'last_landlord_name' => $request->last_landlord_name,
                        'last_landlord_contact' => $request->last_landlord_contact,
                        'occupation' => $request->occupation,
                        'leased_duration' => $request->leased_duration,
                        'no_of_occupants' => $request->no_of_occupants,
                    ]);
                } else {
                    return response()->json([
                        'status'=>false,
                        'messages' => 'Fill Required Fields', 
                        'data' => []
                    ], 202);
                }
            } else {
                if ($request->occupation && $request->leased_duration && $request->no_of_occupants) {
                    $user = User::create([
                        'fullname' => $request->fullname,
                        'email' => $request->email,
                        'username' => $request->username,
                        'phone_number' => $request->phone_number,
                        'password' => Hash::make($request->password),
                        'role_id' => $request->role_id,
                        'profileimage' => $image_name,
                        'platform' => $request->platform,
                        'device_token' => $request->device_token
                    ]);
    
                    Tenant::create([
                        'user_id' => $user->id,
                        'last_status' => $request->last_status,
                        'occupation' => $request->occupation,
                        'leased_duration' => $request->leased_duration,
                        'no_of_occupants' => $request->no_of_occupants,
                    ]);
                } else {
                    return response()->json([
                        'status'=>false,
                        'messages' => 'Fill Required Fields', 
                        'data' => []
                    ], 202);
                }
            }
        } elseif ($request->role_id == 3) {
            if ($request->services && $request->year_experience && $request->availability_start_time && $request->availability_end_time && $request->certification) {
                if ($request->certification == 'yes') {
                    if ($request->file('file') && $request->file('cnic_front') && $request->file('cnic_back')) {
                        // Certification
                        $certificationfile = $request->file('file');
                        $certificationfileimage = 'File-' . uniqid() . '-' . $certificationfile->getClientOriginalName();
                        $certificationfilefilePath = public_path('/assets/certification_images');

                        if (!File::isDirectory($certificationfilefilePath)) {
                            File::makeDirectory($certificationfilefilePath, 0777, true, true);
                        }

                        $certificationfileimg = Image::make($certificationfile->getRealPath()); // Corrected $file to $certificationfile
                        $certificationfileimg->save($certificationfilefilePath . '/' . $certificationfileimage);
                        $certificationfile_name = $certificationfileimage;

                        // Cnic Front
                        $cnic_frontfile = $request->file('cnic_front');
                        $cnic_frontfileimage = 'File-' . uniqid() . '-' . $cnic_frontfile->getClientOriginalName();
                        $cnic_frontfilefilePath = public_path('/assets/cnic');

                        if (!File::isDirectory($cnic_frontfilefilePath)) {
                            File::makeDirectory($cnic_frontfilefilePath, 0777, true, true);
                        }

                        $cnic_frontfileimg = Image::make($cnic_frontfile->getRealPath()); // Corrected $file to $cnic_frontfile
                        $cnic_frontfileimg->save($cnic_frontfilefilePath . '/' . $cnic_frontfileimage);
                        $cnic_front_file_name = $cnic_frontfileimage;

                        // Cnic Back
                        $cnic_backfile = $request->file('cnic_back');
                        $cnic_backfileimage = 'File-' . uniqid() . '-' . $cnic_backfile->getClientOriginalName();
                        $cnic_backfilefilePath = public_path('/assets/cnic');

                        if (!File::isDirectory($cnic_backfilefilePath)) {
                            File::makeDirectory($cnic_backfilefilePath, 0777, true, true);
                        }

                        $cnic_backfileimg = Image::make($cnic_backfile->getRealPath()); // Corrected $file to $cnic_backfile
                        $cnic_backfileimg->save($cnic_backfilefilePath . '/' . $cnic_backfileimage);
                        $cnic_back_file_name = $cnic_backfileimage;

    
                        $user = User::create([
                            'fullname' => $request->fullname,
                            'email' => $request->email,
                            'username' => $request->username,
                            'phone_number' => $request->phone_number,
                            'password' => Hash::make($request->password),
                            'role_id' => $request->role_id,
                            'profileimage' => $image_name,
                            'platform' => $request->platform,
                            'device_token' => $request->device_token,
                            'address' => $request->address,
                            'postal_code' => $request->postal_code,
                        ]);
    
                        ServiceProvider::create([
                            'user_id' => $user->id,
                            'services' => $request->services,
                            'year_experience' => $request->year_experience,
                            'availability_start_time' => $request->availability_start_time,
                            'availability_end_time' => $request->availability_end_time,
                            'cnic_front' => $cnic_front_file_name,
                            'cnic_back' => $cnic_back_file_name,
                            'certification' => $request->certification,
                            'file' => $certificationfile_name
                        ]);
                    } else {
                        return response()->json([
                            'status'=>false,
                            'messages' => 'Certification File & CNIC is Required', 
                            'data' => []
                        ], 202);
                        // return response()->json(['message' => 'Certification & CNIC is Required'], 400);
                    }
                } else {
                    if ($request->file('cnic_front') && $request->file('cnic_back')) {
                        // Cnic Front
                        $cnic_frontfile = $request->file('cnic_front');
                        $cnic_frontfileimage = 'File-' . uniqid() . '-' . $cnic_frontfile->getClientOriginalName();
                        $cnic_frontfilefilePath = public_path('/assets/cnic');
    
                        if (!File::isDirectory($cnic_frontfilefilePath)) {
                            File::makeDirectory($cnic_frontfilefilePath, 0777, true, true);
                        }
    
                        $cnic_frontfileimg = Image::make($cnic_frontfile->getRealPath());
                        $cnic_frontfileimg->save($cnic_frontfilefilePath . '/' . $cnic_frontfileimage);
                        $cnic_front_file_name = $cnic_frontfileimage;
    
                        // Cnic Back
                        $cnic_backfile = $request->file('cnic_back');
                        $cnic_backfileimage = 'File-' . uniqid() . '-' . $cnic_backfile->getClientOriginalName();
                        $cnic_backfilefilePath = public_path('/assets/cnic');
    
                        if (!File::isDirectory($cnic_backfilefilePath)) {
                            File::makeDirectory($cnic_backfilefilePath, 0777, true, true);
                        }
    
                        $cnic_backfileimg = Image::make($cnic_backfile->getRealPath());
                        $cnic_backfileimg->save($cnic_backfilefilePath . '/' . $cnic_backfileimage);
                        $cnic_back_file_name = $cnic_backfileimage;
    
                        $user = User::create([
                            'fullname' => $request->fullname,
                            'email' => $request->email,
                            'username' => $request->username,
                            'phone_number' => $request->phone_number,
                            'password' => Hash::make($request->password),
                            'role_id' => $request->role_id,
                            'profileimage' => $image_name,
                            'platform' => $request->platform,
                            'device_token' => $request->device_token
                        ]);
    
                        ServiceProvider::create([
                            'user_id' => $user->id,
                            'services' => $request->services,
                            'year_experience' => $request->year_experience,
                            'cnic_front' => $cnic_front_file_name,
                            'cnic_back' => $cnic_back_file_name,
                            'availability_start_time' => $request->availability_start_time,
                            'availability_end_time' => $request->availability_end_time,
                            'certification' => $request->certification
                        ]);
                    } else {
                        return response()->json([
                            'status'=>false,
                            'messages' => 'CNIC is Required', 
                            'data' => []
                        ], 202);
                        // return response()->json(['message' => 'CNIC Required'], 400);
                    }
                }
            } else {
                return response()->json([
                    'status'=>false,
                    'messages' => 'Fill Required Fields', 
                    'data' => []
                ], 202);
            }
        } elseif ($request->role_id == 4) {
            $user = User::create([
                'fullname' => $request->fullname,
                'email' => $request->email,
                'username' => $request->username,
                'phone_number' => $request->phone_number,
                'password' => Hash::make($request->password),
                'role_id' => $request->role_id,
                'profileimage' => $image_name,
                'platform' => $request->platform,
                'device_token' => $request->device_token,
                'address' => $request->address,
                'postal_code' => $request->postal_code,
            ]);
    
            Visitor::create([
                'user_id' => $user->id,
            ]);
        }

        return response()->json([
            'status' => true,
            'messages' => 'Registered Successfully'
        ], 200);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $image_name = '';
        if ($request->file('profileimage')) {
            $previousprofilePath = public_path('/assets/profile_images') . '/' . $user->profileimage;
            if (File::exists($previousprofilePath)) {
                File::delete($previousprofilePath);
            }
            $profileimage = $request->file('profileimage');
            $image = 'Profile-' . uniqid() . '-' . $profileimage->getClientOriginalName();
            $filePath = public_path('/assets/profile_images');
    
            if (!File::isDirectory($filePath)) {
                File::makeDirectory($filePath, 0777, true, true);
            }
    
            $img = Image::make($profileimage->getRealPath());
            $img->save($filePath . '/' . $image);
            $image_name = $image;
        }

        $update_data = array(
            'fullname' => $request->fullname,
            'phone_number' => $request->phone_number,
            'profileimage' => $image_name
        );
        User::whereid($user->id)->update($update_data);

        return response()->json([
            'status'=>true,
            'messages' => 'Profile Updated Successfully...'
        ], 200);
       
    }
    
    public function updatePassword(Request $request)
    {
        $user = Auth::user();
       // Validate the request data, including the password confirmation
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'password' => [
                'required',
                'confirmed',
                function ($attribute, $value, $fail) use ($user) {
                    if (Hash::check($value, $user->password)) {
                        $fail('The new password must not be the same as the current password.');
                    }
                },
            ],
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'status'=>false,
                'messages' => $validator->messages()->toArray(), 
                'error' => 'invalid_fields_data'
            ], 202);
        }
        // Check if the provided current password matches the user's actual password
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'status' => false,
                'error' => 'invalid current password'
            ], 202);
        }



        $update_data = array(
            'password' => Hash::make($request->password)
        );
        User::whereid($user->id)->update($update_data);

        return response()->json([
            'status'=>true,
            'messages' => 'Password Updated Successfully...'
        ], 200);
       
    }

    public function userLogin(Request $request)
    { 
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
            // 'device_token' => 'required',
            // 'platform' => 'required',
        ]);
    
        if ($validator->fails()) {
            // return response()->json(['error' => 'Unauthenticated'], 401);
            return response()->json([ 
                'status'=>false,
                'messages' => 'Unauthenticated', 
                'error' => 'invalid_fields_data or token is invalid'
            ], 202);
        }

        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            if (Auth::check()) {
                $user = Auth::user();
                $token = $user->createToken('authToken')->plainTextToken;
                // echo $user->role_id;
                // exit;
                if($user->role_id  != '5'){
                    if ($request->has('device_token')) {
                        $user->update(['device_token' => $request->device_token,'platform' => $request->platform]);
                    }else{
                        return response()->json([
                            'status' => false,
                            'messages' => 'Device Token & Platform is Required '
                        ], 202);
                    }
                }
                
                $expiryDate = Carbon::now()->subDays(15); 
                Contract::where('status', 0)
                    ->where('created_at', '<', $expiryDate)
                    ->update(['status' => 3]);
                    $option =[
                        'title' => 'Login',
                        'body' => $user->name. 'Sign In With this device',
                        'created_by' => Auth::id(),
                        'created_to' => $user->id,
                    ];
                    $this->sendNotification($option);
                return response()->json([
                    'status' => true,
                    'messages' => 'Login Successfully',
                    'data' => $user,
                    // 'notification' => $notification,
                    'token' => $token
                ], 200);
            }else{
                return response()->json([
                    'status' => false,
                    'messages' => 'Invalid Crediational'
                ], 202);
            }
        }else{
            return response()->json([
                'status' => false,
                'messages' => 'Invalid Crediational'
            ], 202);
            // return response()->json([ 'status'=>false,'message' => 'Invalid Crediational','data' => []], 400);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return response()->json([
            'status' => true,
            'messages' => 'Successfully logged out'
        ], 200);
        // return response()->json(['message' => 'Successfully logged out'], 200);
    }

    public function getProperties(Request $request){

        if (!$request->has('user_id')) {
            return response()->json([
                'status' => false,
                'messages' => 'User Id Required'
            ], 202);
            // return response()->json(['message' => 'UserId Required'], 400);
        }
        
        $userId = Auth::id();
        $properties = Property::with(['user'])->whereuser_id($request->user_id)->orderByDesc('created_at')->paginate(20);
        if ($properties->isEmpty()) {
            return response()->json([
                'status' => true,
                'messages' => 'Properties not found'
            ], 200);
            // return response()->json(['message' => 'Properties not found'], 404);
        }
        foreach ($properties as $s){
            $favoriteproperties = FavouriteProperty::where('user_id', $request->user_id)->where('property_id', $s->id)->where('fav_flag', 1)->first();

            if($favoriteproperties){
                $s->is_favorite = true;
            }else{
                $s->is_favorite = false;
            }
        }
         

        return response()->json([
            'status'=>true,
            'message'=>'Properties Found',
            'data' => $properties
        ], 200);
    } 

    public function addProperty(Request $request)
    {
        if (!$request->has('user_id')) {
            return response()->json([
                'status' => false,
                'messages' => 'User Id Required'
            ], 202);
        }
    
        if($request->type && $request->city && $request->amount && $request->address && $request->lat && $request->long && $request->area_range && $request->bedroom && $request->bathroom){

            $bill_image_name = '';
            $comma_separated_names = '';
            if($request->file('electricity_bill')){
                $electricity_bill = $request->file('electricity_bill');
                $billimage = 'ElectricityBill-' . uniqid() . '-' .$electricity_bill->getClientOriginalName();
                $filePath = public_path('/assets/electricity_bill');
                if(!File::isDirectory($filePath)){
                    File::makeDirectory($filePath, 0777, true, true);
                }
                $billimg = Image::make($electricity_bill->getRealPath());
                $billimg->save($filePath.'/'.$billimage);
                $bill_image_name = $billimage;
            }else{
                return response()->json([
                    'status'=>false,
                    'messages' => 'Electricity Bill Required', 
                    'data' => []
                ], 202);
            }
            if ($request->hasFile('property_images')) {
                $files = $request->file('property_images');
                $file_names = [];
                foreach ($files as $file) {
                    $file_image = 'File-' . uniqid() . '-' . $file->getClientOriginalName();
                    $filefilePath = public_path('/assets/property_images');
                    if (!File::isDirectory($filefilePath)) {
                        File::makeDirectory($filefilePath, 0777, true, true);
                    }
                    $fileimg = Image::make($file->getRealPath());
                    $fileimg->save($filefilePath . '/' . $file_image);
                    $file_names[] = $file_image;
                }
                $comma_separated_names = implode(',', $file_names);
            }else{
                return response()->json([
                    'status'=>false,
                    'messages' => 'Property Image Required', 
                    'data' => []
                ], 202);
            }

            Property::create([
                'user_id' => $request->user_id,
                'type' => $request->type,
                'city' => $request->city,
                'amount' => $request->amount,
                'address' => $request->address,
                'lat' => $request->lat,
                'long' => $request->long,
                'area_range' => $request->area_range,
                'bedroom' => $request->bedroom,
                'bathroom' => $request->bathroom,
                'description' => $request->description,
                'images' => $comma_separated_names,
                'electricity_bill' => $bill_image_name,
                'property_type' => $request->property_type,
                'property_sub_type' => $request->property_sub_type,
            ]);
            return response()->json([
                'status'=>true,
                'messages' => 'Property Added Successfully...'
            ], 200);
            // return response()->json(['message' => 'Property Added Successfully...'], 200);
        }else{
            return response()->json([
                'status'=>false,
                'messages' => 'Fill Required Fields', 
                'data' => []
            ], 202);
            // return response()->json([ 'message' => 'Fill Required Fields'], 400);
        }
    }
    
    public function updateProperty(Request $request, $id)
    {
        if (!$request->has('user_id')) {
            return response()->json([
                'status' => false,
                'messages' => 'User Id Required'
            ], 202);
            
        }

        $property = Property::find($id);

        if (!$property) {
            return response()->json([
                'status' => false,
                'messages' => 'Property not found'
            ], 202);
            
            // return response()->json(['message' => 'Property not found'], 404);
        }

        if ($request->type && $request->city && $request->amount && $request->address && $request->lat && $request->long && $request->area_range && $request->bedroom && $request->bathroom) {

            $bill_image_name = '';
            $comma_separated_names = '';

            if ($request->file('electricity_bill')) {
                // Delete previous electricity bill if exists
                if ($property->electricity_bill) {
                    $previousBillPath = public_path('/assets/electricity_bill') . '/' . $property->electricity_bill;
                    if (File::exists($previousBillPath)) {
                        File::delete($previousBillPath);
                    }
                }

                $electricity_bill = $request->file('electricity_bill');
                $billimage = 'ElectricityBill-' . uniqid() . '-' . $electricity_bill->getClientOriginalName();
                $filePath = public_path('/assets/electricity_bill');
                if (!File::isDirectory($filePath)) {
                    File::makeDirectory($filePath, 0777, true, true);
                }
                $billimg = Image::make($electricity_bill->getRealPath());
                $billimg->save($filePath . '/' . $billimage);
                $bill_image_name = $billimage;
            } else {
                return response()->json([
                    'status'=>false,
                    'messages' => 'Electricity Bill Required', 
                    'data' => []
                ], 202);
                // return response()->json(['message' => 'Electricity Bill Required'], 400);
            }

            if ($request->hasFile('property_images')) {
                if ($property->images) {
                    $previousImages = explode(',', $property->images);
                    foreach ($previousImages as $previousImage) {
                        $previousImagePath = public_path('/assets/property_images') . '/' . $previousImage;
                        if (File::exists($previousImagePath)) {
                            File::delete($previousImagePath);
                        }
                    }
                }

                $files = $request->file('property_images');
                $file_names = [];
                foreach ($files as $file) {
                    $file_image = 'File-' . uniqid() . '-' . $file->getClientOriginalName();
                    $filefilePath = public_path('/assets/property_images');
                    if (!File::isDirectory($filefilePath)) {
                        File::makeDirectory($filefilePath, 0777, true, true);
                    }
                    $fileimg = Image::make($file->getRealPath());
                    $fileimg->save($filefilePath . '/' . $file_image);
                    $file_names[] = $file_image;
                }
                $comma_separated_names = implode(',', $file_names);
            } else {
                return response()->json([
                    'status'=>false,
                    'messages' => 'Property Images Required', 
                    'data' => []
                ], 202);
            }

            // Update property
            $property->update([
                'user_id' => $request->user_id,
                'type' => $request->type,
                'city' => $request->city,
                'amount' => $request->amount,
                'address' => $request->address,
                'lat' => $request->lat,
                'long' => $request->long,
                'area_range' => $request->area_range,
                'bedroom' => $request->bedroom,
                'bathroom' => $request->bathroom,
                'description' => $request->description,
                'images' => $comma_separated_names,
                'electricity_bill' => $bill_image_name,
                'property_type' => $request->property_type,
                'property_sub_type' => $request->property_sub_type,
            ]);
            return response()->json([
                'status'=>true,
                'messages' => 'Property Updated Successfully...'
            ], 200);
        } else {
            return response()->json([
                'status'=>false,
                'messages' => 'Fill Required Fields', 
                'data' => []
            ], 202);
            // return response()->json(['message' => 'Fill Required Fields'], 400);
        }
    }

    public function getProperty($id)
    {

        $property = Property::with(['user'])->find($id);

        if (!$property) {
            return response()->json([
                'status' => false,
                'messages' => 'Property not found'
            ], 202);
        }
        return response()->json([
            'status'=>true,
            'message'=>'Property Found',
            'data' => $property
        ], 200);
    }

    public function singleProperty($id)
    {
        $property = Property::with(['user'])->find($id);
        if (!$property) {
            return response()->json([
                'status'=>false,
                'message' => 'Properties not found',
                'data' => []
            ], 200);
        }
        return response()->json([
            'status'=>true,
            'message'=>'Property Found',
            'data' => $property
        ], 200);
        // $billbaseUrl = public_path('/assets/electricity_bill/');
        // $propertybaseUrl = public_path('/assets/property_images/');
        // $imageNames = explode(',', $property->images);
        // $imageUrls = array_map(function ($imageName) use ($propertybaseUrl) {
        //     return $propertybaseUrl . trim($imageName);
        // }, $imageNames);
        // $electricityBillUrl = $billbaseUrl . $property->electricity_bill;
        // $responseData = [
        //     'property' => $property,
        //     'images' => $imageUrls,
        //     'electricity_bill_url' => $electricityBillUrl
        // ];

        // return response()->json($responseData, 200);
    }
    
    public function allProperties(Request $request)
    {
        $searchQuery = $request->query('search');
        $propertyType = $request->property_type;
        $subType = $request->sub_type;
        $minAmount = $request->min_amount;
        $maxAmount = $request->max_amount;
        $bedroom = $request->bedroom;
        $bathroom = $request->bathroom;
        $description = $request->description;
        $areaRange = $request->area_range;
        $type = $request->type;

        $user = Auth::guard('api')->user();
        $userId = $user ? $user->id : null;

        $propertiesQuery = Property::with(['user']);

        if ($minAmount && $maxAmount) {
            $propertiesQuery->whereBetween('amount', [$minAmount, $maxAmount]);
        } elseif ($minAmount) {
            $propertiesQuery->where('amount', '<=', $minAmount);
        } elseif ($maxAmount) {
            $propertiesQuery->where('amount', '>=', $maxAmount);
        }

        if ($bedroom) { 
            $propertiesQuery->where('bedroom', $bedroom); 
        }
        if ($bathroom) { 
            $propertiesQuery->where('bathroom', $bathroom); 
        }
        if ($description) { 
            $propertiesQuery->where('description', 'like', '%' . $description . '%'); 
        }
        if ($areaRange) { 
            $propertiesQuery->where('area_range', 'like', '%' . $areaRange . '%'); 
        }

        if ($type) {
            $propertiesQuery->where('type', $type);
        }

        if ($propertyType) {
            $propertiesQuery->where('property_type', $propertyType);
        }
        if ($subType) {
            $propertiesQuery->where('property_sub_type', $subType);
        }

        $properties = $propertiesQuery->paginate(20);
        if ($properties->isEmpty()) {
            return response()->json([
                'status'=>false,
                'message' => 'Properties not found',
                'data' => []
            ], 404);
        }

        if (Auth::guard('api')->check()) {
            $userId = Auth::guard('api')->user()->id;

            $properties->load(['favoritedByUsers' => function ($query) use ($userId) {
                $query->where('user_id', $userId);
            }]);

            $properties->each(function ($property) use ($userId) {
                $property->is_favorite = $property->favoritedByUsers->isNotEmpty();
                unset($property->favoritedByUsers);
            });
        } else {
            $properties->each(function ($property) {
                $property->is_favorite = false;
            });
        }
         
        return response()->json([
            'status'=>true,
            'message'=>'Property Found',
            'data' =>$properties
        ], 200);
    } 

    public function storeService(Request $request)
    {
        try {
            $validation = Validator::make($request->all(),
            [
                'user_id' => 'required',
                'service_name' => 'required',
                'pricing' => 'required',
                'lat' => 'required',
                'long' => 'required',
                'location' => 'required',
                'start_time' => 'required',
                'end_time' => 'required',
                'country' => 'required',
                'city' => 'required',
                'media' => 'required',
            ]);
            if ($validation->fails()) {
                return response()->json([
                    'status' => true,
                    'messages' => $validation->messages()->toArray()
                ], 200);
            } else {

                $comma_separated_names = '';
                if ($request->hasFile('media')) {
                    $files = $request->file('media');
                    $file_names = [];
                    foreach ($files as $file) {
                        $file_image = 'Media-' . uniqid() . '-' . $file->getClientOriginalName();
                        $filefilePath = public_path('/assets/media_images');
                        if (!File::isDirectory($filefilePath)) {
                            File::makeDirectory($filefilePath, 0777, true, true);
                        }
                        $fileimg = Image::make($file->getRealPath());
                        $fileimg->save($filefilePath . '/' . $file_image);
                        $file_names[] = $file_image;
                    }
                    $comma_separated_names = implode(',', $file_names);
                }
    
                Service::create([
                    'user_id' => $request->user_id,
                    'service_name' => $request->service_name,
                    'description' => $request->description,
                    'pricing' => $request->pricing,
                    'start_time' => $request->start_time,
                    'end_time' => $request->end_time,
                    'location' => $request->location,
                    'lat' => $request->lat,
                    'long' => $request->long,
                    'media' => $comma_separated_names,
                    'country' => $request->country,
                    'city' => $request->city,
                    'additional_information' => $request->additional_information
                    
                ]);
    
                return response()->json([
                    'status'=>true,
                    'messages' => 'Service Added Successfully...'
                ], 200);
            }
        } catch (\Throwable $th) {
            $th->getMessage();
        }
        // if (!$request->has('user_id')) {
        //     return response()->json([
        //         'status' => false,
        //         'messages' => 'User Id Required'
        //     ], 202);
        // }
    
        // if($request->service_name && $request->pricing && $request->lat && $request->long && $request->location && $request->start_time && $request->end_time && $request->country && $request->city){

            // $comma_separated_names = '';
            // if ($request->hasFile('media')) {
            //     $files = $request->file('media');
            //     $file_names = [];
            //     foreach ($files as $file) {
            //         $file_image = 'Media-' . uniqid() . '-' . $file->getClientOriginalName();
            //         $filefilePath = public_path('/assets/media_images');
            //         if (!File::isDirectory($filefilePath)) {
            //             File::makeDirectory($filefilePath, 0777, true, true);
            //         }
            //         $fileimg = Image::make($file->getRealPath());
            //         $fileimg->save($filefilePath . '/' . $file_image);
            //         $file_names[] = $file_image;
            //     }
            //     $comma_separated_names = implode(',', $file_names);
            // }

            // Service::create([
            //     'user_id' => $request->user_id,
            //     'service_name' => $request->service_name,
            //     'description' => $request->description,
            //     // 'category_id' => $request->category_id,
            //     'pricing' => $request->pricing,
            //     'start_time' => $request->start_time,
            //     'end_time' => $request->end_time,
            //     'location' => $request->location,
            //     'lat' => $request->lat,
            //     'long' => $request->long,
            //     'media' => $comma_separated_names,
            //     'additional_information' => $request->additional_information
            //     'country' => $request->country
            //     'city' => $request->city
            // ]);

            // return response()->json([
            //     'status'=>true,
            //     'messages' => 'Service Added Successfully...'
            // ], 200);
        // }else{
        //     return response()->json([
        //         'status'=>false,
        //         'messages' => 'Fill Required Fields', 
        //         'data' => []
        //     ], 202);
        // }
    }
     
    public function updateServices(Request $request, $id)
    {
        if (!$request->has('user_id')) {
            return response()->json([
                'status' => false,
                'messages' => 'User Id Required'
            ], 202);
            
        }

        $service = Service::find($id);

        if (!$service) {
            return response()->json([
                'status' => false,
                'messages' => 'Service not found'
            ], 202);
            
            // return response()->json(['message' => 'Property not found'], 404);
        }

        if($request->service_name && $request->pricing  && $request->lat && $request->long && $request->location && $request->start_time && $request->end_time){
            $comma_separated_names = '';


            if ($request->hasFile('media')) {
                if ($service->media) {
                    $previousImages = explode(',', $service->media);
                    foreach ($previousImages as $previousImage) {
                        $previousImagePath = public_path('/assets/media_images') . '/' . $previousImage;
                        if (File::exists($previousImagePath)) {
                            File::delete($previousImagePath);
                        }
                    }
                }

                $files = $request->file('media');
                $file_names = [];
                foreach ($files as $file) {
                    $file_image = 'Media-' . uniqid() . '-' . $file->getClientOriginalName();
                    $filefilePath = public_path('/assets/media_images');
                    if (!File::isDirectory($filefilePath)) {
                        File::makeDirectory($filefilePath, 0777, true, true);
                    }
                    $fileimg = Image::make($file->getRealPath());
                    $fileimg->save($filefilePath . '/' . $file_image);
                    $file_names[] = $file_image;
                }
                $comma_separated_names = implode(',', $file_names);
            }

            // Update property
            $service->update([
                'user_id' => $request->user_id,
                'service_name' => $request->service_name,
                'description' => $request->description,
                // 'category_id' => $request->category_id,
                'pricing' => $request->pricing,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'location' => $request->location,
                'lat' => $request->lat,
                'long' => $request->long,
                'media' => $comma_separated_names,
                'country' => $request->country,
                'city' => $request->city,
                'additional_information' => $request->additional_information
            ]);
            return response()->json([
                'status'=>true,
                'messages' => 'Service Updated Successfully...'
            ], 200);
        } else {
            return response()->json([
                'status'=>false,
                'messages' => 'Fill Required Fields', 
                'data' => []
            ], 202);
        }
    }

    public function destroyService($id)
    {
        $service = Service::find($id);
        if (!$service) {
            return response()->json([
                'status'=>false,
                'messages' => 'Service not found', 
                'data' => []
            ], 202);
        }
        $service->delete();
        return response()->json([
            'status'=>true,
            'messages' => 'Service deleted successfully'
        ], 200);
    }
    
    public function getServices(Request $request)
    {
        
        // $userId = $request->input('user_id');

        // if (!$userId) {
        //     return response()->json([
        //         'status' => false,
        //         'message' => 'User Id Required'
        //     ], 400);
        // }

        $userId = Auth::id();

        $searchQuery = $request->query('search');
        $serviceName = $request->has('service_name') ? $request->input('service_name') : null;
        $min = $request->input('minPrice');
        $max = $request->input('maxPrice');
        $country = $request->input('country');
        $city = $request->input('city');

        $serviceQuery = Service::with('user', 'provider');

        if ($min && $max) {
            $serviceQuery->whereBetween('pricing', [$min, $max]);
        } elseif ($min) {
            $serviceQuery->where('pricing', '<=', $min);
        } elseif ($max) {
            $serviceQuery->where('pricing', '>=', $max);
        }

        if ($serviceName) {
            $serviceQuery->where('service_name', 'like', '%' . $serviceName . '%');
        }
        if ($country) {
            $serviceQuery->where('country', $country);
        }
        if ($city) {
            $serviceQuery->where('city', $city);
        }
  
        $services = $serviceQuery->orderByDesc('created_at')->paginate(20);

        // $services = Service::with('user','provider')->where('user_id', $userId)->orderByDesc('created_at')->paginate(20);

        if ($services->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'Service not found'
            ], 404);
        }
        
        foreach ($services as $s){
            $favoriteService = FavouriteService::where('user_id', $userId)->where('service_id', $s->id)->where('fav_flag', 1)->first();

            $provider_services = Service::whereuser_id($s->user_id)->pluck('id');
            $review = ServiceReview::whereIn('service_id',$provider_services)->get();
            $count = count($review);
            $total_rate = 0;
            foreach ($review as $r) {
                $total_rate += $r->rate;
            }
            
            $average_rate = $count > 0 ? $total_rate / $count : 0;
            $s->count_of_service = $count;
            $s->total_rate = $total_rate;
            $s->average_rate = $average_rate;

            if($favoriteService){
                $s->is_favorite = true;
            }else{
                $s->is_favorite = false;
            }
        }

        return response()->json([
            'status' => true,
            'message' => 'Services Found',
            'data' => $services
        ], 200);
    } 

    public function allServices()
    {
        $userId = Auth::id();
        
        $services = Service::with('user','provider')->orderByDesc('created_at')->paginate(20);
        
        foreach ($services as $s){
            $favoriteService = FavouriteService::where('user_id', $userId)->where('service_id', $s->id)->where('fav_flag', 1)->first();

            $provider_services = Service::whereuser_id($s->user_id)->pluck('id');
            $review = ServiceReview::whereIn('service_id',$provider_services)->get();
            $count = count($review);
            $total_rate = 0;
            foreach ($review as $r) {
                $total_rate += $r->rate;
            }
            
            $average_rate = $count > 0 ? $total_rate / $count : 0;
            $s->count_of_service = $count;
            $s->total_rate = $total_rate;
            $s->average_rate = $average_rate;

            if($favoriteService){
                $s->is_favorite = true;
            }else{
                $s->is_favorite = false;
            }
        }
        if ($services->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'Service not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Services Found',
            'data' => $services
        ], 200);
    } 

    public function getService($id)
    {
        $userId = Auth::id();
        $service = Service::with('user','provider')->find($id);
        if (!$service) {
            return response()->json([
                'status' => false,
                'messages' => 'Service not found'
            ], 202);
        }
        $favoriteService = FavouriteService::where('user_id', $userId)->where('service_id', $service->id)->where('fav_flag', 1)->first();
        $provider_services = Service::whereuser_id($service->user_id)->pluck('id');
        $review = ServiceReview::whereIn('service_id',$provider_services)->get();
        $count = count($review);
        $total_rate = 0;
        foreach ($review as $r) {
            $total_rate += $r->rate;
        }
        
        $average_rate = $count > 0 ? $total_rate / $count : 0;
        $service->count_of_service = $count;
        $service->total_rate = $total_rate;
        $service->average_rate = $average_rate;

        if($favoriteService){
            $service->is_favorite = true;
        }else{
            $service->is_favorite = false;
        }
       
        return response()->json([
            'status'=>true,
            'message'=>'Service Found',
            'data' => $service
        ], 200);
    }

    // 15 Feb 2024 
    public function addFavouriteProvider(Request $request){
        $userId = $request->input('user_id');
        $providerId = $request->input('provider_id');
        $fav = $request->input('fav_flag');

        if (!$userId) {
            return response()->json([
                'status' => false,
                'message' => 'Filled Required Fields'
            ], 400);
        }
        if (!$providerId) {
            return response()->json([
                'status' => false,
                'message' => 'Filled Required Fields'
            ], 400);
        }
        if (!$fav) {
            return response()->json([
                'status' => false,
                'message' => 'Filled Required Fields'
            ], 400);
        }
        if($fav == 1){
            $fav_provider = FavouriteProvider::where('user_id', $userId)
                                  ->where('provider_id', $providerId)
                                  ->first();
            if ($fav_provider){
                return response()->json([
                    'status' => true,
                    'message' => 'Already Favorited'
                ], 200);
            }else{

                FavouriteProvider::create([
                    'user_id' => $userId,
                    'provider_id' => $providerId,
                    'fav_flag' => $fav
                ]);
                return response()->json([
                    'status'=>true,
                    'messages' => 'Favourite added successfully.'
                ], 200);
            }
        }else if($fav == 2){
            $fav_provider = FavouriteProvider::where('user_id', $userId)
                                  ->where('provider_id', $providerId)
                                  ->first();
            if ($fav_provider){

                $fav_provider->update([
                    'user_id' => $userId,
                    'provider_id' => $providerId,
                    'fav_flag' => $fav
                ]);
                return response()->json([
                    'status'=>true,
                    'messages' => 'Favourite updated successfully.'
                ], 200);
            }
        }else{
            return response()->json([
                'status' => true,
                'message' => 'Favourite flag incorrect'
            ], 400);
        }

    }
    
    public function addFavouriteProperty(Request $request){
        $userId = $request->input('user_id');
        $propertyId = $request->input('property_id');
        $fav = $request->input('fav_flag');

        if (!$userId) {
            return response()->json([
                'status' => false,
                'message' => 'Filled Required Fields'
            ], 400);
        }
        if (!$propertyId) {
            return response()->json([
                'status' => false,
                'message' => 'Filled Required Fields'
            ], 400);
        }
        if (!$fav) {
            return response()->json([
                'status' => false,
                'message' => 'Filled Required Fields'
            ], 400);
        }
        if($fav == 1){
            FavouriteProperty::where('user_id', $userId)
                                  ->where('property_id', $propertyId)
                                  ->delete();
            
            FavouriteProperty::create([
                'user_id' => $userId,
                'property_id' => $propertyId,
                'fav_flag' => $fav
            ]);
            return response()->json([
                'status'=>true,
                'messages' => 'Successfully created'
            ], 200);
        

        }else if($fav == 2){
            $fav_property = FavouriteProperty::where('user_id', $userId)
                                  ->where('property_id', $propertyId)
                                  ->first();
            if ($fav_property){
            
                $fav_property->update([
                    'user_id' => $userId,
                    'property_id' => $propertyId,
                    'fav_flag' => $fav
                ]);
                return response()->json([
                    'status'=>true,
                    'messages' => 'Favourite updated successfully.'
                ], 200);
            }
        }else{
            return response()->json([
                'status' => false,
                'message' => 'Favourite flag incorrect'
            ], 400);
        }
    }

    public function getFavourite(Request $request){
        $userId = $request->input('user_id');

        if (!$userId) {
            return response()->json([
                'status' => false,
                'message' => 'User Id Required'
            ], 400);
        }

        // $favoriteProviders = FavouriteProvider::with(['provider'])
        //                                   ->where('user_id', $userId)
        //                                   ->where('fav_flag', 1)
        //                                   ->orderByDesc('created_at')
        //                                   ->get();

        $favoriteService = FavouriteService::with(['service'])
            ->where('user_id', $userId)
            ->where('fav_flag', 1)
            ->orderByDesc('created_at')
            ->paginate(20);

        // Retrieve favorite properties with their related data
        $favoriteProperties = FavouriteProperty::with('property')
                                                ->where('user_id', $userId)
                                                ->where('fav_flag', 1)
                                                ->orderByDesc('created_at')
                                                ->paginate(20);

                                            
        if ($favoriteService->isEmpty() && $favoriteProperties->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'Data not found.'
            ], 200);
        }

        return response()->json([
            'status' => true,
            // 'favorite_service_providers' => $favoriteProviders,
            'favorite_services' => $favoriteService,
            'favorite_properties' => $favoriteProperties,
            'message' => 'Data found.'
        ], 200);

    }

    public function getServiceProviders(Request $request){

        $serviceproviders = ServiceProvider::with('user')->paginate(20);
        if ($serviceproviders->isEmpty()) {
            return response()->json([
                'status'=>false,
                'message' => 'Service Providers not found',
                'data' => []
            ], 404);
        }
         
        return response()->json([
            'status'=>true,
            'message'=>'Service Providers Found',
            'data' => $serviceproviders
        ], 200);
    }

    public function getServiceProvider($id){
        $serviceprovider = ServiceProvider::with('user')->find($id);

        if (!$serviceprovider) {
            return response()->json([
                'status' => false,
                'messages' => 'Service Provider not found'
            ], 202);
        }
        return response()->json([
            'status'=>true,
            'message'=>'Service Provider Found',
            'data' => $serviceprovider
        ], 200);

    }

    //  18 Feb 2024
    public function getUserById($id){ 

        $user = User::find($id);

        if (!$user) {
            return response()->json([
              'status' => false,
              'messages' => 'User not found'
            ], 202);
        }
        return response()->json([
          'status'=>true,
          'message'=>'User Found',
            'data' => $user
        ], 200);
    }

    public function addServiceRequest(Request $request){
        
        $userId = $request->input('user_id');

        if (!$userId) {
            return response()->json([
                'status' => false,
                'message' => 'User Id Required'
            ], 400);
        } 
        $serviceId = $request->input('service_id');

        if (!$serviceId) {
            return response()->json([
                'status' => false,
                'message' => 'Service Id Required'
            ], 400);
        }
        $serviceprovider_id = $request->input('serviceprovider_id');

        if (!$serviceprovider_id) {
            return response()->json([
                'status' => false,
                'message' => 'Service Provider Id Required'
            ], 400);
        }
        
        if ($request->address && $request->price && $request->lat && $request->long && $request->property_type && $request->date && $request->time) {

            ServiceProviderRequest::create([
                'user_id' => $userId,
                'serviceprovider_id' => $serviceprovider_id,
                'service_id' => $serviceId,
                'address' => $request->address,
                'lat' => $request->lat,
                'long' => $request->long,
                'property_type' => $request->property_type,
                'price' => $request->price,
                'date' => $request->date,
                'time' => $request->time,
                'description' => $request->description,
                'additional_info' => $request->additional_info,
            ]);
            $option =[
                'title' => 'Service Request',
                'body' => 'You Receive a Service Request',
                'created_by' => Auth::id(),
                'created_to' => $serviceprovider_id,
            ];
            $this->sendNotification($option);

            return response()->json([
                'status' => true,
                'message' => 'Successfully created'
            ], 200);
        }else{
            return response()->json([
                'status' => false,
                'message' => 'Filled Required Fields'
            ], 400); 
        }
    }

    public function getServiceRequest(Request $request){
        
        $serviceprovider_id = $request->input('serviceprovider_id');

        if (!$serviceprovider_id) {
            return response()->json([
                'status' => false,
                'message' => 'Service Provider Id Required'
            ], 400);
        }
        
        $requests = ServiceProviderRequest::with(['property_type','user','service'])->whereserviceprovider_id($serviceprovider_id)->orderByDesc('created_at')->paginate(20);
        return response()->json([
            'status' => true,
            'data' => $requests,
            'message' => 'Successfully created'
        ], 200);
    
    }
    
    public function getUserRequest(Request $request){
        
        $user_id = $request->input('user_id');

        if (!$user_id) {
            return response()->json([
                'status' => false,
                'message' => 'User Id Required'
            ], 400);
        }
        
        $requests = ServiceProviderRequest::with(['property_type','provider','service'])->whereuser_id($user_id)->whereapproved('0')->orderByDesc('created_at')->paginate(20);

        return response()->json([
            'status' => true,
            'data' => $requests,
            'message' => 'Successfully created'
        ], 200);
    
    }

    public function getServiceProviderRequest($id){
        $request = ServiceProviderRequest::with(['property_type','provider','service'])->find($id);

        if (!$request) {
            return response()->json([
              'status' => false,
              'messages' => 'Request Not Found.'
            ], 202);
        }
        return response()->json([
          'status'=>true,
            'data' => $request,
          'message' => 'Service Request Found.'
        ], 200);
    }

    public function addServiceJob(Request $request){
        $userId = $request->input('user_id');

        if (!$userId) {
            return response()->json([
                'status' => false,
                'message' => 'User Id Required'
            ], 400);
        }
        $provider_id = $request->input('provider_id');

        if (!$provider_id) {
            return response()->json([
                'status' => false,
                'message' => 'Service Provider Id Required'
            ], 400);
        }
        
        $request_id = $request->input('request_id');

        if (!$request_id) {
            return response()->json([
                'status' => false,
                'message' => 'Request Id Required'
            ], 400);
        }
        
        ServiceProviderJob::create([
            'user_id' => $userId,
            'provider_id' => $provider_id,
            'request_id' => $request_id
        ]);
        
        $option =[
            'title' => 'Job Started',
            'body' => 'Your Job Started from now against the Booked Service',
            'created_by' => $userId,
            'created_to' => $provider_id,
        ];
        $this->sendNotification($option);

        
        $service_request = ServiceProviderRequest::find($request_id);
        $service_request->update([
            'approved' => 1,
            'decline' => 0
        ]);

        $option =[
            'title' => 'Request Approved',
            'body' => 'You Service Request Approved',
            'created_by' => $service_request->serviceprovider_id,
            'created_to' => $service_request->user_id,
        ];
        $this->sendNotification($option);

        return response()->json([
            'status' => true,
            'message' => 'Successfully created'
        ], 200);
    
    }
    
    public function getServiceJob(Request $request){
        
        $user_id = $request->input('user_id');

        if (!$user_id) {
            return response()->json([
                'status' => false,
                'message' => 'User Id Required'
            ], 400);
        }  

        $provider_id = $request->input('provider_id');

        if (!$provider_id) {
            return response()->json([
                'status' => false,
                'message' => 'Service Provider Id Required'
            ], 400);
        } 

        $request_id = $request->input('request_id');

        if (!$request_id) {
            return response()->json([
                'status' => false,
                'message' => 'Request Id Required'
            ], 400);
        }
        
        $requests = ServiceProviderJob::with(['request','provider','user'])->whereuser_id($user_id)->orderByDesc('created_at')->get();

        return response()->json([
            'status' => true,
            'data' => $requests,
            'message' => 'Successfully created'
        ], 200);
    }

    public function allPropertyType()
    {
        
        $type = PropertyType::get();
        if ($type->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'Property Types not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Property Types Found',
            'data' => $type
        ], 200);
    } 

    public function getPropertySubType($id)
    {
        
        $subtype = PropertySubType::wheretype_id($id)->get();
        if ($subtype->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'Property Sub Types not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Property Sub Types Found',
            'data' => $subtype
        ], 200);
    } 
        
    // 26-02-2024
    public function addServiceRequestDecline(Request $request){
        $request_id = $request->input('request_id');

        if (!$request_id) {
            return response()->json([
                'status' => false,
                'message' => 'Request Id Required'
            ], 400);
        }
        $service_request = ServiceProviderRequest::find($request_id);
        $service_request->update([
            'approved' => 0,
            'decline' => 1
        ]);
        $option =[
            'title' => 'Request Approved',
            'body' => 'You Service Request Approved',
            'created_by' => $service_request->serviceprovider_id,
            'created_to' => $service_request->user_id,
        ];
        $this->sendNotification($option);

        return response()->json([
            'status' => true,
            'message' => 'Successfully created'
        ], 200);
    
    }
    
    public function markServiceStatusJob(Request $request){
        $job_id = $request->input('job_id');

        if (!$job_id) {
            return response()->json([
                'status' => false,
                'message' => 'Job Id Required'
            ], 400);
        }
        $status = $request->input('status');

        if (!$status) {
            return response()->json([
                'status' => false,
                'message' => 'Status Required'
            ], 400);
        }

        $service_status = ServiceProviderJob::find($job_id);
        $service_status->update([
            'status' => $status
        ]);
        // $action_name = '';
        $message = '';
        if($status == 1){
            $title = 'Job Completed';
            $body = 'You Job Completed by Service Provider';
        }elseif($status == 2){
            $title = 'Job Cancelled';
            $body = 'You Job Cancelled by Service Provider';
        }elseif($status == 3){
            $title = 'Job Rejected';
            $body = 'You Job Rejected by Service Provider';
        }
        $option =[
            'title' => $title,
            'body' => $body,
            'created_by' => Auth::id(),
            'created_to' => $service_status->user_id,
        ];
        $this->sendNotification($option);
        
        return response()->json([
            'status' => true,
            'message' => 'Successfully created'
        ], 200);
    
    }

    public function serviceProviderstat(Request $request){

        $provider_id = $request->input('serviceprovider_id');

        if (!$provider_id) {
            return response()->json([
                'status' => false,
                'message' => 'Service Provider Id Required'
            ], 400);
        }else{
            $provider = ServiceProvider::with(['user','provider_service'])->whereuser_id($provider_id)->first();
            if (!$provider) {
                return response()->json([
                   'status' => false,
                  'message' => 'Service Provider not found'
                ], 404);
            }

            $stat = array();
            $stat['serviceprovider'] = $provider;
            $service_count = Service::whereuser_id($provider_id)->count();
            $stat['total_service'] = $service_count;
            $job = ServiceProviderJob::whereprovider_id($provider_id)->wherestatus('1')->get();
            $job_count = $job->count();
            $stat['total_jobs'] = $job_count;
            $total = 0;
            foreach($job as $j){
                $s_request = ServiceProviderRequest::select('price')->whereid($j->request_id)->first();
                $total += $s_request->price;
            }
            $stat['total_price'] = $total;
            $total_rate = 0;
            $rating = ServiceReview::select('rate')->where('user_id', $provider_id)->get(); 
            $count = count($rating);
            foreach ($rating as $r) {
                $total_rate += $r->rate;
            }
            
            $average_rate = $count > 0 ? $total_rate / $count : 0;
            // $average_rate = $total_rate / $count;
            // $stat['total_rate'] = $total_rate;
            // $stat['average_rate'] = $average_rate;
            // $stat['count'] = $count;
            $stat['rate'] = $average_rate;
            return response()->json([
                'status' => true,
                'data' => $stat,
                'message' => 'Data Found'
             ], 200);
        }
    }
    
    public function Landlordstat(Request $request){

        $landlord_id = $request->input('landlord_id');

        if (!$landlord_id) {
            return response()->json([
                'status' => false,
                'message' => 'Landlord Id Required'
            ], 400);
        }else{
            $landlord = Landlord::with(['user'])->whereuser_id($landlord_id)->first();
            if (!$landlord) {
                return response()->json([
                   'status' => false,
                  'message' => 'Landlord not found'
                ], 404);
            }

            $stat = array();
            
            $pending_contract = Contract::wherelandlord_id($landlord_id)->wherestatus('0')->count();
            $total_properties = Property::whereuser_id($landlord_id)->count();
            
            $stat['landlord'] = $landlord;
            $stat['pending_contract'] = $pending_contract;
            $stat['total_properties'] = $total_properties;
            $stat['total_spend'] = '2000';



            return response()->json([
                'status' => true,
                'data' => $stat,
                'message' => 'Data Found'
             ], 200);
        }
    }
    
    public function Visitorstat(Request $request){

        $visitor_id = $request->input('visitor_id');

        if (!$visitor_id) {
            return response()->json([
                'status' => false,
                'message' => 'Visitor Id Required'
            ], 400);
        }else{
            $visitor = Visitor::with(['user'])->whereuser_id($visitor_id)->first();
            if (!$visitor) {
                return response()->json([
                   'status' => false,
                  'message' => 'Visitor not found'
                ], 404);
            }

            $stat = array();
            
            $stat['visitor'] = $visitor;
              
            $pending_job = ServiceProviderJob::whereuser_id($visitor_id)->wherestatus('0')->count();
            $total_favorite = FavouriteService::whereuser_id($visitor_id)->wherefav_flag('1')->count();

            $stat['pending_job'] = $pending_job;
            $stat['total_spend'] = '2000';
            $stat['total_favorite'] = $total_favorite;



            return response()->json([
                'status' => true,
                'data' => $stat,
                'message' => 'Data Found'
             ], 200);
        }
    }
    
    public function Tenantstat(Request $request){

        $tenant_id = $request->input('tenant_id');

        if (!$tenant_id) {
            return response()->json([
                'status' => false,
                'message' => 'Tenant Id Required'
            ], 400);
        }else{
            $tenant = Tenant::with(['user'])->whereuser_id($tenant_id)->first();
            if (!$tenant) {
                return response()->json([
                   'status' => false,
                  'message' => 'Tenant not found'
                ], 404);
            }

            $stat = array();
            $pending_contract = Contract::whereuser_id($tenant_id)->wherestatus('0')->count();
            $total_rented = Contract::whereuser_id($tenant_id)->wherestatus('1')->count();
            $stat['tenant'] = $tenant;
            $stat['pending_contract'] = $pending_contract;
            $stat['total_rented'] = $total_rented;
            $stat['total_spend'] = '2';

            return response()->json([
                'status' => true,
                'data' => $stat,
                'message' => 'Data Found'
            ], 200);
        }
    }
    
    public function serviceJobDetailWithStatus(Request $request){
        $userId = $request->input('user_id');

        if (!$userId) {
            return response()->json([
                'status' => false,
                'message' => 'User Id Required'
            ], 400);
        }

        $pending = ServiceProviderJob::with(['request','request.service','provider'])->whereuser_id($userId)->wherestatus('0')->orderByDesc('created_at')->paginate(20);
        $completed = ServiceProviderJob::with(['request','request.service','provider'])->whereuser_id($userId)->wherestatus('1')->orderByDesc('created_at')->paginate(20);
        $rejected = ServiceProviderJob::with(['request','request.service','provider'])->whereuser_id($userId)->wherestatus('2')->orderByDesc('created_at')->paginate(20);
        
        $data = [
            'pending_jobs' => $pending,
            'completed_jobs' => $completed,
            'rejected_jobs' => $rejected,
        ];

        return response()->json([
            'status' => true,
            'data' => $data,
            'message' => 'Successfully created'
        ], 200);
    
    }

    public function getServiceProvidersJob(){
        $userId = Auth::id();
        $jobs = ServiceProviderJob::with(['request','provider'])->whereprovider_id($userId)->wherestatus('0')->orderByDesc('created_at')->paginate(20);

        if ($jobs->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No Data Found.',
                'data' => []
            ], 404);
        }
        
        return response()->json([
            'status' => true,
            'data' => $jobs,
            'message' => 'Successfully fetched'
        ], 200);
    }

    public function getJobDetails($id){
        $job = ServiceProviderJob::with(['request','request.service','provider'])->whereid($id)->first();
        if (!$job) {
            return response()->json([
                'status' => false,
                'message' => 'No Data Found.',
                'data' => []
            ], 404);
        }
        return response()->json([
            'status' => true,
            'data' => $job,
            'message' => 'Successfully fetched'
        ], 200);
    }
    
    // 28-2-24
    public function markServiceReview(Request $request){
       
        $service_id = $request->input('service_id');
        $user_id = $request->input('user_id');

        if (!$service_id) {
            return response()->json([
                'status' => false,
                'message' => 'Service ID Required'
            ], 400);
        }
        if (!$user_id) {
            return response()->json([
                'status' => false,
                'message' => 'User ID Required'
            ], 400);
        }

        ServiceReview::create([
            'user_id' => $user_id,
            'service_id' => $service_id,
            'property_sub_type_id' => $request->property_sub_type_id,
            'rate' => $request->rate,
            'description' => $request->description,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Successfully done.'
        ], 200);
    }

    public function getServiceReview(Request $request){
       
        $service_id = $request->input('service_id');

        if (!$service_id) {
            return response()->json([
                'status' => false,
                'message' => 'Service ID Required'
            ], 400);
        }

        $feedback = ServiceReview::with(['user','subpropertytype'])->whereservice_id($service_id)->get();

        return response()->json([
            'status' => true,
            'data' => $feedback,
            'message' => 'Successfully done.'
        ], 200);
    }
    
    public function addFavouriteService(Request $request){

        $userId = $request->input('user_id');
        $serviceId = $request->input('service_id');
        $fav = $request->input('fav_flag');

        if (!$userId) {
            return response()->json([
                'status' => false,
                'message' => 'Filled Required Fields'
            ], 400);
        }
        if (!$serviceId) {
            return response()->json([
                'status' => false,
                'message' => 'Filled Required Fields'
            ], 400);
        }
        if (!$fav) {
            return response()->json([
                'status' => false,
                'message' => 'Filled Required Fields'
            ], 400);
        }

        if($fav == 1){
            FavouriteService::where('user_id', $userId)->where('service_id', $serviceId)->delete();
           
            FavouriteService::create([
                'user_id' => $userId,
                'service_id' => $serviceId,
                'fav_flag' => $fav
            ]);
            return response()->json([
                'status'=>true,
                'messages' => 'Successfully created'
            ], 200);
           
        }else if($fav == 2){
            $fav_service = FavouriteService::where('user_id', $userId)
                                  ->where('service_id', $serviceId)
                                  ->first();
            if ($fav_service){
            
                $fav_service->update([
                    'user_id' => $userId,
                    'service_id' => $serviceId,
                    'fav_flag' => $fav
                ]);

                return response()->json([
                    'status'=>true,
                    'messages' => 'Favourite updated successfully.'
                ], 200);
            }
        }else{
            return response()->json([
                'status' => false,
                'message' => 'Favourite flag incorrect'
            ], 400);
        }
    }

    public function deleteProperty($id){
        if($id){
            Property::whereid($id)->delete();
            return response()->json([
              'status' => true,
              'messages' => 'Rubric Deleted Successfully...'
            ], 200);
        }
        return response()->json([
          'status' => false,
          'messages' => 'Property Id Required'
        ], 200);
    }
    
    public function getServiceFavourite(Request $request){
        $userId = $request->input('user_id');

        if (!$userId) {
            return response()->json([
                'status' => false,
                'message' => 'User Id Required'
            ], 400);
        }

        $serviceId = $request->input('service_id');

        if (!$serviceId) {
            return response()->json([
                'status' => false,
                'message' => 'Service Id Required'
            ], 400);
        }

        $favoriteService = FavouriteService::with(['service'])->where('user_id', $userId)->where('service_id', $serviceId)->get();
        if ($favoriteService->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'Data not found.'
            ], 200);
        }
        return response()->json([
            'status' => true,
            'data' => $favoriteService[0],
            'message' => 'Data found.'
        ], 200);

    }

    // Notification
    
    public function getNotificationByUserId(){ 

        $id = Auth::id();
        $user = User::find($id);

        if (!$user) {
            return response()->json([
              'status' => false,
              'messages' => 'User not found'
            ], 202);
        }
        $notification = Notification::wherecreated_to($id)->paginate(15);

        return response()->json([
          'status'=>true,
          'data' => $notification,
          'message'=>'Data Found'
        ], 200);
    }

    public function destroyNotification($id)
    {
        $notification = Notification::find($id);
        if (!$notification) {
            return response()->json([
                'status'=>false,
                'messages' => 'Notification not found', 
                'data' => []
            ], 202);
        }
        $notification->delete();
        return response()->json([
            'status'=>true,
            'messages' => 'Notification deleted successfully'
        ], 200);
    }

    public function getProviderReviews(){
        $userId = Auth::id();
        // $userId = 1;
        $services_ids = Service::where('user_id', $userId)->pluck('id');
        $one = ServiceReview::whereIn('service_id', $services_ids)->whererate(1)->count();
        $two = ServiceReview::whereIn('service_id', $services_ids)->whererate(2)->count();
        $three = ServiceReview::whereIn('service_id', $services_ids)->whererate(3)->count();
        $four = ServiceReview::whereIn('service_id', $services_ids)->whererate(4)->count();
        $five = ServiceReview::whereIn('service_id', $services_ids)->whererate(5)->count();
        $allrating = ServiceReview::with(['subpropertytype:id,name'])->whereIn('service_id', $services_ids)->orderByDesc('created_at')->paginate(20);
        
        return response()->json([
            'status'=>true,
            'data' => [
                'one_rate' => $one,
                'two_rate' => $two,
                'three_rate' => $three,
                'four_rate' => $four,
                'five_rate' => $five,
                'rating_data' => $allrating,
            ]
        ], 200);
    }

    // Contract 
    public function storeContract(Request $request){

        if (
            $request->landlordName &&
            $request->landlordAddress &&
            $request->landlordPhone &&
            $request->tenantName &&
            $request->tenantAddress &&
            $request->tenantPhone &&
            $request->tenantEmail &&
            $request->occupants &&
            $request->premisesAddress &&
            $request->propertyType &&
            $request->leaseStartDate &&
            $request->leaseEndDate &&
            $request->leaseType &&
            $request->rentAmount &&
            $request->rentDueDate &&
            $request->rentPaymentMethod &&
            $request->securityDepositAmount &&
            $request->includedUtilities &&
            $request->tenantResponsibilities &&
            $request->emergencyContactName &&
            $request->emergencyContactPhone &&
            $request->emergencyContactAddress &&
            $request->buildingSuperintendentName &&
            $request->buildingSuperintendentAddress &&
            $request->buildingSuperintendentPhone &&
            $request->rentIncreaseNoticePeriod &&
            $request->noticePeriodForTermination &&
            $request->latePaymentFee &&
            $request->rentalIncentives &&
            $request->additionalTerms
        ) {
            Contract::create([
                'user_id' => Auth::id(),
                'property_id' => $request->property_id,
                'landlord_id' => $request->landlord_id,
                'landlordName' => $request->landlordName,
                'landlordAddress' => $request->landlordAddress,
                'landlordPhone' => $request->landlordPhone,
                'tenantName' => $request->tenantName,
                'tenantAddress' => $request->tenantAddress,
                'tenantPhone' => $request->tenantPhone,
                'tenantEmail' => $request->tenantEmail,
                'occupants' => $request->occupants,
                'premisesAddress' => $request->premisesAddress,
                'propertyType' => $request->propertyType,
                'leaseStartDate' => $request->leaseStartDate,
                'leaseEndDate' => $request->leaseEndDate,
                'leaseType' => $request->leaseType,
                'rentAmount' => $request->rentAmount,
                'rentDueDate' => $request->rentDueDate,
                'rentPaymentMethod' => $request->rentPaymentMethod,
                'securityDepositAmount' => $request->securityDepositAmount,
                'includedUtilities' => $request->includedUtilities,
                'tenantResponsibilities' => $request->tenantResponsibilities,
                'emergencyContactName' => $request->emergencyContactName,
                'emergencyContactPhone' => $request->emergencyContactPhone,
                'emergencyContactAddress' => $request->emergencyContactAddress,
                'buildingSuperintendentName' => $request->buildingSuperintendentName,
                'buildingSuperintendentAddress' => $request->buildingSuperintendentAddress,
                'buildingSuperintendentPhone' => $request->buildingSuperintendentPhone,
                'rentIncreaseNoticePeriod' => $request->rentIncreaseNoticePeriod,
                'noticePeriodForTermination' => $request->noticePeriodForTermination,
                'latePaymentFee' => $request->latePaymentFee,
                'rentalIncentives' => $request->rentalIncentives,
                'additionalTerms' => $request->additionalTerms,
                'status ' => '0'
            ]);
        
            return response()->json([
                'status' => true,
                'message' => 'Contract Added Successfully...',
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Fill Required Fields',
                'data' => [],
            ], 202);
        }
    }

    public function getContract(){

        $userId = Auth::id();
        
        $expiryDate = Carbon::now()->subDays(15); 

        Contract::where('status', 0)
            ->where('created_at', '<', $expiryDate)
            ->update(['status' => 3]);
        
        $contracts = Contract::with(['property','tenant','landlord'])
            ->where('user_id', $userId)
            ->orderByDesc('created_at')
            ->paginate(20);

        return response()->json([
            'status' => true,
            'data' => $contracts,
            'message' => 'Data Found.',
        ], 200);
    }

    public function getTanentContractProperty(){

        $userId = Auth::id();
        $property_ids = Contract::where('user_id', $userId)->wherestatus('1')->distinct()->pluck('property_id');
        $properties = Property::whereIn('id', $property_ids)->orderByDesc('created_at')->paginate(20);

        return response()->json([
            'status' => true,
            'data' => $properties,
            'message' => 'Data Found.',
        ], 200);
    }

    public function getLandlordContract(){
        $userId = Auth::id();
                
        $expiryDate = Carbon::now()->subDays(15); 

        Contract::where('status', 0)
            ->where('created_at', '<', $expiryDate)
            ->update(['status' => 3]);
        
        $contracts = Contract::with(['property','tenant','landlord'])
            ->where('landlord_id', $userId)
            ->orderByDesc('created_at')
            ->paginate(20);

        return response()->json([
           'status' => true,
            'data' => $contracts,
           'message' => 'Data Found.',
        ], 200);
    }

    public function markContractStatus(Request $request){
        $id = $request->contract_id;
        $status = $request->status;
        $contract = Contract::find($id);
        if ($contract) {

            $contract->status = $status;
            $contract->save();

            return response()->json([
             'status' => true,
             'message' => 'Contract Status Updated Successfully.',
            ], 200);
        } else {
            return response()->json([
             'status' => false,
             'message' => 'Contract Not Found.',
            ], 202);
        }
    }
    
    public function getContractDetail($id){
        $contract = Contract::with(['property','tenant','landlord'])->whereid($id)->get();
        if ($contract->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No Data Found.',
                'data' => []
            ], 404);
        }
        return response()->json([
            'status' => true,
            'data' => $contract,
            'message' => 'Successfully fetched'
        ], 200);
    }

    // Notification
    public function sendNotification($option)
    {
        
        $curl = curl_init(); 
        $user = User::findOrFail($option['created_to']);
        $deviceToken = $user->device_token;
        $platform = $user->platform;
        
        $created_to = $option['created_to'];
        $created_by = $option['created_by'];
        // $action_name = $option['action_name'];
        $title = $option['title'];
        $body = $option['body'];
        $data = array(
            'to' => $deviceToken,
            'notification' => array(
                'title' => $title,
                'body' => $body
            )
        );
        
        $payload = json_encode($data);
        return $payload;

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://fcm.googleapis.com/fcm/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Bearer AAAAwG3fBRY:APA91bGswE_GtChlZU3fq5A6iLypoG90MsPnx7TRTzAhM3HuPgKiL9RbHhAFNw0QmZFUSbj6vMXEZ1YtNNweKYvmt3BNm5VK-hmbBCYxU6llDzU-5Mh_Vyp2_uhCHHtvE3TgsswxdJTL'
            ),
        ));
        
        $response = curl_exec($curl);
        
        Notification::create([
            'title' => $title,
            'body' => $body,
            'created_by' => $created_by,
            'created_to' => $created_to,
            'action_date' =>  Carbon::now()
        ]);

        curl_close($curl);
        
        return $response;

    }
    // Chat
    
    
    // public function sendMessage(Request $request)
    // {
        
    //     $receiver_id = $request->input('receiver_id');

    //     if (!$receiver_id) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Receiver Id Required'
    //         ], 400);
    //     }
    //     $type = $request->input('type');
    //     if ($type == 1) {
    //         $messagefile = $request->file('message');
            
    //         if (!$messagefile) {
    //             return response()->json([
    //                 'status' => false,
    //                 'message' => 'File is required for type 1 message'
    //             ], 400);
    //         }
            
    //         $messagefileimage = 'File-' . uniqid() . '-' . $messagefile->getClientOriginalName();
    //         $messagefilefilePath = public_path('/assets/chat');
    
    //         if (!File::isDirectory($messagefilefilePath)) {
    //             File::makeDirectory($messagefilefilePath, 0777, true, true);
    //         }
    
    //         $messagefileimg = Image::make($messagefile->getRealPath());
    //         $messagefileimg->save($messagefilefilePath . '/' . $messagefileimage);
    //         $message = $messagefileimage;
    //     } elseif($type == 0) {
    //         $message = $request->input('message');
    //     }else {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Invalid message type. Only type 1 (file) and type 2 (text) are supported.'
    //         ], 400);
    //     }


    //     $message = Message::create([
    //         'sender_id' => Auth::id(),
    //         'receiver_id' => $receiver_id,
    //         'message' => $message,
    //         'type' => $request->type
    //     ]);
    //     return response()->json(['status'=>true,'message' => 'Message sent'],200);
    // }
    
    // broadcast(new MessageSent($message));
    // // Broadcast::event(new MessageSent($message));
    // // Broadcast the event
    // // broadcast(new MessageSent($message))->toOthers();
    // // broadcast(new MessageSent('new', Auth::id()))->toOthers();
    // // broadcast(new ChatMessageSent($message, Auth::id(), $receiver_id))->toOthers();
    // // event(new ChatMessageSent($message, Auth::id(), $receiver_id));
    // // $c = broadcast(new ChatMessageSent());
    // // event(new ChatMessageSent($message));

    public function inboxListing(Request $request)
    {
        $userId = Auth::id();
        // $userId = Auth::id();
        $page = $request->input('page', 1); // Default to page 1 if not provided

        $perPage = 10; // You can change this value according to your needs

        $sender_ids = Message::where('sender_id', $userId)
        ->whereColumn('sender_id', '!=', 'receiver_id')
            ->orderBy('created_at', 'desc')
            ->offset(($page - 1) * $perPage)
            ->limit($perPage)
            ->pluck('sender_id');

        // Retrieve receiver IDs for the current page
        $receiver_ids = Message::where('sender_id', $userId)
        ->whereColumn('sender_id', '!=', 'receiver_id')
            ->orderBy('created_at', 'desc')
            ->offset(($page - 1) * $perPage)
            ->limit($perPage)
            ->pluck('receiver_id');
        // $user_ids = array_unique(array_merge($sender_ids->toArray(), $receiver_ids->toArray()));
        $inboxListing = [];

        foreach ($user_ids as $user_id) {
            // Retrieve user details
            $user = User::find($user_id);

            // Retrieve the last message sent or received by the user
            $last_message = Message::with(['receiver'])
                ->where(function ($query) use ($userId, $user_id) {
                    $query->where('sender_id', $userId)->where('receiver_id', $user_id);
                })
                ->orWhere(function ($query) use ($userId, $user_id) {
                    $query->where('sender_id', $user_id)->where('receiver_id', $userId);
                })
                ->orderBy('created_at', 'desc')
                ->first();

            // If there's no last message, but the user is a sender, get their last message
            if (!$last_message && in_array($user_id, $sender_ids->toArray())) {
                $last_message = Message::with(['receiver'])
                    ->where('sender_id', $user_id)
                    ->where('receiver_id', $userId)
                    ->orderBy('created_at', 'desc')
                    ->first();
            }

            // Append user details and last message to the inboxListing array
            if ($user && $last_message) {
                $inboxListing[] = [
                    'user_details' => $user,
                    'last_message' => $last_message,
                ];
            }
        }

        return response()->json([
            'status' => true,
            'data' => $inboxListing,
            'message' => 'Message sent successfully'
        ], 200);
        // $latestMessagesSubquery = DB::table('messages')
        //     ->select(DB::raw('MAX(id) as id'))
        //     ->where('sender_id', $userId)
        //     ->orWhere('receiver_id', $userId)
        //     ->groupBy(DB::raw('IF(sender_id > receiver_id, sender_id, receiver_id), IF(sender_id > receiver_id, receiver_id, sender_id)'));

        // $messages = DB::table('messages')
        //     ->joinSub($latestMessagesSubquery, 'latest_messages', function ($join) {
        //         $join->on('messages.id', '=', 'latest_messages.id');
        //     })
        //     ->orderBy('created_at', 'desc')
        //     ->paginate(10);

        // return response()->json(['messages' => $messages], 200);
    }

    public function getChatMessages(Request $request)
    {
        $senderId = Auth::id();
        $receiverId = $request->input('receiver_id');
        $perPage = $request->input('per_page', 30); // Default per page is 10, adjust as needed
        $page = $request->input('page', 1);

        $messagesQuery = Message::where(function ($query) use ($senderId, $receiverId) {
            $query->where('sender_id', $senderId)
                ->where('receiver_id', $receiverId);
        })->orWhere(function ($query) use ($senderId, $receiverId) {
            $query->where('sender_id', $receiverId)
                ->where('receiver_id', $senderId);
        });

        $messages = $messagesQuery->orderBy('created_at', 'desc')->paginate($perPage, ['*'], 'page', $page);
        return response()->json(['status'=>true,'data'=>$messages,'message' => 'Messages Found'],200);
    }
    
    function generateCustomToken() {
        return Str::random(60); // Generate a random string token
    }

    public function sendResetLinkEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->messages()->toArray()
            ], 422); // Use 422 for validation failures
        }
    
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'This email address is not registered.'
            ], 404);
        }
    
        $customToken = Str::random(60);
        // Check if a token already exists for the email
        $tokenData = DB::table('password_resets')->where('email', $user->email)->first();
        if ($tokenData) {
            // Update the existing token's expiration time
            DB::table('password_resets')->where('email', $user->email)->update([
                'created_at' => now(), // Reset the creation time
                'token' => $customToken, // Generate a new token
            ]);
        } else {
            // Generate a new token
            $customToken = Str::random(60);
            DB::table('password_resets')->insert([
                'email' => $user->email,
                'token' => $customToken,
                'created_at' => now(),
            ]);
        }

        try {
            $mailData = [
                'email' => $request->email,
                'token' => $customToken ?? $tokenData->token,
            ];
             
            Mail::to($request->email)->send(new UserMail($mailData));
            return response()->json([
                'status' => true,
                'message' => 'Custom password reset link has been sent to your email address.'
            ]);
        } catch (\Exception $e) {
            // Log the exception
            \Log::error('Error sending password reset link: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function showResetForm(Request $request, $token)
    {
        $email = $request->email ?? '';

        // Retrieve the token record based on email and token
        $tokenData = DB::table('password_resets')
            ->where('email', $email)
            ->where('token', $token)
            ->first();

        if (!$tokenData) {
            // Token not found or email/token combination is invalid
            return redirect()->route('expired')->with('error', 'Invalid or expired password reset link.');
        }

        $createdAt = Carbon::parse($tokenData->created_at);
        $expiryTime = $createdAt->addDay(1); // Assuming tokens expire in 1 day

        if ($expiryTime->isPast()) {
            // Token has expired
            return redirect()->route('expired')->with('error', 'Password reset link has expired.');
        }

        // Proceed to show the reset form
        return view('auth.passwords.reset', [
            'token' => $token,
            'email' => $email,
        ]);
    }

    // Reset the password
    public function reset(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
            'token' => 'required',
        ],
        [
            'email.required' => 'Email is required',
            'password.required' => 'password is required'
        ]);

        $tokenData = DB::table('password_resets')->where('email', $request->email)->first();

        if (!$tokenData) {
            return redirect()->route('expired')->with('error', 'Invalid or expired password reset link.');
        }

        if (Carbon::parse($tokenData->created_at)->addDay(1)->isPast()) {
            // Token has expired
            return redirect()->route('expired')->with('error', 'Password reset link has expired.');
        }

        if (!Hash::check($request->token, $tokenData->token)) {
            return redirect()->route('expired')->with('error', 'Invalid password reset link.');
        }

        // Proceed with password reset
        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        // Delete the token after successful reset
        DB::table('password_resets')->where('email', $request->email)->delete();

        return redirect()->route('done')->with('status', 'Password has been reset successfully. Please log in.');
    }


    public function approvedContractProperty(){

        $property_ids = Contract::wherestatus('1')->distinct()->pluck('property_id');
        $properties = Property::with(['user'])->whereIn('id', $property_ids)->orderByDesc('created_at')->paginate(20);

        return response()->json([
            'status' => true,
            'data' => $properties,
            'message' => 'Data Found.',
        ], 200);
    }




    






}
