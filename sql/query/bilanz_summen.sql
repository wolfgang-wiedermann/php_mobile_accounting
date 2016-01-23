select ka.kontenart_id, ka.bezeichnung,
  case when ka.kontenart_id = 1
  then coalesce(soll.betrag, 0) - coalesce(haben.betrag, 0)
  else coalesce(haben.betrag, 0) - coalesce(soll.betrag, 0) end as saldo
  /* DEBUG: , soll.betrag, haben.betrag */
from fi_kontenart as ka
left outer join (
    select b.mandant_id, k.kontenart_id, sum(b.betrag) as betrag
    from fi_buchungen b
    inner join fi_konto k
        on b.mandant_id = k.mandant_id
        and b.mandant_id = #mandant_id#
        and b.sollkonto = k.kontonummer
        and b.datum < '#year#-#geschj_start_monat#-01'
    group by b.mandant_id, k.kontenart_id
    ) as soll
on ka.kontenart_id = soll.kontenart_id
left outer join (
    select b.mandant_id, k.kontenart_id, sum(b.betrag) as betrag
    from fi_buchungen b
    inner join fi_konto k
          on b.mandant_id = k.mandant_id
          and b.mandant_id = #mandant_id#
          and b.habenkonto = k.kontonummer
          and b.datum < '#year#-#geschj_start_monat#-01'
    group by b.mandant_id, k.kontenart_id
    ) as haben
on ka.kontenart_id = haben.kontenart_id
where ka.kontenart_id in (1, 2)

UNION

select 5 as kontenart_id, 'Gewinn' as Bezeichnung, soll.betrag - haben.betrag
from (
       select b.mandant_id, sum(b.betrag) as betrag
       from fi_buchungen b
         inner join fi_konto k
           on b.mandant_id = k.mandant_id
              and b.mandant_id = #mandant_id#
              and b.sollkonto = k.kontonummer
              and k.kontenart_id in (1, 2)
              and b.datum < '#year#-#geschj_start_monat#-01'
       group by b.mandant_id
     ) as soll
left outer join (
       select b.mandant_id, k.kontenart_id, sum(b.betrag) as betrag
       from fi_buchungen b
         inner join fi_konto k
           on b.mandant_id = k.mandant_id
              and b.mandant_id = #mandant_id#
              and b.habenkonto = k.kontonummer
              and k.kontenart_id in (1, 2)
              and b.datum < '#year#-#geschj_start_monat#-01'
       group by b.mandant_id
     ) as haben
  on soll.mandant_id = haben.mandant_id