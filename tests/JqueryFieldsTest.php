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

    public function testDateTimePickerField()
    {
        $dateTime = new DateTime();
        $dateTime->process('fake_date_time_picker', $this->post);
        $this->assertEquals('05:06:08/19-10:2016', $dateTime->get()->format('s:i:H/d-m:Y')); // 2016-10-19 08:06:05
    }

    public function testTimePickerField()
    {
        $time = new DateTime();
        $time->process('fake_time_picker', $this->post);
        $this->assertEquals('00/17/30', $time->get()->format('s/H/i')); // 17:30:00
    }
}