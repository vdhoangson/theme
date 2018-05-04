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
