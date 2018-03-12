<?php

use Corcel\Model\Post;
use Corcel\Acf\Tests\TestCase;
use Corcel\Acf\Repositories\PostRepository;

class PostRepositoryTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $post = factory(Post::class)->create();
        $this->createAcfField($post, 'fake_oembed', 'https://www.youtube.com/watch?v=LiyQ8bvLzIE', 'oembed', [], 'field_1234567890abc');

        $this->repo = new PostRepository($post);
    }

    public function testGetFieldKey()
    {
        $this->assertEquals('field_1234567890abc', $this->repo->getFieldKey('fake_oembed'));
    }

    public function testGetFieldType()
    {
        $this->assertEquals('oembed', $this->repo->getFieldType('fake_oembed'));
    }

    public function testFetchValue()
    {
        $this->assertEquals('https://www.youtube.com/watch?v=LiyQ8bvLzIE', $this->repo->fetchValue('fake_oembed'));
    }

    public function testGetKeyName()
    {
        $this->assertEquals('post_id', $this->repo->getKeyName('fake_oembed'));
    }

    public function testGetConnectionName()
    {
        $this->assertEquals('wp', $this->repo->getConnectionName('fake_oembed'));
    }

}
