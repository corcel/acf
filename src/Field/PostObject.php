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

        if (is_array($postId)) {
            $this->object = $this->post->whereIn('ID', $postId)->get();
        } else {
            $this->object = $this->post->find($postId);
        }
    }

    /**
     * @return mixed
     */
    public function get()
    {
        return $this->object;
    }
}
