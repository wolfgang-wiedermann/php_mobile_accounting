select substr(cast(base_view.monat_id as char(6)), 1, 4) as jahr,
  base_view.monat_id as monat_id,
  base_view.kontenart_id,
  fi_kontenart.bezeichnung as kontenart,
  substr(base_view.kontonummer, 1, 1) as k1,
  substr(base_view.kontonummer, 1, 2) as k2,
  base_view.kontonummer as konto,
  base_view.bezeichnung as kontenname,
  case when base_view.kontenart_id = 4
       then format(sum(base_view.betrag)*-1, 2, 'de_DE')
       else format(sum(base_view.betrag), 2, 'de_DE') end as saldo,
  format(sum(base_view.betrag)*-1, 2, 'de_DE') as saldo_summierbar
from (

select (year(b.datum)*100)+month(b.datum) as monat_id, 
k.kontonummer as kontonummer,
k.bezeichnung as bezeichnung,
k.kontenart_id as kontenart_id,
sum(b.betrag) as betrag,
'S' as buchungstyp 
from fi_buchungen as b
inner join fi_konto as k
on b.sollkonto = k.kontonummer
and b.mandant_id = k.mandant_id
where k.mandant_id = #mandant_id#
and k.kontenart_id in (3, 4)
group by (year(b.datum)*100)+month(b.datum), k.kontonummer, k.bezeichnung

union

select (year(b.datum)*100)+month(b.datum) as monat_id, 
k.kontonummer as kontonummer,
k.bezeichnung as bezeichnung,
k.kontenart_id as kontenart_id,
sum(b.betrag)*-1 as betrag,
'H' as buchungstyp
from fi_buchungen as b
inner join fi_konto as k
on b.habenkonto = k.kontonummer
and b.mandant_id = k.mandant_id
where k.mandant_id = #mandant_id#
and k.kontenart_id in (3, 4)
group by (year(b.datum)*100)+month(b.datum), k.kontonummer, k.bezeichnung

) as base_view

  left outer join fi_kontenart
  on base_view.kontenart_id = fi_kontenart.kontenart_id

group by base_view.monat_id, base_view.kontenart_id,
  base_view.kontonummer, base_view.bezeichnung, fi_kontenart.bezeichnung
