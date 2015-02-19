<?php namespace Modules\Core\Tests;

use Illuminate\Foundation\Testing\ApplicationTrait;
use TestCase;

abstract class BaseTestCase extends TestCase
{
    use ApplicationTrait;

    protected $app;

    public function setUp()
    {
        parent::setUp();
        $this->refreshApplication();
    }

    protected function checkResponseIsOkAndContains($request, $filter)
    {
        $crawler = $this->client->request($request[0], $request[1]);

        $this->assertTrue($this->client->getResponse()->isOk());

        $this->assertCount(1, $crawler->filter($filter));
    }
}
