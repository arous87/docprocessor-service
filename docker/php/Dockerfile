# Use the official PHP-FPM image as the base image
FROM php:8.2-fpm

# Set working directory
WORKDIR /var/www/project

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libicu-dev \
    libpq-dev \
    libzip-dev \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-install intl pdo pdo_mysql zip opcache \
    && cp /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set permissions for Symfony
RUN usermod -u 1000 www-data && chown -R www-data:www-data /var/www/project

# Copy application code
COPY . /var/www/project

# Expose port 9000 and start PHP-FPM
EXPOSE 9000
CMD ["php-fpm"]