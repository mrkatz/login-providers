<?php

namespace Mrkatz\LoginProviders\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

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
     * Execute the console command.
     *
     * @return void
     * @throws FileNotFoundException
     */
    public function handle()
    {
        if (count($this->argument('query')) !== 2) {
            $this->error('invalid query properties - Require 2 values, ColumnName ColumnValue');

            return;
        }

        if (parent::handle() === false && !$this->option('force')) {
            return;
        }

        if ($this->option('factory')) {
            $this->createFactory();
        }
    }

    /**
     * Create a model factory for the model.
     *
     * @return void
     * @throws FileNotFoundException
     */
    protected function createFactory()
    {
        $name = $this->qualifyClass($this->getNameInput());

        $fileName = str_replace(
            ['\\', '/'], '', $this->argument('name')
        );
        $path = $this->laravel->databasePath() . "/factories/{$fileName}Factory.php";

        $stub = $this->getFactoryStub();

        $this->files->put($path, $this->replaceNamespace($stub, $name)->replaceClass($stub, $name));

        $this->info($name . ' factory created successfully.');
    }

    /**
     * Replace the namespace for the given stub.
     *
     * @param  string $stub
     * @param  string $name
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
     * @param  string $rootNamespace
     *
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . config('laravel-stubs.namespaces.model', '');
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['factory', 'f', InputOption::VALUE_NONE, 'Create a new factory for the model'],

            ['force', null, InputOption::VALUE_NONE, 'Create the class even if the model already exists.'],
        ];
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the class'],
            ['query', InputArgument::IS_ARRAY | InputArgument::REQUIRED, 'The Query Columns & Value Of User - Eg \'is_admin\' true'],
        ];
    }
}
