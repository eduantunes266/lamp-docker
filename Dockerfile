# Official PHP 8.3 image with Apache, plus the extensions needed to talk to MySQL/MariaDB
FROM php:8.3-apache

RUN docker-php-ext-install mysqli pdo_mysql
