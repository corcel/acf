<?php

namespace Corcel\Acf\Field;

use Corcel\Acf\FieldInterface;
use Corcel\Acf\Models\AcfField;
use Corcel\Acf\FieldFactory;

/**
 * Class CloneField. Only supports cloning __one__ field, not groups. If you
 * need to clone a group of fields, consider putting all of them into a repeater
 * with min = 1 & max = 1 and clone that repeater field
 */
class CloneField extends BasicField implements FieldInterface
{
    /**
     * Holds the cloned field
     * 
     * @var FieldInterface
     */
    protected $originalField;

    /**
     * @param string $field
     */
    public function process(string $field)
    {
        parent::process($field);

        $fieldKey = $this->repository->getFieldKey($field);

        $search = substr($fieldKey, strrpos($fieldKey, 'field_'));

        $acfField = AcfField::where('post_name', $search)->first();

        if (!$acfField) {
            // dd($this->fieldName);
            trigger_error('Could not find acf field for ' . $search . ' / ' . $fieldKey . ' / ' . $field);
        }

        $this->originalField = FieldFactory::makeField($field, $this->repository, $acfField->type);
    }

    /**
     * @return string
     */
    public function get()
    {
        return $this->originalField->get();
    }
}
