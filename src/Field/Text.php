<?php

namespace Corcel\Acf\Field;

use Corcel\Acf\FieldInterface;
use Corcel\Model\Post;

/**
 * Class Text.
 *
 * @author Junior Grossi <juniorgro@gmail.com>
 */
class Text extends BasicField implements FieldInterface
{
    /**
     * @var string
     */
    protected $value;

    /**
     * @param string $field
     */
    public function process($field)
    {
        $this->value = $this->fetchValue($field);
    }

    /**
     * @return string
     */
    public function get()
    {
        return $this->value;
    }
}
