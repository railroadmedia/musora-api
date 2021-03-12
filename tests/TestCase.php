<?php

namespace Railroad\MusoraApi\Tests;

use Carbon\Carbon;
use Faker\Generator;
use Illuminate\Auth\AuthManager;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Orchestra\Testbench\TestCase as BaseTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Railroad\Ecommerce\Gateways\AppleStoreKitGateway;
use Railroad\MusoraApi\Contracts\ChatProviderInterface;
use Railroad\MusoraApi\Contracts\ProductProviderInterface;
use Railroad\MusoraApi\Contracts\UserProviderInterface;
use Railroad\MusoraApi\Middleware\AuthIfTokenExist;
use Railroad\MusoraApi\Providers\MusoraApiServiceProvider;
use Railroad\MusoraApi\Tests\Fixtures\ChatProvider;
use Railroad\MusoraApi\Tests\Fixtures\ProductProvider;
use Railroad\MusoraApi\Tests\Fixtures\UserProvider;
use Railroad\MusoraApi\Tests\Resources\Models\User;
use Railroad\Railcontent\Factories\CommentFactory;
use Railroad\Railcontent\Factories\ContentFactory;
use Railroad\Railcontent\Factories\ContentPermissionsFactory;
use Railroad\Railcontent\Factories\PermissionsFactory;
use Railroad\Railcontent\Factories\UserContentProgressFactory;
use Railroad\Railcontent\Middleware\ContentPermissionsMiddleware;
use Railroad\Railcontent\Providers\RailcontentServiceProvider;
use Railroad\Response\Providers\ResponseServiceProvider;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Providers\LaravelServiceProvider;

class TestCase extends BaseTestCase
{
    /**
     * @var Generator
     */
    protected $faker;

    /**
     * @var DatabaseManager
     */
    protected $databaseManager;

    /**
     * @var AuthManager
     */
    protected $authManager;
    /**
     * @var CommentFactory
     */
    protected $commentFactory;
    /**
     * @var UserContentProgressFactory
     */
    protected $userProgressFactory;
    /**
     * @var MockObject
     */
    protected $appleStoreKitGatewayMock;

    protected $ecommerceFaker;

    /**
     * @var ContentFactory
     */
    protected $contentFactory;
    /**
     * @var PermissionsFactory
     */
    protected $permissionFactory;

    /**
     * @var ContentPermissionsFactory
     */
    protected $contentPermissionFactory;

    protected $jwtAuth;

    protected function setUp()
    {
        parent::setUp();

        error_reporting(E_ALL);

        $this->artisan('migrate:fresh');

        $this->artisan('cache:clear', []);

        $this->databaseManager = $this->app->make(DatabaseManager::class);

        $this->faker = $this->app->make(Generator::class);
        $this->contentFactory = $this->app->make(ContentFactory::class);
        $this->commentFactory = $this->app->make(CommentFactory::class);
        $this->userProgressFactory = $this->app->make(UserContentProgressFactory::class);
        $this->permissionFactory = $this->app->make(PermissionsFactory::class);
        $this->contentPermissionFactory = $this->app->make(ContentPermissionsFactory::class);
        $this->authManager = $this->app->make(AuthManager::class);

        //call the MobileAppTokenAuth
        $middleware = $this->app->make(AuthIfTokenExist::class);
        $middleware->handle(
            request(),
            function () {
            }
        );

        $middleware = $this->app->make(ContentPermissionsMiddleware::class);
        $middleware->handle(
            request(),
            function () {
            }
        );

        if (!DB::connection()
            ->getSchemaBuilder()
            ->hasTable('users')) {
            $result =
                DB::connection()
                    ->getSchemaBuilder()
                    ->create(
                        'users',
                        function (Blueprint $table) {
                            $table->increments('id');
                            $table->string('email');
                        }
                    );
        }

        $this->appleStoreKitGatewayMock =
            $this->getMockBuilder(AppleStoreKitGateway::class)
                ->disableOriginalConstructor()
                ->getMock();
        $this->app->instance(AppleStoreKitGateway::class, $this->appleStoreKitGatewayMock);

        $chatProvider = new ChatProvider();
        $this->app->instance(ChatProviderInterface::class, $chatProvider);

        $userProvider = new UserProvider();
        $this->app->instance(UserProviderInterface::class, $userProvider);

        $productProvider = new ProductProvider();
        $this->app->instance(ProductProviderInterface::class, $productProvider);

        \Railroad\Railcontent\Repositories\RepositoryBase::$connectionMask = null;
    }

