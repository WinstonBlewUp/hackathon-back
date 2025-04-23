FROM php:8.3.20-fpm-alpine

# Install minimal system dependencies
RUN apk add --no-cache \
    git \
    unzip \
    libzip-dev \
    icu-dev \
    bash \
    curl

# Install minimal PHP extensions required for Symfony
RUN docker-php-ext-install \
    pdo_mysql \
    zip \
    intl

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Install Symfony CLI
RUN curl -sS https://get.symfony.com/cli/installer | bash \
    && mv /root/.symfony5/bin/symfony /usr/local/bin/symfony

# Expose port
EXPOSE 8000