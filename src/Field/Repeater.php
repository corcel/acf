<?php

namespace Corcel\Acf\Field;

use Corcel\Acf\FieldFactory;
use Corcel\Acf\FieldInterface;
use Corcel\Post;
use Illuminate\Support\Collection;

/**
 * Class Repeater
 *
 * @package Corcel\Acf\Field
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
     * @param Post $post
     */
    public function process($fieldName, Post $post)
    {
        $this->name = $fieldName;
        $this->post = $post;

        $count = $this->fetchValue($fieldName, $post);
        $builder = $this->postMeta->where('post_id', $post->ID);

        $builder->where(function($query) use ($count) {
            foreach (range(0, $count - 1) as $i) {
                $pattern = "fake_repeater_{$i}_%";
                $query = $query->orWhere('meta_key', 'like', $pattern);
            }
        });

        $fields = [];
        foreach ($builder->get() as $meta) {
            $id = $this->retrieveIdFromFieldName($meta->meta_key);
            $name = $this->retrieveFieldName($meta->meta_key, $fieldName, $id);
            $field = FieldFactory::make($meta->meta_key, $this->post->find($meta->post_id));
            $fields[$id][$name] = $field->get();
        }

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
     * @return int
     */
    protected function retrieveIdFromFieldName($metaKey)
    {
        $pattern = '/^[a-z_]+_(\d)/';
        preg_match($pattern, $metaKey, $matches);
        if (isset($matches[1])) {
            return $matches[1];
        }
    }

    /**
     * @param string $metaKey
     * @param string $fieldName
     * @param int $id
     * @return string
     */
    protected function retrieveFieldName($metaKey, $fieldName, $id)
    {
        $pattern = "{$fieldName}_${id}_";

        return str_replace($pattern, '', $metaKey);
    }
}