FROM php:8.3-apache

# Paquetes base + dependencias para extensiones PHP
RUN apt-get update && apt-get install -y --no-install-recommends \
    curl \
    libonig-dev \
    pkg-config \
    && rm -rf /var/lib/apt/lists/*

# Extensiones necesarias
RUN docker-php-ext-install pdo pdo_mysql mbstring

# PHP y vhost
COPY php.ini /usr/local/etc/php/conf.d/custom.ini
COPY vhost.conf /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite headers

# Descargar Bootstrap local
ENV BOOTSTRAP_VER=5.3.3
RUN mkdir -p /var/www/html/assets/bootstrap && \
    curl -fsSL https://cdn.jsdelivr.net/npm/bootstrap@${BOOTSTRAP_VER}/dist/css/bootstrap.min.css -o /var/www/html/assets/bootstrap/bootstrap.min.css && \
    curl -fsSL https://cdn.jsdelivr.net/npm/bootstrap@${BOOTSTRAP_VER}/dist/js/bootstrap.bundle.min.js -o /var/www/html/assets/bootstrap/bootstrap.bundle.min.js && \
    chown -R www-data:www-data /var/www/html/assets

WORKDIR /var/www/html
