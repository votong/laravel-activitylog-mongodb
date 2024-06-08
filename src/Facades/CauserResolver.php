<?php

namespace VoTong\Activitylog\Facades;

use Illuminate\Support\Facades\Facade;
use VoTong\Activitylog\CauserResolver as ActivitylogCauserResolver;

/**
 * @method static \MongoDB\Laravel\Eloquent\Model|null resolve(\MongoDB\Laravel\Eloquent\Model|int|string|null $subject = null)
 * @method static \VoTong\Activitylog\CauserResolver resolveUsing(\Closure $callback)
 * @method static \VoTong\Activitylog\CauserResolver setCauser(\MongoDB\Laravel\Eloquent\Model|null $causer)
 *
 * @see \VoTong\Activitylog\CauserResolver
 */
class CauserResolver extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return ActivitylogCauserResolver::class;
    }
}
