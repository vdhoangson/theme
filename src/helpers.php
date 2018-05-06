<?php
/**
 * Package: vdhoangson\theme
 * Author: vdhoangson
 * Github: https://github.com/vdhoangson/theme
 * Web: vdhoangson.com
 */

if (!function_exists('vassets')) {
    /**
     * Generate an asset path.
     *
     * @param string $path
     * @param bool   $secure
     *
     * @return string
     */
    function vassets($path, $secure = null) {
        return Theme::assets($path, $secure);
    }
}

if (!function_exists('vtrans')) {
    /**
     * Translate.
     *
     * @param string $path
     *
     * @return string
     */
    function vtrans($param) {
        return Theme::language($param);
    }
}
