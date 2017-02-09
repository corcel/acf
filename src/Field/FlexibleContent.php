<?php

namespace Corcel\Acf\Field;

use Corcel\Acf\FieldFactory;
use Corcel\Acf\FieldInterface;
use Corcel\Post;
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
    protected function fetchPostsMeta($fieldName, Post $post)
    {
        $builder = $this->postMeta->where('post_id', $post->ID);
        $builder->where(function ($query) use ($fieldName) {
            $query->orWhere('meta_key', 'like', "{$fieldName}_%");
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
		$blocks  = $this->fetchValue($fieldName, $this->post);

        foreach ($builder->get() as $meta) {

            $id = $this->retrieveIdFromFieldName($meta->meta_key, $fieldName);

            $name = $this->retrieveFieldName($meta->meta_key, $fieldName, $id);
            $field = FieldFactory::make($meta->meta_key, $this->post->find($meta->post_id));

			if (!array_key_exists($id, $blocks)) {
				continue;
			}

			if (empty($fields[$id])) {
				$fields[$id] = new \stdClass;
				$fields[$id]->type = $blocks[$id];
				$fields[$id]->fields =  new \stdClass;
			}

            $fields[$id]->fields->$name = $field->get();
        }

        return $fields;
    }
}