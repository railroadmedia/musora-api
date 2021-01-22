<?php

namespace Railroad\MusoraApi\Tests\Content;

use Railroad\MusoraApi\Tests\TestCase;
use Railroad\Railcontent\Factories\ContentFactory;
use Tymon\JWTAuth\Facades\JWTAuth;

class ContentControllerTest extends TestCase
{
    /**
     * @var ContentFactory
     */
    protected $contentFactory;

    protected function setUp()
    {
        parent::setUp();

        //$this->contentFactory = $this->app->make(ContentFactory::class);
    }

    public function test_first()
    {
      $this->assertTrue(true);
    }

    public function test_pull_content_endpoint()
    {
        $response = $this->actingAs()->get(
             '/musora-api/content/1'
        );

        $response->assertStatus(200);
    }


}
