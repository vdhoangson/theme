<?php
/**
 * Package: vdhoangson\theme
 * Author: vdhoangson
 * Github: https://github.com/vdhoangson/theme
 * Web: vdhoangson.com
 */

namespace vdhoangson\Theme\Console;

use Illuminate\Config\Repository;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem as File;

class ThemeGeneratorCommand extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'theme:create {place} {name}';

    /**
     * Console command description.
     *
     * @var string
     */
    protected $description = 'Create Theme Folder';

    /**
     * Filesystem.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * Config.
     *
     * @var \Illuminate\Support\Facades\Config
     */
    protected $config;

    /**
     * Theme Folder Path.
     *
     * @var string
     */
    protected $themePath;

    /**
     * Create Theme Info.
     *
     * @var array
     */
    protected $theme;

    /**
     * Created Theme Structure.
     *
     * @var array
     */
    protected $themeFolders;

    /**
     * Stubs path.
     *
     * @var string
     */
    protected $stubPath;

    /**
     * ThemeGeneratorCommand constructor.
     *
     * @param Repository $config
     * @param File       $files
     */
    public function __construct(Repository $config, File $files) {
        $this->config = $config;

        $this->files = $files;

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        $this->themePath = $this->config->get('theme.path');
        $this->theme['place'] = strtolower($this->argument('place'));

        $this->theme['name'] = strtolower($this->argument('name'));
        
        $createdThemePath = $this->themePath . '/' . $this->theme['place'] . '/' . $this->theme['name'];

        if ($this->files->isDirectory($createdThemePath)) {
            return $this->error('Sorry. '.ucfirst($this->theme['name']).' Theme Folder Already Exist !!!');
        }

        $this->consoleAsk();

        $this->themeFolders = $this->config->get('theme.folders');
        $this->stubPath = $this->config->get('theme.stubs.path');

        $themeStubFiles['theme'] = $this->config->get('theme.config.name');
        $themeStubFiles['changelog'] = $this->config->get('theme.config.changelog');

        $this->makeDir($createdThemePath);

        foreach ($this->themeFolders as $key => $folder) {
            $this->makeDir($createdThemePath.'/'.$folder);
        }

        $this->createStubs($themeStubFiles, $createdThemePath);

        $this->info(ucfirst($this->theme['name']).' Theme Folder Successfully Generated !!!');
    }

    /**
     * Console command ask questions.
     *
     * @return void
     */
    public function consoleAsk(){
        $this->theme['description'] = $this->ask('What is theme description?', false);
        $this->theme['description'] = !$this->theme['description'] ? '' : title_case($this->theme['description']);

        $this->theme['author'] = $this->ask('What is theme author name?', false);
        $this->theme['author'] = !$this->theme['author'] ? 'vdhoangson' : title_case($this->theme['author']);

        $this->theme['version'] = $this->ask('What is theme version?', false);
        $this->theme['version'] = !$this->theme['version'] ? '1.0.0' : $this->theme['version'];
    }

    /**
     * Create theme stubs.
     *
     * @param array  $themeStubFiles
     * @param string $createdThemePath
     */
    public function createStubs($themeStubFiles, $createdThemePath) {
        foreach ($themeStubFiles as $filename => $path) {
            if ($filename == 'changelog') {
                $filename = 'changelog'.pathinfo($path, PATHINFO_EXTENSION);
            } elseif ($filename == 'theme') {
                $filename = pathinfo($path, PATHINFO_EXTENSION);
            }

            $themeStubFile = $this->stubPath.'/'.$filename.'.stub';
            $this->makeFile($themeStubFile, $createdThemePath.'/'.$path);
        }
    }

    /**
     * Make directory.
     *
     * @param string $directory
     *
     * @return void
     */
    protected function makeDir($directory) {
        if (!$this->files->isDirectory($directory)) {
            $this->files->makeDirectory($directory, 0777, true);
        }
    }

    /**
     * Make file.
     *
     * @param string $file
     * @param string $path
     *
     * @return void
     */
    protected function makeFile($file, $path) {
        if ($this->files->exists($file)) {
            $content = $this->replaceStubs($this->files->get($file));

            $this->files->put($path, $content);
        }
    }

    /**
     * Replace Stub string.
     *
     * @param string $contents
     *
     * @return string
     */
    protected function replaceStubs($contents) {
        $mainString = [
            '[PLACE]',
            '[NAME]',
            '[DESCRIPTION]',
            '[AUTHOR]',
            '[VERSION]',
        ];
        $replaceString = [
            $this->theme['place'],
            $this->theme['name'],
            $this->theme['description'],
            $this->theme['author'],
            $this->theme['version']
        ];

        $replaceContents = str_replace($mainString, $replaceString, $contents);

        return $replaceContents;
    }
}
