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
    protected function retrieveFieldName($metaKey, $fieldName, $id)
    {
        $pattern = "{$fieldName}_{$id}_";

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
        
        $builder = $this->postMeta->where($this->getKeyName(), $this->getPostId());

        $builder->where(function ($query) use ($count, $fieldName) {
            foreach (range(0, $count - 1) as $i) {
                $query->orWhere('meta_key', 'like', "{$fieldName}_{$i}_%");
            }
        });

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
            $id = $this->retrieveIdFromFieldName($meta->meta_key, $fieldName);
            $name = $this->retrieveFieldName($meta->meta_key, $fieldName, $id);

            $metaPostId = $meta->{$this->getKeyName()};

            $post = $this->getPostId() != $metaPostId ? $this->post->find($metaPostId) : $this->post;
            $field = FieldFactory::make($meta->meta_key, $post);

            if ($field == null) {
                continue;
            }

            $fields[$id][$name] = $field->get();
        }

        return $fields;
    }
}
