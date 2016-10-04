<?php

use Corcel\Acf\Field\Basic\Text;
use Corcel\Post;

/**
 * Class BasicFieldTest
 *
 * @author Junior Grossi <juniorgro@gmail.com>
 */
class BasicFieldTest extends PHPUnit_Framework_TestCase 
{
    public function testTextFieldValue()
    {
        $post = Post::find(11); // it' a page with the custom fields
        $text = new Text($post, 'fake_text');
        $this->assertEquals('Proin eget tortor risus', $text->get());
    }
}
