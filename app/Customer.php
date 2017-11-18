<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    //
    protected $table="customers";
	protected $fillable=['name','address','votes'];

	static function something(){
		return "somehthing happened";


	}

	static function tampildata(){

	}

	

}

