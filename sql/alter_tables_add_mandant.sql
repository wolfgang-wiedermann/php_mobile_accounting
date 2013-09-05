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
ALTER TABLE `fi_buchungen`ADD COLUMN `mandant_id` int(11);
