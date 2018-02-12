<?php

namespace Corcel\Acf;

use Corcel\Acf\Exception\MissingFieldNameException;
use Corcel\Model;
use Corcel\Model\Post;
use Corcel\Model\Option;

class OptionPage extends Model
{
    public $prefix;

    public $page;

    /**
     * @var Collection
     */
    public $options;

    public function __construct($prefix = 'options_')
    {
        parent::__construct();
        $this->prefix = $prefix;
        $this->page = Post::where('post_excerpt', 'optionen')->first(); // FIXME

        $this->loadOptions();
    }

    /**
     * Load all options for this page into $this->options to save queries
     */
    public function loadOptions()
    {
        $builder = (new Option())->where('option_name', 'like', $this->prefix . '%');
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
}
