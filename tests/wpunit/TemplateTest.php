<?php

namespace StellarWP\Templates;

use InvalidArgumentException;
use StellarWP\Templates\Tests\Dummy_Plugin_Origin;
use StellarWP\Templates\Tests\TemplateTestCase;

include_once codecept_data_dir( 'classes/Dummy_Plugin_Origin.php' );

class TemplateTest extends TemplateTestCase {

	/**
	 * It should allow setting a number of values at the same time
	 *
	 * @test
	 */
	public function should_allow_setting_a_number_of_values_at_the_same_time() {
		$template = new Template();

		$template->set_values( [
			'twenty-three' => '23',
			'eighty-nine'  => 89,
			'an_array'     => [ 'key' => 2389 ],
			'an_object'    => (object) [ 'key' => 89 ],
			'a_null_value' => null,
		] );

		$this->assertEquals( '23', $template->get( 'twenty-three' ) );
		$this->assertEquals( 89, $template->get( 'eighty-nine' ) );
		$this->assertEquals( [ 'key' => 2389 ], $template->get( 'an_array' ) );
		$this->assertEquals( (object) [ 'key' => 89 ], $template->get( 'an_object' ) );
		$this->assertEquals( null, $template->get( 'a_null_value' ) );
	}

	/**
	 * It should allow setting contextual values without overriding the primary values
	 *
	 * @test
	 */
	public function should_allow_setting_contextual_values_without_overriding_the_primary_values() {
		$template      = new Template();
		$global_set    = [
			'twenty-three' => '23',
			'eighty-nine'  => 89,
		];
		$global_values = $global_set;

		$template->set_values( $global_set, false );

		$this->assertEquals( $global_values, $template->get_global_values() );
		$this->assertEquals( [], $template->get_local_values() );
		$this->assertEquals( $global_values, $template->get_values() );

		$local_set = [
			'eighty-nine' => 2389,
			'another_var' => 'another_value',
		];
		$template->set_values( $local_set );

		$this->assertEquals( $global_values, $template->get_global_values() );
		$this->assertEquals( $local_set, $template->get_local_values() );
		$this->assertEquals( array_merge( $global_values, $local_set ), $template->get_values() );
	}

	/**
	 * @test
	 */
	public function should_include_entry_points_on_template_html() {
		$hook_prefix = Config::get_hook_prefix();
		$plugin   = new Dummy_Plugin_Origin();
		$template = new Template();
		$template->set_template_origin( $plugin );

		add_action( "stellarwp/templates/{$hook_prefix}/template_entry_point:dummy/dummy-template:after_container_open", function () {
			echo '%%after_container_open%%';
		} );
		add_action( "stellarwp/templates/{$hook_prefix}/template_entry_point:dummy/dummy-template:before_container_close", function () {
			echo '%%before_container_close%%';
		} );

		$html = $template->template( 'dummy-template', [], false );

		$this->assertStringContainsString( '<div class="test">%%after_container_open%%', $html );
		$this->assertStringEndsWith( '%%before_container_close%%</div>', $html );
	}

	/**
	 * @test
	 */
	public function should_include_custom_entry_points_on_template_html() {
		$hook_prefix = Config::get_hook_prefix();
		$plugin   = new Dummy_Plugin_Origin();
		$template = new Template();
		$template->set_template_origin( $plugin );

		add_action( "stellarwp/templates/{$hook_prefix}/template_entry_point::custom_entry_point", function () {
			echo '%%custom_entry_point%%';
		} );

		$customer_entry_point_html = $template->do_entry_point( 'custom_entry_point', false );
		$last_tag_html             = '</div>';
		$html                      = $template->template( 'dummy-template', [], false );
		$html                      = Utils\Strings::replace_last( $last_tag_html, $last_tag_html . $customer_entry_point_html, $html );

		$this->assertStringContainsString( '</div>%%custom_entry_point%%', $html );
	}

	/**
	 * @test
	 */
	public function should_not_include_with_invalid_html() {
		$hook_prefix = Config::get_hook_prefix();
		$plugin   = new Dummy_Plugin_Origin();
		$template = new Template();
		$template->set_template_origin( $plugin );

		add_action( "stellarwp/templates/{$hook_prefix}/template_entry_point:dummy/dummy-invalid-template-01:after_container_open", function () {
			echo '%%after_container_open%%';
		} );
		add_action( "stellarwp/templates/{$hook_prefix}/template_entry_point:dummy/dummy-invalid-template-01:before_container_close", function () {
			echo '%%before_container_close%%';
		} );
		$html = $template->template( 'dummy-invalid-template-01', [], false );

		$this->assertStringNotContainsString( '%%after_container_open%%', $html );
		$this->assertStringEndsNotWith( '%%before_container_close%%', $html );
	}

