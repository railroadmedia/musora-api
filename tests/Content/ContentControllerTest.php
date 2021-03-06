<?php

namespace Railroad\MusoraApi\Tests\Content;

use Carbon\Carbon;
use Railroad\MusoraApi\Tests\TestCase;
use Railroad\Railcontent\Factories\ContentContentFieldFactory;
use Railroad\Railcontent\Factories\ContentFactory;
use Railroad\Railcontent\Services\ContentService;

class ContentControllerTest extends TestCase
{
    /**
     * @var ContentFactory
     */
    protected $contentFactory;

    /**
     * @var ContentContentFieldFactory
     */
    protected $contentFieldFactory;

    protected function setUp()
    {
        parent::setUp();

        $this->contentFactory = $this->app->make(ContentFactory::class);
        $this->contentFieldFactory = $this->app->make(ContentContentFieldFactory::class);
    }

    public function test_first()
    {
        $this->assertTrue(true);
    }

    public function test_pull_not_existing_content_endpoint()
    {
        $response =
            $this->actingAs()
                ->get(
                    '/musora-api/content/' . rand()
                );

        $response->assertStatus(404);
    }

    public function test_pull_existing_content_endpoint()
    {
        $content = $this->contentFactory->create(null, 'course');

        $this->contentFactory->create(
            null,
            'course-part',
            ContentService::STATUS_PUBLISHED,
            null,
            config('railcontent.brand'),
            null,
            Carbon::now()
                ->subDays(3)
                ->toDateTimeString(),
            $content['id']
        );

        $response =
            $this->actingAs()
                ->get(
                    '/musora-api/content/' . $content['id']
                );

        $response->assertStatus(200);

        $this->assertEquals(
            $content['id'],
            $response->decodeResponseJson()['id']
        );
    }

    public function test_pull_content_for_download_endpoint()
    {
        $content = $this->contentFactory->create(null, 'course');

        for ($i = 0; $i < 5; $i++) {
            $lessons[] = $this->contentFactory->create(
                $this->faker->text,
                'course-part',
                ContentService::STATUS_PUBLISHED,
                null,
                config('railcontent.brand'),
                null,
                Carbon::now()
                    ->subDays(3)
                    ->toDateTimeString(),
                $content['id']
            );
        }

        $comment = $this->commentFactory->create($this->faker->text, $lessons[0]['id']);

        $response =
            $this->actingAs()
                ->get(
                    '/musora-api/content/' . $content['id'] . '?download=true'
                );

        $response->assertStatus(200);

        foreach ($response->decodeResponseJson()['lessons'] as $lesson) {
            $this->assertTrue(array_key_exists('comments', $lesson));
        }

        $this->assertEquals(
            $content['id'],
            $response->decodeResponseJson()['id']
        );
    }

    public function test_pull_filter_by_type_endpoint()
    {
        $includedTypes = [
            $this->faker->word,
            $this->faker->word,
        ];

        for ($i = 0; $i < 50; $i++) {
            $this->contentFactory->create(null, $this->faker->randomElement($includedTypes));
        }

        $response =
            $this->actingAs()
                ->call(
                    'GET',
                    '/musora-api/all',
                    [
                        'included_types' => [$includedTypes[0]],
                    ]
                );

        $response->assertStatus(200);

        foreach ($response->decodeResponseJson('data') as $content) {
            $this->assertEquals($includedTypes[0], $content['type']);
        }
    }

    public function test_pull_filter_by_type_and_instructor_endpoint()
    {
        $contents = [];
        $includedTypes = [
            $this->faker->word,
            $this->faker->word,
        ];

        for ($i = 0; $i < 50; $i++) {
            $contents[] = $this->contentFactory->create(null, $this->faker->randomElement($includedTypes));
        }

        $instructor = $this->contentFactory->create($this->faker->word, 'instructor', 'published');

        $fieldInstructor = [
            'key' => 'instructor',
            'value' => $instructor['id'],
            'type' => 'content_id',
        ];

        for ($i = 1; $i < 7; $i++) {
            $this->contentFieldFactory->create(
                $contents[$i]['id'],
                $fieldInstructor['key'],
                $fieldInstructor['value'],
                null,
                $fieldInstructor['type']
            );
        }

        $response =
            $this->actingAs()
                ->call(
                    'GET',
                    '/musora-api/all',
                    [
                        'included_types' => [$includedTypes[0]],
                        'required_fields' => ['instructor,' . $instructor['id']],
                    ]
                );

        $response->assertStatus(200);

        foreach ($response->decodeResponseJson('data') as $content) {
            $this->assertTrue(in_array($content['id'], array_pluck(array_slice($contents, 0, 7), 'id')));
        }
    }

