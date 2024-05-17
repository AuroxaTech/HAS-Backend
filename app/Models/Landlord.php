<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Landlord extends Model
{
    use HasFactory;
      
    protected $fillable = [
        'user_id',
        'no_of_property',
        'availability_start_time',
        'availability_end_time',
    ];
    
    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }
    
}
