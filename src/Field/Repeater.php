<?php

namespace Corcel\Acf\Field;

use Corcel\Acf\FieldFactory;
use Corcel\Acf\FieldInterface;
use Corcel\Model\Post;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

/**
 * Class Repeater.
 *
 * @author Junior Grossi <juniorgro@gmail.com>
 */
class Repeater extends BasicField implements FieldInterface
{
    /**
     * @var Collection
     */
    protected $fields;

    /**
     * @param string $fieldName
     */
    public function process($fieldName)
    {
        $this->name = $fieldName;

        $fields = $this->repository->repeaterFetchFields($this);

        $this->fields = new Collection($fields);
    }

    /**
     * @return Collection
     */
    public function get()
    {
        return $this->fields;
    }

    /**
     * @param string $metaKey
     * @param string $fieldName
     *
     * @return int
     */
    public function retrieveIdFromFieldName($metaKey, $fieldName)
    {
        return (int) str_replace("{$fieldName}_", '', $metaKey);
    }

    /**
     * @param string $metaKey
     * @param string $fieldName
     * @param int    $id
     *
     * @return string
     */
    public function retrieveFieldName($metaKey, $fieldName, $id)
    {
        $pattern = "{$fieldName}_{$id}_";

        return str_replace($pattern, '', $metaKey);
    }
}
