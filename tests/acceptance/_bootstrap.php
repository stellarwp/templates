<?php

require_once WP_CONTENT_DIR . '/plugins/templates/vendor/autoload.php';

use StellarWP\Templates\Config;

add_action( 'init', function() {
	Config::set_hook_prefix( 'bork' );
	Config::set_path( WP_CONTENT_DIR . '/plugins/templates/' );
} );
