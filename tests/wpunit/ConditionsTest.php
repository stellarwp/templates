<?php

use StellarWP\Templates\Tests\TemplateTestCase;
use StellarWP\Templates\Utils\Conditions;

class ConditionsTest extends TemplateTestCase {
    /**
	 * @test
	 */
    public function it_returns_true_with_bool()
    {
		$this->assertTrue( Conditions::is_truthy( true ) );
    }

	/**
	 * @test
	 */
    public function it_returns_true_with_valid_string()
    {
		$this->assertTrue( Conditions::is_truthy( 'true' ) );
    }

	/**
	 * @test
	 */
    public function it_returns_false_with_invalid_string()
    {
		$this->assertFalse( Conditions::is_truthy( 'false' ) );
    }

	/**
	 * @test
	 */
    public function it_returns_true_with_truthy_int()
    {
		$this->assertTrue( Conditions::is_truthy( 1 ) );
    }

	/**
	 * @test
	 */
    public function it_returns_false_with_falsy_int()
    {
		$this->assertFalse( Conditions::is_truthy( 0 ) );
    }
}
