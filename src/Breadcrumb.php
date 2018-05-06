<?php 
/**
 * Package: vdhoangson\theme
 * Author: vdhoangson
 * Github: https://github.com/vdhoangson/theme
 * Web: vdhoangson.com
 */

namespace vdhoangson\Theme;

use vdhoangson\Theme\Contracts\BreadcrumbContract;
use URL;

class Breadcrumb implements BreadcrumbContract {
    /**
     * Crumbs
     *
     * @var array
     */
    public $crumbs = [];

    public function __construct(){}

    /**
     * Add breadcrumb to array.
     *
     * @param  mixed  $label
     * @param  string $url
     * @return Breadcrumb
     */
    public function add($label, $url='') {
        if (is_array($label)) {
            if (count($label) > 0) {
                foreach ($label as $crumb) {
                    $defaults = [
                        'label' => '',
                        'url'   => ''
                    ];
                    $crumb = array_merge($defaults, $crumb);
                    $this->add($crumb['label'], $crumb['url']);
                }
            }
        } else {
            $label = trim(strip_tags($label, '<i><b><strong>'));
            if (! preg_match('|^http(s)?|', $url)) {
                $url = URL::to($url);
            }
            $this->crumbs[] = ['label' => $label, 'url' => $url];
        }

        return $this;
    }

    /**
     * Get crumbs.
     *
     * @return array
     */
    public function getCrumbs() {
        return $this->crumbs;
    }

    /**
     * Render breadcrumbs.
     *
     * @return string
     */
    public function render() {
        $breadcrumbs = $this->getCrumbs();

        return view('layouts.breadcrumb', compact('breadcrumbs'))->render();
    }
}
