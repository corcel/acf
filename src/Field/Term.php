<?php

namespace Corcel\Acf\Field;

use Corcel\Acf\FieldInterface;
use Corcel\Model\Post;
use Illuminate\Support\Collection;

/**
 * Class Term.
 *
 * @author Junior Grossi <juniorgro@gmail.com>
 */
class Term extends BasicField implements FieldInterface
{
    /**
     * @var mixed
     */
    protected $items;

    /**
     * @var \Corcel\Model\Term
     */
    protected $term;

    /**
     * @param Post $post
     */
    public function __construct($repository)
    {
        parent::__construct($repository);
        $this->term = new \Corcel\Model\Term();
        $this->term->setConnection($repository->getConnectionName());
    }

    /**
     * @param string $fieldName
     */
    public function process($fieldName)
    {
        $value = $this->fetchValue($fieldName);

        if ($unserialized = @unserialize($value)) {
            $value = $unserialized;
        }

        if (is_array($value)) {
            $this->items = $this->term->whereIn('term_id', $value)->get(); // ids
        } else {
            $this->items = $this->term->find(intval($value));
        }
    }

    /**
     * @return Term|Collection
     */
    public function get()
    {
        return $this->items; // Collection or Term object
    }
}
