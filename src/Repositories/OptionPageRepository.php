<?php

namespace Corcel\Acf\Repositories;

use Corcel\Model\Option;
use Corcel\Acf\Field\Repeater;
use Corcel\Acf\Field\FlexibleContent;
use Corcel\Acf\FieldFactory;
use Corcel\Acf\Field\Text;
use Corcel\Model\Post;
use Corcel\Acf\OptionPage;
use Corcel\Acf\Models\AcfField;

class OptionPageRepository extends Repository
{
    /**
     * OptionPage
     */
    protected $optionPage;

    public function __construct(OptionPage $optionPage)
    {
        parent::__construct();
        $this->optionPage = $optionPage;
    }

    protected function getPrefixedField($field)
    {
        return $this->optionPage->prefix . $field;
    }

    /**
     * Get the value of a field from wp_options.
     *
     * @return string
     */
    public function fetchValue($field)
    {
        $prefixed = $this->getPrefixedField($field);
        return $this->optionPage->options->get($prefixed)->option_value;
    }

    public function getConnectionName()
    {
        return $this->optionPage->page->getConnectionName();
    }

    public function repeaterFetchFields(Repeater $repeater)
    {
        $fieldName = $repeater->name;
        $prefixedField = $this->getPrefixedField($fieldName);

        $count = (int) $this->fetchValue($fieldName);

        $options = $this->optionPage->options->filter(function($option) use ($prefixedField) {
            return preg_match("/^${prefixedField}_\d+_/", $option->option_name);
        });

        $types = [];
        $repeaterId = $this->optionPage->page->children->where('post_excerpt', $fieldName)->first()->ID;
        
        $acfFields = AcfField::where('post_parent', $repeaterId)->get();
        foreach ($acfFields as $acfField) {
            $types[$acfField->post_excerpt] = $acfField->type;
        }

        $fields = [];
        foreach ($options as $option) {

            // option_name is sth like "options_quicklinks_1_link"

            $id = intval(str_replace("${prefixedField}_", '', $option->option_name)); // 1
            $name = str_replace("${prefixedField}_${id}_", '', $option->option_name); // "link"
            $type = $types[$name]; // "page_link"
            $full = sprintf('%s_%d_%s', $fieldName, $id, $name); // "quicklinks_1_link"

            $field = FieldFactory::makeOptionField($full, $this->optionPage, $type);

            if ($field == null) {
                continue;
            }

            $fields[$id][$name] = $field->get();
        }

        return $fields;
    }

    public function flexibleContentFetchFields(FlexibleContent $fc)
    {
        trigger_error('not implemented yet'); // FIXME
    }
}
