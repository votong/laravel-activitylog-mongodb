<?php

namespace VoTong\Activitylog;

use MongoDB\Laravel\Eloquent\Model;
use VoTong\Activitylog\Contracts\Activity;
use VoTong\Activitylog\Contracts\Activity as ActivityContract;
use VoTong\Activitylog\Exceptions\InvalidConfiguration;
use VoTong\Activitylog\Models\Activity as ActivityModel;
use VoTong\LaravelPackageTools\Package;
use VoTong\LaravelPackageTools\PackageServiceProvider;

class ActivitylogServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
        ->name('laravel-activitylog')
        ->hasConfigFile('activitylog')
        ->hasMigrations([
            'create_activity_log_table',
            'add_event_column_to_activity_log_table',
            'add_batch_uuid_column_to_activity_log_table',
        ])
        ->hasCommand(CleanActivitylogCommand::class);
    }

    public function registeringPackage()
    {
        $this->app->bind(ActivityLogger::class);

        $this->app->scoped(LogBatch::class);

        $this->app->scoped(CauserResolver::class);

        $this->app->scoped(ActivityLogStatus::class);
    }

    public static function determineActivityModel(): string
    {
        $activityModel = config('activitylog.activity_model') ?? ActivityModel::class;

        if (! is_a($activityModel, Activity::class, true)
            || ! is_a($activityModel, Model::class, true)) {
            throw InvalidConfiguration::modelIsNotValid($activityModel);
        }

        return $activityModel;
    }

    public static function getActivityModelInstance(): ActivityContract
    {
        $activityModelClassName = self::determineActivityModel();

        return new $activityModelClassName();
    }
}
