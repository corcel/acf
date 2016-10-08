<?php

use Corcel\Acf\Field\DateTime;
use Corcel\Post;

class JqueryFieldsTests extends PHPUnit_Framework_TestCase
{
    /**
     * @var Post
     */
    protected $post;

    public function setUp()
    {
        $this->post = Post::find(65);
    }

    public function testGoogleMapField()
    {
        // Google Map field is not working at this moment
    }

    public function testDatePickerField()
    {
        $date = new DateTime();
        $date->process('fake_date_picker', $this->post);
        $this->assertEquals('10/13/2016', $date->get()->format('m/d/Y'));
    }
}