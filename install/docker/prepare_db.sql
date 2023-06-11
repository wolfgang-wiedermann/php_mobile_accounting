create database fibu;
use fibu;

create user 'fibu'@'%' identified by 'fibu';
grant all privileges on fibu.* to 'fibu'@'%';

source /var/www/html/sql/create-tables-and-views.sql
source /var/www/html/sql/create-index.sql

insert into fi_user (user_name, user_description, mandant_id, create_date)
values ('test', 'Testuer', 1, now()), ('fibu', 'Fibu Testuser', 1, now());