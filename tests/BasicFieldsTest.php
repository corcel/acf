<?php

use Corcel\Acf\Field\Text;
use Corcel\Post;

/**
 * Class BasicFieldTest
 *
 * @author Junior Grossi <juniorgro@gmail.com>
 */
class BasicFieldsTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Post
     */
    protected $post;

    /**
     * Setup a base $this->post object to represent the page with the basic fields
     */
    protected function setUp()
    {
        $this->post = Post::find(11); // it' a page with the custom fields
    }

    public function testTextFieldValue()
    {
        $text = new Text($this->post, 'fake_text');
        $text->build();

        $this->assertEquals('Proin eget tortor risus', $text->get());
    }

    public function testTextareaFieldValue()
    {
        $textarea = new Text($this->post, 'fake_textarea');
        $textarea->build();

        $this->assertEquals('Praesent sapien massa, convallis a pellentesque nec, egestas non nisi.', $textarea->get());
    }

    public function testNumberFieldValue()
    {
        $number = new Text($this->post, 'fake_number');
        $number->build();

        $this->assertEquals('1984', $number->get());
    }

    public function testEmailFieldValue()
    {
        $email = new Text($this->post, 'fake_email');
        $email->build();

        $this->assertEquals('junior@corcel.org', $email->get());
    }

    public function testUrlFieldValue()
    {
        $url = new Text($this->post, 'fake_url');
        $url->build();

        $this->assertEquals('https://corcel.org', $url->get());

    }

    public function testPasswordFieldValue()
    {
        $password = new Text($this->post, 'fake_password');
        $password->build();

        $this->assertEquals('123change', $password->get());
    }
}
