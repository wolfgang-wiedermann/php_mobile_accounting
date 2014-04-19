--
-- Tabellenstruktur für Tabelle `fi_buchungen`
--

DROP TABLE IF EXISTS `fi_buchungen`;
CREATE TABLE IF NOT EXISTS `fi_buchungen` (
  `mandant_id` int(11) NOT NULL,
  `buchungsnummer` int(11) NOT NULL AUTO_INCREMENT,
  `buchungstext` varchar(256) DEFAULT NULL,
  `sollkonto` varchar(20) NOT NULL,
  `habenkonto` varchar(20) NOT NULL,
  `betrag` decimal(10,2) NOT NULL,
  `datum` date NOT NULL,
  `bearbeiter_user_id` integer DEFAULT NULL,
  PRIMARY KEY (`buchungsnummer`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Stellvertreter-Struktur des Views `fi_buchungen_view`
--
DROP VIEW IF EXISTS `fi_buchungen_view`;
CREATE TABLE IF NOT EXISTS `fi_buchungen_view` (
`KNZ` varchar(1)
,`buchungsnummer` int(11)
,`buchungstext` varchar(256)
,`konto` varchar(20)
,`gegenkonto` varchar(20)
,`betrag` decimal(11,2)
,`datum` date
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `fi_ergebnisrechnungen`
--
DROP VIEW IF EXISTS `fi_ergebnisrechnungen`;
CREATE TABLE IF NOT EXISTS `fi_ergebnisrechnungen` (
`konto` varchar(20)
,`kontenname` varchar(256)
,`kontenart_id` int(11)
,`saldo` decimal(33,2)
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `fi_ergebnisrechnungen_base`
--
DROP VIEW IF EXISTS `fi_ergebnisrechnungen_base`;
CREATE TABLE IF NOT EXISTS `fi_ergebnisrechnungen_base` (
`mandant_id` int(11)
,`buchungsart` varchar(1)
,`buchungsnummer` int(11)
,`buchungstext` varchar(256)
,`konto` varchar(20)
,`kontenname` varchar(256)
,`kontenart_id` int(11)
,`betrag` decimal(11,2)
,`datum` date
);
-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fi_kontenart`
--

DROP TABLE IF EXISTS `fi_kontenart`;
CREATE TABLE IF NOT EXISTS `fi_kontenart` (
  `kontenart_id` int(11) NOT NULL AUTO_INCREMENT,
  `bezeichnung` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`kontenart_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Daten für Tabelle `fi_kontenart`
--

INSERT INTO `fi_kontenart` (`kontenart_id`, `bezeichnung`) VALUES
(1, 'Aktiv'),
(2, 'Passiv'),
(3, 'Aufwand'),
(4, 'Ertrag'),
(5, 'Neutrale Konten');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fi_konto`
--

DROP TABLE IF EXISTS `fi_konto`;
CREATE TABLE IF NOT EXISTS `fi_konto` (
  `mandant_id` int(11) NOT NULL,
  `kontonummer` varchar(20) NOT NULL,
  `bezeichnung` varchar(256) NOT NULL,
  `kontenart_id` int(11) NOT NULL,
  PRIMARY KEY (`mandant_id`, `kontonummer`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

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
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

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
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fi_quick_config`
--

DROP TABLE IF EXISTS `fi_quick_config`;
CREATE TABLE IF NOT EXISTS `fi_quick_config` (
  `mandant_id` int(11) NOT NULL,
  `config_id` int(11) NOT NULL AUTO_INCREMENT,
  `config_knz` varchar(50) NOT NULL,
  `sollkonto` varchar(50) DEFAULT NULL,
  `habenkonto` varchar(50) DEFAULT NULL,
  `buchungstext` varchar(256) DEFAULT NULL,
  `betrag` decimal(12,2) DEFAULT NULL,
  PRIMARY KEY (`config_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------


--
-- Struktur des Views `fi_buchungen_view`
--

DROP TABLE IF EXISTS `fi_buchungen_view`;
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

--
-- Struktur des Views `fi_ergebnisrechnungen`
--

DROP TABLE IF EXISTS `fi_ergebnisrechnungen`;
DROP VIEW IF EXISTS `fi_ergebnisrechnungen`;

CREATE  VIEW `fi_ergebnisrechnungen` AS 
select `mandant_id` AS `mandant_id`
, `base`.`konto` AS `konto`
, `base`.`kontenname` AS `kontenname`
, `base`.`kontenart_id` AS `kontenart_id`
, sum(`base`.`betrag`) AS `saldo` 
from `fi_ergebnisrechnungen_base` `base` 
group by `base`.`mandant_id`, `base`.`konto`,`base`.`kontenname`,`base`.`kontenart_id` 
order by `base`.`mandant_id`, `base`.`konto`;

-- --------------------------------------------------------

--
-- Struktur des Views `fi_ergebnisrechnungen_base`
--

DROP TABLE IF EXISTS `fi_ergebnisrechnungen_base`;
DROP VIEW IF EXISTS `fi_ergebnisrechnungen_base`;

CREATE  VIEW `fi_ergebnisrechnungen_base` AS 
select b.mandant_id as mandant_id
, 'S' AS `buchungsart`
, `b`.`buchungsnummer` AS `buchungsnummer`
, `b`.`buchungstext` AS `buchungstext`
, `b`.`sollkonto` AS `konto`
, `k1`.`bezeichnung` AS `kontenname`
, `k1`.`kontenart_id` AS `kontenart_id`
, k2.kontenart_id AS gegenkontenart_id
, `b`.`betrag` AS `betrag`
, `b`.`datum` AS `datum` 
from (`fi_buchungen` `b` inner join `fi_konto` `k1`
  on((`b`.`sollkonto` = `k1`.`kontonummer`) and (b.mandant_id = k1.mandant_id)))
  inner join fi_konto k2
  on b.habenkonto = k2.kontonummer and b.mandant_id = k2.mandant_id
union 
select b.mandant_id as mandant_id
, 'H' AS `buchungsart`
, `b`.`buchungsnummer` AS `buchungsnummer`
, `b`.`buchungstext` AS `buchungstext`
, `b`.`habenkonto` AS `konto`
, `k1`.`bezeichnung` AS `kontenname`
, `k1`.`kontenart_id` AS `kontenart_id`
, k2.kontenart_id AS gegenkontenart_id
, (`b`.`betrag` * -(1)) AS `betrag`
, `b`.`datum` AS `datum`
from `fi_buchungen` `b` join `fi_konto` `k1`
  on `b`.`habenkonto` = `k1`.`kontonummer` and b.mandant_id = k1.mandant_id
  inner join fi_konto k2
  on b.sollkonto = k2.kontonummer and b.mandant_id = k2.mandant_id
order by `buchungsnummer`,`buchungsart`;

-- --------------------------------------------------------

-- Mandant 1 Anlegen
insert into fi_mandant values(0, 'Standardmandant', 1, now());

-- Template für Schnellbuchungen anlegen
insert into fi_quick_config values(1, 1, 'Template', '0000', '0000', 'Template', 0); 
