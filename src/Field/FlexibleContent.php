<?php

namespace Corcel\Acf\Field;

use Corcel\Acf\FieldFactory;
use Corcel\Acf\FieldInterface;
use Corcel\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

/**
 * Class Flexible Content.
 *
 * @author Marco Boom <info@marcoboom.nl>
 */
class FlexibleContent extends BasicField implements FieldInterface
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

        $fields = $this->repository->flexibleContentFetchFields($this);

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
