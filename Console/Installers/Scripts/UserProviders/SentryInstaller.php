<?php namespace Modules\Core\Console\Installers\Scripts\UserProviders;

use Modules\Core\Console\Installers\SetupScript;

class SentryInstaller extends ProviderInstaller implements SetupScript
{
    /**
     * @var string
     */
    protected $driver = 'Sentry';

    /**
     * Check if the user driver is correctly registered.
     * @return bool
     */
    public function checkIsInstalled()
    {
        return class_exists('Cartalyst\Sentry\SentryServiceProvider');
    }

    /**
     * Not called
     * @return mixed
     */
    public function composer()
    {
        $this->application->register('Cartalyst\Sentry\SentryServiceProvider');
    }

    /**
     * @return mixed
     */
    public function publish()
    {
        $this->command->call('vendor:publish', ['--provider' => 'Cartalyst\Sentry\SentryServiceProvider']);
    }

    /**
     * @return mixed
     */
    public function migrate()
    {
        $this->command->call('migrate');
    }

    /**
     * @return mixed
     */
    public function configure()
    {
        $this->replaceCartalystUserModelConfiguration(
            'Cartalyst\Sentry\Users\Eloquent\User',
            $this->driver
        );

        $this->bindUserRepositoryOnTheFly('Sentry');
    }

    /**
     * @return mixed
     */
    public function seed()
    {
        $this->command->call('db:seed', ['--class' => 'Modules\User\Database\Seeders\SentryGroupSeedTableSeeder']);
    }

    /**
     * @param $password
     * @return mixed
     */
    public function getHashedPassword($password)
    {
        return $password;
    }
}
