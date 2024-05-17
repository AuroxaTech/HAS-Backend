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
use Image;
use Illuminate\Support\Facades\File;
use DB;
use Illuminate\Support\Facades\Http;


class DashboardController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
    }
    
    public function adminRegister(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fullname' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required|confirmed',
            'role_id' => 'required',
            'phone_number' => 'required'
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'status'=>false,
                'messages' => $validator->messages()->toArray(), 
                'error' => 'invalid_fields_data'
            ], 202);
        }
        
        if($request->role_id == 5){
            $image_name = '';
            if ($request->hasFile('profileimage')) {
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
            $user = User::create([
                'fullname' => $request->fullname,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'password' => Hash::make($request->password),
                'role_id' => $request->role_id, // Use the provided role_id
                'profileimage' => $image_name
            ]);

            return response()->json([
                'status' => true,
                'messages' => 'Registered Successfully'
            ], 200);
        }else{
            
            return response()->json([
                'status' => true,
                'messages' => 'Role should be admin'
            ], 200);            
        }
    }

    public function getUsers($role_id)
    {
        if($role_id =='5'){
            $user = [];
        }else{
            $users = User::where('role_id', $role_id)->paginate(20);
        }
        
        return response()->json([
            'status' => true,
            'data' => $users,
            'message' => 'Data Found....'
        ], 200);
    }
    
    public function getProperties($landlord_id)
    {
        $users = User::select('role_id')->whereid($landlord_id)->first();
           
        if (!$users) {
            return response()->json([
                'status' => false,
                'message' => 'No Data Found.',
                'data' => []
            ], 404);
        }
        
        if($users->role_id == '1'){
            $properties = Property::selectuser_id($landlord_id)->paginate(20);
        }else{
            $properties = Property::paginate(20);
        }
        
        return response()->json([
            'status' => true,
            'data' => $properties,
            'message' => 'Data Found....'
        ], 200);
    }

    public function destroyUser($id){

        $user = User::find($id);
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found'
            ], 404); // Use 404 for not found
        }

        switch ($user->role_id) {
            case 1:
                // $landlordIds = Landlord::where('user_id', $id)->pluck('id');
                $propertyIds = Property::where('user_id', $id)->pluck('id');
    
                FavouriteProperty::whereIn('property_id', $propertyIds)->delete();
                Property::where('user_id', $id)->delete();
                Landlord::where('user_id', $id)->delete();
                User::whereid($id)->delete();
                break;
            case 2:
                Tenant::where('user_id', $id)->delete();
                User::whereid($id)->delete();
                break;
            case 3:
                $serviceIds = Service::where('user_id', $id)->pluck('id');
                $requestIds = ServiceProviderRequest::whereIn('service_id', $serviceIds)->pluck('id');
                $jobIds = ServiceProviderJob::whereIn('request_id', $requestIds)->pluck('id');
                
                ServiceProviderJob::whereIn('request_id', $jobIds)->delete();
                ServiceProviderRequest::whereIn('id', $requestIds)->delete();
                ServiceReview::whereIn('service_id', $serviceIds)->delete();
                Service::whereIn('id', $serviceIds)->delete();
                ServiceProvider::where('user_id', $id)->delete();
                User::whereid($id)->delete();
                break;
            case 4:
                Visitor::where('user_id', $id)->delete();
                User::whereid($id)->delete();
                break;
        }
    

        return response()->json([
            'status' => true,
            'message' => 'User Deleted Successfully'
        ], 200);
    }

    public function getAllContracts() {
        
        $contracts = Contract::with(['property','tenant','landlord'])
            ->orderByDesc('created_at')
            ->paginate(20);

        return response()->json([
            'status' => true,
            'data' => $contracts,
            'message' => 'Data Found.',
        ], 200);
    }
    
    public function getContract($id){

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

    public function destroyProperty($id){

        $property = Property::find($id);
        if (!$property) {
            return response()->json([
                'status' => false,
                'message' => 'Property not found'
            ], 404); // Use 404 for not found
        }

        Property::whereid($id)->delete();
    

        return response()->json([
            'status' => true,
            'message' => 'Property Deleted Successfully'
        ], 200);
    }

    
    public function destroyContract($id){

        $contract = Contract::find($id);
        if (!$contract) {
            return response()->json([
                'status' => false,
                'message' => 'Contract not found'
            ], 404); // Use 404 for not found
        }

        Contract::whereid($id)->delete();
    

        return response()->json([
            'status' => true,
            'message' => 'Contract Deleted Successfully'
        ], 200);
    }

    public function getTanentContract($id){

        $contract = Contract::with(['property','tenant','landlord'])->whereuser_id($id)->get();
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
    
    public function getProviderServices($id){

        $service = Service::with(['user','provider'])->whereuser_id($id)->get();
        
        if ($service->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No Data Found.',
                'data' => []
            ], 404);
        }
        
        return response()->json([
            'status' => true,
            'data' => $service,
            'message' => 'Successfully fetched'
        ], 200);
    }


}
