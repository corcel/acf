<?php

use Corcel\Acf\Field\Repeater;
use Corcel\Acf\Field\Text;

/**
 * Class LayoutFieldsTest
 *
 * @author Junior Grossi <juniorgro@gmail.com>
 */
class LayoutFieldsTest extends PHPUnit_Framework_TestCase
{
    public function testRepeaterField()
    {
        $page = Post::find(73);
        $repeater =  new Repeater();
        $repeater->process('fake_repeater', $page);
        $fields = $repeater->get()->toArray();

        $this->assertEquals('First text', $fields[0]['repeater_text']);
        $this->assertEquals('Second text', $fields[1]['repeater_text']);
        $this->assertEquals('blue', $fields[0]['repeater_radio']);
        $this->assertEquals('red', $fields[1]['repeater_radio']);
    }
}