<?php

require_once __DIR__ . '/../bootstrap/app.php';

use Src\Infrastructure\Database\Database;

try {

    $db = new Database();

    echo "Database Connected Successfully";

} catch (\Exception $e) {

    echo $e->getMessage();
}