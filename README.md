Technical requirements:
- php7.4
- mysql8
- composer

Create the user and the database with the given parameters
CREATE DATABASE USER:

CREATE USER 'nbp';
GRANT ALL PRIVILEGES ON * . * TO 'nbp';
FLUSH PRIVILEGES;

create database currency;
use currency;

Next step:
copy declaration of tables from this file and use it in mysql console

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


Using database and tables you have to run composer install in project folder.

To import actual exchange rates run command 'php script/cli.php rates-import'

To start project run: composer start in project folder. 


http://localhost:8080/currency-exchange : tool for calculate exchange currency.
http://localhost:8080/exchange-rates tool for selecting date and checking the exchange rates

Run test: composer test
