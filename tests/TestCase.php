<?php

namespace Corcel\Acf\Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Corcel\Model\Attachment;
use Corcel\Model\Post;
use Corcel\Model\Meta\PostMeta;
use Corcel\Acf\Models\AcfField;

class TestCase extends OrchestraTestCase
{
    protected function setUp()
    {
        parent::setUp();

        // FIXME we load migrations and factories from corcel, but is that
        // really better than copying it over here?
        $this->pathPrefix = '/../../../jgrossi/corcel';

        // --realpath can be used once we upgrade to 5.6
        $this->loadMigrationsFrom([
            '--database' => 'foo',
            '--path' => $this->pathPrefix.'/tests/database/migrations',
        ]);

        $this->loadMigrationsFrom([
            '--database' => 'wp',
            '--path' => $this->pathPrefix.'/tests/database/migrations',
        ]);

        $this->withFactories(base_path() . '/' .$this->pathPrefix.'/tests/database/factories');
        $this->withFactories(__DIR__ . '/database/factories');
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $this->configureDatabaseConfig($app);
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    private function configureDatabaseConfig($app)
    {
        // dd(base_path());
        $app['config']->set('database.connections.wp', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => 'wp_',
        ]);

        $app['config']->set('database.connections.foo', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => 'foo_',
        ]);

        $app['config']->set('database.default', 'wp');
    }

    /**
     * Create a sample post with acf fields
     */
    protected function createAcfPost()
    {
        $post = factory(Post::class)->create();
        $this->createAcfField($post, 'fake_text', 'Proin eget tortor risus');
        $this->createAcfField($post, 'fake_textarea', 'Praesent sapien massa, convallis a pellentesque nec, egestas non nisi.', 'textarea');
        $this->createAcfField($post, 'fake_number', '1984', 'number');
        $this->createAcfField($post, 'fake_email', 'junior@corcel.org', 'email');
        $this->createAcfField($post, 'fake_url', 'https://corcel.org', 'url');
        $this->createAcfField($post, 'fake_password', '123change', 'password');

        $attachment = factory(Attachment::class)->create();
        $attachment->meta()->save(factory(PostMeta::class)->states('attachment_metadata')->create());
        $this->createAcfField($post, 'test-image', $attachment->ID, 'image');

        return $post;
    }

    /**
     * Create a acf field for a post with a field name and a value
     */
    protected function createAcfField(Post $post, $fieldName, $value, $type = 'text', $override = [])
    {
        $internal = 'field_' . str_random(13);

        $postmeta1 = factory(PostMeta::class)->create([
            'post_id' => $post->ID,
            'meta_key' => $fieldName,
            'meta_value' => $value,
        ]);
        $postmeta2 = factory(PostMeta::class)->create([
            'post_id' => $post->ID,
            'meta_key' => '_' . $fieldName,
            'meta_value' => $internal,
        ]);

        $override['post_name'] = $internal;
        $override['post_excerpt'] = $type;

        $acffield = factory(AcfField::class)->create($override);
        return $acffield;
    }
}
