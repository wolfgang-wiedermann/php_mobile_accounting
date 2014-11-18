select data.mandant_id, data.buchungsart, data.buchungsnummer, data.buchungstext,
 data.konto, k.bezeichnung as konto_bezeichnung, k.kontenart_id,
 data.gegenkonto, format(data.betrag, 2, 'de_DE') as betrag, 
 date_format(data.datum, '%d.%m.%Y') as datum
from (select mandant_id as mandant_id
, 'S' AS buchungsart
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
, 'H' AS buchungsart
, fi_buchungen.buchungsnummer AS buchungsnummer
, fi_buchungen.buchungstext AS buchungstext
, fi_buchungen.habenkonto AS konto
, fi_buchungen.sollkonto AS gegenkonto
, (fi_buchungen.betrag * -(1)) AS betrag
, fi_buchungen.datum AS datum
from fi_buchungen
where mandant_id = #mandant_id#
) as data
inner join fi_konto as k
on k.kontonummer = data.konto
and k.mandant_id = #mandant_id#
order by buchungsnummer, buchungsart;
