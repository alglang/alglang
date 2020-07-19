FROM php:7.4-apache

RUN apt-get update

# Install base applications: git, gnupg, nodejs, and npm
RUN apt-get update && apt-get install -y \
        git \
        gnupg \
    && curl -sL https://deb.nodesource.com/setup_10.x | bash - \
    && apt-get install -y nodejs
RUN npm install --global yarn

# Add PHP extensions for Laravel
RUN apt-get install -y \
        unzip \
        vim \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        libzip-dev \
    && docker-php-ext-install -j$(nproc) zip mysqli pdo_mysql \
    && docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd

# Create working directory
RUN mkdir -p /var/www/alglang
ENV APACHE_DOCUMENT_ROOT /var/www/alglang/public
ENV APP_NAME "alglang"

# Install composer from image
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Adjust apache configuration
WORKDIR /var/www/alglang
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Enable mod-rewrite
RUN a2enmod rewrite && service apache2 restart

EXPOSE 80
