# StellarWP Templates

[![Tests](https://github.com/stellarwp/templates/workflows/Tests/badge.svg)](https://github.com/stellarwp/templates/actions?query=branch%3Amain) [![Static Analysis](https://github.com/stellarwp/templates/actions/workflows/static-analysis.yml/badge.svg)](https://github.com/stellarwp/templates/actions/workflows/static-analysis.yml)

A library for templating in WordPress.

## Table of contents

* [Installation](#installation)
* [Notes on examples](#notes-on-examples)
* [Configuration](#configuration)

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
use Boomshakalaka\StellarWP\Assets\Config;

add_action( 'plugins_loaded', function() {
	Config::set_hook_prefix( 'boom-shakalaka' );
	Config::set_path( PATH_TO_YOUR_PROJECT_ROOT );
} );
```

## Register and enqueue assets

There are a lot of options that are available for handling assets

### Simple examples

For all examples, assume that the following `use` statement is being used:

```php
use Boomshakalaka\StellarWP\Assets\Asset;
```

#### A simple registration

```php
Asset::add( 'my-style', 'css/my-style.css' )
	->register();
```

#### A URL-based asset registration

```php
Asset::add( 'remote-js', 'https://someplace.com/script.js' )
	->register();
```

#### Specifying the version
By default, assets inherit the version of that set in Config::get_version(), but you
can specify a version manually:

```php
Asset::add( 'another-style', 'css/another.css', '1.2.3' )
	->register();
```

#### Specifying the root path
By default, assets are searched for/found from the root path of your project based on
the value set in Config::get_path(), but you can specify a root path manually:

```php
Asset::add( 'another-style', 'css/another.css', null, $my_path )
	->register();
```

#### Assets with no file extension

If you need to register an asset where the asset does not have an extension,
you can do so by manually setting the asset type, like so:

```php
Asset::add( 'extension-less', 'https://someplace.com/a/style' )
	->set_type( 'css' )
	->register();

// or:

Asset::add( 'extension-less', 'https://someplace.com/a/script' )
	->set_type( 'js' )
	->register();
```

#### Setting priority order

You can set scripts to enqueue in a specific order via the `::set_priority()` method. This method takes an integer and
works similar to the action/filter priorities in WP:

```php
Asset::add( 'my-style', 'css/my-style.css' )
	->set_priority( 20 )
	->register();
```

#### Dependencies
If your asset has dependencies, you can specify those like so:

```php
Asset::add( 'script-with-dependencies', 'js/something.js' )
	->set_dependencies( 'jquery', 'jquery-ui', 'some-other-thing' )
	->register();
```

#### Auto-enqueuing on an action
To specify when to enqueue the asset, you can indicate it like so:

```php
Asset::add( 'yet-another-style', 'css/yet-another.css' )
	->enqueue_on( 'wp_enqueue_scripts' )
	->register();
```

### Comprehensive CSS example

The following example shows all of the options available during the registration of an asset.

```php
use Boomshakalaka\StellarWP\Assets\Asset;

Asset::add( 'my-asset', 'css/some-asset.css', $an_optional_version, $an_optional_path_to_project_root )
	->add_style_data( 'rtl', true )
	->add_style_data( 'suffix', '.rtl' )
	->add_to_group( 'my-assets' ) // You can have more than one of these.
	->call_after_enqueue( // This can be any callable.
		static function() {
			// Do something after the asset is enqueued.
		}
	)
	->enqueue_on( 'wp_enqueue_scripts', 20 ) // The second arg is optional and can be set separately via `::set_priority()`.
	->set_condition( // This can be any callable that returns a boolean.
		static function() {
			return is_front_page() || is_single();
		}
	)
	->set_dependencies( 'some-css' ) // Each dependency becomes a parameter in this method.
	->set_media( 'screen' )
	->set_min_path( 'src/assets/build/' )
	->set_path( 'src/assets' )
	->set_type( 'css' ) // Technically unneeded due to the .js extension.
	->register();
```

### Comprehensive JS example

```php
use Boomshakalaka\StellarWP\Assets\Asset;

Asset::add( 'my-asset', 'js/some-asset.js', $an_optional_version, $an_optional_path_to_project_root )
	->add_localize_script( // You can have more than one of these.
		'some_js_variable',
		[
			'color' => 'blue',
		]
	)
	->add_to_group( 'my-assets' ) // You can have more than one of these.
	->call_after_enqueue( // This can be any callable.
		static function() {
			// Do something after the asset is enqueued.
		}
	)
	->enqueue_on( 'wp_enqueue_scripts', 20 ) // The second arg is optional and can be set separately via `::set_priority()`.
	->print_before( '<b>Before</b>' )
	->print_after( '<b>After</b>' )
	->set_as_async( true )
	->set_as_deferred( true )
	->set_as_module( true )
	->set_condition( // This can be any callable that returns a boolean.
		static function() {
			return is_front_page() || is_single();
		}
	)
	->set_dependencies( 'jquery' ) // Each dependency becomes a parameter in this method.
	->set_min_path( 'src/assets/build/' )
	->set_path( 'src/assets' )
	->set_type( 'js' ) // Technically unneeded due to the .js extension.
	->register();
```

### Enqueuing manually

Sometimes you don't wish to set an asset to enqueue automatically on a specific action. In these cases, you can
trigger a manual enqueue:

```php
use Boomshakalaka\StellarWP\Assets\Assets;

// Enqueue a single asset:
Assets::get( 'my-style' )->enqueue();

// Enqueue multiple assets:
Assets::instance()->enqueue(
	[
		'my-style',
		'my-script',
		'something-else',
	]
);

/**
 * If you want to force the enqueue to happen and ignore any conditions,
 * you can pass `true` to the second argument.
 */
Assets::instance()->enqueue(
	[
		'my-style',
		'my-script',
		'something-else',
	],
	true
);

// And here's how you can do it with a specific asset:
Assets::get( 'my-style' )->enqueue( true );
```

#### Enqueuing a whole group

If you have a group of assets that you want to enqueue, you can do so like this:

```php
use Boomshakalaka\StellarWP\Assets\Assets;

// You can do single groups:
Assets::instance()->enqueue_group( 'group-name' );

// or multiple:
Assets::instance()->enqueue_group( [ 'group-one', 'group-two' ] );

// or if you want to force the enqueuing despite conditions:
Assets::instance()->enqueue_group( 'group-name', true );
```

## Working with registered `Assets`

### `exists()`

You can check if an asset has been registered with this library by using the `::exists()` method. This method takes the
the asset slug as an argument and returns a `bool`.

```php
use Boomshakalaka\StellarWP\Assets\Asset;
use Boomshakalaka\StellarWP\Assets\Assets;

Asset::add( 'my-asset', 'js/some-asset.js' )->register();

$assets = Assets::instance();
$assets->exists( 'my-asset' ); // true
$assets->exists( 'another-asset' ); // false
```

### `get()`

You can retrieve an asset object that has been registered by calling the `::get()` method. This method takes the asset
slug as an argument and returns an `Asset` object or `null`.

```php
use Boomshakalaka\StellarWP\Assets\Asset;
use Boomshakalaka\StellarWP\Assets\Assets;

Asset::add( 'my-asset', 'js/some-asset.js' )->register();

$assets    = Assets::instance();
$asset_obj = $assets->get( 'my-asset' );
```

### `remove()`

You can remove an asset from registration and enqueueing by calling the `::remove()` method. This method takes the asset
slug as an argument and returns an `Asset` object or `null`.

```php
use Boomshakalaka\StellarWP\Assets\Asset;
use Boomshakalaka\StellarWP\Assets\Assets;

Asset::add( 'my-asset', 'js/some-asset.js' )->register();

$assets    = Assets::instance();
$assets->get( 'my-asset' )->enqueue();

// This will wp_dequeue_*() the asset and remove it from registration.
$assets->remove( 'my-asset' );
```

## Advanced topics

### Minified files

By default, if you register an asset and `SCRIPT_DEBUG` is not enabled, minified files will dynamically be used if present
in the same directory as the original file. You can, however, specify a different path to look for the minified asset.

The following example will look for `js/some-asset.min.js` in `src/assets/build/` (note the alteration of the file name):

```php
Asset::add( 'my-asset', 'js/some-asset.js' )
	->set_min_path( 'src/assets/build/' )
	->register();
```

### Conditional enqueuing

It is rare that you will want to enqueue an asset on every page load. Luckily, you can specify a condition for when an
asset should be enqueued using the `::set_condition()` method. This method takes a callable that should return a boolean
that represents whether the asset should be enqueued or not.

```php
use Boomshakalaka\StellarWP\Assets\Asset;

// Simple condition.
Asset::add( 'my-asset', 'css/some-asset.css' )
	->set_condition( 'is_single' )
	->register();

// Class-based method.
Asset::add( 'my-asset', 'css/some-asset.css' )
	->set_condition( [ $my_class, 'my_method_that_returns_boolean' ] )
	->register();

// Anonymous function.
Asset::add( 'my-asset', 'css/some-asset.css' )
	->set_condition( static function() {
		// You can do whatever you want here as long as it returns a boolean!
		return is_single() || is_home();
	} )
	->register();
```

### Firing a callback after enqueuing occurs

Sometimes you need to know when enqueuing happens. You can specify a callback to be fired once enequeuing occurs using
the `::call_after_enqueue()` method. Like the `::set_condition()` method, this method takes a callable.

```php
use Boomshakalaka\StellarWP\Assets\Asset;

// Simple function execution.
Asset::add( 'my-asset', 'css/some-asset.css' )
	->call_after_enqueue( 'do_some_global_function' )
	->register();

// Class-based method.
Asset::add( 'my-asset', 'css/some-asset.css' )
	->call_after_enqueue( [ $my_class, 'my_callback' ] )
	->register();

// Anonymous function.
Asset::add( 'my-asset', 'css/some-asset.css' )
	->call_after_enqueue( static function() {
		// Do whatever in here.
	} )
	->register();
```

### Output JS data

If you wish to output JS data to the page after enqueuing (similar to `wp_localize_script()`), you can make use of the
`::add_localize_script()` method. This method takes two arguments: the first is the name of the JS variable to be
output and the second argument is the data to be assigned to the JS variable. You can chain this method as many times
as you wish!

```php
use Boomshakalaka\StellarWP\Assets\Asset;

Asset::add( 'my-asset', 'css/some-asset.css' )
	->add_localize_script(
		'boomshakalaka_animal',
		[
			'animal' => 'cat',
			'color'  => 'orange',
		]
	)
	->add_localize_script(
		'boomshakalaka_food',
		[
			'breakfast' => 'eggs',
			'lunch'     => 'sandwich',
			'dinner'    => 'enchiladas',
		]
	)
	->register();
```

### Output content before/after a JS asset is output

There may be times when you wish to output markup or text immediately before or immediately after outputting the JS
asset. You can make use of `::print_before()` and `::print_after()` to do this.

```php
use Boomshakalaka\StellarWP\Assets\Asset;

Asset::add( 'my-asset', 'js/some-asset.js' )
	->print_before( '<b>Before</b>' )
	->print_after( '<b>After</b>' )
	->register();
```

### Style meta data

Assets support adding meta data to stylesheets. This is done via the `::add_style_data()` method. This method takes two
arguments: the first is the name of the meta data and the second is the value of the meta data. You can chain this and
call this method multiple times.

This works similar to the [`wp_style_add_data()`](https://developer.wordpress.org/reference/functions/wp_style_add_data/) function.

```php
use Boomshakalaka\StellarWP\Assets\Asset;

Asset::add( 'my-asset', 'css/some-asset.css' )
	->add_style_data( 'rtl', true )
	->add_style_data( 'suffix', '.rtl' )
	->register();
```
