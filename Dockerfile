FROM php:8.1-apache

# Install ekstensi mysqli
RUN docker-php-ext-install mysqli

# Salin semua file ke direktori root apache
COPY . /var/www/html/

# Berikan permission
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
