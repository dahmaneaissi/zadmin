FROM php:8.1-apache
RUN a2enmod rewrite
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer


RUN apt-get update && apt-get install -y --no-install-recommends \
    apt-transport-https \
    libicu-dev \
    libzip-dev \
    libxml2-dev \
    libz-dev \
                libpq-dev \
                libjpeg-dev \
                libpng-dev \
                libfreetype6-dev \
                libssl-dev \
                libwebp-dev \
                libxpm-dev \
                libmcrypt-dev \
                libonig-dev \
    git \
    curl \
    zip \
    vim \
    unzip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

RUN set -eux; \
    # Install the PHP gd library
    docker-php-ext-configure gd \
            --prefix=/usr \
            --with-jpeg \
            --with-webp \
            --with-xpm \
            --with-freetype; \
    docker-php-ext-install gd;

RUN pecl install apcu && docker-php-ext-enable apcu
RUN pecl install zip pcov  && docker-php-ext-enable zip
RUN docker-php-ext-install mysqli pdo pdo_mysql intl opcache && docker-php-ext-enable mysqli intl
#RUN pecl install xdebug \
    #&& docker-php-ext-enable xdebug