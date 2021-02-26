<?php

namespace Railroad\MusoraApi\Tests\Content;


use Railroad\MusoraApi\Tests\TestCase;
use Railroad\Railcontent\Services\ContentService;

class PackControllerTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();
    }

    public function test_all_packs_endpoint_no_packs()
    {
        $response =
            $this->actingAs()
                ->call(
                    'GET',
                    '/musora-api/packs'
                );

        $this->assertEquals(['myPacks', 'morePacks', 'topHeaderPack'], array_keys($response->decodeResponseJson()));
    }

    public function test_all_packs_endpoint_free_pack_with_membership()
    {
        $user = $this->createAndLogInNewUser();

        $packFreeWithMembership = $this->fakeContent([
            'type' => 'pack'
        ]);

        $response =
            $this->actingAs($user)
                ->call(
                    'GET',
                    '/musora-api/packs'
                );

        $this->assertEquals(0, count($response->decodeResponseJson()['morePacks']));
        $this->assertEquals(1, count($response->decodeResponseJson()['myPacks']));
        $this->assertEquals(
            $packFreeWithMembership['id'],
            array_first($response->decodeResponseJson()['myPacks'])['id']
        );
        $this->assertEquals($packFreeWithMembership['id'], $response->decodeResponseJson()['topHeaderPack']['id']);
    }

    public function test_all_packs_endpoint_more_packs()
    {
        $user = $this->createAndLogInNewUser();

        $pack = $this->fakeContent([
            'type' => 'pack'
        ]);


        // $pack = $this->contentFactory->create($this->faker->slug, 'pack', ContentService::STATUS_PUBLISHED);
        $permission = $this->fakePermission();
$this->fakeContentPermission()
        //$this->contentPermissionFactory->create($pack['id'], null, $permission['id'], config('railcontent.brand'));

        $response =
            $this->actingAs($user)
                ->call(
                    'GET',
                    '/musora-api/packs'
                );

        $this->assertEquals(0, count($response->decodeResponseJson()['myPacks']));
        $this->assertEquals(1, count($response->decodeResponseJson()['morePacks']));
        $this->assertEquals(
            $pack['id'],
            array_first($response->decodeResponseJson()['morePacks'])['id']
        );
        $this->assertNull($response->decodeResponseJson()['topHeaderPack']['id']);
    }

    public function _test_all_packs_endpoint_sorted_by_latest_progress()
    {
        $user = $this->createAndLogInNewUser();

        for ($i = 0; $i < 5; $i++) {
            $packsFreeWithMembership[] =
                $this->contentFactory->create($this->faker->slug, 'pack', ContentService::STATUS_PUBLISHED);
        }

        $this->userProgressFactory->saveContentProgress($packsFreeWithMembership[3]['id'], 5, $user['id']);

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
            array_first($response->decodeResponseJson()['myPacks'])['id']
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