<?php
/**
 * Obsidian
 * Copyright 2020 ObsidianPHP, All Rights Reserved
 *
 * License: https://github.com/ObsidianPHP/polyfill-hrtime/blob/master/LICENSE
 */

namespace Obsidian\Polyfill\Hrtime\Tests;

use PHPUnit\Framework\TestCase;

/**
 * @requires hrtime
 * @requires uv
 */
class HrtimeTest extends TestCase {
	function providerTestFunctions() {
		return array(
			array('\\Obsidian\\Polyfill\\Hrtime\\hrtime_ext_uv', false),
			array('\\Obsidian\\Polyfill\\Hrtime\\hrtime_ext_hrtime', false),
			array('\\Obsidian\\Polyfill\\Hrtime\\hrtime_fallback', true)
		);
	}
	
	/**
	 * @dataProvider providerTestFunctions
	 * @param callable  $function
	 * @param bool      $sleep
	 */
	function testHrtimeAsNumber(callable $function, bool $sleep) {
		$number = $function(true);
		$this->assertIsInt($number);
		
		if($sleep) {
			\sleep(1);
		}
		
		$number2 = $function(true);
		$this->assertIsInt($number2);
		
		$this->assertGreaterThan($number, $number2);
	}
	
	/**
	 * @dataProvider providerTestFunctions
	 * @param callable  $function
	 * @param bool      $sleep
	 */
	function testHrtimeAsArray(callable $function, bool $sleep) {
		$arr = $function(false);
		$this->assertIsArray($arr);
		$this->assertCount(2, $arr);
		
		if($sleep) {
			\sleep(1);
		}
		
		$arr2 = $function(false);
		$this->assertIsArray($arr2);
		$this->assertCount(2, $arr2);
		
		$this->assertSame($arr[0], $arr2[0]);
		$this->assertGreaterThan($arr[1], $arr2[1]);
	}
}
