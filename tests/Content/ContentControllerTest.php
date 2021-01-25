<?php

namespace Railroad\MusoraApi\Tests\Content;

use Railroad\MusoraApi\Tests\TestCase;
use Railroad\Railcontent\Factories\ContentContentFieldFactory;
use Railroad\Railcontent\Factories\ContentFactory;
use Tymon\JWTAuth\Facades\JWTAuth;

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

        $response->assertStatus(200);
    }

    public function test_pull_existing_content_endpoint()
    {
        $content = $this->contentFactory->create(null, 'course');

        $response =
            $this->actingAs()
                ->get(
                    '/musora-api/content/' . $content['id']
                );

        $response->assertStatus(200);

        $this->assertEquals(
            array_merge(
                $content,
                [
                    'comments' => [],
                    'total_comments' => 0,
                    'related_lessons' => [],
                    'next_lesson' => null,
                    'previous_lesson' => null,
                ]
            ),
            $response->decodeResponseJson()
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

}
