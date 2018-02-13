<?php

use Corcel\Model\Meta\PostMeta;

$factory->define(PostMeta::class, function (Faker\Generator $faker) {
    return [
        'post_id' => $faker->numberBetween(1, 100),
        'meta_key' => $faker->word,
        'meta_value' => $faker->sentence(),
    ];
});

$factory->state(PostMeta::class, 'attachment_metadata', function (Faker\Generator $faker) {
    return [
        'meta_key' => '_wp_attachment_metadata',
        'meta_value' => serialize([
            'width' => 567,
            'height' => 345,
            'file' => '2014/01/test.jpg',
            'sizes' => [
                'thumbnail' => [
                    'file' => 'test-150x150.jpg',
                    'width' => 150,
                    'height' => 150,
                    'mime-type' => 'image/jpeg',
                ],
                'medium' => [
                    'file' => 'test-300x183.jpg',
                    'width' => 300,
                    'height' => 183,
                    'mime-type' => 'image/jpeg',
                ],
            ],
        ]),
    ];
});
