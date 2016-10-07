<?php

use Corcel\Acf\Field\PageLink;
use Corcel\Acf\Field\PostObject;
use Corcel\Acf\Field\Text;
use Corcel\Post;

/**
 * Class RelationalFieldsTests
 *
 * @author Junior Grossi <juniorgro@gmail.com>
 */
class RelationalFieldsTests extends PHPUnit_Framework_TestCase
{
    /**
     * @var Post
     */
    protected $post;

    public function setUp()
    {
        $this->post = Post::find(56);
    }

    public function testPostObjectField()
    {
        $object = new PostObject();
        $object->process('fake_post_object', $this->post);
        $this->assertEquals('ACF Basic Fields', $object->get()->post_title);
    }

    public function testPageLinkField()
    {
        $page = new PageLink();
        $page->process('fake_page_link', $this->post);
        $this->assertEquals('http://wordpress.corcel.dev/acf-content-fields/', $page->get());
    }

    public function testRelationshipField()
    {
        $relation = new PostObject();
        $relation->process('fake_relationship', $this->post);
        $posts = $relation->get();
        $this->assertEquals([44, 56], $posts->pluck('ID')->toArray());
    }
}