<?php

namespace Corcel\Acf\Field;

use Carbon\Carbon;
use Corcel\Acf\FieldInterface;
use Corcel\Post;

/**
 * Class DateTime
 *
 * @package Corcel\Acf\Field
 * @author Junior Grossi <juniorgro@gmail.com>
 */
class DateTime extends BasicField implements FieldInterface
{
    /**
     * @var Carbon
     */
    protected $date;

    /**
     * @param string $fieldName
     * @param Post $post
     */
    public function process($fieldName, Post $post)
    {
        $dateString = $this->fetchValue($fieldName, $post);
        $format = $this->getDateFormatFromString($dateString);
        $this->date = Carbon::createFromFormat($format, $dateString);
    }

    /**
     * @return Carbon
     */
    public function get()
    {
        return $this->date;
    }

    /**
     * @param string $dateString
     * @return string
     */
    protected function getDateFormatFromString($dateString)
    {
        if (preg_match('/^\d{8}$/', $dateString)) { // 20161013 => date only
            return 'Ymd';
        } else if (preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', $dateString)) { // 2016-10-19 08:06:05
            return 'Y-m-d H:i:s';
        } else if (preg_match('/^\d{2}:\d{2}:\d{2}$/')) { // 17:30:00
            return 'H:i:s';
        }
    }
}