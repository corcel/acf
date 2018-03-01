<?php

use Corcel\Acf\Field\Repeater;
use Corcel\Acf\Field\FlexibleContent;
use Corcel\Model\Post;
use Corcel\Acf\Tests\TestCase;
use Corcel\Model\User;
use Corcel\Model\Attachment;

/**
 * Class LayoutFieldsTest.
 *
 * @author Junior Grossi <juniorgro@gmail.com>
 */
class LayoutFieldsTest extends TestCase
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

        // repeater #1
        $this->createAcfField($post, 'fake_repeater', '2', 'repeater');
        $this->createAcfField($post, 'fake_repeater_0_repeater_text', 'First text');
        $this->createAcfField($post, 'fake_repeater_0_repeater_radio', 'blue', 'radio_button');
        $this->createAcfField($post, 'fake_repeater_1_repeater_text', 'Second text');
        $this->createAcfField($post, 'fake_repeater_1_repeater_radio', 'red', 'radio_button');


        // repeater #1
        $users = factory(User::class, 2)->create();
        $files = factory(Attachment::class, 2)->states('file')->create();
        $relationships = factory(Post::class, 5)->states('page')->create();

        $this->createAcfField($post, 'fake_repeater_2', '2', 'repeater');
        $this->createAcfField($post, 'fake_repeater_2_0_fake_user', $users->first()->ID, 'user');
        $this->createAcfField($post, 'fake_repeater_2_0_fake_file', $files->first()->ID, 'file');
        $this->createAcfField($post, 'fake_repeater_2_0_fake_relationship', serialize($relationships->take(2)->pluck('ID')), 'relationship');
        $this->createAcfField($post, 'fake_repeater_2_1_fake_user', $users->last()->ID, 'user');
        $this->createAcfField($post, 'fake_repeater_2_1_fake_file', $files->last()->ID, 'file');
        $this->createAcfField($post, 'fake_repeater_2_1_fake_relationship', serialize($relationships->take(1)->pluck('ID')), 'relationship');


        // flexible content
        $post2 = factory(Post::class)->create();
        $posts = factory(Post::class, 2)->create();
        $this->createAcfField($post, 'fake_flexible_content', 'a:3:{i:0;s:11:"normal_text";i:1;s:12:"related_post";i:2;s:14:"multiple_posts";}', 'flexible_content');
        $this->createAcfField($post, 'fake_flexible_content_0_text', 'Lorem ipsum');
        $this->createAcfField($post, 'fake_flexible_content_1_post', $post2->ID, 'post_object');
        $this->createAcfField($post, 'fake_flexible_content_2_post', serialize($posts->pluck('ID')->toArray()), 'post_object');


        return $post;
    }

    public function testRepeaterField()
    {
        $repeater = new Repeater($this->post);
        $repeater->process('fake_repeater');
        $fields = $repeater->get()->toArray();

        $this->assertEquals('First text', $fields[0]['repeater_text']);
        $this->assertEquals('Second text', $fields[1]['repeater_text']);
        $this->assertEquals('blue', $fields[0]['repeater_radio']);
        $this->assertEquals('red', $fields[1]['repeater_radio']);
    }

    public function testComplexRepeaterField()
    {
        $repeater = new Repeater($this->post);
        $repeater->process('fake_repeater_2');
        $fields = $repeater->get()->toArray();

        $this->assertEquals('admin', $fields[0]['fake_user']->user_login);
        $this->assertEquals('admin', $fields[1]['fake_user']->user_login);
        $this->assertEquals(2, $fields[0]['fake_relationship']->count());
        $this->assertEquals(1, $fields[1]['fake_relationship']->count());
        $this->assertInstanceOf(Post::class, $fields[0]['fake_relationship']->first());
    }


    public function testFlexibleContentField()
    {
        $flex = new FlexibleContent($this->post);
        $flex->process('fake_flexible_content');
        $layout = $flex->get();

        // FIXME this test has be changed to work with the new & fixed flexible
        // content

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
