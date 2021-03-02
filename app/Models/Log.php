<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Log extends Model
{
    use SoftDeletes;

    public $table = 'admin_log';

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s'
    ];



    protected $fillable = [
      'url','ip','method'
    ];
}
