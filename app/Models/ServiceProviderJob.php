<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceProviderJob extends Model
{
    use HasFactory;
    
     
    protected $fillable = [
        'user_id',
        'request_id',
        'provider_id',
        'status'
    ];
    
    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }
    public function provider(){
        return $this->belongsTo(User::class,'provider_id','id');
    }
    
    public function request(){
        return $this->belongsTo(ServiceProviderRequest::class,'request_id','id');
    }

}
