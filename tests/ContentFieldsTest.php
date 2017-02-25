<?php

use Corcel\Acf\Field\File;
use Corcel\Acf\Field\Gallery;
use Corcel\Acf\Field\Image;
use Corcel\Acf\Field\Text;
use Corcel\Post;

/**
 * Class ContentFieldsTest.
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
     * Setup a base $this->post object to represent the page with the content fields.
     */
    protected function setUp()
    {
        $this->post = Post::find(21); // it' a page with the custom fields
    }

    public function testEditorFieldValue()
    {
        $field = new Text($this->post);
        $field->process('fake_editor');

        $this->assertEquals(
            'Nulla <em>porttitor</em> <del>accumsan</del> <strong>tincidunt</strong>. Sed porttitor lectus nibh.',
            $field->get()
        );
    }

    public function testOembedFieldValue()
    {
        $field = new Text($this->post);
        $field->process('fake_oembed');

        $this->assertEquals('https://www.youtube.com/watch?v=LiyQ8bvLzIE', $field->get());
    }

    public function testImageFieldValue()
    {
        $image = new Image($this->post);
        $image->process('fake_image');

        $this->assertEquals('1920', $image->width);
        $this->assertEquals('1080', $image->height);
        $this->assertEquals('maxresdefault-1.jpg', $image->filename);
        $this->assertEquals('1024', $image->size('large')->width);
        $this->assertEquals('image/jpeg', $image->mime_type);
        $this->assertEquals('This is a caption', $image->description);
    }

    public function testFileFieldValue()
    {
        $file = new File($this->post);
        $file->process('fake_file');

        $this->assertEquals('Description here', $file->description);
        $this->assertEquals('Title here', $file->title);
        $this->assertEquals('Caption here', $file->caption);
        $this->assertEquals('application/pdf', $file->mime_type);
        $this->assertEquals('Consolidado-Manifestacao-do-Conselho-Deliberativo.pdf', $file->filename);
    }

    public function testGalleryFieldValue()
    {
        $gallery = new Gallery($this->post);
        $gallery->process('fake_gallery');

        $this->assertEquals(7, $gallery->get()->count());

        /** @var Image $image */
        foreach ($gallery->get() as $image) {
            $this->assertTrue($image->width > 0);
            $this->assertTrue($image->height > 0);
            $this->assertTrue(strlen($image->url) > 0);
        }

        // Testing the image in the 6th position
        $image = $gallery->get()->get(6);
        $this->assertEquals(1920, $image->width);
        $this->assertEquals(1080, $image->height);
    }

    public function testImageCustomMetadataValues($value='')
    {
        $image = new Image($this->post);
        $image->process('fake_image');

        $this->assertCount(
            4, 
            $image->fetchCustomMetadataValues(
                '_gallery_link_url,
                _gallery_link_target,
                _gallery_link_preserve_click,
                _gallery_link_additional_css_classes')
        );
        $this->assertTrue( is_string($image->fetchCustomMetadataValues('_gallery_link_url')) );

        $this->assertEquals('www.example.com', $image->fetchCustomMetadataValues('_gallery_link_url'));
        $this->assertEquals('_blank', $image->fetchCustomMetadataValues('_gallery_link_target'));
        $this->assertEquals('remove', $image->fetchCustomMetadataValues('_gallery_link_preserve_click'));
        $this->assertEquals('underlined', $image->fetchCustomMetadataValues('_gallery_link_additional_css_classes'));
    }


}
