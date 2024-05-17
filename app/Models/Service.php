<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;
    
     
    protected $fillable = [
        'user_id',
        'service_name',
        'description',
        'category_id',
        'pricing',
        'duration_id',
        'start_time',
        'end_time',
        'location',
        'lat',
        'long',
        'media',
        'additional_information',
        'country',
        'city'
    ];

    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }
    
    public function provider(){
        return $this->belongsTo(ServiceProvider::class,'user_id','user_id');
    }
}
