<?php

use Corcel\Acf\Field\Text;
use Corcel\Model\Post;

/**
 * Class BasicFieldTest.
 *
 * @author Junior Grossi <juniorgro@gmail.com>
 */
class BasicFieldsTest extends PHPUnit\Framework\TestCase
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
        $this->post = Post::find(11); // it' a page with the custom fields
    }

    public function testTextFieldValue()
    {
        $text = new Text($this->post);
        $text->process('fake_text');

        $this->assertEquals('Proin eget tortor risus', $text->get());
    }

    public function testTextareaFieldValue()
    {
        $textarea = new Text($this->post);
        $textarea->process('fake_textarea');

        $this->assertEquals('Praesent sapien massa, convallis a pellentesque nec, egestas non nisi.', $textarea->get());
    }

    public function testNumberFieldValue()
    {
        $number = new Text($this->post);
        $number->process('fake_number');

        $this->assertEquals('1984', $number->get());
    }

    public function testEmailFieldValue()
    {
        $email = new Text($this->post);
        $email->process('fake_email');

        $this->assertEquals('junior@corcel.org', $email->get());
    }

    public function testUrlFieldValue()
    {
        $url = new Text($this->post);
        $url->process('fake_url');

        $this->assertEquals('https://corcel.org', $url->get());
    }

    public function testPasswordFieldValue()
    {
        $password = new Text($this->post);
        $password->process('fake_password');

        $this->assertEquals('123change', $password->get());
    }
}
