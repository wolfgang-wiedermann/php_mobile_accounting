select sum(betrag) as saldo 
from (
    SELECT sum(betrag) as betrag 
    from fi_buchungen 
    where mandant_id = :mandant_id 
        and sollkonto = :kontonummer

    union 
    
    SELECT sum(betrag)*-1 as betrag 
    from fi_buchungen 
    where mandant_id = :mandant_id 
        and habenkonto = :kontonummer
) as a 