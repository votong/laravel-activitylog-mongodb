<?php

namespace Votong\Activitylog;

use Jenssegers\Mongodb\Eloquent\Model;
use Votong\Activitylog\Contracts\Activity;
use Votong\Activitylog\Contracts\Activity as ActivityContract;
use Votong\Activitylog\Exceptions\InvalidConfiguration;
use Votong\Activitylog\Models\Activity as ActivityModel;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class ActivitylogServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
        ->name('laravel-activitylog')
        ->hasConfigFile('activitylog')
        ->hasMigrations([
            'CreateActivityLogTable',
            'AddEventColumnToActivityLogTable',
            'AddBatchUuidColumnToActivityLogTable',
        ])
        ->hasCommand(CleanActivitylogCommand::class);
    }

    public function registeringPackage()
    {
        $this->app->bind(ActivityLogger::class);

        $this->app->singleton(LogBatch::class);

        $this->app->singleton(CauserResolver::class);

        $this->app->singleton(ActivityLogStatus::class);
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
