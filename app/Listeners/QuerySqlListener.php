<?php
/**
 * Created By PhpStorm.
 * User : Latent
 * Date : 2022/1/19
 * Time : 4:46 PM.
 */

namespace App\Listeners;

use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\Log;

class QuerySqlListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     */
    public function handle(QueryExecuted $event): void
    {
        if (env('APP_DEBUG')) {
            $sql = str_replace('?', '%s', $event->sql);
            $log = vsprintf($sql, $event->bindings);
            Log::channel('sql')->info($log);
        }
    }
}
