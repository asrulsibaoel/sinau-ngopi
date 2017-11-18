<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
	use SoftDeletes;
    
    protected $dates = ['deleted_at'];
    //
    public $fillable=['name','created_at'];

    public $hidden=['updated_at','deleted_at'];

}