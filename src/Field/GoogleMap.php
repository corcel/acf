<?php

namespace Corcel\Acf\Field;

use Corcel\Acf\FieldInterface;

/**
 * Class GoogleMap.
 *
 * @author Naeem Ullah <naeem_ins@hotmail.com>
 */
class GoogleMap extends BasicField implements FieldInterface
{
    /**
     * @var Array
     */
    protected $cords;

    /**
     * @param string $fieldName
     */
    public function process($fieldName)
    {
        $this->cords = $this->fetchValue($fieldName);
    }

    /**
     * @return Array
     */
    public function get()
    {
        return $this->cords;
    }
}
