#!/usr/bin/env php
<?php

use DI\ContainerBuilder;
use Symfony\Component\Console\Application;

require '../vendor/autoload.php';

$containerBuilder = new ContainerBuilder();

// Set up settings
$settings = require __DIR__ . '/../app/settings.php';
$settings($containerBuilder);

// Set up dependencies
$dependencies = require __DIR__ . '/../app/dependencies.php';
$dependencies($containerBuilder);

// Set up repositories
$repositories = require __DIR__ . '/../app/repositories.php';
$repositories($containerBuilder);

// Build PHP-DI Container instance
$container = $containerBuilder->build();

$application = new Application();

$application->add(new \App\Command\RatesImport('RatesImport', $container));

$application->run();
