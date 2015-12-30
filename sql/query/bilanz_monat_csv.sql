select 
  t.jahr, 
  t.monat,
  substr(k.kontonummer, 1, 1) as k1,
  k.kontonummer, 
  k.bezeichnung, 
  k.kontenart_id, 
  format(sum(
    case when b.sollkonto = b.habenkonto then 0
         when k.kontonummer = b.sollkonto then b.betrag 
	       when k.kontonummer = b.habenkonto then b.betrag * -1
    end
  ), 2, 'de_DE') as saldo
from fi_buchungen as b
  inner join fi_konto as k
  on b.mandant_id = #mandant_id#
    and k.mandant_id = #mandant_id#
    and b.mandant_id = k.mandant_id -- nur zur Sicherheit, falls am Code was geändert wird
    and (
      b.sollkonto = k.kontonummer
      or b.habenkonto = k.kontonummer
    )
    and k.kontenart_id in (1, 2)
  inner join (
    select distinct year(bint.datum) as jahr, month(bint.datum) as monat 
    from fi_buchungen as bint
  ) as t
  on (year(b.datum)*100)+month(b.datum) <= (t.jahr*100)+t.monat
group by t.jahr, t.monat, k.kontonummer, substr(k.kontonummer, 1, 1), k.bezeichnung, k.kontenart_id
