FROM php:8.2-apache

# Installe les dépendances nécessaires
RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip git curl libpng-dev libonig-dev libxml2-dev libjpeg-dev \
    && docker-php-ext-install pdo pdo_mysql zip gd

# Active mod_rewrite pour Symfony
RUN a2enmod rewrite

# Copie le code source dans le conteneur
COPY . /var/www/html/

# Positionne le répertoire de travail
WORKDIR /var/www/html

# Installe Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Installe les dépendances PHP via Composer
RUN composer install --no-interaction --no-dev --optimize-autoloader

# Donne les droits à Apache
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
