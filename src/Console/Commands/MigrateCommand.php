<?php

namespace Src\Console\Commands;

use Src\Infrastructure\Database\Database;

class MigrateCommand
{
    public function handle(): void
    {
        $migrationPath =
            __DIR__ .
            '/../../../database/migrations';

        $files =
            glob($migrationPath . '/*.php');

        $db =
            Database::connection();

        foreach ($files as $file) {

            $migrationName =
                basename($file);

            /*
            |--------------------------------------------------------------------------
            | Already Migrated?
            |--------------------------------------------------------------------------
            */

            $stmt =
                $db->prepare(

                    "SELECT COUNT(*)
                     FROM migrations
                     WHERE migration = ?"

                );

            $stmt->execute([
                $migrationName
            ]);

            $exists =
                $stmt->fetchColumn();

            if ($exists) {

                echo "SKIPPED: {$migrationName}\n";

                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Run Migration
            |--------------------------------------------------------------------------
            */

            $migration =
                require $file;

            $migration->up();

            /*
            |--------------------------------------------------------------------------
            | Save Migration
            |--------------------------------------------------------------------------
            */

            $stmt =
                $db->prepare(

                    "INSERT INTO migrations
                     (migration)
                     VALUES (?)"

                );

            $stmt->execute([
                $migrationName
            ]);

            echo "MIGRATED: {$migrationName}\n";
        }
    }
}