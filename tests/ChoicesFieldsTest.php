<?php
use Corcel\Acf\Field\Select;
use Corcel\Acf\FieldFactory;

/**
 * Class ChoicesFieldsTest
 *
 * @author Junior Grossi <juniorgro@gmail.com>
 */
class ChoicesFieldsTest extends PHPUnit_Framework_TestCase
{
    public function testSelectField()
    {
        $post = Post::find(44); // page
        $select = new Select($post, 'fake_select');
        $select->build();
        $this->assertEquals('red', $select->get());
    }

    public function testSelectMultipleField()
    {
        $post = Post::find(44);
        $select = new Select($post, 'fake_select_multiple');
        $select->build();
        $this->assertEquals(['yellow', 'green'], $select->get());
    }

    public function testCheckboxField()
    {
        $post = Post::find(44);
        $check = new Select($post, 'fake_checkbox');
        $check->build();
        $this->assertEquals(['blue', 'yellow'], $check->get());
    }

    public function testRadioField()
    {
        $post = Post::find(44);
        $radio = new Select($post, 'fake_radio_button');
        $radio->build();
        $this->assertEquals('green', $radio->get());
    }
}