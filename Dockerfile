FROM php:8.2-fpm

# Installiere Abhängigkeiten
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# Installiere PHP-Erweiterungen
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Hole Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Setze Arbeitsverzeichnis
WORKDIR /var/www

COPY . /var/www

# Setze Berechtigungen
RUN chown -R www-data:www-data /var/www

EXPOSE 9000

CMD ["./start.sh"]

