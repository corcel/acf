<?php

namespace Corcel\Acf\Field;

use Corcel\Acf\FieldInterface;
use Corcel\Post;

/**
 * Class PostObject.
 *
 * @author Junior Grossi <juniorgro@gmail.com>
 */
class PostObject extends BasicField implements FieldInterface
{
    /**
     * @var Post
     */
    protected $object;

    /**
     * @param string $fieldName
     */
    public function process($fieldName)
    {
        $postId = $this->fetchValue($fieldName);
        $connection = $this->post->getConnectionName();

        if (is_array($postId)) {
            $posts = Post::on($connection)->whereIn('ID', $postId)->get()->sortBy(function ($item) use ($postId) {
                return array_search($item->getKey(), $postId);
            });

            $this->object = $posts->map(function ($post) {
                return $this->downcast($post);
            });
        } else {
            $this->object = $this->downcast(Post::on($connection)->find($postId));
        }
    }

    /**
     * @return mixed
     */
    public function get()
    {
        return $this->object;
    }

    /**
     * Downcast a model to its appropriate subclass
     * @param  Corcel\Post   $post
     * @return Corcel\Post   an appropriate (sub)class
     */
    private function downcast(Post $post)
    {
        $class = Post::$postTypes[$post->post_type] ?? Post::class;
        if ($class === Post::class) {
            return $post;
        }

        return $class::on($this->post->getConnectionName())->find($post->ID);
    }
}
