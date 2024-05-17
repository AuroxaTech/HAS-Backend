<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

trait TraitFunctions {

  /*-------------------------------------------------------------------------------
  CHECK PERMISSIONS FUNCTION
  -------------------------------------------------------------------------------*/
  public function checkPermissions($permission){
    $permission = Permission::select('id')->wherename($permission)->get()->toArray();
    if($permission){
      return true;
    }else{
      return false;
    }

  }
  
  // public function checkPermissions($permission, $guard_name){
  //   $permission = Permission::select('id')->wherename($permission)->whereguard_name($guard_name)->get()->toArray();
  //   if($permission){
  //     return true;
  //   }else{
  //     return false;
  //   }

  // }

  public function checkRole($role_id){
    $role_data = Role::select('guard_name')->whereid($role_id)->get()->toArray();
    if($role_data){
      return $role_data[0]['guard_name'];
    }else{
      return false;
    }

  }



}
