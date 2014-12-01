select monat_id as monat_id, kontonummer as konto, 
bezeichnung as kontenname,
case when kontenart_id = 4 then format(sum(betrag)*-1, 2, 'de_DE')
     else format(sum(betrag), 2, 'de_DE') end as saldo
from (

select (year(b.datum)*100)+month(b.datum) as monat_id, 
k.kontonummer as kontonummer,
k.bezeichnung as bezeichnung,
k.kontenart_id as kontenart_id,
sum(b.betrag) as betrag,
'S' as buchungstyp 
from fi_buchungen as b
inner join fi_konto as k
on b.sollkonto = k.kontonummer
and b.mandant_id = k.mandant_id
where k.mandant_id = #mandant_id#
and k.kontenart_id in (3, 4)
group by (year(b.datum)*100)+month(b.datum), k.kontonummer, k.bezeichnung

union

select (year(b.datum)*100)+month(b.datum) as monat_id, 
k.kontonummer as kontonummer,
k.bezeichnung as bezeichnung,
k.kontenart_id as kontenart_id,
sum(b.betrag)*-1 as betrag,
'H' as buchungstyp
from fi_buchungen as b
inner join fi_konto as k
on b.habenkonto = k.kontonummer
and b.mandant_id = k.mandant_id
where k.mandant_id = #mandant_id#
and k.kontenart_id in (3, 4)
group by (year(b.datum)*100)+month(b.datum), k.kontonummer, k.bezeichnung

) as base_view

group by monat_id, kontonummer, bezeichnung
