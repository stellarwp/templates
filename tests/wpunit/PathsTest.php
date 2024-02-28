<?php declare(strict_types=1);

use StellarWP\Templates\Tests\TemplateTestCase;
use StellarWP\Templates\Utils\Paths;

class PathsTest extends TemplateTestCase {

	/**
	 * @test
	 */
	public function it_should_merge_two_strings_without_intersections(): void {
		$expected = '/app/public/plugin/templates/src/views/product/list.php';
		$actual   = Paths::merge(
			'/app/public/plugin/templates/src/views',
			'/product/list.php'
		);

		$this->assertSame( $expected, $actual );
	}

	/**
	 * @test
	 */
	public function it_should_merge_two_strings_with_intersections(): void {
		$expected = '/app/public/plugin/templates/src/views/product/list.php';
		$actual   = Paths::merge(
			'/app/public/plugin/templates/src/views',
			'src/views/product/list.php'
		);

		$this->assertSame( $expected, $actual );
	}

	/**
	 * @test
	 */
	public function it_should_merge_three_strings_with_intersections(): void {
		$expected = '/app/public/plugin/templates/src/views/product/list.php';
		$actual   = Paths::merge(
			'/app/public/plugin/templates',
			'/templates/src/views',
			'src/views/product/list.php'
		);

		$this->assertSame( $expected, $actual );
	}

	/**
	 * @test
	 */
	public function it_should_merge_three_strings_without_intersections(): void {
		$expected = '/app/public/plugin/templates/src/views/product/list.php';
		$actual   = Paths::merge(
			'/app/public/plugin/templates',
			'/src/views',
			'/product/list.php'
		);

		$this->assertSame( $expected, $actual );
	}

	/**
	 * @test
	 */
	public function it_should_merge_strings_with_escape_sequences(): void {
		$expected = '/app/public/my\ plugin/templates/src/views/product/list.php';
		$actual   = Paths::merge(
			'/app/public/my\ plugin/templates',
			'/src/views',
			'/product/list.php'
		);

		$this->assertSame( $expected, $actual );
	}

	/**
	 * @test
	 */
	public function it_should_merge_path_fragments_without_intersections(): void {
		$expected = '/app/public/plugin/templates/src/views/product/list.php';
		$actual   = Paths::merge(
			[
				'',
				'app',
				'public',
				'plugin',
				'templates',
			],
			[
				'src',
				'views',
				'product',
				'list.php',
			]
		);

		$this->assertSame( $expected, $actual );
	}

	/**
	 * @test
	 */
	public function it_should_merge_path_fragments_with_intersections(): void {
		$expected = '/app/public/plugin/templates/src/views/product/list.php';
		$actual   = Paths::merge(
			[
				'',
				'app',
				'public',
				'plugin',
			],
			[
				'plugin',
				'templates',
				'src',
			],
			[
				'src',
				'views',
				'product',
				'list.php',
			]
		);

		$this->assertSame( $expected, $actual );
	}
}
