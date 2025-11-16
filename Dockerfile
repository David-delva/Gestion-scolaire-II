# Utiliser l'image officielle PHP 8.2 avec Apache
FROM php:8.2-apache

# Copier la configuration de l'hôte virtuel Apache pour définir le DocumentRoot sur /public
COPY apache-vhost.conf /etc/apache2/sites-available/000-default.conf

# Activer le module rewrite d'Apache pour la prise en charge du .htaccess
RUN a2enmod rewrite

# Installer l'extension PDO MySQL pour la connexion à la base de données
RUN docker-php-ext-install pdo pdo_mysql

# Copier le code de l'application dans le répertoire web du conteneur
COPY . /var/www/html/

# Créer le fichier de log et donner les permissions à Apache
RUN touch /var/www/html/app.log && chown www-data:www-data /var/www/html/app.log

# Exposer le port 80
EXPOSE 80
