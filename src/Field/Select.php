<?php

namespace Corcel\Acf\Field;

use Corcel\Acf\FieldInterface;

class Select extends BasicField implements FieldInterface
{
    protected $values;

    public function build()
    {
        $value = $this->getValueByPostAndFieldName();

        if ($options = @unserialize($value) and is_array($options)) {
            $this->values = $options;
        }

        $this->values = $value;
    }

    public function get()
    {
        return $this->values;
    }
}
