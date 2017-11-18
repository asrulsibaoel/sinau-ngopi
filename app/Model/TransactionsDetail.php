<?php
namespace App\Model;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransactionsDetail extends Model
{
	use SoftDeletes;
    
    protected $dates = ['deleted_at'];
    //
    public $fillable=['transactions_id','products_id','real_price','bargain_price','created_at'];

    public $hidden=['updated_at','deleted_at'];
 
	public function product()
    {
        return $this->belongsTo('App\Model\Product','products_id');
    }
	public function transaction()
    {
        return $this->belongsTo('App\Model\Transaction','transactions_id');
    }



}

