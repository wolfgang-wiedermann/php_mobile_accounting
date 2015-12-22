--
-- Tabellenstruktur für Tabelle `fi_config_params`
--
DROP TABLE IF EXISTS fi_config_params;
CREATE TABLE IF NOT EXISTS fi_config_params (
  mandant_id int(11) NOT NULL,
  param_id int(11) NOT NULL AUTO_INCREMENT,
  param_knz varchar(50) NOT NULL,
  param_desc varchar(100) NOT NULL,
  param_value varchar(256) DEFAULT '',
  PRIMARY KEY (param_id)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

ALTER TABLE fi_config_params ADD UNIQUE fi_config_params_unique_knz(mandant_id, param_knz);

INSERT INTO fi_config_params (mandant_id, param_knz, param_desc, param_value) values
  (1, 'geschj_start_monat', 'Startmonat des Geschaeftsjahres', '1'),
  (1, 'op_schliessen_txt', 'Buchungsvorl. OP schliessen', 'Aufl. OP #op_nr# - #op_btxt#');
