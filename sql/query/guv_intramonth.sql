/*
create table fi_hlp_days (xday int not null primary key);

insert into fi_hlp_days
select xday
from
(select (a.day*10)+b.day as xday 
 from (select 0 as day union select 1 union select 2 union select 3) as a, 
 (select 1 as day union select 2 union select 3 union select 4 union select 5 union select 6 union 
  select 7 union select 8 union select 9 union select 0) as b
) as days
where days.xday > 0 and days.xday < 32;
*/

select a.day, 
a.betrag as aufwand,
e.betrag as ertrag,
e.betrag - a.betrag as gewinn 

from (select day, base.kontenart_id, fi_kontenart.bezeichnung, sum(betrag) as betrag

from ((select d.xday as day, 
k.kontenart_id as kontenart_id,
sum(b.betrag) as betrag,
'S' as buchungstyp
from fi_buchungen as b
inner join fi_hlp_days as d
on day(b.datum) <= d.xday
and (year(b.datum)*100)+month(b.datum) = #monat_id#
and b.mandant_id = #mandant_id#
inner join fi_konto as k
on b.sollkonto = k.kontonummer
and b.mandant_id = k.mandant_id
where k.mandant_id = #mandant_id#
and (year(b.datum)*100)+month(b.datum) = #monat_id#
and k.kontenart_id in (3, 4)
group by d.xday, k.kontenart_id
)

union

(
select d.xday as day,
k.kontenart_id as kontenart_id,
sum(b.betrag)*-1 as betrag,
'H' as buchungstyp
from fi_buchungen as b
inner join fi_hlp_days as d
on day(b.datum) <= d.xday
and (year(b.datum)*100)+month(b.datum) = #monat_id#
and b.mandant_id = #mandant_id#
inner join fi_konto as k
on b.habenkonto = k.kontonummer
and b.mandant_id = k.mandant_id
where k.mandant_id = #mandant_id#
and (year(b.datum)*100)+month(b.datum) = #monat_id#
and k.kontenart_id in (3, 4)
group by d.xday, k.kontenart_id
)) as base
inner join fi_kontenart 
on base.kontenart_id = fi_kontenart.kontenart_id

group by base.day, base.kontenart_id, fi_kontenart.bezeichnung) as a

inner join

(select day, base.kontenart_id, fi_kontenart.bezeichnung, sum(betrag) as betrag

from ((select d.xday as day, 
k.kontenart_id as kontenart_id,
sum(b.betrag)*-1 as betrag,
'S' as buchungstyp
from fi_buchungen as b
inner join fi_hlp_days as d
on day(b.datum) <= d.xday
and (year(b.datum)*100)+month(b.datum) = #monat_id#
and b.mandant_id = #mandant_id#
inner join fi_konto as k
on b.sollkonto = k.kontonummer
and b.mandant_id = k.mandant_id
where k.mandant_id = #mandant_id#
and (year(b.datum)*100)+month(b.datum) = #monat_id#
and k.kontenart_id in (3, 4)
group by d.xday, k.kontenart_id
)

union

(
select d.xday as day,
k.kontenart_id as kontenart_id,
sum(b.betrag) as betrag,
'H' as buchungstyp
from fi_buchungen as b
inner join fi_hlp_days as d
on day(b.datum) <= d.xday
and (year(b.datum)*100)+month(b.datum) = #monat_id#
and b.mandant_id = #mandant_id#
inner join fi_konto as k
on b.habenkonto = k.kontonummer
and b.mandant_id = k.mandant_id
where k.mandant_id = #mandant_id#
and (year(b.datum)*100)+month(b.datum) = #monat_id#
and k.kontenart_id in (3, 4)
group by d.xday, k.kontenart_id
)) as base
inner join fi_kontenart 
on base.kontenart_id = fi_kontenart.kontenart_id

group by base.day, base.kontenart_id, fi_kontenart.bezeichnung) as e

on a.day = e.day
and a.kontenart_id = 3
and e.kontenart_id = 4
