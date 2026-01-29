# Utiliser PHP 8.3 FPM
FROM php:8.3-fpm

# Définir le dossier de travail
WORKDIR /var/www/backend

# Éviter les prompts interactifs
ENV DEBIAN_FRONTEND=noninteractive

# Mise à jour et installation dépendances de base
RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    unzip \
    zip \
    libicu-dev \
    libzip-dev \
    zlib1g-dev \
    g++ \
    curl \
    nano \
    autoconf \
    pkg-config \
    make \
    libonig-dev \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Installer les extensions PHP séparément pour éviter les conflits
RUN docker-php-ext-install intl opcache pdo pdo_mysql zip

# Installer APCu
# RUN pecl install apcu && docker-php-ext-enable apcu
RUN docker-php-ext-enable apcu


# Installer Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Installer Symfony CLI
RUN curl -sS https://get.symfony.com/cli/installer | bash \
    && mv /root/.symfony5/bin/symfony /usr/local/bin/symfony \
    && chmod +x /usr/local/bin/symfony

# Permissions Windows-friendly
RUN chown -R www-data:www-data /var/www/backend

# Vérification (optionnel)
RUN php -v
RUN composer -V
RUN symfony -v
