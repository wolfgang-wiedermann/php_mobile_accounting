insert into fi_buchungen (
    mandant_id, buchungstext, sollkonto, habenkonto, 
    betrag, datum, bearbeiter_user_id, is_offener_posten)
values (
    :mandant_id, :buchungstext, :sollkonto, :habenkonto, 
    :betrag, :datum, :bearbeiter_user_id, :is_offener_posten
)