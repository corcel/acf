<?php

use Illuminate\Support\Str;
use Corcel\Acf\Models\AcfFieldGroup;
use Corcel\Model\User;

$factory->define(AcfFieldGroup::class, function (Faker\Generator $faker) {
    return [
        'post_author' => factory(User::class)->create()->ID,
        'post_date' => $faker->dateTimeThisYear,
        'post_date_gmt' => $faker->dateTimeThisYear,
        'post_content' => 'a:7:{s:8:"location";a:1:{i:0;a:1:{i:0;a:3:{s:5:"param";s:12:"options_page";s:8:"operator";s:2:"==";s:5:"value";s:29:"acf-options-seiten-optionen22";}}}s:8:"position";s:6:"normal";s:5:"style";s:7:"default";s:15:"label_placement";s:3:"top";s:21:"instruction_placement";s:5:"label";s:14:"hide_on_screen";s:0:"";s:11:"description";s:0:"";}',
        'post_title' => $title = $faker->title,
        'post_excerpt' => strtolower($title),
        'post_status' => 'publish',
        'comment_status' => 'closed',
        'ping_status' => 'closed',
        'post_password' => '',
        'post_name' => 'group_' . Str::random(13),
        'to_ping' => '',
        'pinged' => '',
        'post_modified' => $faker->dateTimeThisMonth,
        'post_modified_gmt' => $faker->dateTimeThisMonth,
        'post_content_filtered' => '',
        'post_parent' => 0,
        'guid' => 'http://example.com/?post_type=acf-field-group&#038;p=' . $faker->numberBetween(1, 100),
        'menu_order' => 0,
        'post_type' => 'acf-field-group',
        'post_mime_type' => '',
        'comment_count' => 0,
    ];
});
