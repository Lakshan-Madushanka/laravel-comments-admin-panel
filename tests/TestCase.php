<?php

namespace LakM\CommentsAdminPanel\Tests;

use Filament\Notifications\NotificationsServiceProvider;
use Illuminate\Database\Schema\Blueprint;
use LakM\Commenter\CommenterServiceProvider;
use LakM\CommentsAdminPanel\CommentsAdminPanelServiceProvider;
use Livewire\LivewireServiceProvider;

use function Pest\Laravel\withoutExceptionHandling;
use function Pest\Laravel\withoutVite;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        withoutExceptionHandling();

        withoutVite();

        $this->setUpDatabase($this->app);
    }

    public function setUpDatabase($app): void
    {
        $schema = $app['db']->connection()->getSchemaBuilder();

        $schema->create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->timestamps();
        });

        $schema->create('comments', function (Blueprint $table) {
            $table->id();
            $table->nullableMorphs('commentable');
            $table->nullableMorphs('commenter');
            $table->unsignedBigInteger('reply_id')->nullable()->index();

            $table->text('text');
            $table->string('guest_name')->nullable()->index();
            $table->string('guest_email')->nullable()->index();
            $table->boolean('approved')->default(false)->index();
            $table->string('ip_address')->nullable()->index();

            $table->timestamps();
        });
    }
    protected function getPackageProviders($app): array
    {
        return [
            CommenterServiceProvider::class,
            LivewireServiceProvider::class,
            NotificationsServiceProvider::class,
            CommentsAdminPanelServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('app.key', 'base64:2fl+Ktvkfl+Fuz4Qp/A75G2RTiWVA/ZoKZvp6fiiM10=');
        $app['config']->set('app.debug', true);
        $app['config']->set('app.env', 'local');
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }
}
