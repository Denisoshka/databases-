# Dockerfile
FROM php:8.0-apache

# Устанавливаем зависимости для компиляции TypeScript и работы с PDO
RUN apt-get update && apt-get install -y && docker-php-ext-install pdo pdo_mysql

#COPY ./backend /var/www/html/backend
#COPY ./frontend /var/www/html

RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
