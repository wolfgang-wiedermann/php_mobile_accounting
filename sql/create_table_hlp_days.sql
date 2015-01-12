/*
* Hilfstabelle für Monatsinterne Darstellung einzeln anlegen
*/
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

