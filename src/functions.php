<?php
/**
 * Obsidian
 * Copyright 2020 ObsidianPHP, All Rights Reserved
 *
 * License: https://github.com/ObsidianPHP/polyfill-hrtime/blob/master/LICENSE
 * @noinspection PhpComposerExtensionStubsInspection
 */

namespace Obsidian\Polyfill\Hrtime;

use HrTime\StopWatch;
use HrTime\Unit;

function internal_conversion_nanoseconds_to_seconds(int $nanoseconds): array {
    $seconds = \intdiv($nanoseconds, 1e9);
    $nanoseconds -= (int) ($seconds * 1e9);
    
    return array($seconds, $nanoseconds);
}

if(function_exists('uv_hrtime')) {
    function hrtime_ext_uv(bool $get_as_number = false) {
        $nanoseconds = \uv_hrtime();
        
        if($get_as_number) {
            return $nanoseconds;
        }
        
        return internal_conversion_nanoseconds_to_seconds($nanoseconds);
    }
}

if(extension_loaded('hrtime')) {
    function hrtime_ext_hrtime(bool $get_as_number = false) {
        /** @var StopWatch  $timer */
        static $timer;
        
        if(!$timer) {
            $timer = new StopWatch();
            $timer->start();
        }
        
        $timer->stop();
        $timer->start();
        
        $nanoseconds = (int) $timer->getElapsedTime(Unit::NANOSECOND);
        
        if($get_as_number) {
            return $nanoseconds;
        }
        
        return internal_conversion_nanoseconds_to_seconds($nanoseconds);
    }
}

function hrtime_fallback(bool $get_as_number = false) {
    /** @var int  $timer */
    static $timer;
    
    if(!$timer) {
        $timer = (int) (\microtime(true) * 1e6);
    }
    
    $now = (int) (\microtime(true) * 1e6);
    $nanoseconds = (int) ($now - $timer);
    
    if($get_as_number) {
        return $nanoseconds;
    }
    
    return internal_conversion_nanoseconds_to_seconds($nanoseconds);
}