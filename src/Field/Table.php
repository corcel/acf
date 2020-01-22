<?php

namespace Corcel\Acf\Field;

use Corcel\Acf\FieldInterface;

/**
 * Class Text.
 *
 * @author Junior Grossi <juniorgro@gmail.com>
 */
class Table extends BasicField implements FieldInterface
{
    /**
     * @var string
     */
    protected $value;

    /**
     * @param string $field
     */
    public function process($field)
    {
        $this->value = $this->fetchValue($field);
    }

    /**
     * @return string
     */
    public function get()
    {
        return $this->format_value($this->value);
    }

    protected function format_value($value)
    {
        $a = json_decode($value, true);

        $value = false;

        // IF BODY DATA

        if (count($a['b']) > 0) {
            // IF HEADER DATA

            if ($a['p']['o']['uh'] === 1) {
                $value['header'] = $a['h'];
            } else {
                $value['header'] = false;
            }

            // BODY

            $value['body'] = $a['b'];

            // IF SINGLE EMPTY CELL, THEN DO NOT RETURN TABLE DATA

            if (
                count($a['b']) === 1
                and count($a['b'][0]) === 1
                and trim($a['b'][0][0]['c']) === ''
            ) {
                $value = false;
            }
        }

        return $value;
    }
}
