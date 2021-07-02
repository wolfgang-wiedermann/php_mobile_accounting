select * from fi_buchungen
where mandant_id = :mandant_id
  and is_offener_posten = 1
order by buchungsnummer