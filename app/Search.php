<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
 use App\User ;
 
class Search extends Model {

    protected $guarded = [];
 
    
    public function User() {
        return $this->belongsTo(User::class , 'user_id') ;
    }
    
    
}
