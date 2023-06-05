<?php

namespace Mi\L5Core\Commands;

use Illuminate\Console\GeneratorCommand;

class MakeCriteria extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:criteria {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make a new criteria';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Criteria';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__ . '/stubs/criteria.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Criteria';
    }
}
