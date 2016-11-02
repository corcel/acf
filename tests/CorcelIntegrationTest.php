<?php

/**
 * Class CorcelIntegrationTest.
 *
 * @author Junior Grossi <juniorgro@gmail.com>
 */
class CorcelIntegrationTest extends PHPUnit_Framework_TestCase
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
}
