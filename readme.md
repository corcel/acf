# Corcel ACF Plugin

> This is a plugin that allows you to fetch all Advanced Custom Fields (ACF) fields inside Corcel easily.

# Installation

To install the ACF plugin for Corcel is easy:
 
```
composer require corcel/acf
```

# Usage

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
| Message           | missing   |                                       |
| Tab               | missing   |                                       |
| Repeater          | missing   |                                       |
| Flexible Content  | missing   |                                       |

# Contributing

# Licence

# Thanks