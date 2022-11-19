<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permissions extends Model
{
    use SoftDeletes;

    public const IS_MENU_YES = 1;
    public const IS_MENU_NO = 0;

    public const STATUS_DOWN = 0;
    public const STATUS_OK = 1;

    public const HIDDEN_YES = 1;
    public const HIDDEN_NO = 0;

    public const HTTP_REQUEST_ALL = '*';
    public const HTTP_REQUEST_GET = 'GET';
    public const HTTP_REQUEST_POST = 'POST';
    public const HTTP_REQUEST_PUT = 'PUT';
    public const HTTP_REQUEST_PATCH = 'PATCH';
    public const HTTP_REQUEST_DELETE = 'DELETE';

    protected $table = 'admin_permissions';

    public function getIsMenuAttribute($key)
    {
        if (1 === $key) {
            return true;
        }

        return false;
    }

    public function getPid()
    {
        return $this->hasOne(self::class, 'id', 'p_id')
            ->select(['id', 'p_id', 'path', 'name', 'title', 'icon', 'method', 'url'])
        ;
    }
}
