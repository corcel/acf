<?php

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
            $field->get()
        );
    }

    public function testOembedFieldValue()
    {
        $field = new Text($this->post, 'fake_oembed');
        $this->assertEquals('https://www.youtube.com/watch?v=LiyQ8bvLzIE', $field->get());
    }
}
