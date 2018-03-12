<?php

namespace Corcel\Acf\Field;

use Corcel\Acf\FieldInterface;
use Corcel\Model\Post;

/**
 * Class User.
 *
 * @author Junior Grossi <juniorgro@gmail.com>
 */
class User extends BasicField implements FieldInterface
{
    /**
     * @var \Corcel\Model\User
     */
    protected $user;

    /**
     * @var \Corcel\Model\User
     */
    protected $value;

    /**
     * @param Post $post
     */
    public function __construct($post)
    {
        parent::__construct($post);
        $this->user = new \Corcel\Model\User();
        $this->user->setConnection($this->repository->getConnectionName());
    }

    /**
     * @param string $fieldName
     */
    public function process(string $fieldName)
    {
        parent::process($fieldName);
        $userId = $this->fetchValue($fieldName);
        $this->value = $this->user->find($userId);
    }

    /**
     * @return \Corcel\Model\User
     */
    public function get()
    {
        return $this->value;
    }
}
