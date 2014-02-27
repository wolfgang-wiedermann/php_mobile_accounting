-- Skript, das der Buchungs-Tabelle eine zusätzliche Spalte für die User-ID des
-- Bearbeiters/Erfassenden hinzufügt

alter table fi_buchungen add bearbeiter_user_id integer;

-- TODO: Beim jeweiligen Update prüfen, ob das für die vorliegende Datensituation so passt
--       bei mir passte dieses einfache Statement zufällig
-- update fi_buchungen set bearbeiter_user_id = mandant_id;
