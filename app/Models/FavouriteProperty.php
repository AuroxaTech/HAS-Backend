<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FavouriteProperty extends Model
{
    use HasFactory;
      
    protected $table = 'favourite_properties';
    
    protected $fillable = [
        'user_id',
        'property_id',
        'fav_flag'
    ];
    
    public function property(){
        return $this->belongsTo(Property::class,'property_id','id');
    }
    
}
