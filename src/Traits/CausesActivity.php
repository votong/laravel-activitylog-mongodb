<?php

namespace VoTong\Activitylog\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use VoTong\Activitylog\ActivitylogServiceProvider;

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
