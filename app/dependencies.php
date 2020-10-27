<?php

declare(strict_types=1);

use DI\ContainerBuilder;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Slim\App;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        LoggerInterface::class => function (ContainerInterface $c) {
            $settings = $c->get('settings');

            $loggerSettings = $settings['logger'];
            $logger = new Logger($loggerSettings['name']);

            $processor = new UidProcessor();
            $logger->pushProcessor($processor);

            $handler = new StreamHandler($loggerSettings['path'], $loggerSettings['level']);
            $logger->pushHandler($handler);

            return $logger;
        },
        'db' => function (ContainerInterface $c): PDO {
            $pdo = new PDO("mysql:host=localhost; dbname=nbp", 'nbp', '');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $pdo;
        },
        Twig::class => function (ContainerInterface $c) {
            $twigSettings = $c->get('twig');

            $options = $twigSettings['options'];
            $options['cache'] = $options['cache_enabled'] ? $options['cache_path'] : false;

            $twig = Twig::create($twigSettings['paths'], $options);
            return $twig;
        },
        TwigMiddleware::class => function (ContainerInterface $container) {
            return TwigMiddleware::createFromContainer(
                $container->get(App::class),
                Twig::class
            );
        },
    ]);
};
