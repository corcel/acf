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
}
