<?php

use Corcel\Model\Post;
use Corcel\Acf\Tests\TestCase;
use Corcel\Model\User;

/**
 * Class CorcelIntegrationTest.
 *
 * @author Junior Grossi <juniorgro@gmail.com>
 */
class CorcelIntegrationTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();
        $this->post = $this->createAcfPost();
    }

    /**
     * Create a sample post with acf fields
     */
    protected function createAcfPost()
    {
        $post = factory(Post::class)->create();

        $user = factory(User::class)->create();
        $this->createAcfField($post, 'fake_user', $user->ID, 'user');

        $this->createAcfField($post, 'fake_date_picker', '20161013', 'date_picker');

        return $post;
    }

    public function testIfCorcelIntegrationIsWorking()
    {
        $this->assertInstanceOf(User::class, $this->post->acf->fake_user);
    }

    public function testUsageOfHelperFunctions()
    {
        $this->assertInstanceOf(User::class, $this->post->acf->user('fake_user'));
    }

    public function testFunctionHelperWithSnakeCaseFieldType()
    {
        $this->assertEquals('10/13/2016', $this->post->acf->fake_date_picker->format('m/d/Y'));
        $this->assertEquals('10/13/2016', $this->post->acf->datePicker('fake_date_picker')->format('m/d/Y'));
    }
}
