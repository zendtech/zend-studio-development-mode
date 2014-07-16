Zend Studio Development Mode
============================

Zend Framework 2 module that helps developing Zend Framework 2 applications with Zend Studio.

Introduction
------------

This module adds the following configuration to the Zend Framework 2 application:
* Adds a `Cache-Control: no-cache` header to the response object to avoid caching in Internet Explorer.
* Changes the `Origin: file://` header in the request object sent by Android 4.x devices to `Origin: file:///` to avoid failure when using the [ZfrCors](https://github.com/zf-fr/zfr-cors) module.

> ### NOTE
>
> **DO NOT** enable this module in production systems.

Installation
------------

Run the following `composer` command:

```console
$ composer require --dev "zend/zend-studio-development-mode:~1.0"
```

Alternately, manually add the following to your `composer.json`, in the `require` section:

```javascript
"require-dev": {
    "zend/zend-studio-development-mode": "~1.0"
}
```

And then run `composer update` to ensure the module is installed.

Finally, add the module name to your project's `config/development.config.php` under the `modules`
key:

```php
return array(
    /* ... */
    'modules' => array(
        /* ... */
        'ZendStudioDevelopmentMode',
    ),
    /* ... */
);
```

Typically, this module should be used along with
[zf-development-mode](https://github.com/zfcampus/zf-development-mode) in order to conditionally
enable the module in your application. When doing so, you will add the module to your project's
`config/development.config.php.dist` file instead of the `config/application.config.php` file, and
enable it via `php public/index.php development enable`.
