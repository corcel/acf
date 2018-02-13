<?php

use Corcel\Acf\Field\File;
use Corcel\Acf\Field\Gallery;
use Corcel\Acf\Field\Image;
use Corcel\Acf\Field\Text;
use Corcel\Model\Post;
use Corcel\Acf\Repositories\PostRepository;
use Corcel\Acf\Tests\TestCase;
use Corcel\Model\Attachment;
use Corcel\Model\Meta\PostMeta;

/**
 * Class ContentFieldsTest.
 *
 * @author Junior Grossi <juniorgro@gmail.com>
 */
class ContentFieldsTest extends TestCase
{
    /**
     * @var PostRepository
     */
    protected $repo;

    /**
     * Setup a base $this->post object to represent the page with the content fields.
     */
    protected function setUp()
    {
        parent::setUp();
        $post = $this->createAcfPost();
        $this->repo = new PostRepository($post);
    }

    /**
     * Create a sample post with acf fields
     */
    protected function createAcfPost()
    {
        $post = factory(Post::class)->create();
        $this->createAcfField($post, 'fake_editor', 'Nulla <em>porttitor</em> <del>accumsan</del> <strong>tincidunt</strong>. Sed porttitor lectus nibh.', 'editor');

        $this->createAcfField($post, 'fake_oembed', 'https://www.youtube.com/watch?v=LiyQ8bvLzIE', 'oembed');

        $image = factory(Attachment::class)->create(['post_excerpt' => 'This is a caption.']);
        $image->meta()->save(factory(PostMeta::class)->states('attachment_metadata')->create());
        $this->createAcfField($post, 'fake_image', $image->ID, 'image');

        $file = factory(Attachment::class)->states('file')->create([
            'post_content' => 'Description here',
            'post_title' => 'Title here',
            'post_excerpt' => 'Caption here',
            'guid' => 'http://wordpress.corcel.dev/wp-content/uploads/2016/10/Consolidado-Manifestacao-do-Conselho-Deliberativo.pdf',
        ]);
        $this->createAcfField($post, 'fake_file', $file->ID, 'file');

        $galleryImages = factory(Attachment::class, 7)->create()->each(function ($image) {
            $image->meta()->save(factory(PostMeta::class)->states('attachment_metadata')->create());
        });
        $this->createAcfField($post, 'fake_gallery', serialize($galleryImages->pluck('ID')), 'gallery');

        return $post;
    }

    public function testEditorFieldValue()
    {
        $field = new Text($this->repo);
        $field->process('fake_editor');

        $this->assertEquals(
            'Nulla <em>porttitor</em> <del>accumsan</del> <strong>tincidunt</strong>. Sed porttitor lectus nibh.',
            $field->get()
        );
    }

    public function testOembedFieldValue()
    {
        $field = new Text($this->repo);
        $field->process('fake_oembed');

        $this->assertEquals('https://www.youtube.com/watch?v=LiyQ8bvLzIE', $field->get());
    }

    public function testImageFieldValue()
    {
        $image = new Image($this->repo);
        $image->process('fake_image');

        $this->assertEquals('1920', $image->width);
        $this->assertEquals('1080', $image->height);
        $this->assertEquals('maxresdefault-1.jpg', $image->filename);

        // Test existing image size
        $this->assertEquals('1024', $image->size('large')->width);
        $this->assertNotEmpty($image->size('large')->url);

        // Test non existing image size with thumbnail as fallback
        $this->assertEquals('150', $image->size('fake_size')->width);
        $this->assertNotEmpty($image->size('fake_size')->url);

        // Test non existing image size with original as fallback
        $this->assertEquals($image->width, $image->size('fake_size', true)->width);
        $this->assertEquals($image->height, $image->size('fake_size', true)->height);
        $this->assertNotEmpty($image->size('fake_size', true)->url);

        $this->assertEquals('image/jpeg', $image->mime_type);
        $this->assertEquals('This is a caption.', $image->description);
    }

    public function testFileFieldValue()
    {
        $file = new File($this->repo);
        $file->process('fake_file');

        $this->assertEquals('Description here', $file->description);
        $this->assertEquals('Title here', $file->title);
        $this->assertEquals('Caption here', $file->caption);
        $this->assertEquals('application/pdf', $file->mime_type);
        $this->assertEquals('Consolidado-Manifestacao-do-Conselho-Deliberativo.pdf', $file->filename);
    }

    public function testGalleryFieldValue()
    {
        $gallery = new Gallery($this->repo);
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
}
