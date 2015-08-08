
# WordPress+

[![Build Status](https://travis-ci.org/jumilla/wordpress-plus.svg)](https://travis-ci.org/jumilla/wordpress-plus)
[![Latest Stable Version](https://poser.pugx.org/laravel-plus/wordpress/v/stable.svg)](https://packagist.org/packages/laravel-plus/wordpress)
[![Total Downloads](https://poser.pugx.org/laravel-plus/wordpress/d/total.svg)](https://packagist.org/packages/)
[![Software License](https://poser.pugx.org/laravel-plus/wordpress/license.svg)](https://packagist.org/packages/laravel-plus/wordpress)

[日本語ドキュメント - Japanese](readme-ja.md)

## About WordPress+ (Plus)

WordPress+ wrapped [WordPress](https://ja.wordpress.org) in high-speed micro framework [Lumen](http://lumen.laravel.com) powered by Laravel 5.

## The functional feature

- Existing asset such as the WordPress plug-in exhibited all over the world and a theme is available.
- It's possible to renew the WordPress body (of course, plug-in).
- With Uniform Resource Locator gate (the bed where HTTP access from a browser is filtered).
- The coding speed of a HyperText Markup Language template goes up by a Blade template engine.
- The MySQL use which is to utilize a schema builder query builder of Laravel and is general can be done.
- An existence system and cooperation with the outside web service are easy to make.
- The original management screen done based on a management screen of WordPress is easy to make (The cost of the management screen making can be lowered.)
- Mobile cooperation (ex. notice to smart phone) is also easy!

## The happy of the development side

- It's possible to carry out by a PHP built-in server (The practical use for which I don't depend on Apache is also possible.)
- PHP package management using Composer is possible.
- A tool for all kinds' web craftsmen of giblets of Laravel 5 can be used (O-Auth authentication, command scheduler, Redis and cloud storage integration and all that).

## WordPress+ requires

- PHP more than 5.6 (for a Lumen framework, more than 5.5.9).
- PHP expansion: openssl, mbstring, pdo and pdo-mysql.
- [Composer](https://getcomposer.org/)

## The function WordPress+ is supporting

- Theme plug-in making from a command line
- Theme making using [Blade template engine](http://laravel.com/docs/5.1/blade)
- Multisite (Option: Please designate environment variable `WP_MULTISITE=true` to make it effective. Only corresponding to the subdomain type.)
- Link manager (Option: Please designate environment variable `WP_LINK_MANAGER=true` to make it effective.)

## Installation method

It can be installed from Composer or source cord download.

### Installation using Composer

Please open a command prompt and carry out the next command.

```sh
$ cd <parent-of-an-install-directory>
$ composer create-project laravel-plus/wordpress <an-install-directory>
```

### Source code is downloaded and installed.

A [Download ZIP](https://github.com/jumilla/wordpress-plus/archive/master.zip) button of our repository of GitHub is pressed and source cord is downloaded.

And ZIP is developed in an optional directory of a local machine.

Please open a command prompt and carry out the next command.

```sh
$ cd <installed-directory>
$ php composer update
$ cp .env.example .env
```

### Environment variables

The environment variable setting filing to which a project is peculiar writes the setting by which it's for a data base in `.env`.
Please refer to [.env.example](.env.example) for a setting example.

### Server initiation method

It can start by a PHP built-in server.

Please open a command prompt and carry out the next command.

```sh
$ cd <installed-directory>
$ php artisan serve
```

When `http://localhost:8000` is opened by a web browser, you can access.
A setup of WordPress starts at the time of the first time start.

A management screen is `http://localhost:8000/wp-admin/`.

### Setting of a web server

Lumen 5 / Laravel 5 is recommending to take slash `/` of an end of Uniform Resource Locator by setting of a web server.
But WordPress needs slash `/` of an end, so please be careful in case of setting.
A redirection loop sometimes occurs after login to a management screen.

### artisan commands

- `wordpress:status` Status display for WordPress.
- `wordpress:install` Install WordPress tables.
- `wordpress:uninstall` Uninstall WordPress tables.
- `wordpress:multisite:install` Install tables for multisite.
- `wordpress:multisite:uninstall` Uninstall tables for multisite.
- `wordpress:theme` List of themes.
- `wordpress:theme <name>` Detail of theme.
- `wordpress:plugin` List of plugins.
- `wordpress:plugin <name>` Detail of plugin.
- `make:theme <name>` Make theme.
- `make:plugin <name>` Make plugin.

### Use of Blade in the theme

A directory as `blade` is made in the theme directory and a file along a WordPress template file name agreement is arranged there.
The extension is made `.blade.php`.

When setting environment variable `WP_BLADE_PRECOMPILE` as `true` (default), when indicating a page by WordPress+, a compiled PHP file is output in theme directory falling plumb down. WordPress+ is original mounting for the Blade engine used at the same time.

When setting environment variable `WP_BLADE_PRECOMPILE` as `false`, a PHP file in the sky which corresponds to a blade file is output in theme directory falling plumb down. The Blade engine used at the same time is mounting of Laravel 5.

### Blade directives

- `@filter('filter-name')` Call filter.
- `@action('action-name')` Invoke action.
- `@shortcode([shortcode-name param1="value1"])` Expand shortcode.
- `@postloop` `@postloop($posts)` A loop block of Post query is started.
- `@postempty` A query begins to block one in case of 0 cases.
- `@endpostloop` A loop block of Post query is ended.

## Licenses

[WordPress+](https://github.com/jumilla/wordpress-plus) is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)  
[Lumen framework](http://lumen.laravel.com) is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)  
[WordPress](https://ja.wordpress.org) is open-sourced software licensed under the [– GNU General Public License –](https://ja.wordpress.org/gpl/)  

## Copyright

2015 [Fumio Furukawa](http://jumilla.me), All rights reserved.

