<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $fillable = ['answers'];
    
    public function answers() {
        return $this->belongsToMany('App\Question');
    }
}
