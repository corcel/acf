<?php

use Corcel\Model\Post;
use Illuminate\Database\Eloquent\Model as Eloquent;

class AlternatePost extends Post
{
    /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $connection = 'alternate';
}

/**
 * Class CorcelIntegrationTest.
 *
 * @author Junior Grossi <juniorgro@gmail.com>
 */
class CorcelIntegrationTest extends PHPUnit\Framework\TestCase
{
    public function tearDown()
    {
        parent::tearDown();

        Eloquent::getConnectionResolver()->setDefaultConnection('default');
    }

    public function testIfCorcelIntegrationIsWorking()
    {
        $post = Post::find(56);
        $this->assertEquals('admin', $post->acf->fake_user->nickname);
    }

    public function testUsageOfHelperFunctions()
    {
        $post = Post::find(56);
        $this->assertEquals('admin', $post->acf->user('fake_user')->nickname);
    }

    public function testFunctionHelperWithSnakeCaseFieldType()
    {
        $post = Post::find(65);
        $this->assertEquals('10/13/2016', $post->acf->fake_date_picker->format('m/d/Y'));
        $this->assertEquals('10/13/2016', $post->acf->datePicker('fake_date_picker')->format('m/d/Y'));
    }

    public function testPostWithNonDefaultConnectionsLoadsFieldsUsingSameConnection()
    {
        Eloquent::getConnectionResolver()->setDefaultConnection('missing');

        $post = AlternatePost::find(38);
        $this->assertEquals('http://wordpress.corcel.dev/wp-content/uploads/2016/10/maxresdefault-1.jpg', $post->acf->image('fake_image')->url);
    }
}
