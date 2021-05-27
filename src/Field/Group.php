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
class Group extends BasicField implements FieldInterface
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

        $builder = $this->fetchPostsMeta($fieldName, $this->post);

        $fields = $this->fetchFields($fieldName, $builder);

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
    protected function retrieveIdFromFieldName($metaKey, $fieldName)
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
    protected function retrieveFieldName($metaKey, $fieldName)
    {
        $pattern = "{$fieldName}_";

        return str_replace($pattern, '', $metaKey);
    }

    /**
     * @param $fieldName
     * @param Post $post
     *
     * @return mixed
     */
    protected function fetchPostsMeta($fieldName, $post)
    {
        $count = (int) $this->fetchValue($fieldName);
        
        if ($this->postMeta instanceof \Corcel\Model\Meta\TermMeta) {
            $builder = $this->postMeta->where('term_id', $post->term_id);
        } else {
            $builder = $this->postMeta->where('post_id', $post->ID);
        }

        $builder->where('meta_key', 'like', "{$fieldName}_%");
        return $builder;
    }

    /**
     * @param $fieldName
     * @param $builder
     *
     * @return mixed
     */
    protected function fetchFields($fieldName, Builder $builder)
    {
        $fields = [];
        foreach ($builder->get() as $meta) {


            $name = $this->retrieveFieldName($meta->meta_key, $fieldName);

            $post = $this->post->ID != $meta->post_id ? $this->post->find($meta->post_id) : $this->post;
            $field = FieldFactory::make($meta->meta_key, $post);

            if ($field == null) {
                continue;
            }

            $fields[$name] = $field->get();
        }

        return $fields;
    }
}