    /**
     * Define environment setup.
     *
     * @param Application $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $defaultConfig = require(__DIR__ . '/../config/railcontent.php');

        $app['config']->set('railcontent.database_connection_name', $defaultConfig['database_connection_name']);
        $app['config']->set('railcontent.cache_duration', $defaultConfig['cache_duration']);
        $app['config']->set('railcontent.cache_driver', $defaultConfig['cache_driver']);
        $app['config']->set('railcontent.cache_prefix', $defaultConfig['cache_prefix']);
        $app['config']->set('railcontent.table_prefix', $defaultConfig['table_prefix']);
        $app['config']->set('railcontent.data_mode', $defaultConfig['data_mode']);
        $app['config']->set('railcontent.connection_mask_prefix', $defaultConfig['connection_mask_prefix']);
        $app['config']->set('railcontent.brand', $defaultConfig['brand']);
        $app['config']->set('railcontent.available_brands', $defaultConfig['available_brands']);
        $app['config']->set('railcontent.available_languages', $defaultConfig['available_languages']);
        $app['config']->set('railcontent.default_language', $defaultConfig['default_language']);
        $app['config']->set('railcontent.field_option_list', $defaultConfig['field_option_list']);
        $app['config']->set('railcontent.commentable_content_types', $defaultConfig['commentable_content_types']);
        $app['config']->set('railcontent.showTypes', $defaultConfig['showTypes']);
        $app['config']->set('railcontent.cataloguesMetadata', $defaultConfig['cataloguesMetadata']);
        $app['config']->set('railcontent.topLevelContentTypes', $defaultConfig['topLevelContentTypes']);
        $app['config']->set('railcontent.userListContentTypes', $defaultConfig['userListContentTypes']);
        $app['config']->set('railcontent.appUserListContentTypes', $defaultConfig['appUserListContentTypes']);
        $app['config']->set('railcontent.onboardingContentIds', $defaultConfig['onboardingContentIds']);
        $app['config']->set('railcontent.liveContentTypes', $defaultConfig['liveContentTypes']);
        $app['config']->set('railcontent.appUpcomingEventPriorMinutes', $defaultConfig['appUpcomingEventPriorMinutes']);

        $app['config']->set('railcontent.validation', $defaultConfig['validation']);
        $app['config']->set(
            'railcontent.comment_assignation_owner_ids',
            $defaultConfig['comment_assignation_owner_ids']
        );
        $app['config']->set('railcontent.searchable_content_types', $defaultConfig['searchable_content_types']);
        $app['config']->set('railcontent.statistics_content_types', $defaultConfig['statistics_content_types']);
        $app['config']->set('railcontent.search_index_values', $defaultConfig['search_index_values']);
        $app['config']->set(
            'railcontent.allowed_types_for_bubble_progress',
            $defaultConfig['allowed_types_for_bubble_progress']
        );
        $app['config']->set('railcontent.decorators', $defaultConfig['decorators']);
        $app['config']->set('railcontent.all_routes_middleware', $defaultConfig['all_routes_middleware']);
        $app['config']->set('railcontent.user_routes_middleware', $defaultConfig['user_routes_middleware']);
        $app['config']->set(
            'railcontent.administrator_routes_middleware',
            $defaultConfig['administrator_routes_middleware']
        );

        $app['config']->set('database_connection_name', 'testbench');
        $app['config']->set('database.default', 'testbench');
        $app['config']->set(
            'database.connections.testbench',
            [
                'driver' => 'sqlite',
                'database' => ':memory:',
                'prefix' => '',
            ]
        );

        $app['config']->set(
            'database.redis',
            [
                'client' => 'predis',
                'default' => [
                    'host' => env('REDIS_HOST', 'redis'),
                    'password' => env('REDIS_PASSWORD', null),
                    'port' => env('REDIS_PORT', 6379),
                    'database' => 0,
                ],
            ]
        );
        $app['config']->set('railcontent.database.default', 'testbench');

        $app['config']->set(
            'railcontent.database.redis',
            [
                'client' => 'predis',
                'default' => [
                    'host' => env('REDIS_HOST', 'redis'),
                    'password' => env('REDIS_PASSWORD', null),
                    'port' => env('REDIS_PORT', 6379),
                    'database' => 0,
                ],
            ]
        );
        $app['config']->set('cache.default', env('CACHE_DRIVER', 'redis'));
        $app['config']->set('railcontent.cache_prefix', $defaultConfig['cache_prefix']);
        $app['config']->set('railcontent.cache_driver', $defaultConfig['cache_driver']);

        // allows access to built in user auth
        $app['config']->set('auth.providers.users.model', User::class);

        $musoraApiConfig = require(__DIR__ . '/../config/musora-api.php');
        $app['config']->set('musora-api.response-structure', $musoraApiConfig['response-structure']);

        $jwtConfig = require(__DIR__ . '/../config/jwt.php');
        $app['config']->set('jwt', $jwtConfig);

        $app->register(MusoraApiServiceProvider::class);

        $app->register(LaravelServiceProvider::class);
        $app->register(ResponseServiceProvider::class);
        $app->register(RailcontentServiceProvider::class);

    }

    /**
     * Set the currently logged in user for the application.
     *
     * @param Authenticatable $user
     * @param string|null $driver
     * @return $this
     */
    public function actingAs($user = null, $driver = null)
    {
        if (!$user) {
            $user = $this->createUser();
        }

        $this->withHeader('Authorization', "Bearer " . $user['token']);

        parent::actingAs($user);

        return $this;

    }

