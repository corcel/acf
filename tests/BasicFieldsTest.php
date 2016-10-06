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
        $text = new Text();
        $text->process('fake_text', $this->post);

        $this->assertEquals('Proin eget tortor risus', $text->get());
    }

    public function testTextareaFieldValue()
    {
        $textarea = new Text();
        $textarea->process('fake_textarea', $this->post);

        $this->assertEquals('Praesent sapien massa, convallis a pellentesque nec, egestas non nisi.', $textarea->get());
    }

    public function testNumberFieldValue()
    {
        $number = new Text();
        $number->process('fake_number', $this->post);

        $this->assertEquals('1984', $number->get());
    }

    public function testEmailFieldValue()
    {
        $email = new Text();
        $email->process('fake_email', $this->post);

        $this->assertEquals('junior@corcel.org', $email->get());
    }

    public function testUrlFieldValue()
    {
        $url = new Text();
        $url->process('fake_url', $this->post);

        $this->assertEquals('https://corcel.org', $url->get());

    }

    public function testPasswordFieldValue()
    {
        $password = new Text();
        $password->process('fake_password', $this->post);

        $this->assertEquals('123change', $password->get());
    }
}
