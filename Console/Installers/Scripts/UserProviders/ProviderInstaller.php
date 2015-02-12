<?php namespace Modules\Core\Console\Installers\Scripts\UserProviders;

use Illuminate\Console\Command;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Filesystem\Filesystem;
use Modules\Core\Console\Installers\SetupScript;
use Modules\Core\Services\Composer;
use Modules\User\Repositories\UserRepository;

abstract class ProviderInstaller implements SetupScript
{

    /**
     * @var string
     */
    protected $driver;

    /**
     * @var Command
     */
    protected $command;

    /**
     * @var Filesystem
     */
    protected $finder;

    /**
     * @var UserRepository
     */
    protected $repository;

    /**
     * @var Composer
     */
    protected $composer;

    /**
     * @var Application
     */
    protected $application;

    /**
     * @param Filesystem     $finder
     * @param UserRepository $repository
     * @param Composer       $composer
     * @param Application    $application
     */
    public function __construct(Filesystem $finder, UserRepository $repository, Composer $composer, Application $application)
    {
        $this->finder = $finder;
        $this->repository = $repository;
        $this->composer = $composer;
        $this->application = $application;
    }

    /**
     * Fire the install script
     * @param Command $command
     * @return mixed
     */
    public function fire(Command $command)
    {
        $this->command = $command;

        $this->composer();
        $this->publish();
        $this->migrate();
        $this->configure();
        $this->seed();

        $this->createFirstUser();

        $command->info($this->driver . ' succesfully configured');
    }

    /**
     * @return mixed
     */
    abstract public function composer();

    /**
     * @return mixed
     */
    abstract public function publish();

    /**
     * @return mixed
     */
    abstract public function migrate();

    /**
     * @return mixed
     */
    abstract public function seed();

    /**
     * @return mixed
     */
    abstract public function configure();

    /**
     * @param $password
     * @return mixed
     */
    abstract public function getHashedPassword($password);

    /**
     * @param $search
     * @param $Driver
     */
    protected function replaceCartalystUserModelConfiguration($search, $Driver)
    {
        $driver = strtolower($Driver);

        $path = base_path("config/cartalyst.{$driver}.php");

        $config = $this->finder->get($path);

        $config = str_replace($search, "Modules\\User\\Entities\\{$Driver}\\User", $config);

        $this->finder->put($path, $config);
    }

    /**
     * Create a first admin user
     */
    protected function createFirstUser()
    {
        $info = [
            'first_name' => $this->askForFirstName(),
            'last_name'  => $this->askForLastName(),
            'email'      => $this->askForEmail(),
            'password'   => $this->getHashedPassword(
                $this->askForPassword()
            )
        ];

        $this->repository->createWithRoles(
            $info,
            [1],
            true
        );

        $this->command->info('Admin account created!');
    }

    /**
     * @return string
     */
    private function askForFirstName()
    {
        do {
            $firstname = $this->command->ask('Enter your first name');
            if ($firstname == '') {
                $this->command->error('First name is required');
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
            $lastname = $this->command->ask('Enter your last name: ');
            if ($lastname == '') {
                $this->command->error('Last name is required');
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
            $email = $this->command->ask('Enter your email address: ');
            if ($email == '') {
                $this->command->error('Email is required');
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
                $this->command->error('Password confirmation doesn\'t match. Please try again.');
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
            $password = $this->command->secret('Enter a password: ');
            if ($password == '') {
                $this->command->error('Password is required');
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
            $passwordConfirmation = $this->command->secret('Please confirm you password: ');
            if ($passwordConfirmation == '') {
                $this->command->error('Password confirmation is required');
            }
        } while (! $passwordConfirmation);

        return $passwordConfirmation;
    }
}
