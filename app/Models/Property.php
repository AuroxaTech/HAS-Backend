<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    use HasFactory;
     
    protected $fillable = [
        'user_id',
        'type',
        'images',
        'city',
        'amount',
        'address',
        'lat',
        'long',
        'area_range',
        'bedroom',
        'bathroom',
        'description',
        'electricity_bill',
        'property_type',
        'property_sub_type',
    ];

    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }
}
