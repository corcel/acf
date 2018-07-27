<?php

use Corcel\Acf\Field\DateTime;
use Corcel\Acf\Field\Text;
use Corcel\Acf\Field\CloneField;
use Corcel\Model\Post;
use Corcel\Acf\Tests\TestCase;
use Corcel\Acf\Models\AcfField;

class CloneFieldTests extends TestCase
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

        $acffield = factory(AcfField::class)->create();
        $cloned = $this->createAcfField($post, 'fake_date_picker2', '20161013', 'date_picker', [], 'field_xdferfdsdertd_' . $acffield->post_name);

        return $post;
    }

    public function testCloneField()
    {
        $clone = new CloneField($this->post);
        $clone->process('fake_date_picker');
        $this->assertInstanceOf(\Carbon\Carbon::class, $clone->get());
        $this->assertEquals('10/13/2016', $clone->get()->format('m/d/Y'));
    }
}
