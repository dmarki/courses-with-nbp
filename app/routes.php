<?php
declare(strict_types=1);

use App\Application\Actions\Exchange\CurrencyExchange;
use App\Application\Actions\Exchange\Exchange;
use App\Application\Actions\Exchange\ExchangeRates;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;

return function (App $app) {
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });

    $app->get('/exchange-rates/[{date}]', ExchangeRates::class);
    $app->get('/currency-exchange', CurrencyExchange::class);
    $app->post('/currency-exchange/calculate', Exchange::class);
};
