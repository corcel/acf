<?php

namespace Corcel\Acf\Repositories;

use Corcel\Model\Post;
use Corcel\Acf\Field\Repeater;
use Corcel\Acf\Field\FlexibleContent;
use Corcel\Acf\Models\AcfField;

abstract class Repository
{
    public function __construct()
    {
    }

    abstract public function fetchValue($field);

    abstract public function getConnectionName();

    abstract public function repeaterFetchFields(Repeater $repeater);
    abstract public function flexibleContentFetchFields(FlexibleContent $fc);

    /**
     * @param string $fieldKey
     *
     * @return string|null
     */
    public function fetchFieldType($fieldKey)
    {
        $field = AcfField::where('post_name', $fieldKey)->first();

        if (!$field) {
            return null;
        }

        return $field->type;
    }
}
