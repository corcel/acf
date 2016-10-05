<?php

use Corcel\Acf\Field\Image;
use Corcel\Acf\Field\Text;
use Corcel\Post;

/**
 * Class ContentFieldsTest
 *
 * @author Junior Grossi <juniorgro@gmail.com>
 */
class ContentFieldsTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Post
     */
    protected $post;

    /**
     * Setup a base $this->post object to represent the page with the content fields
     */
    protected function setUp()
    {
        $this->post = Post::find(21); // it' a page with the custom fields
    }

    public function testEditorFieldValue()
    {
        $field = new Text($this->post, 'fake_editor');

        $this->assertEquals(
            'Nulla <em>porttitor</em> <del>accumsan</del> <strong>tincidunt</strong>. Sed porttitor lectus nibh.',
            $field
        );
    }

    public function testOembedFieldValue()
    {
        $field = new Text($this->post, 'fake_oembed');
        $this->assertEquals('https://www.youtube.com/watch?v=LiyQ8bvLzIE', $field);
    }

    public function testImageFieldValue()
    {
        $field = new Image($this->post, 'fake_image');
        $this->assertEquals('1920', $field->width);
        $this->assertEquals('1080', $field->height);
        $this->assertEquals('2016/10/maxresdefault-1.jpg', $field->filename);
        $this->assertEquals('1024', $field->size('large')->width);
        $this->assertEquals('image/jpeg', $field->mime_type);
    }
}
