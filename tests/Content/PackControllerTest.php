<?php

namespace Railroad\MusoraApi\Tests\Content;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Railroad\MusoraApi\Tests\TestCase;
use Railroad\Railcontent\Factories\ContentFactory;
use Railroad\Railcontent\Services\ContentService;

class PackControllerTest extends TestCase
{
    /**
     * @var ContentFactory
     */
    protected $contentFactory;

    protected function setUp()
    : void
    {
        parent::setUp();

        $this->contentFactory = $this->app->make(ContentFactory::class);
    }

    public function test_all_packs_endpoint_no_packs()
    {
        $response =
            $this->actingAs()
                ->call(
                    'GET',
                    '/musora-api/packs'
                );

        $this->assertEquals(['myPacks', 'morePacks', 'topHeaderPack'], array_keys($response->decodeResponseJson()->json()));
    }

    public function test_all_packs_endpoint_free_pack_with_membership()
    {
        $packFreeWithMembership = $this->fakeContent([
                                                         'type' => 'pack',
                                                     ]);

        $response =
            $this->actingAs()
                ->call(
                    'GET',
                    '/musora-api/packs'
                );

        $this->assertEquals(0, count($response->decodeResponseJson()['morePacks']));
        $this->assertEquals(1, count($response->decodeResponseJson()['myPacks']));

        $this->assertEquals(
            $packFreeWithMembership['id'],
            $response->decodeResponseJson()->json('myPacks')[0]['id']
        );

        $this->assertEquals($packFreeWithMembership['id'], $response->decodeResponseJson()->json('topHeaderPack')['id']);
    }

    public function test_all_packs_endpoint_more_packs()
    {
        $pack = $this->contentFactory->create(
            $this->faker->slug,
            'semester-pack',
            ContentService::STATUS_PUBLISHED,
            null,
            config('railcontent.brand')
        );
        $permission = $this->permissionFactory->create($this->faker->word, config('railcontent.brand'));

        $this->contentPermissionFactory->create($pack['id'], null, $permission['id'], config('railcontent.brand'));

        $response =
            $this->actingAs()
                ->call(
                    'GET',
                    '/musora-api/packs'
                );

        $this->assertTrue(!in_array($pack['id'], Arr::pluck($response->decodeResponseJson()->json('morePacks'), 'id')));
    }

    public function test_all_packs_endpoint_sorted_by_latest_progress()
    {
        $user = $this->createUser();

        for ($i = 0; $i < 5; $i++) {
            $packsFreeWithMembership[] = $this->fakeContent(
                [
                    'type' =>  'pack',
                    'slug' => $this->faker->slug,
                    'status' => ContentService::STATUS_PUBLISHED,
                    'published_on' => Carbon::now()
                        ->subDay(3)
                        ->toDateTimeString(),
                ]
            );
        }

        $this->fakeContentProgress([
                                       'content_id' => $packsFreeWithMembership[3]['id'],
                                       'user_id' => $user['id'],
                                       'progress_percent' => 5,
                                   ]);


        $response =
            $this->actingAs($user)
                ->call(
                    'GET',
                    '/musora-api/packs'
                );

        $this->assertEquals(0, count($response->decodeResponseJson()['morePacks']));
        $this->assertEquals(5, count($response->decodeResponseJson()['myPacks']));
        $this->assertEquals(
            $packsFreeWithMembership[3]['id'],
            Arr::first($response->decodeResponseJson()->json('myPacks'))['id']
        );
        $this->assertEquals($packsFreeWithMembership[3]['id'], $response->decodeResponseJson()['topHeaderPack']['id']);

        $responseStructure = (config('musora-api.response-structure')['top-header-pack']);

        $this->assertEquals(count($responseStructure), count($response->decodeResponseJson()['topHeaderPack']));

        foreach ($responseStructure as $key) {
            if (is_string($key) && (!str_contains($key, '.'))) {
                $this->assertTrue(array_key_exists($key, $response->decodeResponseJson()['topHeaderPack']));
            }
        }
    }
}
