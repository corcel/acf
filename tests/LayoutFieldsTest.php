<?php

use Corcel\Acf\Field\Repeater;

/**
 * Class LayoutFieldsTest.
 *
 * @author Junior Grossi <juniorgro@gmail.com>
 */
class LayoutFieldsTest extends PHPUnit_Framework_TestCase
{
    public function testRepeaterField()
    {
        $page = Post::find(73);
        $repeater = new Repeater($page);
        $repeater->process('fake_repeater');
        $fields = $repeater->get()->toArray();

        $this->assertEquals('First text', $fields[0]['repeater_text']);
        $this->assertEquals('Second text', $fields[1]['repeater_text']);
        $this->assertEquals('blue', $fields[0]['repeater_radio']);
        $this->assertEquals('red', $fields[1]['repeater_radio']);
    }

    public function testComplexRepeaterField()
    {
        $page = Post::find(73);
        $repeater = new Repeater($page);
        $repeater->process('fake_repeater_2');
        $fields = $repeater->get()->toArray();

        $this->assertEquals('admin', $fields[0]['fake_user']->nickname);
        $this->assertEquals('admin', $fields[1]['fake_user']->nickname);
        $this->assertEquals(2, $fields[0]['fake_relationship']->count());
        $this->assertEquals(1, $fields[1]['fake_relationship']->count());
        $this->assertInstanceOf(Post::class, $fields[0]['fake_relationship']->first());
    }
}
