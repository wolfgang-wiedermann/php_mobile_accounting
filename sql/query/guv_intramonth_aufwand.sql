select t1.tag, t1.betrag as vormonat, t2.betrag as aktuell
from 
(
select d.xday as tag, 
sum(a.betrag) as betrag
from 

fi_hlp_days as d
left outer join
(
select 
sum(b.betrag) as betrag,
day(b.datum) as tag
from fi_buchungen as b
inner join fi_konto as k
on b.mandant_id = k.mandant_id
and b.sollkonto = k.kontonummer
where 
k.kontenart_id = 3
and b.mandant_id = #mandant_id#
and (year(date_add(b.datum, INTERVAL 1 MONTH))*100)+month(date_add(b.datum, INTERVAL 1 MONTH)) = #month_id#
group by day(b.datum)

union

select
sum(b.betrag) * -1 as betrag,
day(b.datum) as tag
from fi_buchungen as b
inner join fi_konto as k
on b.mandant_id = k.mandant_id
and b.habenkonto = k.kontonummer
where 
k.kontenart_id = 3
and b.mandant_id = #mandant_id#
and (year(date_add(b.datum, INTERVAL 1 MONTH))*100)+month(date_add(b.datum, INTERVAL 1 MONTH)) = #month_id#
group by day(b.datum)
) as a
on a.tag <= d.xday

group by d.xday
) as t1
inner join
(
select d.xday as tag, 
sum(a.betrag) as betrag
from fi_hlp_days as d
left outer join
(
select 
sum(b.betrag) as betrag,
day(b.datum) as tag
from fi_buchungen as b
inner join fi_konto as k
on b.mandant_id = k.mandant_id
and b.sollkonto = k.kontonummer
where 
k.kontenart_id = 3
and b.mandant_id = #mandant_id#
and (year(b.datum)*100)+month(b.datum) = #month_id#
group by day(b.datum)

union

select
sum(b.betrag) * -1 as betrag,
day(b.datum) as tag
from fi_buchungen as b
inner join fi_konto as k
on b.mandant_id = k.mandant_id
and b.habenkonto = k.kontonummer
where 
k.kontenart_id = 3
and b.mandant_id = #mandant_id#
and (year(b.datum)*100)+month(b.datum) = #month_id#
group by day(b.datum)
) as a
on a.tag <= d.xday

group by d.xday
) as t2
on t1.tag = t2.tag