	/**
	 * @test
	 */
	public function should_not_include_with_invalid_html_02() {
		$hook_prefix = Config::get_hook_prefix();
		$plugin   = new Dummy_Plugin_Origin();
		$template = new Template();
		$template->set_template_origin( $plugin );

		add_action( "stellarwp/templates/{$hook_prefix}/template_entry_point:dummy/dummy-invalid-template-02:after_container_open", function () {
			echo '%%after_container_open%%';
		} );
		add_action( "stellarwp/templates/{$hook_prefix}/template_entry_point:dummy/dummy-invalid-template-02:before_container_close", function () {
			echo '%%before_container_close%%';
		} );
		$html = $template->template( 'dummy-invalid-template-02', [], false );

		$this->assertStringNotContainsString( '%%after_container_open%%', $html );
		$this->assertStringEndsNotWith( '%%before_container_close%%', $html );
	}

	/**
	 * @test
	 */
	public function should_not_include_with_invalid_html_03() {
		$hook_prefix = Config::get_hook_prefix();
		$plugin   = new Dummy_Plugin_Origin();
		$template = new Template();
		$template->set_template_origin( $plugin );

		add_action( "stellarwp/templates/{$hook_prefix}/template_entry_point:dummy/dummy-invalid-template-03:after_container_open", function () {
			echo '%%after_container_open%%';
		} );
		add_action( "stellarwp/templates/{$hook_prefix}/template_entry_point:dummy/dummy-invalid-template-03:before_container_close", function () {
			echo '%%before_container_close%%';
		} );
		$html = $template->template( 'dummy-invalid-template-03', [], false );

		$this->assertStringNotContainsString( '%%after_container_open%%', $html );
		$this->assertStringEndsNotWith( '%%before_container_close%%', $html );
	}

	/**
	 * @test
	 */
	public function should_not_include_with_invalid_html_04() {
		$hook_prefix = Config::get_hook_prefix();
		$plugin   = new Dummy_Plugin_Origin();
		$template = new Template();
		$template->set_template_origin( $plugin );

		add_action( "stellarwp/templates/{$hook_prefix}/template_entry_point:dummy/dummy-invalid-template-04:after_container_open", function () {
			echo '%%after_container_open%%';
		} );
		add_action( "stellarwp/templates/{$hook_prefix}/template_entry_point:dummy/dummy-invalid-template-04:before_container_close", function () {
			echo '%%before_container_close%%';
		} );
		$html = $template->template( 'dummy-invalid-template-04', [], false );

		$this->assertStringNotContainsString( '%%after_container_open%%', $html );
		$this->assertStringEndsNotWith( '%%before_container_close%%', $html );
	}

	/**
	 * @test
	 */
	public function should_include_with_valid_html() {
		$hook_prefix = Config::get_hook_prefix();
		$plugin   = new Dummy_Plugin_Origin();
		$template = new Template();
		$template->set_template_origin( $plugin );

		add_action( "stellarwp/templates/{$hook_prefix}/template_entry_point:dummy/dummy-valid-template-01:after_container_open", function () {
			echo '%%after_container_open%%';
		} );
		add_action( "stellarwp/templates/{$hook_prefix}/template_entry_point:dummy/dummy-valid-template-01:before_container_close", function () {
			echo '%%before_container_close%%';
		} );
		$html = $template->template( 'dummy-valid-template-01', [], false );

		$this->assertStringContainsString( '<a href="https://tri.be" class="test" target="_blank" title="Test Link" data-link="automated-tests">%%after_container_open%%', $html );
		$this->assertStringEndsWith( '%%before_container_close%%</a>', $html );

	}

	/**
	 * @test
	 */
	public function should_include_with_valid_html_02() {
		$hook_prefix = Config::get_hook_prefix();
		$plugin   = new Dummy_Plugin_Origin();
		$template = new Template();
		$template->set_template_origin( $plugin );

		add_action( "stellarwp/templates/{$hook_prefix}/template_entry_point:dummy/dummy-valid-template-02:after_container_open", function () {
			echo '%%after_container_open%%';
		} );
		add_action( "stellarwp/templates/{$hook_prefix}/template_entry_point:dummy/dummy-valid-template-02:before_container_close", function () {
			echo '%%before_container_close%%';
		} );
		$html = $template->template( 'dummy-valid-template-02', [], false );

		$replaced_html = str_replace( array( "\n", "\r" ), '', $html );
		$this->assertStringContainsString( 'data-view-breakpoint-pointer="99ccf293-c1b0-41b2-a1c8-033776ac6f10">%%after_container_open%%', $replaced_html );
		$this->assertStringEndsWith( '%%before_container_close%%</div>', $html );
	}

