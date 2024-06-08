<?php

namespace VoTong\Activitylog\Contracts;

use Closure;
use VoTong\Activitylog\EventLogBag;

interface LoggablePipe
{
    public function handle(EventLogBag $event, Closure $next): EventLogBag;
}
