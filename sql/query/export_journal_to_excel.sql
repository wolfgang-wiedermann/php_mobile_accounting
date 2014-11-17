select * 
from (select mandant_id as mandant_id
, 'S' AS KNZ
, fi_buchungen.buchungsnummer AS buchungsnummer
, fi_buchungen.buchungstext AS buchungstext
, fi_buchungen.sollkonto AS konto
, fi_buchungen.habenkonto AS gegenkonto
, fi_buchungen.betrag AS betrag
, fi_buchungen.datum AS datum
from fi_buchungen
where mandant_id = #mandant_id#
union
select mandant_id as mandant_id
, 'H' AS KNZ
, fi_buchungen.buchungsnummer AS buchungsnummer
, fi_buchungen.buchungstext AS buchungstext
, fi_buchungen.habenkonto AS habenkonto
, fi_buchungen.sollkonto AS sollkonto
, (fi_buchungen.betrag * -(1)) AS betrag
, fi_buchungen.datum AS datum
from fi_buchungen
where mandant_id = #mandant_id#
) as data
order by buchungsnummer, KNZ;
