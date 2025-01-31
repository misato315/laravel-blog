<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    #To get the owner of the post
    public function user(){
        return $this -> belongsTo(User::class);
    }
    #To get the cooments related to a post 
    public function comments(){
        return $this->hasMany(Comment::class);
    }
}
