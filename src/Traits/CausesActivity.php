<?php

namespace Votong\Activitylog\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Votong\Activitylog\ActivitylogServiceProvider;

trait CausesActivity
{
    public function actions(): MorphMany
    {
        return $this->morphMany(
            ActivitylogServiceProvider::determineActivityModel(),
            'causer'
        );
    }
}
