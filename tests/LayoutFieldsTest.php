<?php

use Corcel\Acf\Field\Repeater;
use Corcel\Acf\Field\FlexibleContent;
use Corcel\Model\Post;

/**
 * Class LayoutFieldsTest.
 *
 * @author Junior Grossi <juniorgro@gmail.com>
 */
class LayoutFieldsTest extends PHPUnit\Framework\TestCase
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


    public function testFlexibleContentField()
    {
        $page = Post::find(86);
        $flex = new FlexibleContent($page);
        $flex->process('fake_flexible_content');
        $layout = $flex->get();

        $this->assertEquals(3, $layout->count());

        $this->assertEquals('normal_text', $layout[0]->type);
        $this->assertEquals('Lorem ipsum', $layout[0]->fields->text);

        $this->assertEquals('related_post', $layout[1]->type);
        $this->assertInstanceOf(Post::class, $layout[1]->fields->post);

        $this->assertEquals('multiple_posts', $layout[2]->type);
        $this->assertEquals(2, $layout[2]->fields->post->count());
        $this->assertInstanceOf(Post::class, $layout[2]->fields->post->first());
    }
}
