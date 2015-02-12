<?php namespace Modules\Core\Console\Installers\Scripts;

use Illuminate\Console\Command;
use Modules\Core\Console\Installers\SetupScript;

class ModuleMigrator implements SetupScript {

    /**
     * @var array
     */
    protected $modules = [
        'Setting',
        'Menu',
        'Media',
        'Page'
    ];

    /**
     * Fire the install script
     * @param Command $command
     * @return mixed
     */
    public function fire(Command $command)
    {
        foreach ($this->modules as $module) {
            $command->call('module:migrate', ['module' => $module]);
        }
    }
}