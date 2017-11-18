<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Image extends Model
{
    use SoftDeletes;

    protected $table = 'images';
    
    protected $dates = ['deleted_at'];

    public $fillable = ['users_id', 'filename', 'filetype', 'filesize', 'url_cloud'];

    public $hidden = ['updated_at', 'deleted_at']; 
}
