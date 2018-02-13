<?php

use Corcel\Acf\Field\Boolean;
use Corcel\Acf\Field\Text;
use Corcel\Model\Post;
use Corcel\Acf\Tests\TestCase;
use Corcel\Acf\Repositories\PostRepository;

/**
 * Class ChoicesFieldsTest.
 *
 * @author Junior Grossi <juniorgro@gmail.com>
 */
class ChoicesFieldsTest extends TestCase
{
    /**
     * @var PostRepository
     */
    protected $repo;

    public function setUp()
    {
        parent::setUp();
        $post = $this->createAcfPost();
        $this->repo = new PostRepository($post);
    }

    /**
     * Create a sample post with acf fields
     */
    protected function createAcfPost()
    {
        $post = factory(Post::class)->create();

        $this->createAcfField($post, 'fake_select', 'red', 'select');
        $this->createAcfField($post, 'fake_select_multiple', serialize(['yellow', 'green']), 'select_multiple');
        $this->createAcfField($post, 'fake_checkbox', serialize(['blue', 'yellow']), 'checkbox');
        $this->createAcfField($post, 'fake_radio_button', 'green', 'radio_button');
        $this->createAcfField($post, 'fake_true_false', '1', 'true_false');

        return $post;
    }

    public function testSelectField()
    {
        $select = new Text($this->repo);
        $select->process('fake_select');
        $this->assertEquals('red', $select->get());
    }

    public function testSelectMultipleField()
    {
        $select = new Text($this->repo);
        $select->process('fake_select_multiple');
        $this->assertEquals(['yellow', 'green'], $select->get());
    }

    public function testCheckboxField()
    {
        $check = new Text($this->repo);
        $check->process('fake_checkbox');
        $this->assertEquals(['blue', 'yellow'], $check->get());
    }

    public function testRadioField()
    {
        $radio = new Text($this->repo);
        $radio->process('fake_radio_button');
        $this->assertEquals('green', $radio->get());
    }

    public function testTrueFalseField()
    {
        $boolean = new Boolean($this->repo);
        $boolean->process('fake_true_false');
        $this->assertTrue($boolean->get());
    }
}
