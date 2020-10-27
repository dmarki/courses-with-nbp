<?php

namespace App\Application\Actions\Exchange;

use App\Application\Actions\Exception\CalculateExchange;
use App\Domain\Exchange\ExchangeRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Exchange {
    private ExchangeRepository $exchangeRepository;

    public function __construct(ExchangeRepository $exchangeRepository) {
        $this->exchangeRepository = $exchangeRepository;
    }

    public function __invoke(
            ServerRequestInterface $request,
            ResponseInterface $response,
            array $args
    ): ResponseInterface {
        extract($request->getParsedBody());

        $rateToBeReplaced = $this->exchangeRepository->getRateByCurrencyAndDate($currencyToBeReplaced, $date);
        $rateReplaced = $this->exchangeRepository->getRateByCurrencyAndDate($currencyReplaced, $date);

        $calculate = CalculateExchange::calculate($currencyCount, $rateToBeReplaced[0], $rateReplaced[0]);

        $data = array('value' => $calculate);
        $payload = json_encode($data);

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }
}
