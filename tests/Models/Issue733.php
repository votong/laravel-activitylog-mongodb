<?php

namespace Votong\Activitylog\Test\Models;

use Votong\Activitylog\LogOptions;
use Votong\Activitylog\Traits\LogsActivity;

class Issue733 extends Article
{
    use LogsActivity;

    protected static $recordEvents = [
        'retrieved',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->dontSubmitEmptyLogs()
        ->logOnly(['name']);
    }
}