	/**
	 * @test
	 */
	public function should_include_with_valid_html_03() {
		$hook_prefix = Config::get_hook_prefix();
		$plugin   = new Dummy_Plugin_Origin();
		$template = new Template();
		$template->set_template_origin( $plugin );

		add_action( "stellarwp/templates/{$hook_prefix}/template_entry_point:dummy/dummy-valid-template-03:after_container_open", function () {
			echo '%%after_container_open%%';
		} );
		add_action( "stellarwp/templates/{$hook_prefix}/template_entry_point:dummy/dummy-valid-template-03:before_container_close", function () {
			echo '%%before_container_close%%';
		} );
		$html = $template->template( 'dummy-valid-template-03', [], false );

		$replaced_html = str_replace( array( "\n", "\r" ), '', $html );
		$this->assertStringContainsString( '<div class="tribe-view tribe-view--base tribe-view--dummy">%%after_container_open%%', $replaced_html );
		$this->assertStringEndsWith( '%%before_container_close%%</div>', $html );
	}

	/**
	 * @test
	 */
	public function should_not_include_with_entry_points_disabled() {
		$hook_prefix = Config::get_hook_prefix();
		$plugin   = new Dummy_Plugin_Origin();
		$template = new Template();
		$template->set_template_origin( $plugin );

		add_action( "stellarwp/templates/{$hook_prefix}/template_entry_point_is_enabled", '__return_false' );

		add_action( "stellarwp/templates/{$hook_prefix}/template_entry_point:dummy/dummy-template:after_container_open", function () {
			echo '%%after_container_open%%';
		} );
		add_action( "stellarwp/templates/{$hook_prefix}/template_entry_point:dummy/dummy-template:before_container_close", function () {
			echo '%%before_container_close%%';
		} );

		$html = $template->template( 'dummy-template', [], false );

		$this->assertStringNotContainsString( '<div class="test">%%after_container_open%%', $html );
		$this->assertStringEndsNotWith( '%%before_container_close%%</div>', $html );
	}

	/**
	 * It should allow using aliases to rewrite path fragments
	 *
	 * Here we simulate the instance where the new version of the templates (v3_1) changed to use `templates/v3_1` where
	 * the old version used `views/v3`.
	 * Furthermore, this test will check if the DIRECTORY_SEPARATOR normalization will work.
	 *
	 * @test
	 */
	public function should_allow_using_aliases_to_rewrite_path_fragments() {
		$hook_prefix = Config::get_hook_prefix();
		$template = new class extends Template {
			protected array $template_base_path = [ __DIR__ . '/test-plugin' ];
			protected array $folder = [ 'src', 'templates', 'v3_1' ];
			// Note: the aliases use Windows DIRECTORY_SEPARATOR as the tests will likely run on *nix machines.
			protected array $aliases = [ 'templates\v3_1' => 'views\v3' ];
		};

		$assert = function ( array $folders ) {
			$this->assertEquals( __DIR__ . '/test-plugin/src/templates/v3_1', $folders['plugin']['path'] );
			$this->assertEquals( __DIR__ . '/test-plugin/src/views/v3', $folders['plugin_views\v3']['path'] );
		};
		add_filter( "stellarwp/templates/{$hook_prefix}/template_path_list", $assert, PHP_INT_MAX );

		// What we look for is not really relevant: the assertions happens before.
		$template->get_template_file( [ 'foo', 'bar', 'component' ] );
	}

	/**
	 * It should set template folder.
	 *
	 * @test
	 */
	public function it_should_set_template_folder_with_string() {
		$template = new Template();
		$template->set_template_folder( 'src/templates' );

		$this->assertCount( 2, $template->get_template_folder() );
		$this->assertSame( [ 'src', 'templates' ], $template->get_template_folder() );
	}

	/**
	 * It should default to already set class folder.
	 *
	 * @test
	 */
	public function it_should_default_to_extended_class_definition() {
		$template = new class extends Template {
			protected array $folder = [ 'src', 'templates', 'etc'];
		};

		$template->set_template_folder();

		$this->assertIsArray( $template->get_template_folder() );
		$this->assertSame( [ 'src', 'templates', 'etc'], $template->get_template_folder() );
	}

	/**
	 * It should correctly set the template folder lookup.
	 *
	 * @test
	 */
	public function it_should_correctly_set_the_template_folder_lookup() {
		$template = new Template();

		$instance = $template->set_template_folder_lookup();

		$this->assertTrue( $instance->get_template_folder_lookup() );
		$this->assertInstanceOf( Template::class, $template->set_template_folder_lookup() );

		$template->set_template_folder_lookup( false );

		$this->assertFalse( $template->get_template_folder_lookup() );
	}
}
