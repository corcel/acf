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

/**
 * Class FieldFactory.
 *
 * @author Junior Grossi <juniorgro@gmail.com>
 */
class FieldFactory
{
    protected static $defaultTypes = [
        'text'             => Text::class,
        'textarea'         => Text::class,
        'number'           => Text::class,
        'email'            => Text::class,
        'url'              => Text::class,
        'password'         => Text::class,
        'wysiwyg'          => Text::class,
        'editor'           => Text::class,
        'oembed'           => Text::class,
        'embed'            => Text::class,
        'color_picker'     => Text::class,
        'select'           => Text::class,
        'checkbox'         => Text::class,
        'radio'            => Text::class,
        'image'            => Image::class,
        'img'              => Image::class,
        'file'             => File::class,
        'gallery'          => Gallery::class,
        'true_false'       => Boolean::class,
        'boolean'          => Boolean::class,
        'post_object'      => PostObject::class,
        'post'             => PostObject::class,
        'relationship'     => PostObject::class,
        'page_link'        => PageLink::class,
        'taxonomy'         => Term::class,
        'term'             => Term::class,
        'user'             => User::class,
        'date_picker'      => DateTime::class,
        'date_time_picker' => DateTime::class,
        'time_picker'      => DateTime::class,
        'repeater'         => Repeater::class,
        'flexible_content' => FlexibleContent::class,
    ];

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
        if (null === $type) {
            $fakeText = new Text($post);
            $key      = $fakeText->fetchFieldKey($name);

            if ($key === null) { // Field does not exist
                return null;
            }

            $type = $fakeText->fetchFieldType($key);
        }

        $customTypes = [];

        if (class_exists('\Config') && \Config::has('corcel.acf')) {
            $customTypes = \Config::get('corcel.acf')['custom_types'];
        }

        $types = array_merge(self::$defaultTypes, $customTypes);

        if (!empty($types[$type])) {
            $field = new $types[$type]($post);
            $field->process($name);

            return $field;
        }

        return null;
    }
}
