# --- PLANO PARA APP DE LARAVEL (con CSS estático) ---

FROM php:8.2-cli-alpine

# Instala las dependencias del sistema
RUN apk add --no-cache \
    curl \
    libzip-dev \
    unzip \
    build-base \
    mariadb-dev \
    oniguruma-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring zip \
    && apk del build-base mariadb-dev oniguruma-dev

# Instala Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer

WORKDIR /app

# Copia dependencias de Composer
COPY composer.json composer.lock ./

# Instala dependencias
RUN composer install --no-dev --no-interaction --no-scripts --optimize-autoloader

# Copia todo el proyecto
COPY . .

# --- NUEVA LÍNEA DE SEGURIDAD ---
# Esto borra cualquier caché vieja que venga de tu PC. Vital para evitar el error "cloud".
RUN rm -f bootstrap/cache/config.php

# Genera el mapa de clases
RUN php artisan package:discover --ansi

# Expone el puerto
EXPOSE 8000

# Comando de inicio
CMD sh -c "php artisan optimize:clear && php artisan serve --host=0.0.0.0 --port=$PORT"