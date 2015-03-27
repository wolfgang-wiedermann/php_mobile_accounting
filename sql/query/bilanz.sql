select konten.kontonummer, konten.bezeichnung, 
case 
when konten.kontenart_id = 1 then soll.betrag-haben.betrag 
else haben.betrag - soll.betrag end as betrag 
from fi_konto as konten 
left outer join 
(
  select sollkonto as konto, 'S' as buchungstyp, sum(betrag) as betrag
  from fi_buchungen
  group by sollkonto, 'S'
) as soll
on konten.kontonummer = soll.konto
left outer join
(
  select habenkonto as konto, 'H' as buchungstyp, sum(betrag) as betrag
  from fi_buchungen
  group by habenkonto, 'H'
) as haben
on konten.kontonummer = haben.konto
where konten.kontenart_id in (1, 2) 
and (soll.konto is not null or haben.konto is not null) 
