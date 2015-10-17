-- Zusätzliches Feld für die Offene Posten Verwaltung
--
alter table fi_buchungen
add is_offener_posten int(11) NOT NULL DEFAULT 0;