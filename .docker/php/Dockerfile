FROM php:8.1-fpm-alpine
ENV XDEBUG_VERSION 3.1.5

RUN apk add --update --no-cache linux-headers nano icu-dev $PHPIZE_DEPS
RUN pecl install xdebug-${XDEBUG_VERSION}
RUN docker-php-ext-install bcmath intl pdo_mysql
RUN docker-php-ext-enable xdebug

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

CMD ["php-fpm"]
