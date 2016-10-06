<?php

namespace Corcel\Acf\Field;

use Corcel\Acf\FieldInterface;
use Corcel\Post;

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
     * @param string $field
     * @param Post $post
     */
    public function process($field, Post $post)
    {
        $attachmentId = $this->fetchValue($field, $post);
        $this->fillFields($attachmentId, $post); // attachment_id
    }

    /**
     * @return Image
     */
    public function get()
    {
        return $this;
    }

    /**
     * @param int $attachmentId
     * @param Post $post
     */
    protected function fillFields($attachmentId, Post $post)
    {
        $attachment = $post->find(intval($attachmentId));
        $this->mime_type = $attachment->post_mime_type;
        $this->url = $attachment->guid;
        $this->description = $attachment->post_excerpt;
        $this->fillAttachmentMetadata($attachmentId);
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
     * @return Image
     */
    private function getImageMetaData(array $data)
    {
        $size = new static();
        $size->filename = $data['file'];
        $size->width = $data['width'];
        $size->height = $data['height'];
        $size->mime_type = $data['mime-type'];

        return $size;
    }

    /**
     * @param $attachmentId
     */
    protected function fillAttachmentMetadata($attachmentId)
    {
        $meta = $this->postMeta->where('post_id', intval($attachmentId))
            ->where('meta_key', '_wp_attachment_metadata')
            ->first();

        $imageData = unserialize($meta->meta_value);

        $this->filename = basename($imageData['file']);
        $this->width = $imageData['width'];
        $this->height = $imageData['height'];
        $this->sizes = $imageData['sizes'];
    }

}