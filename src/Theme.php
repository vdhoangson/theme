<?php
/**
 * Package: vdhoangson\theme
 * Author: vdhoangson
 * Github: https://github.com/vdhoangson/theme
 * Web: vdhoangson.com
 */

namespace vdhoangson\Theme;

use Illuminate\Config\Repository;
use Illuminate\Container\Container;
use Illuminate\View\ViewFinderInterface;
use Noodlehaus\Config;
use vdhoangson\Theme\Contracts\ThemeContract;
use vdhoangson\Theme\Exceptions\NotFoundException;

class Theme implements ThemeContract {
    /**
     * Config Theme Path.
     *
     * @var string
     */
    protected $path;

    /**
     * All Theme Information.
     *
     * @var collection
     */
    protected $themes;

    /**
     * Application Container.
     *
     * @var \Illuminate\Container\Container
     */
    protected $app;

    /**
     * Blade View Finder.
     *
     * @var \Illuminate\View\ViewFinderInterface
     */
    protected $finder;

    /**
     * Config.
     *
     * @var Repository
     */
    protected $config;

    /**
     * Current Active Theme.
     *
     * @var string|collection
     */
    private $activeTheme = null;

     /**
     * Place Active Theme.
     *
     * @var string
     */
    private $place;

    /**
     * Theme constructor.
     *
     * @param Container           $app
     * @param ViewFinderInterface $finder
     * @param Repository          $config
     */
    public function __construct(Container $app, ViewFinderInterface $finder, Repository $config) {
        $this->app = $app;
        $this->finder = $finder;
        $this->config = $config;
        $this->path = $this->config['theme.path'];

        $this->scanThemeFolder();
    }

    /**
     * Scan all themes in config.theme.path.
     *
     * @return void
     */
    private function scanThemeFolder() {
        $themesPlace = glob($this->path.'/*', GLOB_ONLYDIR);
        
        $themes = [];
        foreach ($themesPlace as $placePath) {
            $themesDir = glob($placePath.'/*', GLOB_ONLYDIR);
            $exp = explode('/', $placePath);
            
            $folder = array_pop($exp);
            
            foreach($themesDir as $themePath){
                $configPath = $themePath . '/' . $this->config['theme.config.name'];
                $logPath = $themePath . '/' . $this->config['theme.config.changelog'];

                if (file_exists($configPath)) {
                    $themeConfig = Config::load($configPath);
                    $themeConfig['changelog'] = Config::load($logPath)->all();
                    $themeConfig['path'] = $themePath;

                    if ($themeConfig->has('name')) {
                        $themes[$folder][$themeConfig->get('name')] = $themeConfig;
                    }
                }
            }
        }
        
        $this->themes = $themes;
    }

    /**
     * Set current theme.
     *
     * @param string $theme
     *
     * @return void
     */
    public function setTheme($place, $themeName) {
        $this->place = $place;

        if (!$this->exists($themeName)) {
            throw new NotFoundException($themeName);
        }

        $this->loadTheme($themeName);
        $this->activeTheme = $themeName;
    }

    /**
     * Returns current theme or particular theme information.
     *
     * @param string $theme
     * @param bool   $collection
     *
     * @return array|null|ThemeInfo
     */
    public function getTheme($theme = null, $collection = false) {
        if (is_null($theme) || !$this->exists($theme)) {
            return !$collection ? $this->themes[$this->place][$this->activeTheme]->all() : $this->themes[$this->place][$this->activeTheme];
        }

        return !$collection ? $this->themes[$this->place][$theme]->all() : $this->themes[$this->place][$theme];
    }

    /**
     * Check theme if exists.
     *
     * @param string $theme
     *
     * @return bool
     */
    public function exists($theme) {
        return isset($this->themes[$this->place]) && isset($this->themes[$this->place][$theme]) ? true : false;
    }

    /**
     * Get particular theme all information.
     *
     * @param string $themeName
     *
     * @return null|ThemeInfo
     */
    public function themeInfo($themeName) {
        return isset($this->themes[$this->place][$themeName]) ? $this->themes[$this->place][$themeName] : null;
    }

    /**
     * Get current active theme name only or themeinfo collection.
     *
     * @param bool $collection
     *
     * @return null|ThemeInfo
     */
    public function currentTheme($collection = false)
    {
        return !$collection ? $this->activeTheme : $this->themeInfo($this->activeTheme);
    }

    /**
     * Get all theme information.
     *
     * @return array
     */
    public function all() {
        return $this->themes;
    }

    /**
     * Find asset file for theme asset.
     *
     * @param string    $path
     * @param null|bool $secure
     *
     * @return string
     */
    public function assets($path, $secure = null) {
        
        $themeName = $this->activeTheme;

        $themeInfo = $this->themeInfo($themeName);

        $themePath = config('theme.baseDir')  . '/' . $this->place . '/' . $themeName . '/';
        
        $assetPath = $this->config['theme.folders.assets'].'/';
        
        $fullPath = $themePath.$assetPath.$path;
       
        return $this->app['url']->asset($fullPath, $secure);
    }

    /**
     * Map view map for particular theme.
     *
     * @param string $theme
     *
     * @return void
     */
    private function loadTheme($theme){
        if (is_null($theme) || is_null($this->place)) {
            return;
        }

        $themeInfo = $this->themeInfo($theme);

        if (is_null($themeInfo)) {
            return;
        }

        $viewPath = $themeInfo->get('path').'/'.$this->config['theme.folders.views'];

        $this->finder->prependLocation($themeInfo->get('path'));
        $this->finder->prependLocation($viewPath);
        $this->finder->prependNamespace($themeInfo->get('place').'.'.$themeInfo->get('name'), $viewPath);

    }
}
