<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User ;
 
class Image extends Model {

    protected $guarded = [];
 
    
    public function User() {
        return $this->belongsTo(User::class , 'user_id') ;
    }
    
    
}
