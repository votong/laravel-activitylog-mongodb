<?php

namespace Votong\Activitylog\Test\Models;

use Votong\Activitylog\Models\Activity;

class CustomTableNameOnActivityModel extends Activity
{
    protected $table = 'custom_table_name';
}
