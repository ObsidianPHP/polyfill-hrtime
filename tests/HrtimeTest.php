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
 * @requires extension hrtime
 * @requires extension uv
 */
class HrtimeTest extends TestCase {
    function providerTestFunctions() {
        return array(
            array('\\Obsidian\\Polyfill\\Hrtime\\hrtime_ext_uv'),
            array('\\Obsidian\\Polyfill\\Hrtime\\hrtime_ext_hrtime'),
            array('\\Obsidian\\Polyfill\\Hrtime\\hrtime_fallback')
        );
    }
    
    /**
     * @dataProvider providerTestFunctions
     * @param callable  $function
     */
    function testHrtimeAsNumber(callable $function) {
        $number = $function(true);
        $this->assertIsInt($number);
        
        $number2 = $function(true);
        $this->assertIsInt($number2);
        
        $this->assertGreaterThan($number, $number2);
    }
    
    /**
     * @dataProvider providerTestFunctions
     * @param callable  $function
     */
    function testHrtimeAsArray(callable $function) {
        $arr = $function(false);
        $this->assertIsArray($arr);
        $this->assertCount(2, $arr);
        
        $arr2 = $function(false);
        $this->assertIsArray($arr2);
        $this->assertCount(2, $arr2);
        
        $this->assertSame($arr[0], $arr2[0]);
        $this->assertGreaterThan($arr[1], $arr2[1]);
    }
}
