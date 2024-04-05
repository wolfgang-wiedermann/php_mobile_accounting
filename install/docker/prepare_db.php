<?php
shell_exec("mysql -h db -u root -pexample < /tmp/prepare_db.sql");
shell_exec("mysql -h db -u root -pexample < /tmp/create-tables-and-views.sql");
shell_exec("mysql -h db -u root -pexample < /tmp/sample_kontenplan_single.sql");
?>