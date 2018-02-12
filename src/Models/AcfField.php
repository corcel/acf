<?php

namespace Corcel\Acf\Models;

use Corcel\Model\Post;

class AcfField extends Post
{
    /**
     * @var string
     */
    protected $postType = 'acf-field';

    public function getTypeAttribute()
    {
        $fieldData = unserialize($this->post_content);
        return isset($fieldData['type']) ? $fieldData['type'] : 'text';
    }
}
