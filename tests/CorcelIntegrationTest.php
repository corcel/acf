<?php

use Corcel\Model\Post;

/**
 * Class CorcelIntegrationTest.
 *
 * @author Junior Grossi <juniorgro@gmail.com>
 */
class CorcelIntegrationTest extends PHPUnit\Framework\TestCase
{
    public function testIfCorcelIntegrationIsWorking()
    {
        $post = Post::find(56);
        $this->assertEquals('admin', $post->acf->fake_user->nickname);
    }

    public function testUsageOfHelperFunctions()
    {
        $post = Post::find(56);
        $this->assertEquals('admin', $post->acf->user('fake_user')->nickname);
    }

    public function testFunctionHelperWithSnakeCaseFieldType()
    {
        $post = Post::find(65);
        $this->assertEquals('10/13/2016', $post->acf->fake_date_picker->format('m/d/Y'));
        $this->assertEquals('10/13/2016', $post->acf->datePicker('fake_date_picker')->format('m/d/Y'));
    }
}
