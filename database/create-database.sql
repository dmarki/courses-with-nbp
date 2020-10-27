SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS Currency;
create table Currency(
    currencyId int(16) AUTO_INCREMENT PRIMARY KEY,
    currency char(3) NOT NULL,
    converter int(16) NOT NULL DEFAULT 1,
    unique key (currency)
);

DROP TABLE IF EXISTS ExchangeRate;
create table ExchangeRate(
    exchangeRateId int(16) AUTO_INCREMENT PRIMARY KEY,
    currencyId int(16) NOT NULL,
    rateDate timestamp NOT NULL,
    rate FLOAT(10,4) NOT NULL,
    FOREIGN KEY (currencyId) REFERENCES Currency(currencyId),
    unique key(currencyId, rateDate)
);

SET FOREIGN_KEY_CHECKS = 1;
