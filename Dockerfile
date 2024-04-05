FROM php:8.2-rc-apache-bullseye
WORKDIR /var/www/html
COPY install/docker/000-default.conf /etc/apache2/sites-available/000-default.conf
COPY install/docker/Database.php /var/www/html/lib/Database.php
COPY install/docker/prepare_db.sql /tmp/prepare_db.sql
COPY sql/create-tables-and-views.sql /tmp/create-tables-and-views.sql
COPY sql/sample_kontenplan_single.sql /tmp/sample_kontenplan_single.sql
RUN chmod guo+rw /var/www/html/lib
RUN apt-get update && apt-get install -y \
		mariadb-client \
	&& docker-php-ext-configure pdo_mysql \
	&& docker-php-ext-install -j$(nproc) pdo_mysql
#RUN mysql -h db -u root -pexample < /tmp/prepare_db.sql