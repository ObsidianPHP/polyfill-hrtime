<?php
/**
 * Obsidian
 * Copyright 2020 ObsidianPHP, All Rights Reserved
 *
 * License: https://github.com/ObsidianPHP/polyfill-hrtime/blob/master/LICENSE
 */

if(!function_exists('\\Obsidian\\Polyfill\\Hrtime\\hrtime_fallback')) {
    require 'functions.php';
}

if(function_exists('hrtime')) {
    return;
}

use function Obsidian\Polyfill\Hrtime\hrtime_ext_uv;
use function Obsidian\Polyfill\Hrtime\hrtime_ext_hrtime;
use function Obsidian\Polyfill\Hrtime\hrtime_fallback;

if(function_exists('uv_hrtime')) {
    function hrtime(bool $get_as_number = false) {
        return hrtime_ext_uv($get_as_number);
    }
} elseif(extension_loaded('hrtime')) {
    function hrtime(bool $get_as_number = false) {
        return hrtime_ext_hrtime($get_as_number);
    }
    
    hrtime();
} else {
    function hrtime(bool $get_as_number = false) {
        return hrtime_fallback($get_as_number);
    }
    
    hrtime();
}
