<?php

namespace VoTong\Activitylog\Facades;

use Illuminate\Support\Facades\Facade;
use VoTong\Activitylog\LogBatch as ActivityLogBatch;

/**
 * @method static string getUuid()
 * @method static mixed withinBatch(\Closure $callback)
 * @method static void startBatch()
 * @method static void setBatch(string $uuid): void
 * @method static bool isOpen()
 * @method static void endBatch()
 *
 * @see \VoTong\Activitylog\LogBatch
 */
class LogBatch extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return ActivityLogBatch::class;
    }
}
