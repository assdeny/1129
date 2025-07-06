FROM php:8.2-apache

# Salin semua file ke direktori Apache default
COPY . /var/www/html/

# Aktifkan mod_rewrite jika perlu
RUN a2enmod rewrite

EXPOSE 80
