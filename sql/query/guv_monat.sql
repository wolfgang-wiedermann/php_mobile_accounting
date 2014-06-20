select kontonummer as konto, bezeichnung as kontenname,
case when kontenart_id = 4 then sum(betrag)*-1
     else sum(betrag) end as saldo
from (

select k.kontonummer as kontonummer,
k.bezeichnung as bezeichnung,
k.kontenart_id as kontenart_id,
sum(b.betrag) as betrag,
'S' as buchungstyp 
from fi_buchungen as b
inner join fi_konto as k
on b.sollkonto = k.kontonummer
and b.mandant_id = k.mandant_id
where k.mandant_id = #mandant_id#
and (year(b.datum)*100)+month(b.datum) = #monat_id#
and k.kontenart_id in (3, 4)
group by k.kontonummer, k.bezeichnung

union

select k.kontonummer as kontonummer,
k.bezeichnung as bezeichnung,
k.kontenart_id as kontenart_id,
sum(b.betrag)*-1 as betrag,
'H' as buchungstyp
from fi_buchungen as b
inner join fi_konto as k
on b.habenkonto = k.kontonummer
and b.mandant_id = k.mandant_id
where k.mandant_id = #mandant_id#
and (year(b.datum)*100)+month(b.datum) = #monat_id#
and k.kontenart_id in (3, 4)
group by k.kontonummer, k.bezeichnung

) as base_view

group by kontonummer, bezeichnung
