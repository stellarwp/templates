<?php

use StellarWP\Templates\Tests\TemplateTestCase;
use StellarWP\Templates\Utils\Strings;

class StringsTest extends TemplateTestCase {
    /**
	 * @test
	 */
    public function it_should_replace_first_occurrence_of_string()
    {
		$expected = 'I really like eggs, but I like bacon more!';
		$actual   = Strings::replace_first( 'bacon', 'eggs', 'I really like bacon, but I like bacon more!');

		$this->assertSame( $expected, $actual );
    }

	/**
	 * @test
	 */
    public function it_should_replace_last_occurrence_of_string()
    {
		$expected = 'I really like bacon, but I like eggs more!';
		$actual   = Strings::replace_last( 'bacon', 'eggs', 'I really like bacon, but I like bacon more!');

		$this->assertSame( $expected, $actual );
    }

	/**
	 * @test
	 */
	public function it_should_return_string_with_empty_search() {
		$expected = 'I really like bacon, but I like eggs more!';
		$actual   = Strings::replace_first( '', 'eggs', 'I really like bacon, but I like eggs more!');

		$this->assertSame( $expected, $actual );
	}

	/**
	 * @test
	 */
	public function it_should_return_original_string_with_no_first_replacement() {
		$expected = 'I really like bacon, but I like eggs more!';
		$actual   = Strings::replace_first( 'sausage', 'eggs', 'I really like bacon, but I like eggs more!');

		$this->assertSame( $expected, $actual );
	}

	/**
	 * @test
	 */
    public function it_should_return_original_string_with_no_last_replacement()
    {
		$expected = 'I really like bacon, but I like eggs more!';
		$actual   = Strings::replace_last( 'sausage', 'eggs', 'I really like bacon, but I like eggs more!');

		$this->assertSame( $expected, $actual );
    }
}
