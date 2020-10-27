<?php

namespace App\Application\Actions\Exception;

final class CalculateExchange {
    public static function calculate(int $currencyCount, array $rateToBeReplaced, array $rateReplaced): ?float {
        $toPLN = self::toPLN($currencyCount, $rateToBeReplaced);

        if (!$toPLN) {
            return null;
        }

        return round(self::fromPLN($toPLN, $rateReplaced), 2);
    }

    private static function toPLN(int $currencyCount, array $rateToBeReplaced): ?float {
        if (!$rateToBeReplaced['converter']) {
            return null;
        }

        return $currencyCount / $rateToBeReplaced['converter'] * $rateToBeReplaced['rate'];
    }

    private static function fromPLN(float $pln, array $rateReplaced): ?float {
        if (!$rateReplaced['rate']) {
            return null;
        }

        return $pln * $rateReplaced['converter'] / $rateReplaced['rate'];
    }

}
