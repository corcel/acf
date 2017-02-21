<?php

namespace Corcel\Acf\Field;

use Corcel\Acf\FieldInterface;
use Corcel\TermTaxonomy;

class TermObject extends BasicField implements FieldInterface
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
            $this->object = Term::on($connection)->whereIn('ID', $postId)->get();
        } else {
            $this->object = Term::on($connection)->find($postId);
        }
    }

    /**
     * Return the current instance of the field.
     *
     * @return mixed
     */
    public function get()
    {
        return $this->object;
    }
}
