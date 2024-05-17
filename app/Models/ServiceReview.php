<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceReview extends Model
{
    use HasFactory;
      
    protected $fillable = [
        'user_id',
        'service_id',
        'property_sub_type_id',
        'rate',
        'description'
    ];

    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }
    
    public function service(){
        return $this->belongsTo(Service::class,'service_id','id');
    }
    public function subpropertytype(){
        return $this->belongsTo(PropertySubType::class,'property_sub_type_id','id');
    }
    
    
}
