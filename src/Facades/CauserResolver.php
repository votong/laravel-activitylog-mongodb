<?php

namespace Votong\Activitylog\Facades;

use Illuminate\Support\Facades\Facade;
use Votong\Activitylog\CauserResolver as ActivitylogCauserResolver;

/**
 * @method static \Jenssegers\Mongodb\Eloquent\Model|null resolve(\Jenssegers\Mongodb\Eloquent\Model|int|string|null $subject = null)
 * @method static \Votong\Activitylog\CauserResolver resolveUsing(\Closure $callback)
 * @method static \Votong\Activitylog\CauserResolver setCauser(\Jenssegers\Mongodb\Eloquent\Model|null $causer)
 *
 * @see \Votong\Activitylog\CauserResolver
 */
class CauserResolver extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return ActivitylogCauserResolver::class;
    }
}
