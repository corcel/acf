<?php

use Corcel\Acf\FieldFactory;
use Corcel\Model\Post;
use Corcel\Acf\Tests\TestCase;
use Corcel\Model\Attachment;
use Corcel\Model\Meta\PostMeta;

/**
 * Class FieldFactoryTest.
 *
 * @author Junior Grossi <juniorgro@gmail.com>
 */
class FieldFactoryTest extends TestCase
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
        $this->post = $this->createAcfPost();
    }

    /**
     * Create a sample post with acf fields
     */
    protected function createAcfPost()
    {
        $post = factory(Post::class)->create();
        $this->createAcfField($post, 'fake_text', 'Proin eget tortor risus');
        $this->createAcfField($post, 'fake_textarea', 'Praesent sapien massa, convallis a pellentesque nec, egestas non nisi.', 'textarea');
        $this->createAcfField($post, 'fake_number', '1984', 'number');
        $this->createAcfField($post, 'fake_email', 'junior@corcel.org', 'email');
        $this->createAcfField($post, 'fake_url', 'https://corcel.org', 'url');
        $this->createAcfField($post, 'fake_password', '123change', 'password');

        $this->createAcfField($post, 'fake_editor', 'Nulla <em>porttitor</em> <del>accumsan</del> <strong>tincidunt</strong>. Sed porttitor lectus nibh.', 'editor');

        $this->createAcfField($post, 'fake_oembed', 'https://www.youtube.com/watch?v=LiyQ8bvLzIE', 'oembed');

        $image = factory(Attachment::class)->create(['post_excerpt' => 'This is a caption.']);
        $image->meta()->save(factory(PostMeta::class)->states('attachment_metadata')->create());
        $this->createAcfField($post, 'fake_image', $image->ID, 'image');

        return $post;
    }

    public function testInvalidFieldName()
    {
        $invalidField = FieldFactory::make('invalid_field', $this->post);
        $this->assertNull($invalidField);
    }

    public function testTextField()
    {
        $text = FieldFactory::make('fake_text', $this->post);
        $this->assertEquals('Proin eget tortor risus', $text->get());
    }

    public function testTextareaField()
    {
        $textarea = FieldFactory::make('fake_textarea', $this->post);
        $this->assertTrue(is_string($textarea->get()));
        $this->assertTrue(strlen($textarea->get()) > 0);
    }

    public function testNumberField()
    {
        $number = FieldFactory::make('fake_number', $this->post);
        $this->assertTrue(is_numeric($number->get()));
    }

    public function testEmailField()
    {
        $email = FieldFactory::make('fake_email', $this->post);
        $this->assertEquals('junior@corcel.org', $email->get());
    }

    public function testUrlField()
    {
        $url = FieldFactory::make('fake_url', $this->post);
        $this->assertEquals('https://corcel.org', $url->get());
    }

    public function testPasswordField()
    {
        $password = FieldFactory::make('fake_password', $this->post);
        $this->assertEquals('123change', $password->get());
    }

    public function testEditorField()
    {
        $editor = FieldFactory::make('fake_editor', $this->post);
        $this->assertNotFalse(strpos($editor->get(), '<em>'));
    }

    public function testOembedField()
    {
        $embed = FieldFactory::make('fake_oembed', $this->post);
        $this->assertNotFalse(strpos($embed->get(), 'youtube.com'));
    }

    public function testImageField()
    {
        $image = FieldFactory::make('fake_image', $this->post);
        $this->assertTrue(is_numeric($image->get()->width));
        $this->assertTrue(is_numeric($image->get()->height));
        $this->assertNotFalse(strpos($image->get()->url, 'http'));
    }

    // TODO write tests for all others fields as Factory
}
