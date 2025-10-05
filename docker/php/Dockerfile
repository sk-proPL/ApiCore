FROM php:8.4-apache-bookworm

RUN adduser backend

# Required dependencies
RUN apt-get update && apt-get install -y \
  git \
  zip \
  && rm -rf /var/lib/apt/lists/*

# Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# PHP required modeules
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions
RUN install-php-extensions gd pdo_mysql amqp calendar xdebug

COPY docker/php/000-default.conf /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite

USER app
