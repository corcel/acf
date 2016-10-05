<?php

namespace Corcel\Acf\Field;

use Corcel\Acf\FieldInterface;
use Corcel\Post;
use stdClass;

/**
 * Class Image
 *
 * @package Corcel\Acf\Field
 * @author Junior Grossi
 */
class Image extends BasicField implements FieldInterface
{
    /**
     * @var int
     */
    public $width;

    /**
     * @var int
     */
    public $height;

    /**
     * @var string
     */
    public $filename;

    /**
     * @var string
     */
    public $description;

    /**
     * @var string
     */
    public $url;

    /**
     * @var string
     */
    public $mime_type;

    /**
     * @var array
     */
    protected $sizes = [];

    /**
     * @var bool
     */
    protected $loadFromPost = false;

    /**
     * @return void
     */
    public function loadDataFromCurrentPost()
    {
        $this->loadFromPost = true;
    }

    /**
     * @return void
     */
    public function build()
    {
        if (!$this->loadFromPost) {
            $meta = $this->postMeta->where('post_id', $this->post->ID)
                ->where('meta_key', $this->fieldName)
                ->first();
            $attachmentId = $meta->meta_value;
        } else {
            $attachmentId = $this->post->ID;
        }

        $this->fillFields($attachmentId); // attachment_id
    }

    /**
     * @return $this
     */
    public function get()
    {
        return $this;
    }

    /**
     * @param array $data
     */
    protected function fillFields($attachmentId)
    {
        $attachment = $this->post->find(intval($attachmentId));
        $this->mime_type = $attachment->post_mime_type;
        $this->url = $attachment->guid;
        $this->description = $attachment->post_excerpt;

        $meta = $this->postMeta->where('post_id', intval($attachmentId))
            ->where('meta_key', '_wp_attachment_metadata')
            ->first();

        $imageData = unserialize($meta->meta_value);

        $this->filename = basename($imageData['file']);
        $this->width = $imageData['width'];
        $this->height = $imageData['height'];
        $this->sizes = $imageData['sizes'];
    }

    /**
     * @param string $size
     * @return Image
     */
    public function size($size)
    {
        if (isset($this->sizes[$size])) {
            $data = $this->sizes[$size];
            $size = $this->getImageMetaData($data);

            return $size;
        }

        $data = $this->size('thumbnail');

        return $this->getImageMetaData($data);
    }

    /**
     * @param array $data
     * @return stdClass
     */
    private function getImageMetaData(array $data)
    {
        $size = new static($this->post);
        $size->filename = $data['file'];
        $size->width = $data['width'];
        $size->height = $data['height'];
        $size->mime_type = $data['mime-type'];

        return $size;
    }

}