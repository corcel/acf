<?php

namespace Corcel\Acf\Models;

use Corcel\Model\Post;

class AcfField extends Post
{
    /**
     * @var string
     */
    protected $postType = 'acf-field';

    public function getContentAttribute()
    {
        return unserialize($this->post_content);
    }

    public function getTypeAttribute()
    {
        $fieldData = $this->content;
        return isset($fieldData['type']) ? $fieldData['type'] : 'text';
    }
}
