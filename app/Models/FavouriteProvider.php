<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FavouriteProvider extends Model
{
    use HasFactory;
      
    protected $fillable = [
        'user_id',
        'provider_id',
        'fav_flag'
    ];
    
    public function provider(){
        return $this->belongsTo(ServiceProvider::class,'provider_id','id');
    }
    
}
