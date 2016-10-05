<?php

namespace Corcel\Acf\Field;

use Corcel\Acf\FieldInterface;
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
     * @return void
     */
    public function build()
    {
        $meta = $this->postMeta->where('post_id', $this->post->ID)
            ->where('meta_key', $this->fieldName)
            ->first();

        $attachment = $this->post->find(intval($meta->meta_value));
        $this->mime_type = $attachment->post_mime_type;
        $this->url = $attachment->guid;
        $this->description = $attachment->post_excerpt;

        $meta = $this->postMeta->where('post_id', intval($meta->meta_value))
            ->where('meta_key', '_wp_attachment_metadata')
            ->first();

        $imageData = unserialize($meta->meta_value);
        $this->fillFields($imageData);
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
    protected function fillFields(array $data)
    {
        $this->filename = basename($data['file']);
        $this->width = $data['width'];
        $this->height = $data['height'];
        $this->sizes = $data['sizes'];
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
        $size = new static($this->post, $this->fieldName);
        $size->filename = $data['file'];
        $size->width = $data['width'];
        $size->height = $data['height'];
        $size->mime_type = $data['mime-type'];

        return $size;
    }

}