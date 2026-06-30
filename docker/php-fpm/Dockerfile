FROM php:8.5-fpm

ARG INSTALL_XDEBUG=false

WORKDIR /app

RUN apt-get update && apt-get install -y \
    libpng-dev \
    libwebp-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    libzip-dev \
    libicu-dev \
    libpq-dev \
    supervisor \
    && docker-php-ext-install zip \
    && docker-php-ext-install pdo_pgsql \
    && docker-php-ext-install pgsql \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd \
    && docker-php-ext-install bcmath \
    && docker-php-ext-install intl

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer --quiet

RUN if [ "$INSTALL_XDEBUG" = "true" ]; then \
        pecl install xdebug && docker-php-ext-enable xdebug; \
    else \
        echo "Skipping Xdebug"; \
    fi

COPY ./docker/php-fpm/xdebug.ini "${PHP_INI_DIR}/conf.d"

COPY ./docker/php/conf.d "/${PHP_INI_DIR}/conf.d"

COPY ./docker/wait-for-it.sh /usr/local/bin/wait-for-it
RUN chmod 555 /usr/local/bin/wait-for-it

EXPOSE 9000

CMD ["php-fpm"]
