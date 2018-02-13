<?php

use Corcel\Acf\Field\Text;
use Corcel\Acf\Field\BasicField;
use Corcel\Model\Post;
use Corcel\Acf\Tests\TestCase;
use Corcel\Acf\Repositories\PostRepository;

/**
 * Class BasicFieldTest.
 *
 * @author Junior Grossi <juniorgro@gmail.com>
 */
class EmptyBasicFieldsTest extends TestCase
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
        $this->createAcfField($post, 'fake_text', '');
        $this->createAcfField($post, 'fake_textarea', '', 'textarea');
        $this->createAcfField($post, 'fake_number', '', 'number');
        $this->createAcfField($post, 'fake_email', '', 'email');
        $this->createAcfField($post, 'fake_url', '', 'url');
        $this->createAcfField($post, 'fake_password', '', 'password');

        return $post;
    }

    public function testTextFieldValue()
    {
        $text = new Text($this->repo);
        $text->process('fake_text');

        $this->assertSame('', $text->get());
    }

    public function testTextareaFieldValue()
    {
        $textarea = new Text($this->repo);
        $textarea->process('fake_textarea');

        $this->assertSame('', $textarea->get());
    }

    public function testNumberFieldValue()
    {
        $number = new Text($this->repo);
        $number->process('fake_number');

        $this->assertSame('', $number->get());
    }

    public function testEmailFieldValue()
    {
        $email = new Text($this->repo);
        $email->process('fake_email');

        $this->assertSame('', $email->get());
    }

    public function testUrlFieldValue()
    {
        $url = new Text($this->repo);
        $url->process('fake_url');

        $this->assertSame('', $url->get());
    }

    public function testPasswordFieldValue()
    {
        $password = new Text($this->repo);
        $password->process('fake_password');

        $this->assertSame('', $password->get());
    }
}
