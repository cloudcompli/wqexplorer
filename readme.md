# WQInspector Interface

This application is a web interface for accessing data managed and interpreted by the [wqinspector](https://github.com/cloudcompli/wqinspector).

## Setup

#### Database Initialization

```
php artisan migrate
```

#### Data Import

```
php artisan import:ocpw:esm storage/app/ocpw/esm/2015.csv
```

```
php artisan import:ocpw:nsmp storage/app/ocpw/nsmp/2015-q1.csv
php artisan import:ocpw:nsmp storage/app/ocpw/nsmp/2015-q2.csv
php artisan import:ocpw:nsmp storage/app/ocpw/nsmp/2015-q3.csv
```

```
php artisan import:ocpw:mass_emissions storage/app/ocpw/mass_emissions/2015.csv
```

```
php artisan import:smarts:construction storage/app/smarts/construction/2014-2015.html
php artisan import:smarts:construction storage/app/smarts/construction/2015-2016.html
```

```
php artisan import:smarts:industrial storage/app/smarts/industrial/2014-2015.html
php artisan import:smarts:industrial storage/app/smarts/industrial/2015-2016.html
```

# Laravel PHP Framework

[![Build Status](https://travis-ci.org/laravel/framework.svg)](https://travis-ci.org/laravel/framework)
[![Total Downloads](https://poser.pugx.org/laravel/framework/d/total.svg)](https://packagist.org/packages/laravel/framework)
[![Latest Stable Version](https://poser.pugx.org/laravel/framework/v/stable.svg)](https://packagist.org/packages/laravel/framework)
[![Latest Unstable Version](https://poser.pugx.org/laravel/framework/v/unstable.svg)](https://packagist.org/packages/laravel/framework)
[![License](https://poser.pugx.org/laravel/framework/license.svg)](https://packagist.org/packages/laravel/framework)

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable, creative experience to be truly fulfilling. Laravel attempts to take the pain out of development by easing common tasks used in the majority of web projects, such as authentication, routing, sessions, queueing, and caching.

Laravel is accessible, yet powerful, providing tools needed for large, robust applications. A superb inversion of control container, expressive migration system, and tightly integrated unit testing support give you the tools you need to build any application with which you are tasked.

## Official Documentation

Documentation for the framework can be found on the [Laravel website](http://laravel.com/docs).

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](http://laravel.com/docs/contributions).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell at taylor@laravel.com. All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
