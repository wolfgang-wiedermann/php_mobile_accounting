/*
 Index zur beschleunigung des Zugriffs auf die einzelnen Mandanten
*/
create index idx_fi_buchungen_soll on fi_buchungen (mandant_id, sollkonto);
create index idx_fi_buchungen_haben on fi_buchungen (mandant_id, habenkonto);
create index idx_fi_buchungen_mandant on fi_buchungen (mandant_id);
