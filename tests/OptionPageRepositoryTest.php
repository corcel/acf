<?php

use Corcel\Model\Post;
use Corcel\Acf\Tests\TestCase;
use Corcel\Acf\Repositories\OptionPageRepository;
use Corcel\Acf\Models\AcfFieldGroup;
use Corcel\Acf\OptionPage;

class OptionPageRepositoryTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $title = 'test-options';
        $prefix = 'options_';

        $post = factory(AcfFieldGroup::class)->create(['post_title' => $title]);

        $post2 = factory(Post::class)->create(['post_title' => 'Test post', 'ID' => 33]);
        $this->createOptionAcfField($post, $prefix, 'fake_post_object', $post2->ID, 'post_object', [], 'field_0123456789abc');

        $optionPage = new OptionPage($title, $prefix);
        $this->repo = new OptionPageRepository($optionPage);
    }

    public function testGetFieldKey()
    {
        $this->assertEquals('field_0123456789abc', $this->repo->getFieldKey('fake_post_object'));
    }

    public function testFetchValue()
    {
        $this->assertEquals('33', $this->repo->fetchValue('fake_post_object'));
    }

    public function testGetConnectionName()
    {
        $this->assertEquals('wp', $this->repo->getConnectionName('fake_oembed'));
    }
}
