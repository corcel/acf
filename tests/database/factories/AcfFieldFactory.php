<?php

use Illuminate\Support\Str;
use Corcel\Acf\Models\AcfField;

$factory->define(AcfField::class, function (Faker\Generator $faker, $options) {
    $type = (empty($options['post_excerpt']) ? 'text' : $options['post_excerpt']);

    return [
        'post_author' => $faker->name,
        'post_date' => $faker->dateTimeThisYear,
        'post_date_gmt' => $faker->dateTimeThisYear,
        'post_content' => serialize(['type' => $type]),
        'post_title' => $faker->title,
        'post_excerpt' => $type,
        'post_status' => 'publish',
        'comment_status' => 'closed',
        'ping_status' => 'closed',
        'post_password' => '',
        'post_name' => 'field_' . Str::random(13),
        'to_ping' => '',
        'pinged' => '',
        'post_modified' => $faker->dateTimeThisMonth,
        'post_modified_gmt' => $faker->dateTimeThisMonth,
        'post_content_filtered' => '',
        'post_parent' => 0,
        'guid' => 'http://example.com/?p=' . $faker->numberBetween(1, 100),
        'menu_order' => 0,
        'post_type' => 'acf-field',
        'post_mime_type' => '',
        'comment_count' => 0,
    ];
});