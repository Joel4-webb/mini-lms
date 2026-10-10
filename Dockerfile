FROM php:8.2-apache

# 1. Installation des dépendances système (Node.js, Git, SQLite)
RUN apt-get update && apt-get install -y libzip-dev zip unzip git nodejs npm sqlite3 libsqlite3-dev

# 2. Installation des extensions PHP requises par Laravel
RUN docker-php-ext-install pdo_sqlite pdo_mysql bcmath

# 3. Activation de la réécriture d'URL pour Apache
RUN a2enmod rewrite

# 4. Configuration d'Apache pour pointer vers le dossier "public" de Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 5. Copie des fichiers de votre projet
COPY . /var/www/html

# 6. Installation de Composer (gestionnaire PHP)
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# 7. Installation des dépendances et compilation du design (Tailwind/Alpine)
RUN composer install --no-dev --optimize-autoloader
RUN npm install && npm run build

# 8. Création du fichier SQLite et gestion des permissions pour Apache
RUN touch /var/www/html/database/database.sqlite
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database