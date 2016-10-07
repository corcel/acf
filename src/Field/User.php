<?php

namespace Corcel\Acf\Field;

use Corcel\Acf\FieldInterface;
use Corcel\Post;

/**
 * Class User
 *
 * @package Corcel\Acf\Field
 * @author Junior Grossi <juniorgro@gmail.com>
 */
class User extends BasicField implements FieldInterface
{
    /**
     * @var \Corcel\User
     */
    protected $user;

    /**
     * @var \Corcel\User
     */
    protected $value;

    /**
     */
    public function __construct()
    {
        parent::__construct();
        $this->user = new \Corcel\User();
    }

    /**
     * @param string $fieldName
     * @param Post $post
     */
    public function process($fieldName, Post $post)
    {
        $userId = $this->fetchValue($fieldName, $post);
        $this->value = $this->user->find($userId);
    }

    /**
     * @return \Corcel\User
     */
    public function get()
    {
        return $this->value;
    }
}