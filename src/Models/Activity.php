<?php

namespace VoTong\Activitylog\Models;

use MongoDB\Laravel\Eloquent\Builder;
use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use VoTong\Activitylog\Contracts\Activity as ActivityContract;

/**
 * VoTong\Activitylog\Models\Activity.
 *
 * @property int $id
 * @property string|null $log_name
 * @property string $description
 * @property string|null $subject_type
 * @property int|null $subject_id
 * @property string|null $causer_type
 * @property int|null $causer_id
 * @property string|null $event
 * @property string|null $batch_uuid
 * @property \Illuminate\Support\Collection|null $properties
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \MongoDB\Laravel\Eloquent\Model|\Eloquent $causer
 * @property-read \Illuminate\Support\Collection $changes
 * @property-read \MongoDB\Laravel\Eloquent\Model|\Eloquent $subject
 *
 * @method static \MongoDB\Laravel\Eloquent\Builder|\VoTong\Activitylog\Models\Activity causedBy(\MongoDB\Laravel\Eloquent\Model $causer)
 * @method static \MongoDB\Laravel\Eloquent\Builder|\VoTong\Activitylog\Models\Activity forBatch(string $batchUuid)
 * @method static \MongoDB\Laravel\Eloquent\Builder|\VoTong\Activitylog\Models\Activity forEvent(string $event)
 * @method static \MongoDB\Laravel\Eloquent\Builder|\VoTong\Activitylog\Models\Activity forSubject(\MongoDB\Laravel\Eloquent\Model $subject)
 * @method static \MongoDB\Laravel\Eloquent\Builder|\VoTong\Activitylog\Models\Activity hasBatch()
 * @method static \MongoDB\Laravel\Eloquent\Builder|\VoTong\Activitylog\Models\Activity inLog($logNames)
 * @method static \MongoDB\Laravel\Eloquent\Builder|\VoTong\Activitylog\Models\Activity newModelQuery()
 * @method static \MongoDB\Laravel\Eloquent\Builder|\VoTong\Activitylog\Models\Activity newQuery()
 * @method static \MongoDB\Laravel\Eloquent\Builder|\VoTong\Activitylog\Models\Activity query()
 */
class Activity extends Model implements ActivityContract
{
    public $guarded = [];

    protected $casts = [
        'properties' => 'collection',
    ];

    public function __construct(array $attributes = [])
    {
        if (! isset($this->connection)) {
            $this->setConnection(config('activitylog.database_connection'));
        }

        if (! isset($this->table)) {
            $this->setTable(config('activitylog.table_name'));
        }

        parent::__construct($attributes);
    }

    public function subject(): MorphTo
    {
        if (config('activitylog.subject_returns_soft_deleted_models')) {
            return $this->morphTo()->withTrashed();
        }

        return $this->morphTo();
    }

    public function causer(): MorphTo
    {
        return $this->morphTo();
    }

    public function getExtraProperty(string $propertyName, mixed $defaultValue = null): mixed
    {
        return Arr::get($this->properties->toArray(), $propertyName, $defaultValue);
    }

    public function changes(): Collection
    {
        if (! $this->properties instanceof Collection) {
            return new Collection();
        }

        return $this->properties->only(['attributes', 'old']);
    }

    public function getChangesAttribute(): Collection
    {
        return $this->changes();
    }

    public function scopeInLog(Builder $query, ...$logNames): Builder
    {
        if (is_array($logNames[0])) {
            $logNames = $logNames[0];
        }

        return $query->whereIn('log_name', $logNames);
    }

    public function scopeCausedBy(Builder $query, Model $causer): Builder
    {
        return $query
            ->where('causer_type', $causer->getMorphClass())
            ->where('causer_id', $causer->getKey());
    }

    public function scopeForSubject(Builder $query, Model $subject): Builder
    {
        return $query
            ->where('subject_type', $subject->getMorphClass())
            ->where('subject_id', $subject->getKey());
    }

    public function scopeForEvent(Builder $query, string $event): Builder
    {
        return $query->where('event', $event);
    }

    public function scopeHasBatch(Builder $query): Builder
    {
        return $query->whereNotNull('batch_uuid');
    }

    public function scopeForBatch(Builder $query, string $batchUuid): Builder
    {
        return $query->where('batch_uuid', $batchUuid);
    }
}
