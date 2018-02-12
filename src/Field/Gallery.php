<?php

namespace Corcel\Acf\Field;

use Corcel\Acf\FieldInterface;
use Corcel\Model\Post;
use Illuminate\Support\Collection;

/**
 * Class Gallery.
 *
 * @author Junior Grossi <juniorgro@gmail.com>
 */
class Gallery extends Image implements FieldInterface
{
    /**
     * @var array
     */
    protected $images = [];

    /**
     * @var Collection
     */
    protected $collection;

    /**
     * @param $field
     */
    public function process($field)
    {
        $value = $this->fetchValue($field);
        $ids = is_array($value) ? $value : @unserialize($value);

        if ($ids) {
            $connection = $this->repository->getConnectionName();
            $attachments = Post::on($connection)->whereIn('ID', $ids)->get();

            $metaDataValues = $this->fetchMultipleMetadataValues($attachments);

            foreach ($attachments as $attachment) {
                if (array_key_exists($attachment->ID, $metaDataValues)) {
                    $image = new Image($this->repository);
                    $image->fillFields($attachment);
                    $image->fillMetadataFields($metaDataValues[$attachment->ID]);
                    $this->images[] = $image;
                }
            }
        }
    }

    /**
     * @return Collection
     */
    public function get()
    {
        if (!$this->collection instanceof Collection) {
            $this->collection = new Collection($this->images);
        }

        return $this->collection;
    }
}
