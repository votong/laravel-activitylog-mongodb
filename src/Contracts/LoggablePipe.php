<?php

namespace Votong\Activitylog\Contracts;

use Closure;
use Votong\Activitylog\EventLogBag;

interface LoggablePipe
{
    public function handle(EventLogBag $event, Closure $next): EventLogBag;
}
