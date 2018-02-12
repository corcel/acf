<?php

namespace Corcel\Acf\Models;

use Corcel\Model\Post;

class AcfFieldGroup extends Post
{
    /**
     * @var string
     */
    protected $postType = 'acf-field-group';
}
