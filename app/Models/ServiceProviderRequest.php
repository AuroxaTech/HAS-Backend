<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceProviderRequest extends Model
{
    use HasFactory;
    
     
    protected $fillable = [
        'user_id',
        'serviceprovider_id',
        'service_id',
        'address',
        'lat',
        'long',
        'price',
        'property_type',
        'date',
        'time',
        'description',
        'additional_info',
        'approved',
        'decline',
        'postal_code',
        'is_applied'
    ];

    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }
    public function provider(){
        return $this->belongsTo(User::class,'serviceprovider_id','id');
    }
    public function service(){
        return $this->belongsTo(Service::class,'service_id','id');
    }

    public function property_type(){
        return $this->belongsTo(PropertyType::class,'property_type','id');
    }

}
