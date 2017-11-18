<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use SoftDeletes;
    
	public $incrementing = false;
    protected $dates = ['deleted_at'];
    //
    public $fillable=['id','users_id','discount','tax','subtotal','total','notes','created_at'];

    public $hidden=['updated_at','deleted_at'];
 
	public function user()
    {
        return $this->belongsTo('App\User','users_id');
    }
 
}
