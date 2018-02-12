<?php

namespace Corcel\Acf\Repositories;

use Corcel\Model\Post;

class Repository
{
    public function __construct()
    {
    }

    /**
     * @param string $fieldKey
     *
     * @return string|null
     */
    public function fetchFieldType($fieldKey)
    {
        $post = Post::on($this->getConnectionName())
                   ->orWhere(function ($query) use ($fieldKey) {
                       $query->where('post_name', $fieldKey);
                       $query->where('post_type', 'acf-field');
                   })->first();

        if ($post) {
            $fieldData = unserialize($post->post_content);
            $this->type = isset($fieldData['type']) ? $fieldData['type'] : 'text';

            return $this->type;
        }

        return null;
    }
}
