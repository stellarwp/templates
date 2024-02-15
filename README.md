# StellarWP Templates

[![Tests](https://github.com/stellarwp/templates/workflows/Tests/badge.svg)](https://github.com/stellarwp/templates/actions?query=branch%3Amain) [![Static Analysis](https://github.com/stellarwp/templates/actions/workflows/static-analysis.yml/badge.svg)](https://github.com/stellarwp/templates/actions/workflows/static-analysis.yml)

A library for templating in WordPress.

## Installation

It's recommended that you install Templates as a project dependency via [Composer](https://getcomposer.org/):

```bash
composer require stellarwp/templates
```

> We _actually_ recommend that this library gets included in your project using [Strauss](https://github.com/BrianHenryIE/strauss).
>
> Luckily, adding Strauss to your `composer.json` is only slightly more complicated than adding a typical dependency, so checkout our [strauss docs](https://github.com/stellarwp/global-docs/blob/main/docs/strauss-setup.md).

## Notes on examples

Since the recommendation is to use Strauss to prefix this library's namespaces, all examples will be using the `Boomshakalaka` namespace prefix.

## Configuration

This library requires some configuration before its features can be used. The configuration is done via the `Config` class.

```php
use Boomshakalaka\StellarWP\Templates\Config;

add_action( 'plugins_loaded', function() {
	Config::set_hook_prefix( 'boom-shakalaka' );
	Config::set_path( PATH_TO_YOUR_PROJECT_ROOT );
} );
```

