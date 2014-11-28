<?php  namespace Modules\Core\Tests\Theme;

use Modules\Core\Foundation\Theme\ThemeManager;
use Modules\Core\Tests\BaseTestCase;

class ThemeManagerTest extends BaseTestCase
{
    /**
     * @var \Modules\Core\Foundation\Theme\ThemeManager
     */
    protected $repository;

    /**
     *
     */
    public function setUp()
    {
        parent::setUp();

        $this->repository = new ThemeManager($this->app, $this->getPath());
    }

    /** @test */
    public function it_should_return_all_themes()
    {
        $this->assertTrue(is_array($this->repository->all()));
        $this->assertEquals($this->repository->count(), 2);
    }

    /** @test */
    public function it_should_return_a_theme()
    {
        $theme = $this->repository->find('demo');

        $this->assertInstanceOf('Modules\Core\Foundation\Theme\Theme', $theme);
        $this->assertEquals('demo', $theme->getLowerName());
    }

    private function getPath()
    {
        return __DIR__ . '/Fixture/Themes';
    }
}
