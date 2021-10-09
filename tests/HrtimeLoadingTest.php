<?php
/**
 * Obsidian
 * Copyright 2020 ObsidianPHP, All Rights Reserved
 *
 * License: https://github.com/ObsidianPHP/polyfill-hrtime/blob/master/LICENSE
 */

namespace Obsidian\Polyfill\Hrtime\Tests;

use PHPUnit\Framework\TestCase;

class HrtimeLoadingTest extends TestCase {
    function testLoading() {
        if(\PHP_VERSION_ID >= 70300) {
            $this->markTestSkipped('Test unnecessary, hrtime natively available');
        }
        
        \xdebug_start_function_monitor(array(
            'Obsidian\\Polyfill\\Hrtime\\hrtime_ext_uv',
            'Obsidian\\Polyfill\\Hrtime\\hrtime_ext_hrtime',
            'Obsidian\\Polyfill\\Hrtime\\hrtime_fallback'
        ));
        
        \hrtime();
        
        [ [ 'function' => $function ] ] = \xdebug_get_monitored_functions();
        \xdebug_stop_function_monitor();
        
        switch(\getenv('EXT_INSTALL', true)) {
            case 'hrtime, uv-beta':
            case 'uv-beta':
                $fun = 'Obsidian\\Polyfill\\Hrtime\\hrtime_ext_uv';
            break;
            case 'hrtime':
                $fun = 'Obsidian\\Polyfill\\Hrtime\\hrtime_ext_hrtime';
            break;
            default:
                $fun = 'Obsidian\\Polyfill\\Hrtime\\hrtime_fallback';
        }
        
        $this->assertSame($fun, $function);
    }
}
