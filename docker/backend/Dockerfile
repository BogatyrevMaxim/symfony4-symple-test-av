FROM php:7.3-fpm

RUN apt-get update -yqq \
	&& apt-get install libicu-dev zlibc zlib1g libzip-dev zlib1g-dev unzip tar g++ apt-transport-https sudo git wget libpq-dev -yqq

RUN pecl install xdebug-2.7.1 \
	&& docker-php-ext-enable xdebug \
	&& docker-php-ext-install mbstring \
	&& docker-php-ext-install zip \
	&& docker-php-ext-configure intl \
    && docker-php-ext-install intl \
    && docker-php-ext-install pdo pdo_pgsql

# composer
RUN curl -sS https://getcomposer.org/installer | php \
	&& mv composer.phar /usr/local/bin/composer \
	&& chmod +x /usr/local/bin/composer
