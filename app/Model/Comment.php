<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use SoftDeletes;
    
    protected $dates = ['deleted_at'];
    //
    public $fillable=['messages','products_id','users_id','created_at'];

    public $hidden=['updated_at','deleted_at'];
 
	public function user()
    {
        return $this->belongsTo('App\User','users_id');
    }

    public function product()
    {
        return $this->belongsTo('App\Model\Product','products_id');
    }    
}
