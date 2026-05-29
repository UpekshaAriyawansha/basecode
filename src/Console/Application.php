<?php

namespace Src\Console;

use Src\Console\Commands\MigrateCommand;
use Src\Console\Commands\MakeModelCommand;
use Src\Console\Commands\MakeControllerCommand;
use Src\Console\Commands\MakeMigrationCommand;
use Src\Console\Commands\RollbackCommand;
use Src\Console\Commands\MakeRequestCommand;

class Application
{
    private array $commands = [];

    public function __construct()
    {
        $this->registerCommands();
    }

    /*
    |--------------------------------------------------------------------------
    | Register Commands
    |--------------------------------------------------------------------------
    */

    private function registerCommands():
        void {

$this->commands = [

    'migrate' =>
        new MigrateCommand(),

    'migrate:rollback' =>
        new RollbackCommand(),

    'make:model' =>
        new MakeModelCommand(),

    'make:controller' =>
        new MakeControllerCommand(),

    'make:migration' =>
        new MakeMigrationCommand(),

    'make:request' =>
        new MakeRequestCommand()

];
    }

    /*
    |--------------------------------------------------------------------------
    | Run Command
    |--------------------------------------------------------------------------
    */

    public function run(
        array $argv
    ): void {

        $commandName =
            $argv[1]
            ?? null;

        if (!$commandName) {

            echo "No command provided.\n";

            return;
        }

        if (
            !isset(
                $this->commands[$commandName]
            )
        ) {

            echo "Command not found.\n";

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Execute Command
        |--------------------------------------------------------------------------
        */

        $this->commands[$commandName]
            ->handle($argv);
    }
}