    public function test_in_progress_endpoint()
    {
        $includedType = 'course-part';

        for ($i = 0; $i < 3; $i++) {
            $contents[] =
                $this->contentFactory->create($this->faker->text, $includedType, ContentService::STATUS_PUBLISHED, null, config('railcontent.brand'),null,Carbon::now()->subDays(3)->toDateTimeString());
        }

        $user = $this->createUser();

        $response =
            $this->actingAs($user)
                ->call(
                    'GET',

                    '/musora-api/in-progress',
                    [
                        'included_types' => [$includedType],
                    ],
                );

        $response->assertStatus(200);

        //assert we have empty data if not exists content in progress
        $this->assertTrue(empty($response->decodeResponseJson('data')));

      //  $this->userProgressFactory->saveContentProgress($contents[0]['id'], 12, $user['id'], true);

            $this->actingAs($user)
                ->call(
                    'PUT',
                    '/railcontent/progress',
                    [
                        'content_id' => $contents[0]['id'],
                        'progress_percent' => 12
                    ]
                );
        $response =
            $this->actingAs($user)
                ->call(
                    'GET',
                    '/musora-api/in-progress',
                    [
                        'included_types' => [$includedType],
                    ]
                );

       $response->assertStatus(200);

        $this->assertEquals($contents[0]['id'], $response->decodeResponseJson('data')[0]['id']);
    }

    public function test_get_live_event()
    {
        $includedTypes = config('railcontent.liveContentTypes');

        //old event
        $recordedEvent = $this->contentFactory->create(
            null,
            $this->faker->randomElement($includedTypes),
            'scheduled',
            null,
            config('railcontent.brand'),
            null,
            Carbon::now()
                ->subHour(5)
        );

        $startTime = [
            'key' => 'live_event_start_time',
            'value' => Carbon::now()
                ->subHour(5),
            'type' => 'datetime',
        ];

        $this->contentFieldFactory->create(
            $recordedEvent['id'],
            $startTime['key'],
            $startTime['value'],
            null,
            $startTime['type']
        );

        $endTime = [
            'key' => 'live_event_end_time',
            'value' => Carbon::now()
                ->subHour(4),
            'type' => 'datetime',
        ];

        $this->contentFieldFactory->create(
            $recordedEvent['id'],
            $endTime['key'],
            $endTime['value'],
            null,
            $endTime['type']
        );

        //live event
        $content = $this->contentFactory->create(
            null,
            $this->faker->randomElement($includedTypes),
            'scheduled',
            null,
            config('railcontent.brand'),
            null,
            Carbon::now()
                ->subHour(1)
        );

        $startTime = [
            'key' => 'live_event_start_time',
            'value' => Carbon::now(),
            'type' => 'datetime',
        ];

        $this->contentFieldFactory->create(
            $content['id'],
            $startTime['key'],
            $startTime['value'],
            null,
            $startTime['type']
        );

        $endTime = [
            'key' => 'live_event_end_time',
            'value' => Carbon::now()
                ->addHour(5),
            'type' => 'datetime',
        ];

        $this->contentFieldFactory->create(
            $content['id'],
            $endTime['key'],
            $endTime['value'],
            null,
            $endTime['type']
        );

        $response =
            $this->actingAs()
                ->call(
                    'GET',
                    '/musora-api/live-event'
                );

        $response->assertStatus(200);

        foreach ($response->decodeResponseJson('data') ?? [] as $result) {
            $this->assertEquals($content['id'], $result['id']);
            $this->assertTrue($content['isLive']);
        }
    }

