# StellarWP Templates

[![Tests](https://github.com/stellarwp/templates/workflows/Tests/badge.svg)](https://github.com/stellarwp/templates/actions?query=branch%3Amain) [![Static Analysis](https://github.com/stellarwp/templates/actions/workflows/static-analysis.yml/badge.svg)](https://github.com/stellarwp/templates/actions/workflows/static-analysis.yml)

A library for including templates in a WordPress plugin that users can choose to override within a specific directory of a theme or child theme.

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

Once you've configured the library, extend the [Template](src/Templates/Template.php) class to define the location of templates for your plugin.

With your class extension, manually define where the base folder for templates is in the class' `plugin_path` property. Here is an example of what that class may look like:
```php
use Boomshakalaka\StellarWP\Templates\Template;

class My_Custom_Template extends Template {

	/**
	 * Defines the base path for the templates.
	 *
	 * @since 1.0.0
	 */
	protected array $template_base_path = [ PATH_TO_YOUR_PROJECT_ROOT ];

}
```
Once you've done that, you can instantiate your class instance and define a few other settings:
```php
$template = new My_Custom_Template();

// Set the folder within your plugin where templates are stored.
$template->set_template_folder( 'src/views/products' );
// Should users be able to override templates in their theme?
$template->set_template_folder_lookup( true );
```
With the template class set up, calling `$template->template('template-file')` will include the template looking for it in:
1. child theme
```
../themes/child/boom-shakalaka/products/template-file
```
1. parent theme
```
../themes/parent/boom-shakalaka/products/template-file
```
1. the plugin
```
../plugins/your-plugin/src/views/products/template-file
```
