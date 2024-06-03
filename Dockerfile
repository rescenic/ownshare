# Use the official PHP image with Apache
FROM php:8.1-apache

# Install necessary PHP extensions
RUN apt-get update && apt-get upgrade -y
RUN docker-php-ext-install mysqli pdo pdo_mysql
RUN apt-get install -y \
        libzip-dev \
        zip \
        && docker-php-ext-install zip

# Copy the build directory to the Apache web root
COPY build /var/www/html/

# Set the working directory
WORKDIR /var/www/html

# Set the proper permissions
RUN chown -R www-data:www-data /var/www/html

# Expose port 80
EXPOSE 80