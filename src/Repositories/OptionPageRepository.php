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
use Corcel\Acf\Exception\MissingFieldException;

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
     * Convert a field name to its internal acf field name, e.g.
     * "modules_1_text" => "field_588e076c2de43"
     *
     * @return string
     */
    public function getAcfFieldName(string $fieldName)
    {
        return $this->optionPage->options->get('_' . $fieldName);
    }

    /**
     * Get the value of a field from wp_options.
     *
     * @return string
     */
    public function fetchValue($field)
    {
        $prefixed = $this->getPrefixedField($field);
        $option = $this->optionPage->options->get($prefixed);
        if (!$option) {
            throw new MissingFieldException('Field does not exist in option page: ' . $field);
        }
        return $option->option_value;
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

        $options = $this->optionPage->options->filter(function ($option) use ($prefixedField) {
            return preg_match("/^${prefixedField}_\d+_/", $option->option_name);
        });

        $types = [];
        $repeater = $this->optionPage->getAcfField($fieldName);

        $acfFields = AcfField::where('post_parent', $repeater->ID)->get();
        foreach ($acfFields as $acfField) {
            $types[$acfField->post_excerpt] = $acfField->type;
        }

        $fields = [];
        foreach ($options as $option) {

            // option_name is sth like "options_quicklinks_1_link"

            $id = $this->retrieveIdFromFieldName($option->option_name, $prefixedField); // 1
            $name = $this->retrieveFieldName($option->option_name, $prefixedField, $id); // "link"
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
        $fieldName = $fc->name;
        $prefixedField = $this->getPrefixedField($fieldName);

        $fields = [];
        $blocks  = unserialize($this->fetchValue($fieldName));

        $options = $this->optionPage->options->filter(function ($option) use ($prefixedField) {
            return preg_match("/^${prefixedField}_/", $option->option_name);
        });

        $types = [];
        $repeater = $this->optionPage->getAcfField($fieldName);

        $acfFields = AcfField::where('post_parent', $repeater->ID)->get();
        foreach ($acfFields as $acfField) {
            $types[$acfField->post_excerpt] = $acfField->type;
        }

        foreach ($options as $option) {
            $id = $this->retrieveIdFromFieldName($option->option_name, $prefixedField); // 1
            $name = $this->retrieveFieldName($option->option_name, $prefixedField, $id); // "link"
            $type = $types[$name]; // "page_link"
            $full = sprintf('%s_%d_%s', $fieldName, $id, $name); // "quicklinks_1_link"

            $field = FieldFactory::makeOptionField($full, $this->optionPage, $type);

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
