<?php

use Illuminate\Support\Str;
use Corcel\Acf\Models\AcfField;

$factory->define(AcfField::class, function (Faker\Generator $faker) {
    return [
        'post_author' => $faker->name,
        'post_date' => $faker->dateTimeThisYear,
        'post_date_gmt' => $faker->dateTimeThisYear,
        'post_content' => 'a:12:{s:4:"type";s:4:"text";s:12:"instructions";s:0:"";s:8:"required";i:0;s:17:"conditional_logic";i:0;s:7:"wrapper";a:3:{s:5:"width";s:0:"";s:5:"class";s:0:"";s:2:"id";s:0:"";}s:13:"default_value";s:0:"";s:11:"placeholder";s:0:"";s:7:"prepend";s:0:"";s:6:"append";s:0:"";s:9:"maxlength";s:0:"";s:8:"readonly";i:0;s:8:"disabled";i:0;}',
        'post_title' => $faker->title,
        'post_excerpt' => $faker->word,
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

$factory->state(AcfField::class, 'textarea', function (Faker\Generator $faker) {
    return [
        'post_content' => 'a:12:{s:4:"type";s:8:"textarea";s:12:"instructions";s:0:"";s:8:"required";i:0;s:17:"conditional_logic";i:0;s:7:"wrapper";a:3:{s:5:"width";s:0:"";s:5:"class";s:0:"";s:2:"id";s:0:"";}s:13:"default_value";s:0:"";s:11:"placeholder";s:0:"";s:9:"maxlength";s:0:"";s:4:"rows";s:0:"";s:9:"new_lines";s:7:"wpautop";s:8:"readonly";i:0;s:8:"disabled";i:0;}',
    ];
});

$factory->state(AcfField::class, 'number', function (Faker\Generator $faker) {
    return [
        'post_content' => 'a:14:{s:4:"type";s:6:"number";s:12:"instructions";s:0:"";s:8:"required";i:0;s:17:"conditional_logic";i:0;s:7:"wrapper";a:3:{s:5:"width";s:0:"";s:5:"class";s:0:"";s:2:"id";s:0:"";}s:13:"default_value";s:0:"";s:11:"placeholder";s:0:"";s:7:"prepend";s:0:"";s:6:"append";s:0:"";s:3:"min";s:0:"";s:3:"max";s:0:"";s:4:"step";s:0:"";s:8:"readonly";i:0;s:8:"disabled";i:0;}',
    ];
});

$factory->state(AcfField::class, 'email', function (Faker\Generator $faker) {
    return [
        'post_content' => 'a:9:{s:4:"type";s:5:"email";s:12:"instructions";s:0:"";s:8:"required";i:0;s:17:"conditional_logic";i:0;s:7:"wrapper";a:3:{s:5:"width";s:0:"";s:5:"class";s:0:"";s:2:"id";s:0:"";}s:13:"default_value";s:0:"";s:11:"placeholder";s:0:"";s:7:"prepend";s:0:"";s:6:"append";s:0:"";}',
    ];
});

$factory->state(AcfField::class, 'url', function (Faker\Generator $faker) {
    return [
        'post_content' => 'a:7:{s:4:"type";s:3:"url";s:12:"instructions";s:0:"";s:8:"required";i:0;s:17:"conditional_logic";i:0;s:7:"wrapper";a:3:{s:5:"width";s:0:"";s:5:"class";s:0:"";s:2:"id";s:0:"";}s:13:"default_value";s:0:"";s:11:"placeholder";s:0:"";}',
    ];
});

$factory->state(AcfField::class, 'password', function (Faker\Generator $faker) {
    return [
        'post_content' => 'a:12:{s:4:"type";s:4:"text";s:12:"instructions";s:0:"";s:8:"required";i:0;s:17:"conditional_logic";i:0;s:7:"wrapper";a:3:{s:5:"width";s:0:"";s:5:"class";s:0:"";s:2:"id";s:0:"";}s:13:"default_value";s:0:"";s:11:"placeholder";s:0:"";s:7:"prepend";s:0:"";s:6:"append";s:0:"";s:9:"maxlength";s:0:"";s:8:"readonly";i:0;s:8:"disabled";i:0;}',
    ];
});

$factory->state(AcfField::class, 'select', function (Faker\Generator $faker) {
    return [
        'post_content' => 'a:14:{s:4:"type";s:6:"select";s:12:"instructions";s:0:"";s:8:"required";i:0;s:17:"conditional_logic";i:0;s:7:"wrapper";a:3:{s:5:"width";s:0:"";s:5:"class";s:0:"";s:2:"id";s:0:"";}s:7:"choices";a:4:{s:3:"red";s:3:"Red";s:4:"blue";s:4:"Blue";s:6:"yellow";s:6:"Yellow";s:5:"green";s:5:"Green";}s:13:"default_value";a:0:{}s:10:"allow_null";i:0;s:8:"multiple";i:1;s:2:"ui";i:0;s:4:"ajax";i:0;s:11:"placeholder";s:0:"";s:8:"disabled";i:0;s:8:"readonly";i:0;}',
    ];
});

$factory->state(AcfField::class, 'select_multiple', function (Faker\Generator $faker) {
    return [
        'post_content' => 'a:14:{s:4:"type";s:6:"select";s:12:"instructions";s:0:"";s:8:"required";i:0;s:17:"conditional_logic";i:0;s:7:"wrapper";a:3:{s:5:"width";s:0:"";s:5:"class";s:0:"";s:2:"id";s:0:"";}s:7:"choices";a:4:{s:3:"red";s:3:"Red";s:4:"blue";s:4:"Blue";s:6:"yellow";s:6:"Yellow";s:5:"green";s:5:"Green";}s:13:"default_value";a:0:{}s:10:"allow_null";i:0;s:8:"multiple";i:1;s:2:"ui";i:0;s:4:"ajax";i:0;s:11:"placeholder";s:0:"";s:8:"disabled";i:0;s:8:"readonly";i:0;}',
    ];
});

$factory->state(AcfField::class, 'checkbox', function (Faker\Generator $faker) {
    return [
        'post_content' => 'a:9:{s:4:"type";s:8:"checkbox";s:12:"instructions";s:0:"";s:8:"required";i:1;s:17:"conditional_logic";i:0;s:7:"wrapper";a:3:{s:5:"width";s:0:"";s:5:"class";s:0:"";s:2:"id";s:0:"";}s:7:"choices";a:4:{s:3:"red";s:3:"Red";s:4:"blue";s:4:"Blue";s:6:"yellow";s:6:"Yellow";s:5:"green";s:5:"Green";}s:13:"default_value";a:0:{}s:6:"layout";s:8:"vertical";s:6:"toggle";i:0;}',
    ];
});

$factory->state(AcfField::class, 'radio_button', function (Faker\Generator $faker) {
    return [
        'post_content' => 'a:11:{s:4:"type";s:5:"radio";s:12:"instructions";s:0:"";s:8:"required";i:1;s:17:"conditional_logic";i:0;s:7:"wrapper";a:3:{s:5:"width";s:0:"";s:5:"class";s:0:"";s:2:"id";s:0:"";}s:7:"choices";a:4:{s:3:"red";s:3:"Red";s:4:"blue";s:4:"Blue";s:6:"yellow";s:6:"Yellow";s:5:"green";s:5:"Green";}s:10:"allow_null";i:0;s:12:"other_choice";i:0;s:17:"save_other_choice";i:0;s:13:"default_value";s:0:"";s:6:"layout";s:8:"vertical";}',
    ];
});

$factory->state(AcfField::class, 'true_false', function (Faker\Generator $faker) {
    return [
        'post_content' => 'a:7:{s:4:"type";s:10:"true_false";s:12:"instructions";s:0:"";s:8:"required";s:0:"";s:17:"conditional_logic";s:0:"";s:7:"wrapper";a:3:{s:5:"width";s:0:"";s:5:"class";s:0:"";s:2:"id";s:0:"";}s:7:"message";s:0:"";s:13:"default_value";i:0;}',
    ];
});

$factory->state(AcfField::class, 'editor', function (Faker\Generator $faker) {
    return [
        'post_content' => 'a:9:{s:4:"type";s:7:"wysiwyg";s:12:"instructions";s:0:"";s:8:"required";i:0;s:17:"conditional_logic";i:0;s:7:"wrapper";a:3:{s:5:"width";s:0:"";s:5:"class";s:0:"";s:2:"id";s:0:"";}s:13:"default_value";s:0:"";s:4:"tabs";s:3:"all";s:7:"toolbar";s:4:"full";s:12:"media_upload";i:1;}',
    ];
});

$factory->state(AcfField::class, 'oembed', function (Faker\Generator $faker) {
    return [
        'post_content' => 'a:7:{s:4:"type";s:6:"oembed";s:12:"instructions";s:0:"";s:8:"required";i:0;s:17:"conditional_logic";i:0;s:7:"wrapper";a:3:{s:5:"width";s:0:"";s:5:"class";s:0:"";s:2:"id";s:0:"";}s:5:"width";i:640;s:6:"height";i:390;}',
    ];
});

$factory->state(AcfField::class, 'image', function (Faker\Generator $faker) {
    return [
        'post_content' => 'a:15:{s:4:"type";s:5:"image";s:12:"instructions";s:0:"";s:8:"required";i:0;s:17:"conditional_logic";i:0;s:7:"wrapper";a:3:{s:5:"width";s:0:"";s:5:"class";s:0:"";s:2:"id";s:0:"";}s:13:"return_format";s:5:"array";s:12:"preview_size";s:9:"thumbnail";s:7:"library";s:3:"all";s:9:"min_width";s:0:"";s:10:"min_height";s:0:"";s:8:"min_size";s:0:"";s:9:"max_width";s:0:"";s:10:"max_height";s:0:"";s:8:"max_size";s:0:"";s:10:"mime_types";s:0:"";}',
    ];
});

$factory->state(AcfField::class, 'file', function (Faker\Generator $faker) {
    return [
        'post_content' => 'a:10:{s:4:"type";s:4:"file";s:12:"instructions";s:0:"";s:8:"required";i:0;s:17:"conditional_logic";i:0;s:7:"wrapper";a:3:{s:5:"width";s:0:"";s:5:"class";s:0:"";s:2:"id";s:0:"";}s:13:"return_format";s:5:"array";s:7:"library";s:3:"all";s:8:"min_size";s:0:"";s:8:"max_size";s:0:"";s:10:"mime_types";s:0:"";}',
    ];
});

$factory->state(AcfField::class, 'gallery', function (Faker\Generator $faker) {
    return [
        'post_content' => 'a:17:{s:4:"type";s:7:"gallery";s:12:"instructions";s:0:"";s:8:"required";i:0;s:17:"conditional_logic";i:0;s:7:"wrapper";a:3:{s:5:"width";s:0:"";s:5:"class";s:0:"";s:2:"id";s:0:"";}s:3:"min";s:0:"";s:3:"max";s:0:"";s:12:"preview_size";s:9:"thumbnail";s:6:"insert";s:6:"append";s:7:"library";s:3:"all";s:9:"min_width";s:0:"";s:10:"min_height";s:0:"";s:8:"min_size";s:0:"";s:9:"max_width";s:0:"";s:10:"max_height";s:0:"";s:8:"max_size";s:0:"";s:10:"mime_types";s:0:"";}',
    ];
});

$factory->state(AcfField::class, 'user', function (Faker\Generator $faker) {
    return [
        'post_content' => 'a:8:{s:4:"type";s:4:"user";s:12:"instructions";s:0:"";s:8:"required";i:0;s:17:"conditional_logic";i:0;s:7:"wrapper";a:3:{s:5:"width";s:0:"";s:5:"class";s:0:"";s:2:"id";s:0:"";}s:4:"role";s:0:"";s:10:"allow_null";i:0;s:8:"multiple";i:0;}',
    ];
});

$factory->state(AcfField::class, 'date_picker', function (Faker\Generator $faker) {
    return [
        'post_content' => 'a:8:{s:4:"type";s:11:"date_picker";s:12:"instructions";s:0:"";s:8:"required";i:0;s:17:"conditional_logic";i:0;s:7:"wrapper";a:3:{s:5:"width";s:0:"";s:5:"class";s:0:"";s:2:"id";s:0:"";}s:14:"display_format";s:5:"d/m/Y";s:13:"return_format";s:5:"d/m/Y";s:9:"first_day";i:1;}',
    ];
});

$factory->state(AcfField::class, 'date_time_picker', function (Faker\Generator $faker) {
    return [
        'post_content' => 'a:8:{s:4:"type";s:16:"date_time_picker";s:12:"instructions";s:0:"";s:8:"required";i:0;s:17:"conditional_logic";i:0;s:7:"wrapper";a:3:{s:5:"width";s:0:"";s:5:"class";s:0:"";s:2:"id";s:0:"";}s:14:"display_format";s:11:"d/m/Y g:i a";s:13:"return_format";s:11:"d/m/Y g:i a";s:9:"first_day";i:1;}',
    ];
});

$factory->state(AcfField::class, 'time_picker', function (Faker\Generator $faker) {
    return [
        'post_content' => 'a:7:{s:4:"type";s:11:"time_picker";s:12:"instructions";s:0:"";s:8:"required";i:0;s:17:"conditional_logic";i:0;s:7:"wrapper";a:3:{s:5:"width";s:0:"";s:5:"class";s:0:"";s:2:"id";s:0:"";}s:14:"display_format";s:5:"g:i a";s:13:"return_format";s:5:"g:i a";}',
    ];
});

$factory->state(AcfField::class, 'color_picker', function (Faker\Generator $faker) {
    return [
        'post_content' => 'a:6:{s:4:"type";s:12:"color_picker";s:12:"instructions";s:0:"";s:8:"required";i:0;s:17:"conditional_logic";i:0;s:7:"wrapper";a:3:{s:5:"width";s:0:"";s:5:"class";s:0:"";s:2:"id";s:0:"";}s:13:"default_value";s:7:"#ffcc99";}',
    ];
});
