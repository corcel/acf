<?php

use Corcel\Acf\Field\Text;
use Corcel\Model\Post;
use Corcel\Acf\Tests\TestCase;
use Corcel\Acf\Repositories\PostRepository;

/**
 * Class BasicFieldTest.
 *
 * @author Junior Grossi <juniorgro@gmail.com>
 */
class BasicFieldsTest extends TestCase
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
        parent::setUp();

        $post = $this->createAcfPost();
        $this->repo = new PostRepository($post);
    }

    public function testTextFieldValue()
    {
        $text = new Text($this->repo);
        $text->process('fake_text');

        $this->assertEquals('Proin eget tortor risus', $text->get());
    }

    public function testTextareaFieldValue()
    {
        $textarea = new Text($this->repo);
        $textarea->process('fake_textarea');

        $this->assertEquals('Praesent sapien massa, convallis a pellentesque nec, egestas non nisi.', $textarea->get());
    }

    public function testNumberFieldValue()
    {
        $number = new Text($this->repo);
        $number->process('fake_number');

        $this->assertEquals('1984', $number->get());
    }

    public function testEmailFieldValue()
    {
        $email = new Text($this->repo);
        $email->process('fake_email');

        $this->assertEquals('junior@corcel.org', $email->get());
    }

    public function testUrlFieldValue()
    {
        $url = new Text($this->repo);
        $url->process('fake_url');

        $this->assertEquals('https://corcel.org', $url->get());
    }

    public function testPasswordFieldValue()
    {
        $password = new Text($this->repo);
        $password->process('fake_password');

        $this->assertEquals('123change', $password->get());
    }
}
