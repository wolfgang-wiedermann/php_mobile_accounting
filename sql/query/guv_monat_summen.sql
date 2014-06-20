select kontenart_id, sum(betrag) as saldo
from (

select k.kontenart_id, 'S' as buchungstyp, sum(b.betrag)*-1 as betrag
from fi_buchungen as b
inner join fi_konto as k
on b.sollkonto = k.kontonummer
and b.mandant_id = k.mandant_id
where b.mandant_id = #mandant_id#
and k.kontenart_id in (3, 4)
and (year(b.datum)*100)+month(b.datum) = #monat_id#
group by k.kontenart_id

union

select k.kontenart_id, 'H' as buchungstyp, sum(b.betrag) as betrag
from fi_buchungen as b
inner join fi_konto as k
on b.habenkonto = k.kontonummer
and b.mandant_id = k.mandant_id
where b.mandant_id = #mandant_id#
and k.kontenart_id in (3, 4)
and (year(b.datum)*100)+month(b.datum) = #monat_id#
group by k.kontenart_id

union

select 5, 'S' as buchungstyp, sum(b.betrag)*-1 as betrag
from fi_buchungen as b
inner join fi_konto as k
on b.sollkonto = k.kontonummer
and b.mandant_id = k.mandant_id
where b.mandant_id = #mandant_id#
and k.kontenart_id in (3, 4)
and (year(b.datum)*100)+month(b.datum) = #monat_id#

union

select 5, 'H' as buchungstyp, sum(b.betrag) as betrag
from fi_buchungen as b
inner join fi_konto as k
on b.habenkonto = k.kontonummer
and b.mandant_id = k.mandant_id
where b.mandant_id = #mandant_id#
and k.kontenart_id in (3, 4)
and (year(b.datum)*100)+month(b.datum) = #monat_id#

) as base_view
group by kontenart_id
