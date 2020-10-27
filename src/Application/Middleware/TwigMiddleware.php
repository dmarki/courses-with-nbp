<?php

namespace App\Application\Middleware;

use Slim\App;
use Slim\Middleware\ErrorMiddleware;
use Slim\Views\TwigMiddleware;

return function (App $app) {
    $app->addBodyParsingMiddleware();

    $app->add(TwigMiddleware::class);

    $app->addRoutingMiddleware();
    $app->add(ErrorMiddleware::class);
};
