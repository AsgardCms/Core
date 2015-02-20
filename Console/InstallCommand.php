<?php namespace Modules\Core\Console;

use Illuminate\Console\Command;
use Modules\Core\Console\Installers\Installer;
use Modules\Core\Console\Installers\Traits\BlockMessage;
use Modules\Core\Console\Installers\Traits\SectionMessage;

class InstallCommand extends Command
{
    use BlockMessage, SectionMessage;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'asgard:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install Asgard CMS';

    /**
     * @var Installer
     */
    private $installer;

    /**
     * Create a new command instance.
     *
     * @param Installer $installer
     * @internal param Filesystem $finder
     * @internal param Application $app
     * @internal param Composer $composer
     */
    public function __construct(Installer $installer)
    {
        parent::__construct();
        $this->getLaravel()['env'] = 'local';
        $this->installer = $installer;
    }

    /**
     * Execute the actions
     *
     * @return mixed
     */
    public function fire()
    {
        $this->blockMessage('Welcome,', 'Starting the installation process...');

        $success = $this->installer->stack([
            'Modules\Core\Console\Installers\Scripts\ProtectInstaller',
            'Modules\Core\Console\Installers\Scripts\ConfigureDatabase',
            'Modules\Core\Console\Installers\Scripts\SetAppKey',
            'Modules\Core\Console\Installers\Scripts\ConfigureUserProvider',
            'Modules\Core\Console\Installers\Scripts\ModuleMigrator',
            'Modules\Core\Console\Installers\Scripts\ModuleSeeders',
            'Modules\Core\Console\Installers\Scripts\ModuleAssets',
            'Modules\Core\Console\Installers\Scripts\ThemeAssets',
        ])->install($this);

        if ($success) {
            $this->info('Platform ready! You can now login with your username and password at /backend');
        }
    }
}
