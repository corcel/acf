<?php

use Corcel\Acf\Field\DateTime;
use Corcel\Acf\Field\Text;
use Corcel\Model\Post;
use Corcel\Acf\Tests\TestCase;

class JqueryFieldsTests extends TestCase
{
    /**
     * @var Post
     */
    protected $post;

    /**
     * Setup a base $this->post object to represent the page with the content fields.
     */
    protected function setUp()
    {
        parent::setUp();
        $this->post = $this->createAcfPost();
    }

    /**
     * Create a sample post with acf fields
     */
    protected function createAcfPost()
    {
        $post = factory(Post::class)->create();

        $this->createAcfField($post, 'fake_date_picker', '20161013', 'date_picker');
        $this->createAcfField($post, 'fake_date_time_picker', '2016-10-19 08:06:05', 'date_time_picker');
        $this->createAcfField($post, 'fake_time_picker', '17:30:00', 'time_picker');
        $this->createAcfField($post, 'fake_color_picker', '#7263a8', 'color_picker');

        return $post;
    }

    public function testGoogleMapField()
    {
        // Google Map field is not working at this moment
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
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
