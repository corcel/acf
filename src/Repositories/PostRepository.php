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
use Corcel\Acf\Field\Text;
use Corcel\Acf\Models\AcfField;

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

        // this is the acf field entry in wp_posts for the flexible content
        // field, which holds the possible layouts for this type of flexible
        // content
        $acfField = $this->getAcfField($fieldName);

        // all available layout blocks e.g. ["5898b06bd55ed" => "infobox"]
        $availableLayouts = collect($acfField->content['layouts'])->pluck('name', 'key');

        // the fields in the layout blocks are all children of the root fc field
        $layouts = $acfField->children
            // They are associated with a layout block via parent_layout
            // ("5898b06bd55ed"). So lets group them by their layout block
            ->groupBy('content.parent_layout')
            // and now change the keys from the internal id ("5898b06bd55ed") to
            // the internal block name ("infobox")
            ->keyBy(function ($item, $key) use ($availableLayouts) {
                return $availableLayouts->get($key);
            });

        // now $layouts is a collection "infobox" => Collection(blockField1,
        // blockField2, ...), "layoutblock2" => Collection(blockField)

        // get the actual layout blocks
        $blocks  = $this->fetchValue($fieldName, $this->post);

        $fields = [];

        // loop through the blocks and fetch the respective values
        foreach ($blocks as $i => $block) {
            $fieldData = new \stdClass;
            $fieldData->type = $block;
            $fieldData->fields = new \stdClass;

            $layoutFields = $layouts->get($block);

            // loop through the layout fields and instantiate them via
            // FieldFactory
            foreach ($layoutFields as $layoutField) {
                $layoutFieldName = $layoutField->post_excerpt;
                $internalName = sprintf('%s_%d_%s', $fieldName, $i, $layoutFieldName);

                // fields like "info" have an empty post_excerpt => skip them
                if (empty($layoutFieldName)) {
                    continue;
                }

                $field = FieldFactory::makeField($internalName, $this, $layoutField->type);

                if (!$field) {
                    trigger_error('Could not create field for ' . $internalName . ' with type ' . $layoutField->type, E_USER_NOTICE);
                    continue;
                }

                $fieldData->fields->$layoutFieldName = $field->get();
            }

            $fields[] = $fieldData;
        }

        ksort($fields);

        return $fields;
    }

    public function getFieldType($name)
    {
        $key = $this->fetchFieldKey($name);

        if ($key === null) { // Field does not exist
            return null;
        }

        return $this->fetchFieldType($key);
    }

    /**
     * Convert a field name to its internal acf field name, e.g.
     * "my_image" => "field_588e076c2de43"
     *
     * @return string
     */
    public function getAcfFieldName(string $fieldName)
    {
        return $this->post->getMeta('_' . $fieldName);
    }
}
