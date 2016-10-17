<?php

namespace Corcel\Acf;

use Corcel\Acf\Field\Boolean;
use Corcel\Acf\Field\DateTime;
use Corcel\Acf\Field\File;
use Corcel\Acf\Field\Gallery;
use Corcel\Acf\Field\Image;
use Corcel\Acf\Field\PageLink;
use Corcel\Acf\Field\PostObject;
use Corcel\Acf\Field\Select;
use Corcel\Acf\Field\Term;
use Corcel\Acf\Field\Text;
use Corcel\Acf\Field\User;
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

        if ($key === null) { // Field does not exist
            return null;
        }

        $type = $fakeText->fetchFieldType($key);

        switch ($type) {
            case 'text':
            case 'textarea':
            case 'number':
            case 'email':
            case 'url':
            case 'password':
            case 'wysiwyg':
            case 'editor':
            case 'oembed':
            case 'embed':
            case 'color_picker':
            case 'select':
            case 'checkbox':
            case 'radio':
                $field = new Text();
                break;
            case 'image':
            case 'img':
                $field = new Image();
                break;
            case 'file':
                $field = new File();
                break;
            case 'gallery':
                $field = new Gallery();
                break;
            case 'true_false':
            case 'boolean':
                $field = new Boolean();
                break;
            case 'post_object':
            case 'post':
            case 'relationship':
                $field = new PostObject();
                break;
            case 'page_link':
                $field = new PageLink();
                break;
            case 'taxonomy':
            case 'term':
                $field = new Term();
                break;
            case 'user':
                $field = new User();
                break;
            case 'date_picker':
            case 'date_time_picker':
            case 'time_picker':
                $field = new DateTime();
                break;
        }

        $field->process($name, $post);

        return $field;
    }
}
