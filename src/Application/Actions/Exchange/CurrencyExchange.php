<?php

namespace App\Application\Actions\Exchange;

use App\Domain\Exchange\ExchangeRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;

class CurrencyExchange
{
    private Twig $twig;
    private ExchangeRepository $exchangeRepository;

    public function __construct(Twig $twig, ExchangeRepository $exchangeRepository)
    {
        $this->twig = $twig;
        $this->exchangeRepository = $exchangeRepository;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
        $currency = $this->exchangeRepository->getCurrency();

        $date = $this->exchangeRepository->getRateDate();
        $viewData = [
            'rateDates' => $date,
            'currency' => $currency,
        ];

        return $this->twig->render($response, 'currency-exchange.twig', $viewData);
    }
}
