<?php
namespace App\Command;

use App\Domain\Exchange\ExchangeRepository;
use DI\Container;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RatesImport extends Command
{

    private Container $ci;

    public function __construct(string $name, Container $ci)
    {
        parent::__construct($name);

        $this->ci = $ci;
    }

    /**
     * Configuration
     *
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName("rates-import")
            ->setDescription("This command make import currency rates");
    }

    /**
     * Executes the command
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $nbp = 'https://www.nbp.pl/kursy/xml/dir.txt';

        $rateFiles = explode("\n", file_get_contents($nbp));

        $rateFiles = array_filter($rateFiles, fn($hash) => $hash[0] === 'a');

        $rateFiles = array_slice($rateFiles, -7);

        foreach ($rateFiles as $file) {
            $file = trim($file);
            $fileName = "http://www.nbp.pl/kursy/xml/{$file}.xml";

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $fileName);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');

            $xmlstr = curl_exec($ch);
            curl_close($ch);

            $currencyArray = $this->prepareCurrencyRates($xmlstr);
            $this->addRates($currencyArray);
        }

        return 1;
    }

    private function prepareCurrencyRates($rates)
    {
        $new = simplexml_load_string($rates);
        $con = json_encode($new);
        $newArr = json_decode($con, true);

        return $newArr;
    }

    private function addRates(array $currencyArray)
    {
        $repository = new ExchangeRepository($this->ci);

        foreach ($currencyArray['pozycja'] as $rate) {
            if (empty($rate)) {
                continue;
            }

            $averageRate = (float) str_replace(',', '.', $rate['kurs_sredni']);
            $currencyId = $repository->addOrSelectCurrency($rate['kod_waluty'], $rate['przelicznik']);
            $repository->addDayAverageRate($currencyArray['data_publikacji'], $averageRate, $currencyId);
        }
    }
}
