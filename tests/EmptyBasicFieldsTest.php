<?php

use Corcel\Acf\Field\Text;
use Corcel\Acf\Field\BasicField;
use Corcel\Post;

/**
 * Class BasicFieldTest.
 *
 * @author Junior Grossi <juniorgro@gmail.com>
 */
class EmptyBasicFieldsTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Post
     */
    protected $post;

    /**
     * Setup a base $this->post object to represent the page with the basic fields.
     */
    protected function setUp()
    {
        $this->post = Post::find(82); // it' a page with empty custom fields
    }

    public function testTextFieldValue()
    {
        $text = new Text();
        $text->process('fake_text', $this->post);

        $this->assertSame('', $text->get());
    }

    public function testTextareaFieldValue()
    {
        $textarea = new Text();
        $textarea->process('fake_textarea', $this->post);

        $this->assertSame('', $textarea->get());
    }

    public function testNumberFieldValue()
    {
        $number = new Text();
        $number->process('fake_number', $this->post);

        $this->assertSame('', $number->get());
    }

    public function testEmailFieldValue()
    {
        $email = new Text();
        $email->process('fake_email', $this->post);

        $this->assertSame('', $email->get());
    }

    public function testUrlFieldValue()
    {
        $url = new Text();
        $url->process('fake_url', $this->post);

        $this->assertSame('', $url->get());
    }

    public function testPasswordFieldValue()
    {
        $password = new Text();
        $password->process('fake_password', $this->post);

        $this->assertSame('', $password->get());
    }

    /** @group this */
    public function testEmptyTextFieldValue()
    {
        $emptyPost = Post::find(82); // it' a page with empty custom fields

        $text = new Text();
        $text->process('fake_text', $emptyPost);

        $this->assertSame('', $text->get());
    }
}
