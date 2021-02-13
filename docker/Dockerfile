FROM php:7.4-apache
COPY ./php/php.ini /usr/local/etc/php/
COPY ./apache/*.conf /etc/apache2/sites-enabled/

RUN apt-get update \
  && apt-get install -y zlib1g-dev libpq-dev mariadb-client unzip libzip-dev\
  && docker-php-ext-install zip pdo_mysql mysqli \
  && docker-php-ext-enable mysqli \
  && mv /etc/apache2/mods-available/rewrite.load /etc/apache2/mods-enabled
# /etc/apache2/mods-available/にあるrewrite.loadを/etc/apache2/mods-enabledディレクトリに移動してやる
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER 1
ENV COMPOSER_HOME /composer
ENV PATH $PATH:/composer/vendor/bin

# mod_rewriteを 有効に
RUN /bin/sh -c a2enmod rewrite

WORKDIR /var/www/html

RUN composer global require "laravel/installer"