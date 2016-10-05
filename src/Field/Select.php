<?php

namespace Corcel\Acf\Field;

use Corcel\Acf\FieldInterface;

/**
 * Class Select
 *
 * @package Corcel\Acf\Field
 * @author Junior Grossi <juniorgro@gmail.com>
 */
class Select extends BasicField implements FieldInterface
{
    /**
     * @var mixed
     */
    protected $values;

    /**
     * @return void
     */
    public function build()
    {
        $value = $this->getValueByPostAndFieldName();

        if ($options = @unserialize($value) and is_array($options)) {
            $this->values = $options;
        } else {
            $this->values = $value;
        }
    }

    /**
     * @return mixed
     */
    public function get()
    {
        return $this->values;
    }
}