# Dockerfile ejemplo para PHP-FPM (Laravel dev)

FROM php:8.1-fpm

# Instalar librerías necesarias y extensiones PHP
RUN apt-get update && apt-get install -y libpq-dev libzip-dev unzip git && \
    docker-php-ext-install pdo pdo_pgsql pgsql zip

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Crea los directorios storage y bootstrap/cache para evitar errores al hacer chown
RUN mkdir -p storage bootstrap/cache

# Ajustar permisos para el usuario www-data
RUN chown -R www-data:www-data storage bootstrap/cache

# Exponer puerto PHP-FPM
EXPOSE 9000

CMD ["php-fpm"]
