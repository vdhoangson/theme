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

    /**
     * Title
     * 
     * @var string
     */
    public $title;

    /**
     * Subtitle
     * 
     * @var string
     */
    public $subtitle;

    /**
     * Icon
     * 
     * @var string
     */
    public $icon;

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
     * Set title
     * 
     * @param string $title
     */
    public function setTitle($title){
        $this->title = $title;
    }

    /**
     * Set subtitle
     * 
     * @param string $subtitle
     */
    public function setSubTitle($subtitle){
        $this->subtitle = $subtitle;
    }

    /**
     * Set icon
     * 
     * @param string $icon
     */
    public function setIcon($icon){
        $this->icon = $icon;
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

        $title = $this->title;
        $subtitle = $this->subtitle;
        $icon = $this->icon;

        return view('layouts.breadcrumb', compact('breadcrumbs', 'title', 'subtitle', 'icon'))->render();
    }
}
