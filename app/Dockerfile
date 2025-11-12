FROM php:8.3-fpm

# Installe les dépendances nécessaires à Symfony et PDO MySQL
RUN apt-get update && apt-get install -y \
    git unzip libicu-dev libpq-dev libzip-dev zip \
    && docker-php-ext-install intl pdo pdo_mysql zip opcache

# Installe Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Installe Symfony CLI
RUN curl -sS https://get.symfony.com/cli/installer | bash && \
    mv /root/.symfony*/bin/symfony /usr/local/bin/symfony

WORKDIR /var/www/html

# Commande pour démarrer le serveur PHP intégré (accessible de l'extérieur)
CMD ["bash", "-c", "cd /var/www/html/app && php -S 0.0.0.0:8000 -t public"]
