<?php
/**
 * Package: vdhoangson\theme
 * Author: vdhoangson
 * Github: https://github.com/vdhoangson/theme
 * Web: vdhoangson.com
 */

namespace vdhoangson\Theme\Exceptions;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class NotFoundException extends NotFoundHttpException {
    public function __construct($theme) {
        parent::__construct("Theme [ $theme ] not found!");
    }
}
