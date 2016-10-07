<?php

use Corcel\Acf\Field\Text;
use Corcel\Acf\FieldFactory;
use Corcel\Post;

/**
 * Class FieldFactoryTest
 *
 * @author Junior Grossi <juniorgro@gmail.com>
 */
class FieldFactoryTest extends PHPUnit_Framework_TestCase
{
    public function testTextField()
    {
        $post = Post::find(11);
        $text = FieldFactory::make('fake_text', $post);
        $this->assertEquals('Proin eget tortor risus', $text);
    }

    public function testTextareaField()
    {
        $post = Post::find(11);
        $textarea = FieldFactory::make('fake_textarea', $post);
        $this->assertTrue(is_string($textarea));
        $this->assertTrue(strlen($textarea) > 0);
    }

    public function testNumberField()
    {
        $post = Post::find(11);
        $number = FieldFactory::make('fake_number', $post);
        $this->assertTrue(is_numeric($number));
    }

    public function testEmailField()
    {
        $post = Post::find(11);
        $email = FieldFactory::make('fake_email', $post);
        $this->assertEquals('junior@corcel.org', $email);
    }

    public function testUrlField()
    {
        $post = Post::find(11);
        $url = FieldFactory::make('fake_url', $post);
        $this->assertEquals('https://corcel.org', $url);
    }

    public function testPasswordField()
    {
        $post = Post::find(11);
        $password = FieldFactory::make('fake_password', $post);
        $this->assertEquals('123change', $password);
    }

    public function testEditorField()
    {
        $post = Post::find(21);
        $editor = FieldFactory::make('fake_editor', $post);
        $this->assertNotFalse(strpos($editor, '<em>'));
    }

    public function testOembedField()
    {
        $post = Post::find(21);
        $embed = FieldFactory::make('fake_oembed', $post);
        $this->assertNotFalse(strpos($embed, 'youtube.com'));
    }

    public function testImageField()
    {
        $post = Post::find(21);
        $image = FieldFactory::make('fake_image', $post);
        $this->assertTrue(is_numeric($image->width));
        $this->assertTrue(is_numeric($image->height));
        $this->assertNotFalse(strpos($image->url, 'http'));
    }

    // TODO write tests for all others fields as Factory
}