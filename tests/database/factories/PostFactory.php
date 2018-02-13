<?php

use Corcel\Model\Post;

$factory->state(Post::class, 'page', function (Faker\Generator $faker) {
    return [
        'post_type' => 'page',
    ];
});
