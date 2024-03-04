<?php declare(strict_types=1);

use StellarWP\Templates\Tests\TemplateTestCase;
use StellarWP\Templates\Utils\Strings;

class StringsTest extends TemplateTestCase {
	/**
	 * @test
	 */
	public function it_should_replace_first_occurrence_of_string(): void {
		$expected = 'I really like eggs, but I like bacon more!';
		$actual   = Strings::replace_first( 'bacon', 'eggs', 'I really like bacon, but I like bacon more!' );

		$this->assertSame( $expected, $actual );
	}

	/**
	 * @test
	 */
	public function it_should_replace_last_occurrence_of_string(): void {
		$expected = 'I really like bacon, but I like eggs more!';
		$actual   = Strings::replace_last( 'bacon', 'eggs', 'I really like bacon, but I like bacon more!' );

		$this->assertSame( $expected, $actual );
	}

	/**
	 * @test
	 */
	public function it_should_return_string_with_empty_search(): void {
		$expected = 'I really like bacon, but I like eggs more!';
		$actual   = Strings::replace_first( '', 'eggs', 'I really like bacon, but I like eggs more!' );

		$this->assertSame( $expected, $actual );
	}

	/**
	 * @test
	 */
	public function it_should_return_original_string_with_no_first_replacement(): void {
		$expected = 'I really like bacon, but I like eggs more!';
		$actual   = Strings::replace_first( 'sausage', 'eggs', 'I really like bacon, but I like eggs more!' );

		$this->assertSame( $expected, $actual );
	}

	/**
	 * @test
	 */
	public function it_should_return_original_string_with_no_last_replacement(): void {
		$expected = 'I really like bacon, but I like eggs more!';
		$actual   = Strings::replace_last( 'sausage', 'eggs', 'I really like bacon, but I like eggs more!' );

		$this->assertSame( $expected, $actual );
	}

	/**
	 * @test
	 */
	public function it_should_return_successfully_replacing_first_with_empty_string(): void {
		$expected = '';
		$actual   = Strings::replace_first( 'bacon', 'eggs', '' );

		$this->assertSame( $expected, $actual );
	}

	/**
	 * @test
	 */
	public function it_should_return_successfully_replacing_last_with_empty_string(): void {
		$expected = '';
		$actual   = Strings::replace_last( 'bacon', 'eggs', '' );

		$this->assertSame( $expected, $actual );
	}
}
