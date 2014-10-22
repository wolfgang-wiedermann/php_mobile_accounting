select base_view.kontenart_id, ka.bezeichnung as bezeichnung, base_view.monat, sum(base_view.betrag) as saldo
from (

select k.kontenart_id, (year(b.datum)*100)+month(b.datum) as monat, 
       'S' as buchungstyp, sum(b.betrag)*-1 as betrag
from fi_buchungen as b
inner join fi_konto as k
on b.sollkonto = k.kontonummer
and b.mandant_id = k.mandant_id
where b.mandant_id = #mandant_id#
and k.kontenart_id in (3, 4)
and (year(b.datum)*100)+month(b.datum) 
    >= (year(date_sub(now(), interval 1 month))*100+month(date_sub(now(), interval 1 month)))
group by k.kontenart_id, (year(b.datum)*100)+month(b.datum)

union

select k.kontenart_id, (year(b.datum)*100)+month(b.datum) as monat, 
       'H' as buchungstyp, sum(b.betrag) as betrag
from fi_buchungen as b
inner join fi_konto as k
on b.habenkonto = k.kontonummer
and b.mandant_id = k.mandant_id
where b.mandant_id = #mandant_id#
and k.kontenart_id in (3, 4)
and (year(b.datum)*100)+month(b.datum)
    >= (year(date_sub(now(), interval 1 month))*100+month(date_sub(now(), interval 1 month)))
group by k.kontenart_id, (year(b.datum)*100)+month(b.datum)

union

select 5, (year(b.datum)*100)+month(b.datum) as monat,
       'S' as buchungstyp, sum(b.betrag)*-1 as betrag
from fi_buchungen as b
inner join fi_konto as k
on b.sollkonto = k.kontonummer
and b.mandant_id = k.mandant_id
where b.mandant_id = #mandant_id#
and k.kontenart_id in (3, 4)
and (year(b.datum)*100)+month(b.datum)
    >= (year(date_sub(now(), interval 1 month))*100+month(date_sub(now(), interval 1 month)))
group by (year(b.datum)*100)+month(b.datum)

union

select 5, (year(b.datum)*100)+month(b.datum) as monat, 
       'H' as buchungstyp, sum(b.betrag) as betrag
from fi_buchungen as b
inner join fi_konto as k
on b.habenkonto = k.kontonummer
and b.mandant_id = k.mandant_id
where b.mandant_id = #mandant_id#
and k.kontenart_id in (3, 4)
and (year(b.datum)*100)+month(b.datum) 
    >= (year(date_sub(now(), interval 1 month))*100+month(date_sub(now(), interval 1 month)))
group by (year(b.datum)*100)+month(b.datum)

) as base_view
inner join fi_kontenart as ka
on base_view.kontenart_id = ka.kontenart_id
group by ka.bezeichnung, monat
having monat is not null
order by monat, ka.bezeichnung
