<?php

namespace App\Domain\Exchange;

use DI\Container;

/**
 * Description of Exchange
 *
 * @author marki
 */
class ExchangeRepository
{

    private $db;

    public function __construct(Container $ci)
    {
        $this->db = $ci->get('db');
    }

    public function addOrSelectCurrency(string $currency, int $converter): int
    {
        $select = 'select currencyId from Currency where currency = ?';
        $stmt = $this->db->prepare($select);
        $stmt->execute([$currency]);
        $result = $stmt->fetch($this->db::FETCH_ASSOC);
        $currencyId = $result['currencyId'] ?? null;

        if ($currencyId) {
            return $currencyId;
        }

        $sql = "INSERT INTO Currency (currency, converter) VALUES (:currency, :converter)";
        $stmt = $this->db->prepare($sql);

        $stmt->bindParam(':currency', $currency);
        $stmt->bindParam(':converter', $converter);
        $stmt->execute();

        $currencyId = $this->db->lastInsertId();

        return (int) $currencyId;
    }

    public function addDayAverageRate($rateDate, float $rate, int $currencyId): ?bool
    {
        $sql = "INSERT IGNORE INTO ExchangeRate (rateDate, rate, currencyId) VALUES (?,?,?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$rateDate, $rate, $currencyId]);
    }

    public function getRateDate(): array
    {
        $sql = "select distinct(rateDate) as rateDate from ExchangeRate";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([]);

        return $stmt->fetchAll($this->db::FETCH_ASSOC);
    }

    public function getRatesByDate($date)
    {
        $sql = "select er.*, c.currency, c.converter from ExchangeRate er join Currency c using(currencyId) where rateDate = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$date]);

        return $stmt->fetchAll($this->db::FETCH_ASSOC);
    }

    public function getCurrency(): array
    {
        $sql = "select distinct(currency) as currency from Currency";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([]);

        return $stmt->fetchAll($this->db::FETCH_ASSOC);
    }

    public function getRateByCurrencyAndDate(string $currency, string $date): array
    {
        $sql = "select er.rate as rate, c.converter as converter from ExchangeRate er join Currency c using(currencyId) where c.currency = ? and er.rateDate = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$currency, $date]);

        return $stmt->fetchAll($this->db::FETCH_ASSOC);
    }
}
