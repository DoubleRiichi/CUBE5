FROM php:7.4-apache

ARG APP_ENV
ARG APPACHE_PORT

RUN docker-php-ext-install pdo pdo_mysql

RUN docker-php-ext-install -j$(nproc) opcache pdo_mysql
COPY ./config/httpd-vhosts.${APP_ENV}.conf /etc/apache2/sites-available/000-default.conf
COPY ./backend/ /etc/apache2/App/Backend/

RUN chown -R www-data:www-data /etc/apache2/App/Backend/
RUN chmod -R 755 /etc/apache2/App/Backend

WORKDIR /etc/apache2/App/Backend/
RUN a2enmod rewrite
COPY .${APP_ENV}.env /etc/apache2/App/Backend/.env
