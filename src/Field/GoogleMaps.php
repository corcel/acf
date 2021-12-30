<?php

namespace Corcel\Acf\Field;

use Corcel\Acf\FieldInterface;

/**
 * Class GoogleMap.
 *
 * @author Fido van den Bos <fvdbos@crebos.online>
 */
class GoogleMap extends BasicField implements FieldInterface
{
    /**
     * @var Array
     */
    protected $data;

    /**
     * @param string $fieldName
     */
    public function process($fieldName)
    {
        $this->data = $this->fetchValue($fieldName);
    }

    /**
     * @return Array
     */
    public function get()
    {
        return $this->data;
    }
}
