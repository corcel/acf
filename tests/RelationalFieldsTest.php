<?php

use Corcel\Acf\Field\PageLink;
use Corcel\Acf\Field\PostObject;
use Corcel\Acf\Field\Term;
use Corcel\Acf\Field\User;
use Corcel\Model\Post;
use Corcel\Acf\Tests\TestCase;
use Corcel\Model\User as CorcelUser;
use Corcel\Model\Term as CorcelTerm;

/**
 * Class RelationalFieldsTests.
 *
 * @author Junior Grossi <juniorgro@gmail.com>
 */
class RelationalFieldsTests extends TestCase
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

        $post2 = factory(Post::class)->create(['post_title' => 'ACF Basic Fields']);
        $this->createAcfField($post, 'fake_post_object', $post2->ID, 'post_object');

        $page = factory(Post::class)->states('page')->create([
            'guid' => 'http://wordpress.corcel.dev/?page_id=21',
            'post_name' => 'acf-content-fields',
        ]);
        $this->createAcfField($post, 'fake_page_link', $page->ID, 'page_link');

        $pageIds = [];
        $pageIds[] = factory(Post::class)->states('page')->create(['post_title' => 'test #1'])->ID;
        $pageIds[] = factory(Post::class)->states('page')->create(['post_title' => 'test #2'])->ID;
        $this->createAcfField($post, 'fake_relationship', serialize($pageIds), 'relationship');

        $term = factory(CorcelTerm::class)->create(['slug' => 'uncategorized']);
        $this->createAcfField($post, 'fake_taxonomy_single', $term->term_id, 'taxonomy_single');

        $term2 = factory(CorcelTerm::class)->create(['slug' => 'test-term']);
        $this->createAcfField($post, 'fake_taxonomy', serialize([$term->term_id, $term2->term_id]), 'taxonomy');


        $user = factory(CorcelUser::class)->create(['user_login' => 'admin']);
        $this->createAcfField($post, 'fake_user', $user->ID, 'user');

        return $post;
    }

    public function testPostObjectField()
    {
        $object = new PostObject($this->post);
        $object->process('fake_post_object');
        $this->assertEquals('ACF Basic Fields', $object->get()->post_title);
    }

    public function testPageLinkField()
    {
        $page = new PageLink($this->post);
        $page->process('fake_page_link');
        $this->assertEquals('http://wordpress.corcel.dev/acf-content-fields/', $page->get());
    }

    public function testRelationshipField()
    {
        $relation = new PostObject($this->post);
        $relation->process('fake_relationship');
        $posts = $relation->get();
        $this->assertEquals(['test #1', 'test #2'], $posts->pluck('post_title')->toArray());
    }

    public function testTaxonomyField()
    {
        $relation = new Term($this->post);

        $relation->process('fake_taxonomy'); // multiple (Collection)
        $this->assertEquals('uncategorized', $relation->get()->first()->slug);
        $this->assertEquals('test-term', $relation->get()->last()->slug);

        $relation->process('fake_taxonomy_single'); // single (Corcel\Term)
        $this->assertEquals('uncategorized', $relation->get()->slug);
    }

    public function testUserField()
    {
        $user = new User($this->post);
        $user->process('fake_user');
        $this->assertEquals('admin', $user->get()->user_login);
    }
}
