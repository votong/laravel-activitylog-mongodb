<?php

namespace Votong\Activitylog\Facades;

use Illuminate\Support\Facades\Facade;
use Votong\Activitylog\LogBatch as ActivityLogBatch;

/**
 * @method static string getUuid()
 * @method static mixed withinBatch(\Closure $callback)
 * @method static void startBatch()
 * @method static bool isOpen()
 * @method static void endBatch()
 *
 * @see \Votong\Activitylog\LogBatch
 */
class LogBatch extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return ActivityLogBatch::class;
    }
}
