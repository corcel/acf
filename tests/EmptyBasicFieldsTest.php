<?php

use Corcel\Acf\Field\Text;
use Corcel\Acf\Field\BasicField;
use Corcel\Model\Post;

/**
 * Class BasicFieldTest.
 *
 * @author Junior Grossi <juniorgro@gmail.com>
 */
class EmptyBasicFieldsTest extends PHPUnit\Framework\TestCase
{
    /**
     * @var Post
     */
    protected $post;

    /**
     * Setup a base $this->post object to represent the page with the basic fields.
     */
    protected function setUp(): void
    {
        $this->post = Post::find(91); // it' a page with empty custom fields
    }

    public function testTextFieldValue()
    {
        $text = new Text($this->post);
        $text->process('fake_text');

        $this->assertSame('', $text->get());
    }

    public function testTextareaFieldValue()
    {
        $textarea = new Text($this->post);
        $textarea->process('fake_textarea');

        $this->assertSame('', $textarea->get());
    }

    public function testNumberFieldValue()
    {
        $number = new Text($this->post);
        $number->process('fake_number');

        $this->assertSame('', $number->get());
    }

    public function testEmailFieldValue()
    {
        $email = new Text($this->post);
        $email->process('fake_email');

        $this->assertSame('', $email->get());
    }

    public function testUrlFieldValue()
    {
        $url = new Text($this->post);
        $url->process('fake_url');

        $this->assertSame('', $url->get());
    }

    public function testPasswordFieldValue()
    {
        $password = new Text($this->post);
        $password->process('fake_password');

        $this->assertSame('', $password->get());
    }
}
