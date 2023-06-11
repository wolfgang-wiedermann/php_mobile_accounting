FROM php:8.1-apache
WORKDIR /var/www/html
COPY install/docker/000-default.conf /etc/apache2/sites-available/000-default.conf
COPY install/docker/Database.php /var/www/html/lib/Database.php
RUN apt-get update && apt-get install -y \
		mariadb-client \
	&& docker-php-ext-configure pdo_mysql \
	&& docker-php-ext-install -j$(nproc) pdo_mysql
RUN /bin/bash ./install/docker/prepare_db.sh