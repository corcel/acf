<?php

namespace Corcel\Acf\Field;

use Corcel\Acf\FieldInterface;

/**
 * Class File
 *
 * @package Corcel\Acf\Field
 * @author Junior Grossi <juniorgro@gmail.com>
 */
class File extends BasicField implements FieldInterface
{
    /**
     * @var string
     */
    public $title;

    /**
     * @var string
     */
    public $filename;

    /**
     * @var string
     */
    public $url;

    /**
     * @var string
     */
    public $description;

    /**
     * @var string
     */
    public $caption;

    /**
     * @var string
     */
    public $mime_type;

    /**
     * @return void
     */
    public function build()
    {
        $meta = $this->postMeta
            ->where('post_id', $this->post->ID)
            ->where('meta_key', $this->fieldName)
            ->first();

        $post = $this->post->find($meta->meta_value);

        $this->url = $post->guid;
        $this->mime_type = $post->post_mime_type;
        $this->title = $post->post_title;
        $this->description = $post->post_content;
        $this->caption = $post->post_excerpt;
        $this->filename = basename($this->url);
    }

    /**
     * @return $this
     */
    public function get()
    {
        return $this;
    }
}