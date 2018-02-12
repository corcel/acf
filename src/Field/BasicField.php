<?php

namespace Corcel\Acf\Field;

use Corcel\Model\Post;
use Corcel\Model;
use Corcel\Model\Meta\PostMeta;
use Corcel\Model\Term;
use Corcel\Model\Meta\TermMeta;
use Corcel\Model\User;
use Corcel\Model\Meta\UserMeta;
use Corcel\Acf\Repositories\Repository;

/**
 * Class BasicField.
 *
 * @author Junior Grossi <juniorgro@gmail.com>
 */
abstract class BasicField
{
    /**
     * @var Repository
     */
    protected $repository;

    /**
     * Constructor method.
     *
     * @param Repository $repository
     */
    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @deprecated in favor of $this->repository->fetchValue()
     */
    public function fetchValue($field)
    {
        return $this->repository->fetchValue($field);
    }

    /**
     * @deprecated in favor of $this->repository->fetchValue()
     */
    public function fetchFieldKey($fieldName)
    {
        return $this->repository->fetchFieldKey($fieldName);
    }

    /**
     * @deprecated in favor of $this->repository->fetchValue()
     */
    public function fetchFieldType($fieldKey)
    {
        return $this->repository->fetchFieldType($fieldKey);
    }

    /**
     * @deprecated in favor of $this->repository->fetchValue()
     */
    public function getKeyName()
    {
        return $this->repository->getKeyName();
    }

    /**
     * @return mixed
     */
    public function __toString()
    {
        return $this->get();
    }
}
