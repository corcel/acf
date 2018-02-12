<?php

namespace Corcel\Acf\Repositories;

use Corcel\Model\Post;
use Corcel\Model;
use Corcel\Model\Meta\PostMeta;
use Corcel\Model\Term;
use Corcel\Model\Meta\TermMeta;
use Corcel\Model\User;
use Corcel\Model\Meta\UserMeta;
use Corcel\Acf\FieldFactory;
use Corcel\Acf\Field\Repeater;
use Corcel\Acf\Field\FlexibleContent;

class PostRepository extends Repository
{
    /**
     * @var Model
     */
    protected $post;

    /**
     * @var PostMeta
     */
    protected $postMeta;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $key;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var mixed
     */
    protected $value;

    /**
     * @var string
     */
    protected $connection;

    public function __construct(Model $post)
    {
        parent::__construct();
        $this->setPost($post);
    }

    /**
     * Sets the post instance and its according postMeta
     */
    public function setPost(Model $post)
    {
        $this->post = $post;

        if ($post instanceof Post) {
            $this->postMeta = new PostMeta();
        } elseif ($post instanceof Term) {
            $this->postMeta = new TermMeta();
        } elseif ($post instanceof User) {
            $this->postMeta = new UserMeta();
        }

        $this->postMeta->setConnection($this->getConnectionName());
    }

    /**
     * @return Model
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * Get the value of a field according it's post ID.
     *
     * @param string $field
     *
     * @return array|string
     */
    public function fetchValue($field)
    {
        $postMeta = $this->postMeta->where(
           $this->getKeyName(), $this->post->getKey()
        )->where('meta_key', $field)->first();

        if (isset($postMeta->meta_value) and ! is_null($postMeta->meta_value)) {
            $value = $postMeta->meta_value;
            if ($array = @unserialize($value) and is_array($array)) {
                $this->value = $array;

                return $array;
            } else {
                $this->value = $value;

                return $value;
            }
        }
    }

    /**
     * @param string $fieldName
     *
     * @return string
     */
    public function fetchFieldKey($fieldName)
    {
        $this->name = $fieldName;

        $postMeta = $this->postMeta->where($this->getKeyName(), $this->post->getKey())
            ->where('meta_key', '_' . $fieldName)
            ->first();

        if (!$postMeta) {
            return null;
        }

        $this->key = $postMeta->meta_value;

        return $this->key;
    }

    /**
     * Get the name of the key for the field.
     *
     * @return string
     */
    public function getKeyName()
    {
        if ($this->post instanceof Post) {
            return 'post_id';
        } elseif ($this->post instanceof Term) {
            return 'term_id';
        } elseif ($this->post instanceof User) {
            return 'user_id';
        }
    }

    public function getConnectionName()
    {
        return $this->post->getConnectionName();
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

    /**
     * @param $fieldName
     * @param $builder
     *
     * @return mixed
     */
    public function repeaterFetchFields(Repeater $repeater)
    {
        $fieldName = $repeater->name;

        $count = (int) $this->fetchValue($fieldName);
        
        if ($this->postMeta instanceof \Corcel\TermMeta) {
            $builder = $this->postMeta->where('term_id', $this->post->term_id);
        } else {
            $builder = $this->postMeta->where('post_id', $this->post->ID);
        }

        $builder->where(function ($query) use ($count, $fieldName) {
            foreach (range(0, $count - 1) as $i) {
                $query->orWhere('meta_key', 'like', "{$fieldName}_{$i}_%");
            }
        });


        $fields = [];
        foreach ($builder->get() as $meta) {
            $id = $this->retrieveIdFromFieldName($meta->meta_key, $fieldName);
            $name = $this->retrieveFieldName($meta->meta_key, $fieldName, $id);

            $post = $this->post->ID != $meta->post_id ? $this->post->find($meta->post_id) : $this->post;
            $field = FieldFactory::make($meta->meta_key, $post);

            if ($field == null) {
                continue;
            }

            $fields[$id][$name] = $field->get();
        }

        return $fields;
    }

    /**
     * @param $fieldName
     * @param $builder
     *
     * @return mixed
     */
    public function flexibleContentFetchFields(FlexibleContent $fc)
    {
        $fieldName = $fc->name;

        $fields = [];
        $blocks  = $this->fetchValue($fieldName, $this->post);

        $builder = $this->postMeta->where($this->getKeyName(), $this->post->getKey());
        $builder->where('meta_key', 'like', "{$fieldName}_%");

        foreach ($builder->get() as $meta) {
            $id = $this->retrieveIdFromFieldName($meta->meta_key, $fieldName);

            $name = $this->retrieveFieldName($meta->meta_key, $fieldName, $id);

            $post = $this->post->ID != $meta->post_id ? $this->post->find($meta->post_id) : $this->post;
            $field = FieldFactory::make($meta->meta_key, $post);

            if ($field === null || !array_key_exists($id, $blocks)) {
                continue;
            }

            if (empty($fields[$id])) {
                $fields[$id] = new \stdClass;
                $fields[$id]->type = $blocks[$id];
                $fields[$id]->fields =  new \stdClass;
            }

            $fields[$id]->fields->$name = $field->get();
        }

        ksort($fields);

        return $fields;
    }
}
