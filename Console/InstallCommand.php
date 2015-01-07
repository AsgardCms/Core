<?php namespace Modules\Core\Console;

use Dotenv;
use Illuminate\Console\Command;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Hash;
use Modules\Core\Services\Composer;

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
     * @var Filesystem
     */
    private $finder;
    /**
     * @var Application
     */
    private $app;
    /**
     * @var Composer
     */
    private $composer;

    /**
     * Create a new command instance.
     *
     * @param Filesystem  $finder
     * @param Application $app
     * @param Composer    $composer
     */
    public function __construct(Filesystem $finder, Application $app, Composer $composer)
    {
        parent::__construct();
        $this->finder = $finder;
        $this->app = $app;
        $this->composer = $composer;
    }

    /**
     * Execute the actions
     *
     * @return mixed
     */
    public function fire()
    {
        $this->info('Starting the installation process...');

        if ($this->checkIfInstalled()) {
            $this->error('Asgard has already been installed. You can already log into your administration.');

            return;
        }

        $this->configureDatabase();

        $userDriver = $this->choice('Which user driver do you wish to use? [1]', ['Sentinel (Paid)', 'Sentry (Free)'], 1);
        $chosenDriver = strstr($userDriver, ' ', true);
        $driverInstallMethod = "run{$chosenDriver}UserCommands";
        $this->$driverInstallMethod();

        $this->runMigrations();
        $this->runSeeds();

        $this->publishAssets();

        $this->blockMessage(
            'Success!',
            'Platform ready! You can now login with your username and password at /backend'
        );
    }

    /**
     * Run the required commands to use Sentinel
     */
    private function runSentinelUserCommands()
    {
        $this->info('Requiring Sentinel package, this may take some time...');
        $this->handleComposerForSentinel();

        $this->info('Running Sentinel migrations...');
        $this->runSentinelMigrations();

        $this->info('Running Sentinel seed...');
        $this->call('db:seed',
            ['--class' => 'Modules\User\Database\Seeders\SentinelGroupSeedTableSeeder', '--no-interaction' => '']);

        $this->replaceUserRepositoryBindings('Sentinel');
        $this->bindUserRepositoryOnTheFly('Sentinel');

        $this->call('publish:config', ['package' => 'cartalyst/sentinel', '--no-interaction' => '']);
        $this->replaceCartalystUserModelConfiguration('Cartalyst\Sentinel\Users\EloquentUser', 'Sentinel');

        $this->createFirstUser('sentinel');

        $this->info('User commands done.');
    }

    /**
     * Run the required commands to use Sentry
     */
    private function runSentryUserCommands()
    {
        $this->info('Running Sentry migrations...');
        $this->call('migrate', ['--package' => 'cartalyst/sentry', '--no-interaction' => '']);

        $this->info('Running Sentry seed...');
        $this->call('db:seed',
            ['--class' => 'Modules\User\Database\Seeders\SentryGroupSeedTableSeeder', '--no-interaction' => '']);

        $this->call('publish:config', ['package' => 'cartalyst/sentry', '--no-interaction' => '']);
        $this->replaceCartalystUserModelConfiguration('Cartalyst\Sentry\Users\Eloquent\User', 'Sentry');

        $this->createFirstUser('sentry');

        $this->info('User commands done.');
    }

    /**
     * Create the first user that'll have admin access
     */
    private function createFirstUser($driver)
    {
        $this->line('Creating an Admin user account...');

        $firstname = $this->askForFirstName();

        $lastname = $this->askForLastName();

        $email = $this->askForEmail();

        $password = $this->askForPassword();

        $userInfo = [
            'first_name' => $firstname,
            'last_name' => $lastname,
            'email' => $email,
        ];

        if ($driver == 'sentinel') {
            $userInfo = array_merge($userInfo, ['password' => Hash::make($password)]);
        } else {
            $userInfo = array_merge($userInfo, ['password' => $password]);
        }

        $user = app('Modules\User\Repositories\UserRepository');
        $user->createWithRoles($userInfo, [1], true);

        $this->info('Admin account created!');
    }

    /**
     * Run migrations specific to Sentinel
     */
    private function runSentinelMigrations()
    {
        $this->call('migrate', ['--package' => 'cartalyst/sentinel', '--no-interaction' => '']);
    }

    /**
     * Run the migrations
     */
    private function runMigrations()
    {
        $this->call('module:migrate', ['module' => 'Setting', '--no-interaction' => '']);
        $this->call('module:migrate', ['module' => 'Menu', '--no-interaction' => '']);
        $this->call('module:migrate', ['module' => 'Media', '--no-interaction' => '']);
        $this->call('module:migrate', ['module' => 'Page', '--no-interaction' => '']);

        $this->info('Application migrated!');
    }

    /**
     * Symfony style block messages
     *
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
        $this->call('module:publish', ['module' => 'Core', '--no-interaction' => '']);
        $this->call('module:publish', ['module' => 'Media', '--no-interaction' => '']);
        $this->call('module:publish', ['module' => 'Menu', '--no-interaction' => '']);
    }

    /**
     * Configuring the database information
     */
    private function configureDatabase()
    {
        do {
            $databaseName = $this->ask('Enter your database name: ');
            if ($databaseName == '') {
                $this->error('Database name is required');
            }
        } while (! $databaseName);
        do {
            $databaseUsername = $this->ask('Enter your database username: ');
            if ($databaseUsername == '') {
                $this->error('Database username is required');
            }
        } while (! $databaseUsername);
        do {
            $databasePassword = $this->secret('Enter your database password: ');
            if ($databasePassword == '') {
                $this->error('Database password is required');
            }
        } while (! $databasePassword);

        $this->setLaravelConfiguration($databaseName, $databaseUsername, $databasePassword);
        $this->configureEnvironmentFile($databaseName, $databaseUsername, $databasePassword);
    }

    /**
     * Writing the environment file
     *
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
            "DB_PASSWORD=homestead",
        ];

        $replace = [
            "DB_USERNAME=$databaseUsername",
            "DB_PASSWORD=$databasePassword".PHP_EOL,
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
     *
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

    /**
     * Find and replace the correct repository bindings with the given driver
     *
     * @param  string                                       $driver
     * @throws \Illuminate\Filesystem\FileNotFoundException
     */
    private function replaceUserRepositoryBindings($driver)
    {
        $path = 'Modules/User/Providers/UserServiceProvider.php';
        $userServiceProvider = $this->finder->get($path);
        $userServiceProvider = str_replace('Sentry', $driver, $userServiceProvider);
        $this->finder->put($path, $userServiceProvider);
    }

    /**
     * Set the correct repository binding on the fly for the current request
     *
     * @param $driver
     */
    private function bindUserRepositoryOnTheFly($driver)
    {
        $this->app->bind(
            'Modules\User\Repositories\UserRepository',
            "Modules\\User\\Repositories\\$driver\\{$driver}UserRepository"
        );
        $this->app->bind(
            'Modules\User\Repositories\RoleRepository',
            "Modules\\User\\Repositories\\$driver\\{$driver}RoleRepository"
        );
        $this->app->bind(
            'Modules\Core\Contracts\Authentication',
            "Modules\\User\\Repositories\\$driver\\{$driver}Authentication"
        );
    }

    /**
     * Replaced the model in the cartalyst configuration file
     *
     * @param  string                                       $search
     * @param  string                                       $Driver
     * @throws \Illuminate\Filesystem\FileNotFoundException
     */
    private function replaceCartalystUserModelConfiguration($search, $Driver)
    {
        $driver = strtolower($Driver);
        $path = "config/packages/cartalyst/{$driver}/config.php";

        $config = $this->finder->get($path);
        $config = str_replace($search, "Modules\\User\\Entities\\{$Driver}\\User", $config);
        $this->finder->put($path, $config);
    }

    /**
     * Install sentinel and remove sentry
     * Set the required Service Providers and Aliases in config/app.php
     *
     * @throws \Illuminate\Filesystem\FileNotFoundException
     */
    private function handleComposerForSentinel()
    {
        $this->composer->enableOutput($this);
        $this->composer->install('cartalyst/sentinel:~1.0');

        // Search and replace SP and Alias in config/app.php
        $appConfig = $this->finder->get('config/app.php');
        $appConfig = str_replace(
            [
                "#'Cartalyst\\Sentinel\\Laravel\\SentinelServiceProvider',",
                "'Cartalyst\\Sentry\\SentryServiceProvider',",
                "#'Activation' => 'Cartalyst\\Sentinel\\Laravel\\Facades\\Activation',",
                "#'Reminder' => 'Cartalyst\\Sentinel\\Laravel\\Facades\\Reminder',",
                "#'Sentinel' => 'Cartalyst\\Sentinel\\Laravel\\Facades\\Sentinel',",
                "'Sentry' => 'Cartalyst\\Sentry\\Facades\\Laravel\\Sentry',",
            ],
            [
                "'Cartalyst\\Sentinel\\Laravel\\SentinelServiceProvider',",
                "#'Cartalyst\\Sentry\\SentryServiceProvider',",
                "'Activation' => 'Cartalyst\\Sentinel\\Laravel\\Facades\\Activation',",
                "'Reminder' => 'Cartalyst\\Sentinel\\Laravel\\Facades\\Reminder',",
                "'Sentinel' => 'Cartalyst\\Sentinel\\Laravel\\Facades\\Sentinel',",
                "#'Sentry' => 'Cartalyst\\Sentry\\Facades\\Laravel\\Sentry',"
            ],
            $appConfig
        );
        $this->finder->put('config/app.php', $appConfig);

        $this->composer->remove('cartalyst/sentry');
    }

    /**
     * Check if Asgard CMS already has been installed
     */
    private function checkIfInstalled()
    {
        return $this->finder->isFile('.env');
    }

    /**
     * Run module seeds
     */
    private function runSeeds()
    {
        $this->call('db:seed',
            ['--class' => 'Modules\Page\Database\Seeders\BasePageDatabaseSeeder', '--no-interaction' => '']);
        $this->call('db:seed',
            ['--class' => 'Modules\Setting\Database\Seeders\SettingDatabaseSeeder', '--no-interaction' => '']);

        $this->info('Application seeded.');
    }

    /**
     * @return string
     */
    private function askForFirstName()
    {
        do {
            $firstname = $this->ask('Enter your first name');
            if ($firstname == '') {
                $this->error('First name is required');
            }
        } while (! $firstname);

        return $firstname;
    }

    /**
     * @return string
     */
    private function askForLastName()
    {
        do {
            $lastname = $this->ask('Enter your last name: ');
            if ($lastname == '') {
                $this->error('Last name is required');
            }
        } while (! $lastname);

        return $lastname;
    }

    /**
     * @return string
     */
    private function askForEmail()
    {
        do {
            $email = $this->ask('Enter your email address: ');
            if ($email == '') {
                $this->error('Email is required');
            }
        } while (! $email);

        return $email;
    }

    /**
     * @return string
     */
    private function askForPassword()
    {
        do {
            $password = $this->askForFirstPassword();
            $passwordConfirmation = $this->askForPasswordConfirmation();
            if ($password != $passwordConfirmation) {
                $this->error('Password confirmation doesn\'t match. Please try again.');
            }
        } while ($password != $passwordConfirmation);

        return $password;
    }

    /**
     * @return string
     */
    private function askForFirstPassword()
    {
        do {
            $password = $this->secret('Enter a password: ');
            if ($password == '') {
                $this->error('Password is required');
            }
        } while (! $password);

        return $password;
    }

    /**
     * @return string
     */
    private function askForPasswordConfirmation()
    {
        do {
            $passwordConfirmation = $this->secret('Please confirm you password: ');
            if ($passwordConfirmation == '') {
                $this->error('Password confirmation is required');
            }
        } while (! $passwordConfirmation);

        return $passwordConfirmation;
    }
}
