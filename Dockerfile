# --- PLANO PARA APP DE LARAVEL (con CSS estático) ---

# "Oye Docker, usa una imagen de PHP 8.2 (o la que uses)"
FROM php:8.2-cli-alpine

# --- LA CORRECCIÓN ---
# Instala las dependencias del sistema Y LAS DE COMPILACIÓN
RUN apk add --no-cache \
    curl \
    libzip-dev \
    unzip \
    build-base \
    mariadb-dev \
    oniguruma-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring zip \
    && apk del build-base mariadb-dev oniguruma-dev # Limpiamos (opcional pero bueno)
# --- FIN DE LA CORRECCIÓN ---

# Instala Composer por separado
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer

WORKDIR /app

# Copia solo las dependencias de Composer (para caché)
COPY composer.json composer.lock ./

# Instala las dependencias SIN correr scripts (porque 'artisan' no existe)
RUN composer install --no-dev --no-interaction --no-scripts --optimize-autoloader

# Copia todo el proyecto (AHORA SÍ COPIA 'artisan')
COPY . .

# Ahora que 'artisan' existe, corremos los scripts que faltaban
RUN php artisan package:discover --ansi

# Expone el puerto que Google Cloud Run espera
EXPOSE 8000

# --- NUEVO COMANDO DE INICIO ---
# Cuando Google encienda el contenedor, primero creará el caché
# (usando las variables de Google) y LUEGO iniciará el servidor.
CMD sh -c "php artisan config:cache && php artisan route:cache && php artisan view:cache && php artisan serve --host=0.0.0.0 --port=8000"