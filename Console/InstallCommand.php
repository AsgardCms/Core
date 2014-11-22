<?php namespace Modules\Core\Console;

use Dotenv;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\Facades\Hash;
use Modules\User\Repositories\UserRepository;

class InstallCommand extends Command
{
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
     * @var UserRepository
     */
    private $user;

    /**
     * @var Filesystem
     */
    private $finder;

    /**
     * Create a new command instance.
     *
     * @param UserRepository $user
     * @param Filesystem $finder
     * @return \Modules\Core\Console\InstallCommand
     */
    public function __construct($user, Filesystem $finder)
    {
        parent::__construct();
        $this->user = $user;
        $this->finder = $finder;
    }

    /**
     * Execute the actions
     *
     * @return mixed
     */
    public function fire()
    {
        $this->info('Starting the installation process...');
        $this->configureDatabase();
        $userDriverlist = [0 => 'None', 'sentry', 'sentinel (paying)'];
        $driver = $this->choice("Which user driver do you wish use ?", $userDriverlist);
        if (isset($driver) && !empty($driver) && $driver !== 'None') {
            $this->{'runUser' . $driver . 'Commands'}();
            $isUserCreated = true;
        } else {
            $isUserCreated = false;
        }

        $this->runMigrations();

        $this->publishAssets();
        if ($isUserCreated) {
            $this->blockMessage(
                'Success!',
                'Platform ready! You can now login with your username and password at /backend'
            );
        } else {
            $this->blockMessage(
                'Success!',
                'Platform ready! But you need to install a user driver and create an account'
            );
        }
    }

    /**
     *
     */
    private function runUserSentinelCommands()
    {
        $this->runSentinelMigrations();
        $this->runSentinelConfigFile();
        $this->runUserSeeds();
        $this->createFirstUser();

        $this->info('User commands done.');
    }

    /**
     *
     */
    private function runUserSentryCommands()
    {
        $this->runSentryMigrations();
        $this->runSentryConfigFile();
//        $app = $this->finder->get('config/app.php');
//        var_dump($app); exit;
//        $app = $app['aliases']['Auth'] = 'Cartalyst\Sentry\Facades\Laravel\Sentry';
//        $this->finder->put('config/app.php', '<?php return ['.$app.']');
        AliasLoader::getInstance()->alias('Test', 'Cartalyst\Sentry\Facades\Laravel\Sentry');
        $this->runUserSeeds();
        $this->createFirstUser();

        $this->info('User commands done.');
    }

    /**
     * Create the first user that'll have admin access
     */
    private function createFirstUser()
    {
        $this->line('Creating an Admin user account...');

        $firstname = $this->ask('Enter your first name');
        $lastname = $this->ask('Enter your last name');
        $email = $this->ask('Enter your email address');
        $password = $this->secret('Enter a password');

        $userInfo = [
            'first_name' => $firstname,
            'last_name' => $lastname,
            'email' => $email,
            'password' => $password,
        ];
        $this->user->createWithRoles($userInfo, ['Admin']);

        $this->info('Admin account created!');
    }

    /**
     * Run migrations specific to Sentinel
     */
    private function runSentinelMigrations()
    {
        $this->call('migrate', ['--package' => 'cartalyst/sentinel']);
    }

    /**
     * Run the migrations
     */
    private function runMigrations()
    {
        $this->call('module:migrate', ['module' => 'Setting']);

        $this->info('Application migrated!');
    }

    private function runUserSeeds()
    {
        $this->call('module:seed', ['module' => 'User']);
    }

    /**
     * Symfony style block messages
     * @param $title
     * @param $message
     * @param string $style
     */
    protected function blockMessage($title, $message, $style = 'info')
    {
        $formatter = $this->getHelperSet()->get('formatter');
        $errorMessages = [$title, $message];
        $formattedBlock = $formatter->formatBlock($errorMessages, $style, true);
        $this->line($formattedBlock);
    }

    /**
     * Publish the CMS assets
     */
    private function publishAssets()
    {
        $this->call('module:publish', ['module' => 'Core']);
    }

    /**
     * Configuring the database information
     */
    private function configureDatabase()
    {
        // Ask for credentials
        $databaseName = $this->ask('Enter your database name');
        $databaseUsername = $this->ask('Enter your database username');
        $databasePassword = $this->secret('Enter your database password');

        $this->setLaravelConfiguration($databaseName, $databaseUsername, $databasePassword);
        $this->configureEnvironmentFile($databaseName, $databaseUsername, $databasePassword);
    }

    /**
     * Writing the environment file
     * @param $databaseName
     * @param $databaseUsername
     * @param $databasePassword
     */
    private function configureEnvironmentFile($databaseName, $databaseUsername, $databasePassword)
    {
        Dotenv::makeMutable();

        $environmentFile = $this->finder->get('.env.example');

        $search = [
            "DB_USERNAME=homestead",
            "DB_PASSWORD=homestead"
        ];

        $replace = [
            "DB_USERNAME=$databaseUsername",
            "DB_PASSWORD=$databasePassword" . PHP_EOL
        ];
        $newEnvironmentFile = str_replace($search, $replace, $environmentFile);
        $newEnvironmentFile .= "DB_NAME=$databaseName";

        // Write the new environment file
        $this->finder->put('.env', $newEnvironmentFile);
        // Delete the old environment file
        $this->finder->delete('env.example');

        $this->info('Environment file written');

        Dotenv::makeImmutable();
    }

    /**
     * Set DB credentials to laravel config
     * @param $databaseName
     * @param $databaseUsername
     * @param $databasePassword
     */
    private function setLaravelConfiguration($databaseName, $databaseUsername, $databasePassword)
    {
        $this->laravel['config']['database.connections.mysql.database'] = $databaseName;
        $this->laravel['config']['database.connections.mysql.username'] = $databaseUsername;
        $this->laravel['config']['database.connections.mysql.password'] = $databasePassword;
    }

    private function runSentryMigrations()
    {
        $this->call('migrate', ['--package' => 'cartalyst/sentry']);
    }

    private function runSentryConfigFile()
    {
        $path = 'Modules/User/Config/userdriver.php';
        $string = "<?php return ['driver'=>'Sentry','seeder'=>['SentryGroupSeedTableSeeder','SentryUserSeedTableSeeder']];";
        $file = $this->finder->put($path, $string);

        if ($file) {
            $this->info('User driver define in config file.');
        }
    }

    private function runSentinelConfigFile()
    {
        $path = 'Modules/User/Config/userdriver.php';
        $string = "<?php return ['driver'=>'Sentinel','seeder'=>['SentryGroupSeedTableSeeder','SentryUserSeedTableSeeder']];";
        $file = $this->finder->put($path, $string);

        if ($file) {
            $this->info('User driver define in config file !');
        }
    }

}
