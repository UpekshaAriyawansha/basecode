<?php

require_once __DIR__ . '/../vendor/autoload.php';

require_once __DIR__ . '/../src/Infrastructure/Support/helpers.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(
    dirname(__DIR__)
);

$dotenv->load();