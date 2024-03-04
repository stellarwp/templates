<?php declare(strict_types=1);

use StellarWP\Templates\Tests\TemplateTestCase;
use StellarWP\Templates\Utils\Conditions;

class ConditionsTest extends TemplateTestCase {
	/**
	 * @test
	 */
	public function it_returns_true_with_bool(): void {
		$this->assertTrue( Conditions::is_truthy( true ) );
	}

	/**
	 * @test
	 */
	public function it_returns_true_with_valid_string(): void {
		$this->assertTrue( Conditions::is_truthy( 'true' ) );
	}

	/**
	 * @test
	 */
	public function it_returns_false_with_invalid_string(): void {
		$this->assertFalse( Conditions::is_truthy( 'false' ) );
	}

	/**
	 * @test
	 */
	public function it_returns_true_with_truthy_int(): void {
		$this->assertTrue( Conditions::is_truthy( 1 ) );
	}

	/**
	 * @test
	 */
	public function it_returns_false_with_falsy_int(): void {
		$this->assertFalse( Conditions::is_truthy( 0 ) );
	}
}
