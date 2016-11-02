<?php

use Corcel\Acf\Field\Boolean;
use Corcel\Acf\Field\Text;
use Corcel\Post;

/**
 * Class ChoicesFieldsTest.
 *
 * @author Junior Grossi <juniorgro@gmail.com>
 */
class ChoicesFieldsTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Post
     */
    protected $post;

    public function setUp()
    {
        $this->post = Post::find(44);
    }

    public function testSelectField()
    {
        $select = new Text();
        $select->process('fake_select', $this->post);
        $this->assertEquals('red', $select->get());
    }

    public function testSelectMultipleField()
    {
        $select = new Text();
        $select->process('fake_select_multiple', $this->post);
        $this->assertEquals(['yellow', 'green'], $select->get());
    }

    public function testCheckboxField()
    {
        $check = new Text();
        $check->process('fake_checkbox', $this->post);
        $this->assertEquals(['blue', 'yellow'], $check->get());
    }

    public function testRadioField()
    {
        $radio = new Text();
        $radio->process('fake_radio_button', $this->post);
        $this->assertEquals('green', $radio->get());
    }

    public function testTrueFalseField()
    {
        $boolean = new Boolean();
        $boolean->process('fake_true_false', $this->post);
        $this->assertTrue($boolean->get());
    }
}