    /**
     * @return int
     */
    public function createUser()
    {
        $userId =
            $this->databaseManager->connection()
                ->query()
                ->from('users')
                ->insertGetId(
                    ['email' => $this->faker->email]
                );

        $user =
            User::query()
                ->find($userId);

        $user['token'] = JWTAuth::fromUser($user);

        return $user;
    }

    /**
     * Helper method to seed a test content
     *      *
     *
     * @return array
     */
    public function fakeContent($contentData = [])
    {
        $contentData += [
            'slug' => $this->faker->slug,
            'type' => $this->faker->text,
            'brand' => config('railcontent.brand'),
            'language' => $this->faker->text,
            'status' => 'published',
            'published_on' => Carbon::now()
                ->subDays(1)
                ->toDateTimeString(),
            'created_on' => Carbon::now()
                ->toDateTimeString(),
        ];

        $contentId =
            $this->databaseManager->table('railcontent_content')
                ->insertGetId($contentData);

        $contentData['id'] = $contentId;

        return $contentData;
    }

    /**
     * Helper method to seed a test permission
     *
     * @return array
     */
    public function fakePermission($permissionData = [])
    {
        $permissionData += [
            'name' => $this->faker->slug,
            'brand' => config('railcontent.brand'),
        ];

        $permissionId =
            $this->databaseManager->table('railcontent_permissions')
                ->insertGetId($permissionData);

        $permissionData['id'] = $permissionId;

        return $permissionData;
    }

    /**
     * Helper method to seed a test permission
     *
     * @return array
     */
    public function fakeContentPermission($contentPermissionData = [])
    {
        $contentPermissionData += [
            'permission_id' => rand(1, 10),
            'brand' => config('railcontent.brand'),
        ];

        $permissionId =
            $this->databaseManager->table('railcontent_content_permissions')
                ->insertGetId($contentPermissionData);

        $contentPermissionData['id'] = $permissionId;

        return $contentPermissionData;
    }

    protected function tearDown()
    {
        Redis::flushDB();

        parent::tearDown();
    }
}
