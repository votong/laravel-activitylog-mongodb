<?php

namespace Votong\Activitylog\Test;

use AddBatchUuidColumnToActivityLogTable;
use AddEventColumnToActivityLogTable;
use CreateActivityLogTable;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Encryption\Encrypter;
use Illuminate\Support\Facades\Schema;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Votong\Activitylog\ActivitylogServiceProvider;
use Votong\Activitylog\Models\Activity;
use Votong\Activitylog\Test\Models\Article;
use Votong\Activitylog\Test\Models\User;

abstract class TestCase extends OrchestraTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabase();
    }

    protected function getPackageProviders($app)
    {
        return [
            ActivitylogServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('activitylog.database_connection', 'sqlite');
        config()->set('database.default', 'sqlite');
        config()->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
        ]);

        config()->set('auth.providers.users.model', User::class);
        config()->set('app.key', 'base64:'.base64_encode(
            Encrypter::generateKey(config()['app.cipher'])
        ));
    }

    protected function setUpDatabase()
    {
        $this->migrateActivityLogTable();

        $this->createTables('articles', 'users');
        $this->seedModels(Article::class, User::class);
    }

    protected function migrateActivityLogTable()
    {
        require_once __DIR__.'/../database/migrations/CreateActivityLogTable.php.stub';
        require_once __DIR__.'/../database/migrations/AddEventColumnToActivityLogTable.php.stub';
        require_once __DIR__.'/../database/migrations/AddBatchUuidColumnToActivityLogTable.php.stub';

        (new CreateActivityLogTable())->up();
        (new AddEventColumnToActivityLogTable())->up();
        (new AddBatchUuidColumnToActivityLogTable())->up();
    }

    protected function createTables(...$tableNames)
    {
        collect($tableNames)->each(function (string $tableName) {
            Schema::create($tableName, function (Blueprint $table) use ($tableName) {
                $table->increments('id');
                $table->string('name')->nullable();
                $table->string('text')->nullable();
                $table->timestamps();
                $table->softDeletes();

                if ($tableName === 'articles') {
                    $table->integer('user_id')->unsigned()->nullable();
                    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                    $table->text('json')->nullable();
                    $table->string('interval')->nullable();
                    $table->decimal('price')->nullable();
                }
            });
        });
    }

    protected function seedModels(...$modelClasses)
    {
        collect($modelClasses)->each(function (string $modelClass) {
            foreach (range(1, 0) as $index) {
                $modelClass::create(['name' => "name {$index}"]);
            }
        });
    }

    public function getLastActivity(): ?Activity
    {
        return Activity::all()->last();
    }

    public function markTestAsPassed(): void
    {
        $this->assertTrue(true);
    }
}
