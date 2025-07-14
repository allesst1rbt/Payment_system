FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    libmcrypt-dev \
    libjpeg-dev \
    libpng-dev \
    libwebp-dev \
    libfreetype6-dev \
    libxml2-dev \
    libonig-dev \
    libzip-dev \
    unzip \
    libssl-dev \
    gcc \
    make \
    autoconf \
    && pecl install mcrypt-1.0.7 \
    && docker-php-ext-enable mcrypt \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j$(nproc) \
        gd \
        xml \
        pdo \
        mbstring \
        pdo_mysql \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

        

COPY ./ /var/www/html

WORKDIR /var/www/html


RUN chown -R www-data:www-data /var/www/html
COPY ./docker-php-entrypoint /usr/local/bin/docker-php-entrypoint

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN chmod +x /usr/local/bin/docker-php-entrypoint


EXPOSE 9000