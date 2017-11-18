<?php
namespace App\Model;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChatsRoom extends Model
{
	use SoftDeletes;
    
    protected $dates = ['deleted_at'];
    //
    public $fillable=['name','created_at','sender_id','receiver_id'];

    public $hidden=['updated_at','deleted_at'];


    public function sender()
    {
        return $this->belongsTo('App\User','sender_id');
    }
    public function receiver()
    {
        return $this->belongsTo('App\User','receiver_id');
    }
}

