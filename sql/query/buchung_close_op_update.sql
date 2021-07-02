update fi_buchungen 
set is_offener_posten = 0
where mandant_id = :mandant_id
  and buchungsnummer = :buchungsnummer