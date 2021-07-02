select * 
from fi_buchungen
where mandant_id = :mandant_id
order by buchungsnummer desc 
limit 25