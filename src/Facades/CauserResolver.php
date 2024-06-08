<?php

namespace Spatie\Activitylog\Facades;

use Illuminate\Support\Facades\Facade;
use Spatie\Activitylog\CauserResolver as ActivitylogCauserResolver;

/**
 * @method static \MongoDB\Laravel\Eloquent\Model|null resolve(\MongoDB\Laravel\Eloquent\Model|int|string|null $subject = null)
 * @method static \Spatie\Activitylog\CauserResolver resolveUsing(\Closure $callback)
 * @method static \Spatie\Activitylog\CauserResolver setCauser(\MongoDB\Laravel\Eloquent\Model|null $causer)
 *
 * @see \Spatie\Activitylog\CauserResolver
 */
class CauserResolver extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return ActivitylogCauserResolver::class;
    }
}