    public function test_get_upcoming_event()
    {
        $includedTypes = config('railcontent.liveContentTypes');

        //old event
        $recordedEvent = $this->contentFactory->create(
            null,
            $this->faker->randomElement($includedTypes),
            'scheduled',
            null,
            config('railcontent.brand'),
            null,
            Carbon::now()
                ->subHour(5)
        );

        $startTime = [
            'key' => 'live_event_start_time',
            'value' => Carbon::now()
                ->subHour(5),
            'type' => 'datetime',
        ];

        $this->contentFieldFactory->create(
            $recordedEvent['id'],
            $startTime['key'],
            $startTime['value'],
            null,
            $startTime['type']
        );

        $endTime = [
            'key' => 'live_event_end_time',
            'value' => Carbon::now()
                ->subHour(4),
            'type' => 'datetime',
        ];

        $this->contentFieldFactory->create(
            $recordedEvent['id'],
            $endTime['key'],
            $endTime['value'],
            null,
            $endTime['type']
        );

        //upcoming event
        $content = $this->contentFactory->create(
            null,
            $this->faker->randomElement($includedTypes),
            'scheduled',
            null,
            config('railcontent.brand'),
            null,
            Carbon::now()
                ->subHour(1)
        );

        $hours = round($this->faker->numberBetween(60, config('railcontent.appUpcomingEventPriorMinutes')) / 60);
        $startTime = [
            'key' => 'live_event_start_time',
            'value' => Carbon::now()
                ->addHour($hours),
            'type' => 'datetime',
        ];

        $this->contentFieldFactory->create(
            $content['id'],
            $startTime['key'],
            $startTime['value'],
            null,
            $startTime['type']
        );

        $endTime = [
            'key' => 'live_event_end_time',
            'value' => Carbon::now()
                ->addHour($hours + 1),
            'type' => 'datetime',
        ];

        $this->contentFieldFactory->create(
            $content['id'],
            $endTime['key'],
            $endTime['value'],
            null,
            $endTime['type']
        );

        $response =
            $this->actingAs()
                ->call(
                    'GET',
                    '/musora-api/live-event'
                );

        $response->assertStatus(200);

        foreach ($response->decodeResponseJson('data') ?? [] as $result) {
            $this->assertEquals($content['id'], $result['id']);
            $this->assertFalse($content['isLive']);
        }
    }

    public function test_forced_content_live()
    {
        $includedTypes = config('railcontent.liveContentTypes');

        $forcedContent = $this->contentFactory->create(
            null,
            $this->faker->randomElement($includedTypes),
            'scheduled',
            null,
            config('railcontent.brand'),
            null,
            Carbon::now()
                ->subHour(5)
        );

        $startTime = [
            'key' => 'live_event_start_time',
            'value' => Carbon::now()
                ->addDays(5),
            'type' => 'datetime',
        ];

        $this->contentFieldFactory->create(
            $forcedContent['id'],
            $startTime['key'],
            $startTime['value'],
            null,
            $startTime['type']
        );

        $endTime = [
            'key' => 'live_event_end_time',
            'value' => Carbon::now()
                ->addDays(6),
            'type' => 'datetime',
        ];

        $this->contentFieldFactory->create(
            $forcedContent['id'],
            $endTime['key'],
            $endTime['value'],
            null,
            $endTime['type']
        );

        //live event
        $content = $this->contentFactory->create(
            null,
            $this->faker->randomElement($includedTypes),
            'scheduled',
            null,
            config('railcontent.brand'),
            null,
            Carbon::now()
                ->subHour(1)
        );

        $startTime = [
            'key' => 'live_event_start_time',
            'value' => Carbon::now(),
            'type' => 'datetime',
        ];

        $this->contentFieldFactory->create(
            $content['id'],
            $startTime['key'],
            $startTime['value'],
            null,
            $startTime['type']
        );

        $endTime = [
            'key' => 'live_event_end_time',
            'value' => Carbon::now()
                ->addHour(5),
            'type' => 'datetime',
        ];

        $this->contentFieldFactory->create(
            $content['id'],
            $endTime['key'],
            $endTime['value'],
            null,
            $endTime['type']
        );

        $response =
            $this->actingAs()
                ->call(
                    'GET',
                    '/musora-api/live-event',
                    ['forced-content-id' => $forcedContent['id']]
                );

        $response->assertStatus(200);

        foreach ($response->decodeResponseJson('data') ?? [] as $result) {
            $this->assertEquals($forcedContent['id'], $result['id']);
            $this->assertFalse($forcedContent['isLive']);
        }
    }

}
