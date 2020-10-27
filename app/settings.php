<?php

declare(strict_types=1);

use DI\ContainerBuilder;
use Monolog\Logger;

return function (ContainerBuilder $containerBuilder) {
    // Global Settings Object
    $containerBuilder->addDefinitions([
        'settings' => [
            'displayErrorDetails' => true, // Should be set to false in production
            'logger' => [
                'name' => 'slim-app',
                'path' => __DIR__ . '/../logs/app.log',
                'level' => Logger::DEBUG,
            ],
        ],
        'db' => [
            'host' => 'localhost',
            'dbname' => 'currency',
            'user' => 'bibby-lab',
            'pass' => ''
        ],
        'twig' => [
            'paths' => [
                __DIR__ . '/../templates',
            ],
            'options' => [
                // Should be set to true in production
                'cache_enabled' => false,
                'cache_path' => __DIR__ . '/../tmp/twig',
            ],
        ],
    ]);
};
