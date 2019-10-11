<?php

namespace Mrkatz\LoginProviders\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Symfony\Component\Console\Input\InputArgument;

class UserMakeCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Extend a User Modal to create a new User Type';


    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Model';

    /**
     * Replace the namespace for the given stub.
     *
     * @param string $stub
     * @param string $name
     * @return UserMakeCommand
     */
    protected function replaceNamespace(&$stub, $name)
    {
        $stub = str_replace(
            ['DummyNamespace', 'DummyRootNamespace', 'NamespacedDummyUserModel', 'DummyColumn', 'DummyValue'],
            [$this->getNamespace($name), $this->rootNamespace(), config('auth.providers.users.model'), $this->argument('query')[0], $this->argument('query')[1]],
            $stub
        );

        return $this;
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        $stub = config('laravel-stubs.path') . '/user.stub';

        return file_exists($stub) ? $stub : __DIR__ . '\stubs\user.stub';
    }

    /**
     * Get the stub file for the factory generator.
     *
     * @return string
     * @throws FileNotFoundException
     */
    protected function getFactoryStub()
    {
        $stub = config('laravel-stubs.path') . '/user-factory.stub';
        $stub = file_exists($stub) ? $stub : __DIR__ . '\stubs\user-factory.stub';

        return $this->files->get($stub);
    }

    /**
     * Get the default namespace for the class.
     *
     * @param string $rootNamespace
     *
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . config('laravel-stubs.namespaces.model', '');
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the user Model'],
        ];
    }
}
