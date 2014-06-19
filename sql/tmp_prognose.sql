select vormonat.kontonummer, vormonat.bezeichnung, 
       case when vormonat.kontenart_id = 4 then sum(vormonat.betrag)*-1 else sum(vormonat.betrag) end as betrag_vormonat, 
       case when vormonat.kontenart_id = 4 then sum(aktuellermonat.betrag)*-1 else sum(aktuellermonat.betrag) end as betrag_aktuell,
       case when vormonat.kontenart_id = 4 then (sum(aktuellermonat.betrag)*-1)-(sum(vormonat.betrag)*-1)
            else sum(aktuellermonat.betrag)-sum(vormonat.betrag) end as differenz
from (

select k.kontenart_id, k.kontonummer, k.bezeichnung,  year(datum) as jahr, month(datum) as monat, sum(betrag) as betrag, 'S' as buchungstyp, b.mandant_id
from fi_buchungen b
inner join fi_konto k
on b.sollkonto = k.kontonummer
and b.mandant_id = k.mandant_id
where b.mandant_id = 1
and year(datum) = 2013
and month(datum) = 2
group by k.kontenart_id, k.kontonummer, k.bezeichnung, year(datum), month(datum)

union 

select k.kontenart_id, k.kontonummer, k.bezeichnung, year(datum) as jahr, month(datum) as monat, sum(betrag)*-1 as betrag, 'H' as buchungstyp, b.mandant_id
from fi_buchungen b
inner join fi_konto k
on b.habenkonto = k.kontonummer
and b.mandant_id = k.mandant_id
where b.mandant_id = 1
and year(datum) = 2013
and month(datum) = 2
group by k.kontenart_id, k.kontonummer, k.bezeichnung, year(datum), month(datum)

union 

select distinct k.kontenart_id, k.kontonummer, k.bezeichnung, 2013 as jahr, 2 as monat, 0 as betrag, 'Z' as buchungstyp, k.mandant_id
from fi_konto as k

) as vormonat,

(select k.kontenart_id, k.kontonummer, k.bezeichnung, year(datum) as jahr, month(datum) as monat, sum(betrag) as betrag, 'S' as buchungstyp, b.mandant_id
from fi_buchungen b
inner join fi_konto k
on b.sollkonto = k.kontonummer
and b.mandant_id = k.mandant_id
where b.mandant_id = 1
and year(datum) = 2013
and month(datum) = 3
group by k.kontenart_id, k.kontonummer, k.bezeichnung, year(datum), month(datum)

union 

select k.kontenart_id, k.kontonummer, k.bezeichnung, year(datum) as jahr, month(datum) as monat, sum(betrag)*-1 as betrag, 'H' as buchungstyp, b.mandant_id
from fi_buchungen b
inner join fi_konto k
on b.habenkonto = k.kontonummer
and b.mandant_id = k.mandant_id
where b.mandant_id = 1
and year(datum) = 2013
and month(datum) = 3
group by k.kontenart_id, k.kontonummer, k.bezeichnung, year(datum), month(datum)

union

select distinct k.kontenart_id, k.kontonummer, k.bezeichnung, 2013 as jahr, 3 as monat, 0 as betrag, 'Z' as buchungstyp, k.mandant_id
from fi_konto as k

) as aktuellermonat

where vormonat.kontonummer = aktuellermonat.kontonummer
and vormonat.buchungstyp = aktuellermonat.buchungstyp
and vormonat.kontenart_id in (3, 4)
and not (vormonat.betrag = 0 and aktuellermonat.betrag = 0)

group by vormonat.kontonummer, bezeichnung

order by vormonat.kontonummer

