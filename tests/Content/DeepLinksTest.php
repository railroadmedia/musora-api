<?php

namespace Railroad\MusoraApi\Tests\Content;

use Carbon\Carbon;
use Railroad\MusoraApi\Tests\TestCase;
use Railroad\MusoraApi\Tests\UnitTest;
use Railroad\Railcontent\Factories\ContentFactory;
use Railroad\Railcontent\Services\ContentService;
use Illuminate\Http\Request;

class DeepLinksTest extends TestCase
{
    /**
     * @var ContentFactory
     */
    protected $contentFactory;

    protected function setUp():void
    {
        parent::setUp();

        $this->contentFactory = $this->app->make(ContentFactory::class);
    }

    public function test_deep_link_pack_with_two_bundles()
    {

        $pack = $this->fakeContent(
            [
                'type' => 'pack',
                'slug' => $this->faker->slug,
                'status' => ContentService::STATUS_PUBLISHED,
                'published_on' => Carbon::now()
                    ->subDay()
                    ->toDateTimeString(),
            ]
        );

        $packBundle1 = $this->contentFactory->create(
            $this->faker->slug,
            'pack-bundle',
            ContentService::STATUS_PUBLISHED,
            null,
            config('railcontent.brand'),
            null,
            Carbon::now()
                ->subDays(3)
                ->toDateTimeString(),
            $pack['id']
        );

        $packBundle2 = $this->contentFactory->create(
            $this->faker->slug,
            'pack-bundle',
            ContentService::STATUS_PUBLISHED,
            null,
            config('railcontent.brand'),
            null,
            Carbon::now()
                ->subDays(3)
                ->toDateTimeString(),
            $pack['id']
        );

        $response =
            $this->actingAs()
                ->call(
                    'GET',
                    '/musora-api/members/packs/' . $pack['slug']
                );

        $decodedResponse = $response->decodeResponseJson();

        $this->assertEquals($pack['id'], $decodedResponse['id']);
        $this->assertEquals($pack['type'], $decodedResponse['type']);
        $this->assertTrue(array_key_exists('bundles', $decodedResponse));
        $this->assertEquals(2, count($decodedResponse['bundles']));
    }

    public function test_deep_link_pack_with_one_bundle()
    {
        $pack = $this->fakeContent(
            [
                'type' => 'pack',
                'slug' => $this->faker->slug,
                'status' => ContentService::STATUS_PUBLISHED,
                'published_on' => Carbon::now()
                    ->subDay()
                    ->toDateTimeString(),
            ]
        );

        $packBundle1 = $this->contentFactory->create(
            $this->faker->slug,
            'pack-bundle',
            ContentService::STATUS_PUBLISHED,
            null,
            config('railcontent.brand'),
            null,
            Carbon::now()
                ->subDays(3)
                ->toDateTimeString(),
            $pack['id']
        );

        $response =
            $this->actingAs()
                ->call(
                    'GET',
                    '/musora-api/members/packs/' . $pack['slug']
                );

        $decodedResponse = $response->decodeResponseJson();

        //deep link for pack with one bundle should return bundle
        $this->assertEquals($packBundle1['id'], $decodedResponse['id']);
        $this->assertEquals($packBundle1['type'], $decodedResponse['type']);
        $this->assertTrue(!array_key_exists('bundles', $decodedResponse));
    }

    public function test_deep_link_bundle()
    {
        $pack = $this->fakeContent(
            [
                'type' => 'pack',
                'slug' => $this->faker->slug,
                'status' => ContentService::STATUS_PUBLISHED,
                'published_on' => Carbon::now()
                    ->subDay()
                    ->toDateTimeString(),
            ]
        );

        $packBundle1 = $this->fakeChild(
            [
                'type' => 'pack-bundle',
                'slug' => $this->faker->slug,
                'status' => ContentService::STATUS_PUBLISHED,
                'published_on' => Carbon::now()
                    ->subDay()
                    ->toDateTimeString(),
                'parent_id' => $pack['id']
            ]
        );
//            $this->contentFactory->create(
//            $this->faker->slug,
//            'pack-bundle',
//            ContentService::STATUS_PUBLISHED,
//            null,
//            config('railcontent.brand'),
//            null,
//            Carbon::now()
//                ->subDays(3)
//                ->toDateTimeString(),
//            $pack['id']
//        );
$request = new Request();

        $response =
            $this->actingAs()
                ->call(
                    'GET',
                    '/musora-api/members/packs/' . $pack['slug'] . '/' . $packBundle1['slug'].'/'.$request
                );

        $decodedResponse = $response->decodeResponseJson();
dd($decodedResponse);
        $this->assertEquals($packBundle1['id'], $decodedResponse['id']);
        $this->assertEquals($packBundle1['type'], $decodedResponse['type']);
    }

