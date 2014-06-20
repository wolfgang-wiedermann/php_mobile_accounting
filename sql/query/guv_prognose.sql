select vormonat.kontonummer, vormonat.bezeichnung, 
       case when vormonat.kontenart_id = 4 then sum(vormonat.betrag)*-1 else sum(vormonat.betrag) end as betrag_vormonat, 
       case when vormonat.kontenart_id = 4 then sum(aktuellermonat.betrag)*-1 else sum(aktuellermonat.betrag) end as betrag_aktuell,
       case when vormonat.kontenart_id = 4 then (sum(aktuellermonat.betrag)*-1)-(sum(vormonat.betrag)*-1)
            else sum(aktuellermonat.betrag)-sum(vormonat.betrag) end as differenz
from (

select konten.kontenart_id, konten.kontonummer, konten.bezeichnung, coalesce(jahr, year(date_sub(now(), interval 1 month))) as jahr, 
       coalesce(monat, month(date_sub(now(), interval 1 month))) as monat, coalesce(betrag, 0) as betrag, konten.mandant_id

from fi_konto as konten
left outer join (

select kontenart_id, kontonummer, bezeichnung, jahr, monat, sum(betrag) as betrag, mandant_id
from (

select k.kontenart_id, k.kontonummer, k.bezeichnung,  year(datum) as jahr, month(datum) as monat, sum(betrag) as betrag, 'S' as buchungstyp, b.mandant_id
from fi_buchungen b
inner join fi_konto k
on b.sollkonto = k.kontonummer
and b.mandant_id = k.mandant_id
where b.mandant_id = #mandant_id#
and year(datum) = year(date_sub(now(), interval 1 month))
and month(datum) = month(date_sub(now(), interval 1 month))
group by k.kontenart_id, k.kontonummer, k.bezeichnung, year(datum), month(datum)

union 

select k.kontenart_id, k.kontonummer, k.bezeichnung, year(datum) as jahr, month(datum) as monat, sum(betrag)*-1 as betrag, 'H' as buchungstyp, b.mandant_id
from fi_buchungen b
inner join fi_konto k
on b.habenkonto = k.kontonummer
and b.mandant_id = k.mandant_id
where b.mandant_id = #mandant_id#
and year(datum) = year(date_sub(now(), interval 1 month))
and month(datum) = month(date_sub(now(), interval 1 month))
group by k.kontenart_id, k.kontonummer, k.bezeichnung, year(datum), month(datum)
) as vormonat_base

group by kontenart_id, kontonummer, bezeichnung, jahr, monat, mandant_id

) as vormonat_aggreg

on konten.mandant_id = vormonat_aggreg.mandant_id
and konten.kontonummer = vormonat_aggreg.kontonummer
where konten.mandant_id = #mandant_id#

) as vormonat,

(

select konten.kontenart_id, konten.kontonummer, konten.bezeichnung, coalesce(jahr, year(now())) as jahr,
       coalesce(monat, month(now())) as monat, coalesce(betrag, 0) as betrag, konten.mandant_id

from fi_konto as konten
left outer join (

select kontenart_id, kontonummer, bezeichnung, jahr, monat, sum(betrag) as betrag, mandant_id
from (

select k.kontenart_id, k.kontonummer, k.bezeichnung,  year(datum) as jahr, month(datum) as monat, sum(betrag) as betrag, 'S' as buchungstyp, b.mandant_id
from fi_buchungen b
inner join fi_konto k
on b.sollkonto = k.kontonummer
and b.mandant_id = k.mandant_id
where b.mandant_id = #mandant_id# 
and year(datum) = year(now())
and month(datum) = month(now())
group by k.kontenart_id, k.kontonummer, k.bezeichnung, year(datum), month(datum)

union

select k.kontenart_id, k.kontonummer, k.bezeichnung, year(datum) as jahr, month(datum) as monat, sum(betrag)*-1 as betrag, 'H' as buchungstyp, b.mandant_id
from fi_buchungen b
inner join fi_konto k
on b.habenkonto = k.kontonummer
and b.mandant_id = k.mandant_id
where b.mandant_id = #mandant_id#
and year(datum) = year(now())
and month(datum) = month(now())
group by k.kontenart_id, k.kontonummer, k.bezeichnung, year(datum), month(datum)
) as aktmonat_base

group by kontenart_id, kontonummer, bezeichnung, jahr, monat, mandant_id

) as aktmonat_aggreg

on konten.mandant_id = aktmonat_aggreg.mandant_id
and konten.kontonummer = aktmonat_aggreg.kontonummer
where konten.mandant_id = #mandant_id#

) as aktuellermonat

where vormonat.kontonummer = aktuellermonat.kontonummer
and vormonat.kontenart_id in (3, 4)
and not (vormonat.betrag = 0 and aktuellermonat.betrag = 0)

group by vormonat.kontonummer, bezeichnung

order by vormonat.kontonummer

