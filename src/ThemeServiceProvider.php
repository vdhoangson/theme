<?php
/**
 * Package: vdhoangson\theme
 * Author: vdhoangson
 * Github: https://github.com/vdhoangson/theme
 * Web: vdhoangson.com
 */

namespace vdhoangson\Theme;

use Illuminate\Support\ServiceProvider;

use App;
use File;

use vdhoangson\Theme\Theme;
use vdhoangson\Theme\Console\ThemeListCommand;
use vdhoangson\Theme\Contracts\ThemeContract;
use vdhoangson\Theme\Contracts\BreadcrumbContract;
use \Illuminate\Foundation\AliasLoader;

class ThemeServiceProvider extends ServiceProvider {
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot() {
        if (!File::exists(config('theme.path')) && File::exists(config('theme.path'))) {
            App::make('files')->link(config('theme.path'), config('theme.path'));
        }

        $this->app['router']->aliasMiddleware('VSThemeFront', Middleware\FrontMiddleware::class);
        $this->app['router']->aliasMiddleware('VSThemeBack', Middleware\BackMiddleware::class);

         /* Register Alias */
        $alias = AliasLoader::getInstance();
        $alias->alias('Theme', Facades\Theme::class);
        $alias->alias('Breadcrumb', Facades\Breadcrumb::class);
        
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register() {
        $this->publishConfig();
        $this->registerTheme();
        $this->registerBreadcrumb();
        $this->registerHelper();
        $this->consoleCommand();
    }

    /**
     * Publish config file.
     *
     * @return void
     */
    public function publishConfig() {
        $configPath = realpath(__DIR__.'/../config/theme.php');

        $this->publishes([
            $configPath => config_path('theme.php'),
        ]);
        
        $this->mergeConfigFrom($configPath, 'vdhoangson.theme');
    }

    /**
     * Register theme required components .
     *
     * @return void
     */
    public function registerTheme() {
        $this->app->singleton(ThemeContract::class, function ($app) {
            $theme = new Theme($app, $this->app['view']->getFinder(), $this->app['config']);

            return $theme;
        });
    }

    /**
     * Register breadcrum required components .
     *
     * @return void
     */
    public function registerBreadcrumb() {
        $this->app->singleton(BreadcrumbContract::class, function ($app) {
            $breadcrumb = new Breadcrumb();

            return $breadcrumb;
        });
    }

    /**
     * Register All Helpers.
     *
     * @return void
     */
    public function registerHelper() {
        foreach (glob(__DIR__.'/*.php') as $filename) {
            require_once $filename;
        }
    }

    /**
     * Add Commands.
     *
     * @return void
     */
    public function consoleCommand() {
        $this->registerThemeGeneratorCommand();
        $this->registerThemeListCommand();
        
        $this->commands(
            'theme.create',
            'theme.list'
        );
    }

    /**
     * Register generator command.
     *
     * @return void
     */
    public function registerThemeGeneratorCommand() {
        $this->app->singleton('theme.create', function ($app) {
            return new \vdhoangson\Theme\Console\ThemeGeneratorCommand($app['config'], $app['files']);
        });
    }

    /**
     * Register theme list command.
     *
     * @return void
     */
    public function registerThemeListCommand() {
        $this->app->singleton('theme.list', ThemeListCommand::class);
    }

    /**
	 * Get the services provided by the provider.
	 *
	 * @return string[]
	 */
	public function provides() {
		return ['vdhoangson.theme', 'view.finder'];
	}
}
