<?php

namespace Corcel\Acf;

use Corcel\Acf\Field\File;
use Corcel\Acf\Field\Gallery;
use Corcel\Acf\Field\Image;
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
     * @param string $fieldType
     * @param Post $post
     * @param string $fieldName
     * @return FieldInterface|Collection|string
     */
    public static function make($fieldType, Post $post, $fieldName)
    {
        switch ($fieldType) {
            case 'text':
            case 'textarea':
            case 'number':
            case 'email':
            case 'url':
            case 'password':
            case 'wysiwyg':
            case 'oembed':
                $field = new Text($post, $fieldName);
                break;
            case 'image':
                $field = new Image($post, $fieldName);
                break;
            case 'file':
                $field = new File($post, $fieldName);
                break;
            case 'gallery':
                $field = new Gallery($post, $fieldName);
                break;
        }

        $field->build();

        return $field->get();
    }
}