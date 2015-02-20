<?php namespace Modules\Core\Console\Installers\Scripts;

use Illuminate\Console\Command;
use Modules\Core\Console\Installers\SetupScript;

class ModuleSeeders implements SetupScript
{
    /**
     * @var array
     */
    protected $modules = [
        'Setting',
        'Page',
    ];

    /**
     * Fire the install script
     * @param  Command $command
     * @return mixed
     */
    public function fire(Command $command)
    {
        $command->blockMessage('Seeds', 'Running the module seeds ...', 'comment');

        foreach ($this->modules as $module) {
            $command->call('module:seed', ['module' => $module]);
        }
    }
}
