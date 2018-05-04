<?php
/**
 * Package: vdhoangson\theme
 * Author: vdhoangson
 * Github: https://github.com/vdhoangson/theme
 * Web: vdhoangson.com
 */

namespace vdhoangson\Theme\Console;

use Illuminate\Console\Command;
use vdhoangson\Theme\Contracts\ThemeContract;

class ThemeListCommand extends Command {
    /**
     * Name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'theme:list';

    /**
     * Console command description.
     *
     * @var string
     */
    protected $description = 'List available themes';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        $places = $this->laravel[ThemeContract::class]->all();
        $headers = ['Place', 'Name', 'Author', 'Version'];
        $output = [];
        foreach ($places as $place => $themes) {
            foreach($themes as $theme){
                $output[] = [
                    'Place' => $place,
                    'Name'    => $theme->get('name'),
                    'Author'  => $theme->get('author'),
                    'Version' => $theme->get('version'),
                ];
            }
        }
        $this->table($headers, $output);
    }
}
