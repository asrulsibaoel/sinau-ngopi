<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;
    
    protected $dates = ['deleted_at'];
    //
    public $fillable=['name','price','description','images','categories_id','users_id','created_at'];
 
    public $hidden=['updated_at','deleted_at'];

    public function user()
    {
        return $this->belongsTo('App\User','users_id');
    }

    public function category(){
    	return $this->belongsTo('App\Model\Category','categories_id');
    }
}
