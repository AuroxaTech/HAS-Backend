<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceProvider extends Model
{
    use HasFactory;
     
    protected $fillable = [
        'user_id',
        'services',
        'year_experience',
        'availability_start_time',
        'availability_end_time',
        'cnic_front',
        'cnic_end',
        'certification',
        'file'
    ];
    
    // public function services(){
    //     return $this->belongsToMany(Service::class,'service_provider_services','service_provider_id','service_id');
    // }

    
    public function provider_service(){
        return $this->belongsTo(ProviderService::class,'services','id');
    }
    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }
}
