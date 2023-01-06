FROM php:8.2-fpm-alpine

WORKDIR /var/www/html
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . .

# RUN apk add --no-cache zip libzip libzip-dev p7zip libpq-dev
RUN apk add --no-cache libpq-dev

# RUN docker-php-ext-configure zip
# RUN docker-php-ext-install zip
# RUN docker-php-ext-enable zip

# RUN docker-php-ext-configure pgsql
RUN docker-php-ext-install pgsql
RUN docker-php-ext-enable pgsql

# RUN docker-php-ext-configure pdo_pgsql
RUN docker-php-ext-install pdo_pgsql
RUN docker-php-ext-enable pdo_pgsql

RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# RUN mkdir var
# RUN chmod -R 777 var/

RUN apk add --no-cache nginx
ADD symfony.conf /etc/nginx/http.d/default.conf

EXPOSE 80 443
STOPSIGNAL SIGTERM

CMD ["/bin/sh", "-c", "php-fpm -D && nginx -g 'daemon off;'"]