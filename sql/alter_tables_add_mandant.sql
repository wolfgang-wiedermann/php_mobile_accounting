--
-- Tabellenstruktur für Tabelle `fi_user`
--

DROP TABLE IF EXISTS `fi_user`;
CREATE TABLE IF NOT EXISTS `fi_user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(50) NOT NULL,
  `user_description` varchar(256) DEFAULT NULL,
  `mandant_id` int(11) NOT NULL,
  `create_date` date NOT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY (`user_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Tabellenstruktur für Tabelle `fi_mandant`
--

DROP TABLE IF EXISTS `fi_mandant`;
CREATE TABLE IF NOT EXISTS `fi_mandant` (
  `mandant_id` int(11) NOT NULL AUTO_INCREMENT,
  `mandant_description` varchar(256) DEFAULT NULL,
  `primary_user_id` int(11) NOT NULL,
  `create_date` date NOT NULL,
  PRIMARY KEY (`mandant_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--- 
--- Tabellenanpassungen: Mandant-Feld hinzufügen
---
ALTER TABLE `fi_konto` ADD COLUMN `mandant_id` int(11);
ALTER TABLE `fi_konto` DROP PRIMARY KEY, ADD PRIMARY KEY(mandant_id, kontonummer);
ALTER TABLE `fi_buchungen` ADD COLUMN `mandant_id` int(11);
ALTER TABLE `fi_quick_config` ADD COLUMN `mandant_id` int(11);

DROP VIEW IF EXISTS `fi_buchungen_view`;

CREATE  VIEW `fi_buchungen_view` AS
select mandant_id as mandant_id
, 'S' AS `KNZ`
, `fi_buchungen`.`buchungsnummer` AS `buchungsnummer`
, `fi_buchungen`.`buchungstext` AS `buchungstext`
, `fi_buchungen`.`sollkonto` AS `konto`
, `fi_buchungen`.`habenkonto` AS `gegenkonto`
, `fi_buchungen`.`betrag` AS `betrag`
, `fi_buchungen`.`datum` AS `datum`
from `fi_buchungen`
union
select mandant_id as mandant_id
, 'H' AS `H`
, `fi_buchungen`.`buchungsnummer` AS `buchungsnummer`
, `fi_buchungen`.`buchungstext` AS `buchungstext`
, `fi_buchungen`.`habenkonto` AS `habenkonto`
, `fi_buchungen`.`sollkonto` AS `sollkonto`
, (`fi_buchungen`.`betrag` * -(1)) AS `betrag*-1`
, `fi_buchungen`.`datum` AS `datum`
from `fi_buchungen`
order by `buchungsnummer`;

-- --------------------------------------------------------

DROP VIEW IF EXISTS `fi_ergebnisrechnungen_base`;

CREATE  VIEW `fi_ergebnisrechnungen_base` AS
select b.mandant_id as mandant_id
, 'S' AS `buchungsart`
, `b`.`buchungsnummer` AS `buchungsnummer`
, `b`.`buchungstext` AS `buchungstext`
, `b`.`sollkonto` AS `konto`
, `k`.`bezeichnung` AS `kontenname`
, `k`.`kontenart_id` AS `kontenart_id`
, `b`.`betrag` AS `betrag`
, `b`.`datum` AS `datum`
from (`fi_buchungen` `b` join `fi_konto` `k` on((`b`.`sollkonto` = `k`.`kontonummer`)))
union
select b.mandant_id as mandant_id
, 'H' AS `buchungsart`
, `b`.`buchungsnummer` AS `buchungsnummer`
, `b`.`buchungstext` AS `buchungstext`
, `b`.`habenkonto` AS `konto`
, `k`.`bezeichnung` AS `kontenname`
, `k`.`kontenart_id` AS `kontenart_id`
, (`b`.`betrag` * -(1)) AS `betrag`
, `b`.`datum` AS `datum`
from (`fi_buchungen` `b` join `fi_konto` `k` on((`b`.`habenkonto` = `k`.`kontonummer`)))
order by `buchungsnummer`,`buchungsart`;

-- --------------------------------------------------------

DROP VIEW IF EXISTS `fi_ergebnisrechnungen`;

CREATE  VIEW `fi_ergebnisrechnungen` AS
select `mandant_id` AS `mandant_id`
, `base`.`konto` AS `konto`
, `base`.`kontenname` AS `kontenname`
, `base`.`kontenart_id` AS `kontenart_id`
, sum(`base`.`betrag`) AS `saldo`
from `fi_ergebnisrechnungen_base` `base`
group by `base`.`konto`,`base`.`kontenname`,`base`.`kontenart_id`
order by `base`.`konto`;

update fi_konto set mandant_id = 1;
update fi_buchungen set mandant_id = 1;
update fi_quick_config set mandant_id = 1;
