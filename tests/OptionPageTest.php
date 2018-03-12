<?php

use Corcel\Acf\Field\Repeater;
use Corcel\Acf\Field\FlexibleContent;
use Corcel\Model\Post;
use Corcel\Model\Page;
use Corcel\Acf\Tests\TestCase;
use Corcel\Model\User;
use Corcel\Model\Attachment;
use Corcel\Acf\OptionPage;
use Corcel\Acf\Models\AcfFieldGroup;
use Corcel\Model\Meta\PostMeta;
use Corcel\Acf\Field\File;
use Corcel\Acf\Field\Gallery;
use Corcel\Acf\Field\Image;
use Corcel\Acf\Field\Text;
use Corcel\Acf\Exception\MissingFieldException;
use Corcel\Model\User as CorcelUser;
use Corcel\Model\Term as CorcelTerm;

class OptionPageTest extends TestCase
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
        parent::setUp();
    }

    /**
     * Create a sample post with acf fields
     */
    protected function createAcfPost($title, $prefix = 'options_')
    {
        $post = factory(AcfFieldGroup::class)->create(['post_title' => $title]);

        $this->createOptionAcfField($post, $prefix, 'fake_email', 'junior@corcel.org', 'email');

        $image = factory(Attachment::class)->create(['post_excerpt' => 'This is a caption.']);
        $image->meta()->save(factory(PostMeta::class)->states('attachment_metadata')->create());
        $this->createOptionAcfField($post, $prefix, 'fake_image', $image->ID, 'image');

        $file = factory(Attachment::class)->states('file')->create();
        $this->createOptionAcfField($post, $prefix, 'fake_file', $file->ID, 'file');

        $galleryImages = factory(Attachment::class, 7)->create()->each(function ($image) {
            $image->meta()->save(factory(PostMeta::class)->states('attachment_metadata')->create());
        });
        $this->createOptionAcfField($post, $prefix, 'fake_gallery', serialize($galleryImages->pluck('ID')), 'gallery');

        $this->createOptionAcfField($post, $prefix, 'fake_true_false', '1', 'true_false');

        $post2 = factory(Post::class)->create(['post_title' => 'Test post']);
        $this->createOptionAcfField($post, $prefix, 'fake_post_object', $post2->ID, 'post_object');

        $page2 = factory(Post::class)->states('page')->create([
            'guid' => 'http://wordpress.corcel.dev/?page_id=21',
            'post_name' => 'test-slug',
        ]);
        $this->createOptionAcfField($post, $prefix, 'fake_page_link', $page2->ID, 'page_link');

        $term = factory(CorcelTerm::class)->create(['slug' => 'uncategorized']);
        $this->createOptionAcfField($post, $prefix, 'fake_taxonomy_single', $term->term_id, 'taxonomy_single');

        $user = factory(CorcelUser::class)->create(['user_login' => 'admin']);
        $this->createOptionAcfField($post, $prefix, 'fake_user', $user->ID, 'user');

        $this->createOptionAcfField($post, $prefix, 'fake_date_time_picker', '2016-10-19 08:06:05', 'date_time_picker');


        return $post;
    }

    protected function createOptionPage($title = 'Test-Options', $prefix = 'options_')
    {
        $post = $this->createAcfPost($title, $prefix);
        return new OptionPage($title, $prefix);
    }

    public function testPrefix()
    {
        $post = $this->createAcfPost('test', 'test-prefix');

        $page = new OptionPage('test', 'test-prefix');
        $page->loadOptions();
        $this->assertEquals(10, $page->options->count());
    }

    public function testMissingField()
    {
        $page = $this->createOptionPage();
        $this->expectException(MissingFieldException::class);
        $page->text('missing-field');
    }

    public function testText()
    {
        $page = $this->createOptionPage();
        $this->assertEquals('junior@corcel.org', $page->text('fake_email'));
    }

    public function testImage()
    {
        $page = $this->createOptionPage();

        $image = $page->image('fake_image');

        $this->assertInstanceOf(Image::class, $image);

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

    public function testFile()
    {
        $page = $this->createOptionPage();
        $file = $page->file('fake_file');

        $this->assertInstanceOf(File::class, $file);
        $this->assertEquals('application/pdf', $file->mime_type);
    }

    public function testGallery()
    {
        $page = $this->createOptionPage();
        $gallery = $page->gallery('fake_gallery');

        $this->assertEquals(7, $gallery->count());

        /** @var Image $image */
        foreach ($gallery as $image) {
            $this->assertTrue($image->width > 0);
            $this->assertTrue($image->height > 0);
            $this->assertTrue(strlen($image->url) > 0);
        }

        // Testing the image in the 6th position
        $image = $gallery->get(6);
        $this->assertEquals(1920, $image->width);
        $this->assertEquals(1080, $image->height);
    }

    public function testBoolean()
    {
        $page = $this->createOptionPage();
        $this->assertTrue($page->boolean('fake_true_false'));
    }

    public function testPostobject()
    {
        $page = $this->createOptionPage();
        $post = $page->post_object('fake_post_object');
        $this->assertInstanceOf(Post::class, $post);
        $this->assertEquals('Test post', $post->post_title);
    }

    public function testPagelink()
    {
        $page = $this->createOptionPage();
        $link = $page->page_link('fake_page_link');
        $this->assertEquals('http://wordpress.corcel.dev/test-slug/', $link);
    }

    public function testTerm()
    {
        $page = $this->createOptionPage();
        $term = $page->term('fake_taxonomy_single');
        $this->assertInstanceOf(CorcelTerm::class, $term);
        $this->assertEquals('uncategorized', $term->slug);
    }

    public function testUser()
    {
        $page = $this->createOptionPage();
        $user = $page->user('fake_user');
        $this->assertInstanceOf(CorcelUser::class, $user);
        $this->assertEquals('admin', $user->user_login);
    }

    public function testDatetime()
    {
        $page = $this->createOptionPage();
        $datetime = $page->date_time_picker('fake_date_time_picker');
        $this->assertInstanceOf(\Carbon\Carbon::class, $datetime);
        $this->assertEquals('05:06:08/19-10:2016', $datetime->format('s:i:H/d-m:Y'));
    }
}
