--
-- Tabellenstruktur für Tabelle `fi_buchungen`
--

DROP TABLE IF EXISTS `fi_buchungen`;
CREATE TABLE IF NOT EXISTS `fi_buchungen` (
  `buchungsnummer` int(11) NOT NULL AUTO_INCREMENT,
  `buchungstext` varchar(256) DEFAULT NULL,
  `sollkonto` varchar(20) NOT NULL,
  `habenkonto` varchar(20) NOT NULL,
  `betrag` decimal(10,2) NOT NULL,
  `datum` date NOT NULL,
  PRIMARY KEY (`buchungsnummer`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=99 ;

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
`buchungsart` varchar(1)
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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

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
  `kontonummer` varchar(20) NOT NULL,
  `bezeichnung` varchar(256) NOT NULL,
  `kontenart_id` int(11) NOT NULL,
  PRIMARY KEY (`kontonummer`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `fi_konto`
--



-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fi_quick_config`
--

DROP TABLE IF EXISTS `fi_quick_config`;
CREATE TABLE IF NOT EXISTS `fi_quick_config` (
  `config_id` int(11) NOT NULL AUTO_INCREMENT,
  `config_knz` varchar(50) NOT NULL,
  `sollkonto` varchar(50) DEFAULT NULL,
  `habenkonto` varchar(50) DEFAULT NULL,
  `buchungstext` varchar(256) DEFAULT NULL,
  `betrag` decimal(12,2) DEFAULT NULL,
  PRIMARY KEY (`config_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Daten für Tabelle `fi_quick_config`
--


-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `fi_salden_jaehrlich_view`
--
DROP VIEW IF EXISTS `fi_salden_jaehrlich_view`;
CREATE TABLE IF NOT EXISTS `fi_salden_jaehrlich_view` (
`jahr` int(4)
,`konto` varchar(20)
,`saldo` decimal(33,2)
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `fi_salden_monatlich_view`
--
DROP VIEW IF EXISTS `fi_salden_monatlich_view`;
CREATE TABLE IF NOT EXISTS `fi_salden_monatlich_view` (
`monat` int(2)
,`jahr` int(4)
,`konto` varchar(20)
,`saldo` decimal(33,2)
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `fi_salden_taeglich_view`
--
DROP VIEW IF EXISTS `fi_salden_taeglich_view`;
CREATE TABLE IF NOT EXISTS `fi_salden_taeglich_view` (
`tag` int(2)
,`monat` int(2)
,`jahr` int(4)
,`konto` varchar(20)
,`saldo` decimal(33,2)
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `fi_salden_view`
--
DROP VIEW IF EXISTS `fi_salden_view`;
CREATE TABLE IF NOT EXISTS `fi_salden_view` (
`konto` varchar(20)
,`saldo` decimal(33,2)
);
-- --------------------------------------------------------

--
-- Struktur des Views `fi_buchungen_view`
--
DROP TABLE IF EXISTS `fi_buchungen_view`;

CREATE  VIEW `fi_buchungen_view` AS select 'S' AS `KNZ`,`fi_buchungen`.`buchungsnummer` AS `buchungsnummer`,`fi_buchungen`.`buchungstext` AS `buchungstext`,`fi_buchungen`.`sollkonto` AS `konto`,`fi_buchungen`.`habenkonto` AS `gegenkonto`,`fi_buchungen`.`betrag` AS `betrag`,`fi_buchungen`.`datum` AS `datum` from `fi_buchungen` union select 'H' AS `H`,`fi_buchungen`.`buchungsnummer` AS `buchungsnummer`,`fi_buchungen`.`buchungstext` AS `buchungstext`,`fi_buchungen`.`habenkonto` AS `habenkonto`,`fi_buchungen`.`sollkonto` AS `sollkonto`,(`fi_buchungen`.`betrag` * -(1)) AS `betrag*-1`,`fi_buchungen`.`datum` AS `datum` from `fi_buchungen` order by `buchungsnummer`;

-- --------------------------------------------------------

--
-- Struktur des Views `fi_ergebnisrechnungen`
--
DROP TABLE IF EXISTS `fi_ergebnisrechnungen`;

CREATE  VIEW `fi_ergebnisrechnungen` AS select `base`.`konto` AS `konto`,`base`.`kontenname` AS `kontenname`,`base`.`kontenart_id` AS `kontenart_id`,sum(`base`.`betrag`) AS `saldo` from `fi_ergebnisrechnungen_base` `base` group by `base`.`konto`,`base`.`kontenname`,`base`.`kontenart_id` order by `base`.`konto`;

-- --------------------------------------------------------

--
-- Struktur des Views `fi_ergebnisrechnungen_base`
--
DROP TABLE IF EXISTS `fi_ergebnisrechnungen_base`;

CREATE  VIEW `fi_ergebnisrechnungen_base` AS select 'S' AS `buchungsart`,`b`.`buchungsnummer` AS `buchungsnummer`,`b`.`buchungstext` AS `buchungstext`,`b`.`sollkonto` AS `konto`,`k`.`bezeichnung` AS `kontenname`,`k`.`kontenart_id` AS `kontenart_id`,`b`.`betrag` AS `betrag`,`b`.`datum` AS `datum` from (`fi_buchungen` `b` join `fi_konto` `k` on((`b`.`sollkonto` = `k`.`kontonummer`))) union select 'H' AS `buchungsart`,`b`.`buchungsnummer` AS `buchungsnummer`,`b`.`buchungstext` AS `buchungstext`,`b`.`habenkonto` AS `konto`,`k`.`bezeichnung` AS `kontenname`,`k`.`kontenart_id` AS `kontenart_id`,(`b`.`betrag` * -(1)) AS `betrag`,`b`.`datum` AS `datum` from (`fi_buchungen` `b` join `fi_konto` `k` on((`b`.`habenkonto` = `k`.`kontonummer`))) order by `buchungsnummer`,`buchungsart`;

-- --------------------------------------------------------

--
-- Struktur des Views `fi_salden_jaehrlich_view`
--
DROP TABLE IF EXISTS `fi_salden_jaehrlich_view`;

CREATE  VIEW `fi_salden_jaehrlich_view` AS select year(`fi_buchungen_view`.`datum`) AS `jahr`,`fi_buchungen_view`.`konto` AS `konto`,sum(`fi_buchungen_view`.`betrag`) AS `saldo` from `fi_buchungen_view` group by year(`fi_buchungen_view`.`datum`),`fi_buchungen_view`.`konto`;

-- --------------------------------------------------------

--
-- Struktur des Views `fi_salden_monatlich_view`
--
DROP TABLE IF EXISTS `fi_salden_monatlich_view`;

CREATE  VIEW `fi_salden_monatlich_view` AS select month(`fi_buchungen_view`.`datum`) AS `monat`,year(`fi_buchungen_view`.`datum`) AS `jahr`,`fi_buchungen_view`.`konto` AS `konto`,sum(`fi_buchungen_view`.`betrag`) AS `saldo` from `fi_buchungen_view` group by month(`fi_buchungen_view`.`datum`),year(`fi_buchungen_view`.`datum`),`fi_buchungen_view`.`konto`;

-- --------------------------------------------------------

--
-- Struktur des Views `fi_salden_taeglich_view`
--
DROP TABLE IF EXISTS `fi_salden_taeglich_view`;

CREATE  VIEW `fi_salden_taeglich_view` AS select dayofmonth(`fi_buchungen_view`.`datum`) AS `tag`,month(`fi_buchungen_view`.`datum`) AS `monat`,year(`fi_buchungen_view`.`datum`) AS `jahr`,`fi_buchungen_view`.`konto` AS `konto`,sum(`fi_buchungen_view`.`betrag`) AS `saldo` from `fi_buchungen_view` group by dayofmonth(`fi_buchungen_view`.`datum`),month(`fi_buchungen_view`.`datum`),year(`fi_buchungen_view`.`datum`),`fi_buchungen_view`.`konto`;

-- --------------------------------------------------------

--
-- Struktur des Views `fi_salden_view`
--
DROP TABLE IF EXISTS `fi_salden_view`;

CREATE  VIEW `fi_salden_view` AS select `fi_buchungen_view`.`konto` AS `konto`,sum(`fi_buchungen_view`.`betrag`) AS `saldo` from `fi_buchungen_view` group by `fi_buchungen_view`.`konto`;

