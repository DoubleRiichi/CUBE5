FROM php:7.4-apache

RUN docker-php-ext-install pdo pdo_mysql

COPY ./config/httpd-vhosts.dev.conf /etc/apache2/sites-available/000-default.conf
COPY . /var/www/html/

RUN chown -R www-data:www-data /var/www/html/
RUN chmod -R 755 /var/www/html

WORKDIR /var/www/html
RUN a2enmod rewrite