    public function test_deep_link_lesson_pack_with_bundle()
    {
        $pack = $this->fakeContent(
            [
                'type' => 'pack',
                'slug' => $this->faker->slug,
                'status' => ContentService::STATUS_PUBLISHED,
                'published_on' => Carbon::now()
                    ->subDay()
                    ->toDateTimeString(),
            ]
        );

        $packBundle1 = $this->contentFactory->create(
            $this->faker->slug,
            'pack-bundle',
            ContentService::STATUS_PUBLISHED,
            null,
            config('railcontent.brand'),
            null,
            Carbon::now()
                ->subDays(3)
                ->toDateTimeString(),
            $pack['id']
        );

        $lesson = $this->contentFactory->create(
            $this->faker->slug,
            'pack-bundle-lesson',
            ContentService::STATUS_PUBLISHED,
            null,
            config('railcontent.brand'),
            null,
            Carbon::now()
                ->subDays(3)
                ->toDateTimeString(),
            $packBundle1['id']
        );

        $response =
            $this->actingAs()
                ->call(
                    'GET',
                    '/musora-api/members/packs/' . $pack['slug'] . '/' . $packBundle1['slug'].'/'.$lesson['slug']
                );

        $decodedResponse = $response->decodeResponseJson();

        $this->assertEquals($lesson['id'], $decodedResponse['id']);
        $this->assertEquals($lesson['type'], $decodedResponse['type']);
    }

    public function test_deep_link_semester_pack()
    {
        $pack = $this->fakeContent(
            [
                'type' => 'semester-pack',
                'slug' => $this->faker->slug,
                'status' => ContentService::STATUS_PUBLISHED,
                'published_on' => Carbon::now()
                    ->subDay()
                    ->toDateTimeString(),
            ]
        );

        $response =
            $this->actingAs()
                ->call(
                    'GET',
                    '/musora-api/members/semester-packs/' . $pack['slug']
                );

        $decodedResponse = $response->decodeResponseJson();

        $this->assertEquals($pack['id'], $decodedResponse['id']);
        $this->assertEquals($pack['type'], $decodedResponse['type']);
    }

    public function test_deep_link_semester_pack_lesson()
    {
        $pack = $this->fakeContent(
            [
                'type' => 'semester-pack',
                'slug' => $this->faker->slug,
                'status' => ContentService::STATUS_PUBLISHED,
                'published_on' => Carbon::now()
                    ->subDay()
                    ->toDateTimeString(),
            ]
        );

        $lesson = $this->contentFactory->create(
            $this->faker->slug,
            'semester-pack-lesson',
            ContentService::STATUS_PUBLISHED,
            null,
            config('railcontent.brand'),
            null,
            Carbon::now()
                ->subDays(3)
                ->toDateTimeString(),
            $pack['id']
        );

        $response =
            $this->actingAs()
                ->call(
                    'GET',
                    '/musora-api/members/semester-packs/' . $pack['slug'] . '/' . $lesson['slug']
                );

        $decodedResponse = $response->decodeResponseJson();

        $this->assertEquals($lesson['id'], $decodedResponse['id']);
        $this->assertEquals($lesson['type'], $decodedResponse['type']);
    }

