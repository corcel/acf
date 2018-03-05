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

    /**
     * Children of an acf field are acf fields themselves. Override parent
     * method to get the correct class type
     */
    public function children()
    {
        return $this->hasMany(AcfField::class, 'post_parent');
    }
}
