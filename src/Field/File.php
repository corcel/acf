<?php

namespace Corcel\Acf\Field;

use Corcel\Acf\FieldInterface;
use Corcel\Post;

/**
 * Class File.
 *
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
     * @param string $field
     * @param Post   $post
     */
    public function process($field, Post $post)
    {
        $value = $this->fetchValue($field, $post);
        $file = $post->find($value);
        $this->fillFields($file);
    }

    /**
     * @return File
     */
    public function get()
    {
        return $this;
    }

    /**
     * @param Post $file
     */
    protected function fillFields(Post $file)
    {
        $this->url = $file->guid;
        $this->mime_type = $file->post_mime_type;
        $this->title = $file->post_title;
        $this->description = $file->post_content;
        $this->caption = $file->post_excerpt;
        $this->filename = basename($this->url);
    }
}
