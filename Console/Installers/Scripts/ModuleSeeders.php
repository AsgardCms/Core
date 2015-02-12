<?php namespace Modules\Core\Console\Installers\Scripts;

use Illuminate\Console\Command;
use Modules\Core\Console\Installers\SetupScript;

class ModuleSeeders implements SetupScript {

    /**
     * @var array
     */
    protected $seeders = [
        'Modules\Page\Database\Seeders\BasePageDatabaseSeeder',
        'Modules\Setting\Database\Seeders\SettingDatabaseSeeder'
    ];

    /**
     * Fire the install script
     * @param Command $command
     * @return mixed
     */
    public function fire(Command $command)
    {
        foreach($this->seeders as $seeder) {
            $command->call('db:seed', ['--class' => $seeder]);
        }
    }
}