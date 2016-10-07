<?php

namespace Corcel\Acf\Field;

use Corcel\Acf\FieldInterface;
use Corcel\Post;
use Illuminate\Support\Collection;

/**
 * Class Term
 *
 * @package Corcel\Acf\Field
 * @author Junior Grossi <juniorgro@gmail.com>
 */
class Term extends BasicField implements FieldInterface
{
    /**
     * @var mixed
     */
    protected $items;

    /**
     * @var \Corcel\Term
     */
    protected $term;

    /**
     */
    public function __construct()
    {
        parent::__construct();
        $this->term = new \Corcel\Term();
    }

    /**
     * @param string $fieldName
     * @param Post $post
     */
    public function process($fieldName, Post $post)
    {
        $value = $this->fetchValue($fieldName, $post);
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