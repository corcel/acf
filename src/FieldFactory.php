<?php

namespace Corcel\Acf;

use Corcel\Acf\Field\Boolean;
use Corcel\Acf\Field\File;
use Corcel\Acf\Field\Gallery;
use Corcel\Acf\Field\Image;
use Corcel\Acf\Field\Select;
use Corcel\Acf\Field\Text;
use Corcel\Post;
use Illuminate\Support\Collection;

/**
 * Class FieldFactory
 *
 * @package Corcel\Acf
 * @author Junior Grossi <juniorgro@gmail.com>
 */
class FieldFactory
{
    private function __construct()
    {
        //
    }

    /**
     * @param string $name
     * @param Post $post
     * @return FieldInterface|Collection|string
     */
    public static function make($name, Post $post)
    {
        $fakeText = new Text;
        $key = $fakeText->fetchFieldKey($name, $post);
        $type = $fakeText->fetchFieldType($key);

        switch ($type) {
            case 'text':
            case 'textarea':
            case 'number':
            case 'email':
            case 'url':
            case 'password':
            case 'wysiwyg':
            case 'oembed':
                $field = new Text();
                break;
            case 'image':
                $field = new Image();
                break;
            case 'file':
                $field = new File();
                break;
            case 'gallery':
                $field = new Gallery();
                break;
            case 'select':
            case 'checkbox':
            case 'radio':
                $field = new Select();
                break;
            case 'true_false':
                $field = new Boolean();
                break;
        }

        $field->process($name, $post);

        return $field->get();
    }
}