<?php declare(strict_types=1);

namespace StellarWP\Templates;

use StellarWP\Templates\Tests\TemplateTestCase;

class ConfigTest extends TemplateTestCase {
	public function setUp(): void {
		// before
		parent::setUp();
	}

	public function tearDown(): void {
		parent::tearDown();
		Config::reset();
	}

	/**
	 * @test
	 */
	public function should_set_hook_prefix(): void {
		Config::set_hook_prefix( 'bork' );

		$this->assertEquals( 'bork', Config::get_hook_prefix() );
	}

	/**
	 * @test
	 */
	public function should_set_path(): void {
		Config::set_path( dirname( dirname( __DIR__ ) ) );

		$this->assertEquals( dirname( dirname( __DIR__ ) ) . '/', Config::get_path() );
	}

	/**
	 * @test
	 */
	public function should_reset(): void {
		Config::set_hook_prefix( 'bork' );
		Config::set_path( dirname( dirname( __DIR__ ) ) );
		Config::reset();

		try {
			Config::get_hook_prefix();
		} catch ( \Exception $e ) {
			$this->assertInstanceOf( \RuntimeException::class, $e );
		}

		try {
			Config::get_path();
		} catch ( \Exception $e ) {
			$this->assertInstanceOf( \RuntimeException::class, $e );
		}
	}
}
