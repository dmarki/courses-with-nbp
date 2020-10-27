<?php

namespace App\Application\Actions\Exchange;

use App\Domain\Exchange\ExchangeRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;

class ExchangeRates
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
        $rateDate = $args['date'] ?? '';

        if ($rateDate) {
            $currencyAverageDate = $this->exchangeRepository->getRatesByDate($rateDate);
        }

        $date = $this->exchangeRepository->getRateDate();
        $viewData = [
            'rateDates' => $date,
            'rateDate' => $rateDate,
            'currencyAverageDate' => $currencyAverageDate ?? [],
        ];


        return $this->twig->render($response, 'exchange-rates.twig', $viewData);
    }
}
