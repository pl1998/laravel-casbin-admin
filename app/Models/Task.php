<?php
/**
 * Created By PhpStorm.
 * User : Latent
 * Date : 2021/8/3
 * Time : 5:02 下午.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    protected $connection = 'sqlite';
//    use SoftDeletes;

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
    ];

    protected $fillable = ['task_name', 'status', 'op_name', 'type', 'email', 'textarea', 'cycle'];
}
