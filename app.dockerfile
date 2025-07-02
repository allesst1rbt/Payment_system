FROM php:8.3-fpm

RUN apt-get update && apt-get install -y git openssl  unzip libmcrypt-dev  libzip-dev libxml2-dev libonig-dev  \
    libmagickwand-dev --no-install-recommends
RUN pecl install mcrypt-1.0.7
RUN docker-php-ext-enable mcrypt
        
RUN docker-php-ext-install gd  tokenizer xml pdo mbstring pdo_mysql

COPY ./ /var/www/html

WORKDIR /var/www/html


RUN chown -R www-data:www-data /var/www/html
COPY ./docker-php-entrypoint /usr/local/bin/docker-php-entrypoint
RUN chmod +x /usr/local/bin/docker-php-entrypoint

EXPOSE 9000