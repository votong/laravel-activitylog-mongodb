<?php

namespace Votong\Activitylog\Test\Models;

use Votong\Activitylog\Models\Activity;

class CustomDatabaseConnectionOnActivityModel extends Activity
{
    protected $connection = 'custom_connection_name';
}
