<?php

use Corcel\Acf\Field\Text;
use Corcel\Acf\FieldFactory;
use Corcel\Post;

class FieldFactoryTest extends PHPUnit_Framework_TestCase
{
    public function testTextField()
    {
        $post = Post::find(11);
        $text = FieldFactory::make('text', $post, 'fake_text');
        $this->assertEquals('Proin eget tortor risus', $text);
    }

    public function testTextareaField()
    {
        $post = Post::find(11);
        $textarea = FieldFactory::make('textarea', $post, 'fake_textarea');
        $this->assertTrue(is_string($textarea));
        $this->assertTrue(strlen($textarea) > 0);
    }

    public function testNumberField()
    {
        $post = Post::find(11);
        $number = FieldFactory::make('number', $post, 'fake_number');
        $this->assertTrue(is_numeric($number));
    }

    public function testEmailField()
    {
        $post = Post::find(11);
        $email = FieldFactory::make('email', $post, 'fake_email');
        $this->assertEquals('junior@corcel.org', $email);
    }

    public function testUrlField()
    {
        $post = Post::find(11);
        $url = FieldFactory::make('url', $post, 'fake_url');
        $this->assertEquals('https://corcel.org', $url);
    }

    public function testPasswordField()
    {
        $post = Post::find(11);
        $password = FieldFactory::make('password', $post, 'fake_password');
        $this->assertEquals('123change', $password);
    }

    public function testEditorField()
    {
        $post = Post::find(21);
        $editor = FieldFactory::make('wysiwyg', $post, 'fake_editor');
        $this->assertNotFalse(strpos($editor, '<em>'));
    }

    public function testOembedField()
    {
        $post = Post::find(21);
        $embed = FieldFactory::make('oembed', $post, 'fake_oembed');
        $this->assertNotFalse(strpos($embed, 'youtube.com'));
    }
}

//        $fields = ['text', 'textarea', 'number', 'email', 'url', 'password', 'wysiwyg', 'oembed'];