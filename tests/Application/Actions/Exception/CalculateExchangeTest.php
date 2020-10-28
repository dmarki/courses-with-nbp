<?php

namespace Tests\Application\Actions\Exception;

use App\Application\Actions\Exception\CalculateExchange;
use Tests\TestCase;

class CalculateExchengeTest extends TestCase
{

    /**
     * @dataProvider provider
     */
    public function testAction(
        int $currencyCount,
        array $rateToBeReplaced,
        array $rateReplaced,
        ?float $result
    ) {

        $this->assertEquals(
            CalculateExchange::calculate(
                $currencyCount,
                $rateToBeReplaced,
                $rateReplaced
            ),
            $result
        );
    }

    public function provider()
    {
        return [
            [
                10,
                ['converter' => 1, 'rate' => 1],
                ['converter' => 1, 'rate' => 1],
                10.00
            ],
            [
                10,
                ['converter' => 100, 'rate' => 2],
                ['converter' => 1, 'rate' => 1],
                0.2
            ],
            [
                10,
                ['converter' => 0, 'rate' => 2],
                ['converter' => 1, 'rate' => 1],
                null
            ],
            [
                10,
                ['converter' => 1000, 'rate' => 2],
                ['converter' => 1, 'rate' => 0],
                null
            ],
        ];
    }
}
