<?php

namespace Jeroenherczeg\Dog\Test;

use App\Console\Kernel;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Filesystem\ClassFinder;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Support\Facades\Hash;
use Jeroenherczeg\Dog\FollowServiceProvider;

class FollowableTest extends TestCase
{
    public function test_user_can_follow_by_id()
    {
        $user1 = User::find(1);
        $user2 = User::find(2);
        $user1->follow($user2->id);
        $this->assertCount(1, $user1->followings);
        $this->assertCount(1, $user2->followers);
    }

    public function test_user_can_follow_multiple_users()
    {
        $user1 = User::find(1);
        $user2 = User::find(2);
        $user3 = User::find(3);
        $user1->follow([$user2->id, $user3->id]);
        $this->assertCount(2, $user1->followings);
        $this->assertCount(1, $user2->followers);
        $this->assertCount(1, $user3->followers);
    }

    public function test_follow_not_existing_user()
    {
        $user1 = User::find(1);
        $user2 = User::find(4);
        try {
            $user1->follow($user2);
        } catch (\Exception $e) {
            $this->assertInstanceOf(ModelNotFoundException::class, $e);
        }
    }

    public function test_unfollow_user()
    {
        $user1 = User::find(1);
        $user2 = User::find(2);
        $user1->follow($user2->id);
        $this->assertCount(1, $user2->followers);
        $user1->unfollow($user2->id);
        $this->assertCount(0, $user1->followings);
    }

    public function test_is_following()
    {
        $user1 = User::find(1);
        $user2 = User::find(2);
        $user1->follow($user2->id);
        $this->assertTrue($user1->isFollowing($user2->id));
    }

    public function test_is_followed_by()
    {
        $user1 = User::find(1);
        $user2 = User::find(2);
        $user1->follow($user2->id);
        $this->assertTrue($user2->isFollowedBy($user1->id));
    }

    /**
     * Creates the application.
     *
     * Needs to be implemented by subclasses.
     *
     * @return \Symfony\Component\HttpKernel\HttpKernelInterface
     */
    public function createApplication()
    {
        $app = require __DIR__ . '/../vendor/laravel/laravel/bootstrap/app.php';
        $app->register(FollowServiceProvider::class);
        $app->make(Kernel::class)->bootstrap();

        return $app;
    }

    public function setUp()
    {
        parent::setUp();

        $this->app['config']->set('database.default', 'sqlite');
        $this->app['config']->set('database.connections.sqlite.database', ':memory:');

        $this->migrate();
        $this->createUsers();
    }

    public function migrate()
    {
        $fileSystem = new Filesystem;
        $classFinder = new ClassFinder;

        foreach ($fileSystem->files(database_path('migrations')) as $file) {
            $fileSystem->requireOnce($file);
            $migrationClass = $classFinder->findClass($file);
            (new $migrationClass)->up();
        }

        $file = 'src/create_followers_table.php';
        $fileSystem->requireOnce($file);
        $packageMigrationClass = $classFinder->findClass($file);
        (new $packageMigrationClass)->up();
    }

    private function createUsers()
    {
        User::create([
            'name' => 'Jeroen Herczeg',
            'email' => 'jeroen@herczeg.be',
            'password' => Hash::make('test')
        ]);

        User::create([
            'name' => 'Taylor Otwell',
            'email' => 'taylor@laravel.com',
            'password' => Hash::make('test')
        ]);

        User::create([
            'name' => 'Jeffrey Way',
            'email' => 'jeffrey@laracasts.com',
            'password' => Hash::make('test')
        ]);
    }
}
