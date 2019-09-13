<?php

use Corcel\Acf\Field\Boolean;
use Corcel\Acf\Field\Text;
use Corcel\Model\Post;

/**
 * Class ChoicesFieldsTest.
 *
 * @author Junior Grossi <juniorgro@gmail.com>
 */
class ChoicesFieldsTest extends PHPUnit\Framework\TestCase
{
    /**
     * @var Post
     */
    protected $post;

    protected function setUp(): void
    {
        $this->post = Post::find(44);
    }

    public function testSelectField()
    {
        $select = new Text($this->post);
        $select->process('fake_select');
        $this->assertEquals('red', $select->get());
    }

    public function testSelectMultipleField()
    {
        $select = new Text($this->post);
        $select->process('fake_select_multiple');
        $this->assertEquals(['yellow', 'green'], $select->get());
    }

    public function testCheckboxField()
    {
        $check = new Text($this->post);
        $check->process('fake_checkbox');
        $this->assertEquals(['blue', 'yellow'], $check->get());
    }

    public function testRadioField()
    {
        $radio = new Text($this->post);
        $radio->process('fake_radio_button');
        $this->assertEquals('green', $radio->get());
    }

    public function testTrueFalseField()
    {
        $boolean = new Boolean($this->post);
        $boolean->process('fake_true_false');
        $this->assertTrue($boolean->get());
    }
}
