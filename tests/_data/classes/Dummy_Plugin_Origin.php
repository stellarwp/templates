<?php
/**
 * A dummy implementation of Dummy Template Origin
 */

namespace StellarWP\Templates\Tests;

/**
 * Class Dummy_Plugin_Origin
 */
class Dummy_Plugin_Origin {

	const VERSION = '1.0.0';

	public $plugin_file;
	public $pluginPath;
	public $plugin_path;

	public $template_namespace = 'dummy';

	public function __construct() {
		$this->plugin_file = __FILE__;
		$this->pluginPath = $this->plugin_path = trailingslashit( dirname( dirname( $this->plugin_file ) ) . '/plugin-views/templates/' );
	}
}