    public function test_deep_link_bundle_pianote_link()
    {
        $pack = $this->fakeContent(
            [
                'type' => 'pack',
                'slug' => $this->faker->slug,
                'status' => ContentService::STATUS_PUBLISHED,
                'published_on' => Carbon::now()
                    ->subDay()
                    ->toDateTimeString(),
            ]
        );

        $packBundle1 = $this->contentFactory->create(
            $this->faker->slug,
            'pack-bundle',
            ContentService::STATUS_PUBLISHED,
            null,
            config('railcontent.brand'),
            null,
            Carbon::now()
                ->subDays(3)
                ->toDateTimeString(),
            $pack['id']
        );

        $response =
            $this->actingAs()
                ->call(
                    'GET',
                    '/musora-api/members/packs/' .
                    $pack['slug'] .
                    '/bundle/' .
                    $packBundle1['slug'] .
                    '/' .
                    $packBundle1['id']
                );

        $decodedResponse = $response->decodeResponseJson();

        //deep link for pack with one bundle should return bundle
        $this->assertEquals($packBundle1['id'], $decodedResponse['id']);
        $this->assertEquals($packBundle1['type'], $decodedResponse['type']);
        $this->assertTrue(!array_key_exists('bundles', $decodedResponse));
    }

    public function test_deep_link_lesson_pianote_link_pack_with_bundles()
    {
        $pack = $this->fakeContent(
            [
                'type' => 'pack',
                'slug' => $this->faker->slug,
                'status' => ContentService::STATUS_PUBLISHED,
                'published_on' => Carbon::now()
                    ->subDay()
                    ->toDateTimeString(),
            ]
        );

        $packBundle1 = $this->contentFactory->create(
            $this->faker->slug,
            'pack-bundle',
            ContentService::STATUS_PUBLISHED,
            null,
            config('railcontent.brand'),
            null,
            Carbon::now()
                ->subDays(3)
                ->toDateTimeString(),
            $pack['id']
        );

        $packBundle2 = $this->contentFactory->create(
            $this->faker->slug,
            'pack-bundle',
            ContentService::STATUS_PUBLISHED,
            null,
            config('railcontent.brand'),
            null,
            Carbon::now()
                ->subDays(3)
                ->toDateTimeString(),
            $pack['id']
        );

        $lesson = $this->contentFactory->create(
            $this->faker->slug,
            'pack-bundle-lesson',
            ContentService::STATUS_PUBLISHED,
            null,
            config('railcontent.brand'),
            null,
            Carbon::now()
                ->subDays(3)
                ->toDateTimeString(),
            $packBundle1['id']
        );

        $lesson2 = $this->contentFactory->create(
            $this->faker->slug,
            'pack-bundle-lesson',
            ContentService::STATUS_PUBLISHED,
            null,
            config('railcontent.brand'),
            null,
            Carbon::now()
                ->subDays(3)
                ->toDateTimeString(),
            $packBundle1['id']
        );

        $response =
            $this->actingAs()
                ->call(
                    'GET',
                    '/musora-api/members/packs/' .
                    $pack['slug'] .
                    '/bundle/' .
                    $packBundle2['slug'] .
                    '/' .
                    $lesson['slug'] .
                    '/' .
                    $lesson['id']
                );

        $decodedResponse = $response->decodeResponseJson();

        $this->assertEquals($lesson['id'], $decodedResponse['id']);
        $this->assertEquals($lesson['type'], $decodedResponse['type']);
        $this->assertTrue(array_key_exists('related_lessons', $decodedResponse));
    }

    public function test_deep_link_lesson_pianote_link_pack_without_bundles()
    {
        $pack = $this->fakeContent(
            [
                'type' => 'pack',
                'slug' => $this->faker->slug,
                'status' => ContentService::STATUS_PUBLISHED,
                'published_on' => Carbon::now()
                    ->subDay()
                    ->toDateTimeString(),
            ]
        );

        $lesson = $this->contentFactory->create(
            $this->faker->slug,
            'pack-bundle-lesson',
            ContentService::STATUS_PUBLISHED,
            null,
            config('railcontent.brand'),
            null,
            Carbon::now()
                ->subDays(3)
                ->toDateTimeString(),
            $pack['id']
        );

        $response =
            $this->actingAs()
                ->call(
                    'GET',
                    '/musora-api/members/packs/' . $pack['slug'] . '/' . $lesson['slug'] . '/' . $lesson['id']
                );

        $decodedResponse = $response->decodeResponseJson();

        $this->assertEquals($lesson['id'], $decodedResponse['id']);
        $this->assertEquals($lesson['type'], $decodedResponse['type']);
        $this->assertTrue(array_key_exists('related_lessons', $decodedResponse));
    }
}
