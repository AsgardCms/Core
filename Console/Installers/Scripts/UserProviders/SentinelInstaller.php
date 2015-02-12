<?php namespace Modules\Core\Console\Installers\Scripts\UserProviders;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Modules\Core\Console\Installers\SetupScript;

class SentinelInstaller extends ProviderInstaller implements SetupScript
{

    /**
     * @return mixed
     */
    public function composer()
    {
        $this->composer->enableOutput($this->command);
        $this->composer->install('cartalyst/sentinel:dev-feature/laravel-5');
        $this->composer->remove('cartalyst/sentry');

        // Dynamically register the service provider, so we can use it during publishing
        $this->application->register('Cartalyst\Sentinel\Laravel\SentinelServiceProvider');
    }

    /**
     * @return mixed
     */
    public function publish()
    {
        $this->command->call('vendor:publish', ['--provider' => 'Cartalyst\Sentinel\Laravel\SentinelServiceProvider']);
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
            'Cartalyst\Sentinel\Users\EloquentUser',
            'Sentinel'
        );

        $this->changeDefaultUserProvider('Sentinel');

        $this->bindUserRepositoryOnTheFly('Sentinel');
    }

    /**
     * @return mixed
     */
    public function seed()
    {
        $this->command->call('db:seed', ['--class' => 'Modules\User\Database\Seeders\SentinelGroupSeedTableSeeder']);
    }

    /**
     * @param $password
     * @return mixed
     */
    public function getHashedPassword($password)
    {
        return Hash::make($password);
    }

    /**
     * @param $driver
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    private function changeDefaultUserProvider($driver)
    {
        $path = base_path('Modules/User/Config/users.php');
        $config = $this->finder->get($path);
        $config = str_replace('Sentry', $driver, $config);
        $this->finder->put($path, $config);
    }

    /**
     * Set the correct repository binding on the fly for the current request
     *
     * @param $driver
     */
    private function bindUserRepositoryOnTheFly($driver)
    {
        $this->application->bind(
            'Modules\User\Repositories\UserRepository',
            "Modules\\User\\Repositories\\$driver\\{$driver}UserRepository"
        );
        $this->application->bind(
            'Modules\User\Repositories\RoleRepository',
            "Modules\\User\\Repositories\\$driver\\{$driver}RoleRepository"
        );
        $this->application->bind(
            'Modules\Core\Contracts\Authentication',
            "Modules\\User\\Repositories\\$driver\\{$driver}Authentication"
        );
    }
}
