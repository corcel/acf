<?php

namespace Corcel\Acf;

use Corcel\Acf\Exception\MissingFieldNameException;
use Corcel\Model;
use Corcel\Model\Post;
use Corcel\Model\Option;
use Corcel\Acf\Models\AcfFieldGroup;

class OptionPage extends Model
{
    /**
     * @var string
     */
    public $prefix;

    /**
     * @var AcfFieldGroup
     */
    public $page;

    /**
     * @var Collection
     */
    public $options;

    /**
     * TODO would be nice if only one of the arguments would be needed, but i
     * dont see any connection between the page object in wp_posts and the
     * prefix used in wp_options
     *
     * @param string $groupName acf field group name
     * @param string $prefix prefix in wp_options
     */
    public function __construct($groupName, $prefix = 'options_')
    {
        parent::__construct();
        $this->prefix = $prefix;
        $this->page = AcfFieldGroup::where('post_title', $groupName)->first();

        $this->loadOptions();
    }

    /**
     * Load all options for this page into $this->options to save queries
     */
    public function loadOptions()
    {
        $builder = Option::where('option_name', 'like', $this->prefix . '%');
        $this->options = $builder->get()->keyBy('option_name');
    }

    /**
     * Make possible to call $optionPage->fieldType('fieldName').
     *
     * @param string$name
     * @param array $arguments
     *
     * @return mixed
     *
     * @throws MissingFieldNameException
     */
    public function __call($name, $arguments)
    {
        if (!isset($arguments[0])) {
            throw new MissingFieldNameException('The field name is missing');
        }

        $field = FieldFactory::makeOptionField($arguments[0], $this, snake_case($name));

        return $field ? $field->get() : null;
    }

    public function getAcfField($fieldName)
    {
        return $this->page->children->where('post_excerpt', $fieldName)->first();
    }
}
