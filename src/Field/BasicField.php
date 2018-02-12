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
use Corcel\Acf\Repositories\PostRepository;

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
    public function __construct($repository)
    {
        // FIXME do we need the backwards-compatibility?
        if ($repository instanceof Model) {
            // trigger_error('Deprecated: fields should be instantiated with a repository.', E_USER_NOTICE);
            $repository = new PostRepository($repository);
        }
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
     * @return mixed
     */
    public function __toString()
    {
        return $this->get();
    }
}
