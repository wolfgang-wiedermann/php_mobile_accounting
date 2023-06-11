with bebuchte_konten as (
  select distinct fi_konto.*, case when fi_buchungen.mandant_id is null then 0 else 1 end as bebucht
  from fi_konto
  left outer join fi_buchungen
  on fi_konto.mandant_id = fi_buchungen.mandant_id
  and fi_konto.kontonummer in (fi_buchungen.sollkonto, fi_buchungen.habenkonto)
  and year(fi_buchungen.datum) = #year#
) 
select konten.kontonummer as konto,
  konten.bezeichnung as kontenname,
case 
when konten.kontenart_id = 1 then coalesce(soll.betrag, 0)-coalesce(haben.betrag, 0)
else coalesce(haben.betrag, 0)-coalesce(soll.betrag, 0) end as saldo,
konten.bebucht
from bebuchte_konten as konten 
left outer join 
(
  select sollkonto as konto, 'S' as buchungstyp, sum(betrag) as betrag
  from fi_buchungen
  where mandant_id = :mandant_id
  and datum < '#year#-#geschj_start_monat#-01'
  group by sollkonto, 'S'
) as soll
on konten.kontonummer = soll.konto
and konten.mandant_id = :mandant_id
left outer join
(
  select habenkonto as konto, 'H' as buchungstyp, sum(betrag) as betrag
  from fi_buchungen
  where mandant_id = :mandant_id
  and datum < '#year#-#geschj_start_monat#-01'
  group by habenkonto, 'H'
) as haben
on konten.kontonummer = haben.konto
and konten.mandant_id = :mandant_id
where konten.kontenart_id in (1, 2) 
and (soll.konto is not null or haben.konto is not null)
having (saldo > 0 or konten.bebucht)
order by konten.kontonummer
