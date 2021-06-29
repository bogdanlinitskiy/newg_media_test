FROM php:7.4-fpm

RUN apt-get update \
    && apt install -y zlib1g-dev g++ git libicu-dev zip libzip-dev zip \
    && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql pgsql \
    && pecl install apcu \
    && docker-php-ext-enable apcu \
    && docker-php-ext-configure zip \
    && docker-php-ext-install zip


COPY composer.json composer.lock symfony.lock  /var/www/symfony/
WORKDIR /var/www/symfony

RUN curl -sS https://get.symfony.com/cli/installer | bash
RUN mv /root/.symfony/bin/symfony /usr/local/bin/symfony

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install  --optimize-autoloader --no-scripts && composer clear-cache

COPY . /var/www/symfony/

EXPOSE 8080

#Use it as a local file:
#  /root/.symfony/bin/symfony
#
#Or add the following line to your shell configuration file:
#  export PATH="$HOME/.symfony/bin:$PATH"
#
#Or install it globally on your system:
#  mv /root/.symfony/bin/symfony /usr/local/bin/symfony
