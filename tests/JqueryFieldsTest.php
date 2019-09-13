<?php

use Corcel\Acf\Field\DateTime;
use Corcel\Acf\Field\Text;
use Corcel\Model\Post;

class JqueryFieldsTests extends PHPUnit\Framework\TestCase
{
    /**
     * @var Post
     */
    protected $post;

    protected function setUp(): void
    {
        $this->post = Post::find(65);
    }

    public function testGoogleMapField()
    {
        $this->markTestSkipped('Google Map field i s not working at the moment');
    }

    public function testDatePickerField()
    {
        $date = new DateTime($this->post);
        $date->process('fake_date_picker');
        $this->assertEquals('10/13/2016', $date->get()->format('m/d/Y'));
    }

    public function testDateTimePickerField()
    {
        $dateTime = new DateTime($this->post);
        $dateTime->process('fake_date_time_picker');
        $this->assertEquals('05:06:08/19-10:2016', $dateTime->get()->format('s:i:H/d-m:Y')); // 2016-10-19 08:06:05
    }

    public function testTimePickerField()
    {
        $time = new DateTime($this->post);
        $time->process('fake_time_picker');
        $this->assertEquals('00/17/30', $time->get()->format('s/H/i')); // 17:30:00
    }

    public function testColorPickerField()
    {
        $color = new Text($this->post);
        $color->process('fake_color_picker');
        $this->assertEquals('#7263a8', $color->get());
    }
}
