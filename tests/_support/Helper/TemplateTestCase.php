<?php

namespace StellarWP\Templates\Tests;

use StellarWP\Templates\Config;

class TemplateTestCase extends \Codeception\TestCase\WPTestCase {
	protected $backupGlobals = false;

	public function setUp() {
		// before
		parent::setUp();

		Config::set_hook_prefix( 'bork' );
		Config::set_path( dirname( dirname( __DIR__ ) ) . '/_data/plugin-views/templates/' );
	}
}

