<?php

namespace Corcel\Acf\Field;

use Corcel\Post;
use Corcel\Acf\FieldInterface;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class Image.
 *
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
     * @var Post
     */
    protected $attachment;

    /**
     * @param string $field
     */
    public function process($field)
    {
        $attachmentId = $this->fetchValue($field);
           
        $connection = $this->post->getConnectionName();
        
        if ($attachment = Post::on($connection)->find(intval($attachmentId))) {
            $this->fillFields($attachment);

            $imageData = $this->fetchMetadataValue($attachment);
        
            $this->fillMetadataFields($imageData);
        }
    }

    /**
     * @return Image
     */
    public function get()
    {
        return $this;
    }

    /**
     * @param Post $attachment
     */
    protected function fillFields(Post $attachment)
    {
        $this->mime_type = $attachment->post_mime_type;
        $this->url = $attachment->guid;
        $this->description = $attachment->post_excerpt;
        $this->attachment = $attachment;
    }

    /**
     * @param string $size
     *
     * @return Image
     */
    public function size($size)
    {

        if (isset($this->sizes[$size])) {
            return $this->fillThumbnailFields($this->sizes[$size]);
        }

        return $this->fillThumbnailFields($this->sizes['thumbnail']);
    }

    /**
     * @param array $data
     *
     * @return Image
     */
    protected function fillThumbnailFields(array $data)
    {
        $size = new static($this->post);
        $size->filename = $data['file'];
        $size->width = $data['width'];
        $size->height = $data['height'];
        $size->mime_type = $data['mime-type'];

        $imgDir = substr($this->url, 0, strrpos($this->url, '/'));
        $size->url = $imgDir.'/'.$data['file'];


        return $size;
    }

    /**
     * @param Post $attachment
     *
     * @return array
     */
    protected function fetchMetadataValue(Post $attachment)
    {
        $meta = $this->postMeta->where('post_id', $attachment->ID)
            ->where('meta_key', '_wp_attachment_metadata')
            ->first();

        return unserialize($meta->meta_value);
    }

    /**
     * @param Collection $attachments
     *
     * @return Collection|array
     */
    protected function fetchMultipleMetadataValues(Collection $attachments)
    {
        $ids = $attachments->pluck('ID')->toArray();
        $metadataValues = [];

        $metaRows = $this->postMeta->whereIn('post_id', $ids)
            ->where('meta_key', '_wp_attachment_metadata')
            ->get();

        foreach ($metaRows as $meta) {
            $metadataValues[$meta->post_id] = unserialize($meta->meta_value);
        }

        return $metadataValues;
    }

    /**
     * @param array $imageData
     */
    protected function fillMetadataFields(array $imageData)
    {
        $this->filename = basename($imageData['file']);
        $this->width = $imageData['width'];
        $this->height = $imageData['height'];
        $this->sizes = $imageData['sizes'];
    }

    /**
     * @param string|array      comma seperated string or an array
     *
     * @return string|array     string if only one meta key was received as input, otherwise an array with the values of all meta keys received as input
     */
    public function fetchCustomMetadataValues($metaKeys)
    {
        if (!is_array ($metaKeys)) {
            $metaKeys = explode(',', $metaKeys);
        }

        $customMetaValues = [];

        foreach ($metaKeys as $metaKey) {
            $customMetaValues[] = $this->attachment->meta->{trim($metaKey)};
        }

        if (count ($customMetaValues) === 1) {
            return $customMetaValues[0];
        }

        return $customMetaValues;
    }
}
