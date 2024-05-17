<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FavouriteService extends Model
{
    use HasFactory;
      
    protected $fillable = [
        'user_id',
        'service_id',
        'fav_flag'
    ];
    
    public function service(){
        return $this->belongsTo(Service::class,'service_id','id');
    }
    
    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }
    
}
