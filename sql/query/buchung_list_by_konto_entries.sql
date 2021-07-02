SELECT buchungsnummer, buchungstext, habenkonto as gegenkonto, betrag, datum, is_offener_posten 
FROM fi_buchungen  
WHERE mandant_id = :mandant_id and sollkonto = :kontonummer and year(datum) = :jahr 
union 
select buchungsnummer, buchungstext, sollkonto as gegenkonto, betrag*-1 as betrag, datum, is_offener_posten 
from fi_buchungen 
where mandant_id = :mandant_id and habenkonto = :kontonummer and year(datum) = :jahr 
order by buchungsnummer desc