# Corcel ACF Plugin

[![Travis](https://travis-ci.org/corcel/acf.svg?branch=master)](https://travis-ci.org/corcel/acf?branch=master)
[![Packagist](https://img.shields.io/packagist/v/corcel/acf.svg)](https://github.com/corcel/acf/releases)
[![Packagist](https://img.shields.io/packagist/dt/corcel/acf.svg)](https://packagist.org/packages/corcel/acf)

> Fetch all Advanced Custom Fields (ACF) fields inside Corcel easily.

This Corcel plugin allows you to fetch WordPress custom fields created by the [ACF](http://advancedcustomfields.com) plugin using the same syntax of Eloquent, from the [Laravel Framework](http://laravel.com). You can use Eloquent models and Collections to improve your development, using the WordPress backend with any PHP application.

For more information about how Corcel works please visit [the repository](http://github.com/jgrossi/corcel).

* [Installation](#installation)
* [Usage](#usage)
    - [The Idea](#the-idea)
    - [What is Missing](#what-is-missing)
    - [Fields](#fields)
* [Contributing](#contributing)
    - [Running Tests](#running-tests)
* [Licence](#licence)

# Version Compatibility

 Corcel    | Laravel  | PHP
:----------|:---------|:----------
 `^4.0`    | 7.x      | `>=7.2`
 `^5.0`    | 8.x      | `>=7.3`

# Installation

To install the ACF plugin for Corcel is easy:

```
composer require corcel/acf
```

Corcel is required for this plugin, but don't worry, if it's missing it will be installed as well.

# Usage

This is a development version so the usage can change further. The desired behavior of this plugin is to allow this:

```php
$post = Post::find(1);
echo $post->acf->url; // returns the url custom field created using ACF
```

## Performance

When using something like `$post->acf->url` the plugin has to make some extra SQL queries to get the field type according ACF approach. So we created another way to get that without making those extra queries. You have only the inform the plugin what is the post type, as a function:

 ```php
 // Extra queries way
 echo $post->acf->author_username; // it's a User field

 // Without extra queries
 echo $post->acf->user('author_username');
 ```

 > PS: The method names should be written in `camelCase()` format. So, for example, for the field type `date_picker` you should write `$post->acf->datePicker('fieldName')`. The plugin does the conversion from `camelCase` to `snake_case` for you.

## The Idea

Using the default `$post->meta->field_name` in Corcel returns the value of the `meta_value` column in the `wp_postmeta` table. It can be a string, an integer or even a serialized array. The problem is, when you're using ACF this value can be more than that. If you have an integer, for example, it can be a `post id`, an `user id` or even an array of `posts ids`.

ACF has to make 2 (two) SQL queries to find out the field type, so according the type it has different behavior with the `meta_value`. For example, if the value is `45` and the `post type` is `post_object`, the value `45` is a post with ID `45`. So, in this case, Corcel should return a `Corcel\Post` instance instead of just an integer.

First ACF fetches the `meta_value` in `wp_postmeta` table, where the `meta_key` is something like `_field_name` and the post ID is the ID of the post you want the custom field. The returned value is the `field key` and it looks like this `field_57f421a2b81bd`. With this key it fetches the corresponding post in `wp_posts`, where `post_name = 'field_57f421a2b81bd'`. With the results it gets the `post_content` value, a serialized array, deserialize it and gets the content on the `type` key. This is the field type. According it the ACF (and also this plugin) does the right thing.

This plugin works with a basic logic inside `Corcel\Acf\Field\BasicField` abstract class, that has a lot of useful functions to return the `field key`, the `value`, etc. The `Corcel\Acf\FieldFactory` is responsible to return the correct field instance according the field type, so, if the field type is `post_object` it return an instance of `Corcel\Acf\Field\PostObject`, and it will returns in the `get()` method an instance of `Corcel\Post`.

## What is Missing

First we should create the fields classes and the test cases. After we have to setup how Corcel is going to work with the `corcel/acf` plugin, returning the custom field value in the format `$post->meta->field` or maybe `$post->acf->field` having different behavior (done!).

 - Create more unit tests for `Repeater` field;
 - Implement the `Flexible Content` field with unit tests (done!);
 - Improve performance. Currently the plugin makes one SQL query for each field. This goal is to improve that using `whereIn()` clauses.

 Some fields are still missing (check table below and contribute).

## Fields

| Field             | Status    | Developer                             | Returns |
|-------------------|-----------|---------------------------------------| --------|
| Text              | ok        | [@jgrossi](http://github.com/jgrossi) | `string`  |
| Textarea          | ok        | [@jgrossi](http://github.com/jgrossi) | `string`  |
| Number            | ok        | [@jgrossi](http://github.com/jgrossi) | `number`  |
| E-mail            | ok        | [@jgrossi](http://github.com/jgrossi) | `string`  |
| URL               | ok        | [@jgrossi](http://github.com/jgrossi) | `string`  |
| Password          | ok        | [@jgrossi](http://github.com/jgrossi) | `string`  |
| WYSIWYG (Editor)  | ok        | [@jgrossi](http://github.com/jgrossi) | `string`  |
| oEmbed            | ok        | [@jgrossi](http://github.com/jgrossi) | `string`  |
| Image             | ok        | [@jgrossi](http://github.com/jgrossi) | `Corcel\Acf\Field\Image` |
| File              | ok        | [@jgrossi](http://github.com/jgrossi) | `Corcel\Acf\Field\File` |
| Gallery           | ok        | [@jgrossi](http://github.com/jgrossi) | `Corcel\Acf\Field\Gallery` |
| Select            | ok        | [@jgrossi](http://github.com/jgrossi) | `string` or `array` |
| Checkbox          | ok        | [@jgrossi](http://github.com/jgrossi) | `string` or `array` |
| Radio             | ok        | [@jgrossi](http://github.com/jgrossi) | `string` |
| True/False        | ok        | [@jgrossi](http://github.com/jgrossi) | `boolean` |
| Post Object       | ok        | [@jgrossi](http://github.com/jgrossi) | `Corcel\Post` |
| Page Link         | ok        | [@jgrossi](http://github.com/jgrossi) | `string` |
| Relationship      | ok        | [@jgrossi](http://github.com/jgrossi) | `Corcel\Post` or `Collection` of `Post` |
| Taxonomy          | ok        | [@jgrossi](http://github.com/jgrossi) | `Corcel\Term` or `Collection` of `Term` |
| User              | ok        | [@jgrossi](http://github.com/jgrossi) | `Corcel\User` |
| Google Map        | missing   |                                       |
| Date Picker       | ok        | [@jgrossi](http://github.com/jgrossi) | `Carbon\Carbon` |
| Date Time Picker  | ok        | [@jgrossi](http://github.com/jgrossi) | `Carbon\Carbon` |
| Time Picker       | ok        | [@jgrossi](http://github.com/jgrossi) | `Carbon\Carbon` |
| Color Picker      | ok        | [@jgrossi](http://github.com/jgrossi) | `string` |
| Repeater          | ok        | [@jgrossi](http://github.com/jgrossi) | `Collection` of fields |
| Flexible Content  | ok        | [@marcoboom](http://github.com/marcoboom) | `Collection` |

# Contributing

All contributions are welcome. Before submitting your Pull Request take a look on the following guidelines:

- Make your changes in a new git branch, based on the `develop` branch: `git checkout -b my-fix-branch develop`;
- Create your patch/feature, including appropriate test cases. Tests are necessary to make sure what you did is working and did not break nothing in the plugin;
- Run the unit tests and ensure that all tests are passing. Please, when submitting your PR paste the results of the `phpunit` command to facilitate the approval job;
- In GitHub, send a pull request to `corcel/acf:develop`, **always**. Do not send PR to our `master` branch! We encourage you to use `git flow` (https://github.com/petervanderdoes/gitflow-avh) workflow to make your life easier. It' not necessary, but it'll also be good for your development career;
- Make sure your code is following the `PSR-2` conventions (http://www.php-fig.org/psr/psr-2/).

## Running Tests

To run the phpunit tests, execute `phpunit` (if you have a global PHPUnit executable) or try the following command:

```
./vendor/bin/phpunit
```

You should import the `database.sql` file to a database inside your local environment to make the tests working. Just unzip the `tests/config/database.sql.zip` file and set the database, user and password fields in `tests/config/bootstrap.php`.

> If you want to access the WordPress Admin Panel just use username as `admin` and password `123456`.

# Licence

[MIT License](http://jgrossi.mit-license.org/) © Junior Grossi
