<?php

namespace Corcel\Acf;

use Corcel\Acf\Field\Boolean;
use Corcel\Acf\Field\DateTime;
use Corcel\Acf\Field\File;
use Corcel\Acf\Field\Gallery;
use Corcel\Acf\Field\Image;
use Corcel\Acf\Field\PageLink;
use Corcel\Acf\Field\PostObject;
use Corcel\Acf\Field\Repeater;
use Corcel\Acf\Field\FlexibleContent;
use Corcel\Acf\Field\Select;
use Corcel\Acf\Field\Term;
use Corcel\Acf\Field\Text;
use Corcel\Acf\Field\User;
use Corcel\Model;
use Illuminate\Support\Collection;
use Corcel\Acf\Repositories\PostRepository;

/**
 * Class FieldFactory.
 *
 * @author Junior Grossi <juniorgro@gmail.com>
 */
class FieldFactory
{
    private function __construct()
    {
    }

    /**
     * @param string $name
     * @param Post $post
     * @param null|string $type
     *
     * @return FieldInterface|Collection|string
     */
    public static function make($name, Model $post, $type = null)
    {
        $repository = new PostRepository($post);

        if (null === $type) {
            $fakeText = new Text($repository);
            $key = $fakeText->fetchFieldKey($name);

            if ($key === null) { // Field does not exist
                return null;
            }

            $type = $fakeText->fetchFieldType($key);
        }


        switch ($type) {
            case 'text':
            case 'textarea':
            case 'number':
            case 'email':
            case 'url':
            case 'link':
            case 'password':
            case 'wysiwyg':
            case 'editor':
            case 'oembed':
            case 'embed':
            case 'color_picker':
            case 'select':
            case 'checkbox':
            case 'radio':
                $field = new Text($repository);
                break;
            case 'image':
            case 'img':
                $field = new Image($repository);
                break;
            case 'file':
                $field = new File($repository);
                break;
            case 'gallery':
                $field = new Gallery($repository);
                break;
            case 'true_false':
            case 'boolean':
                $field = new Boolean($repository);
                break;
            case 'post_object':
            case 'post':
            case 'relationship':
                $field = new PostObject($repository);
                break;
            case 'page_link':
                $field = new PageLink($repository);
                break;
            case 'taxonomy':
            case 'term':
                $field = new Term($repository);
                break;
            case 'user':
                $field = new User($repository);
                break;
            case 'date_picker':
            case 'date_time_picker':
            case 'time_picker':
                $field = new DateTime($repository);
                break;
            case 'repeater':
                $field = new Repeater($repository);
                break;
            case 'flexible_content':
                $field = new FlexibleContent($repository);
                break;
            default: return null;
        }

        $field->process($name);

        return $field;
    }
}
