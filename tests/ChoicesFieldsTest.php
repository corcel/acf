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
     * @var Post
     */
    protected $post;

    public function setUp()
    {
        parent::setUp();
        $post = $this->createAcfPost();
        $this->repo = new PostRepository($post);
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
