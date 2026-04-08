# Use the official PHP image with Apache
FROM php:8.2-apache

# Install necessary PHP extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Copy the application source code into the container
COPY . /var/www/html/

# Provide correct ownership to web server
RUN chown -R www-data:www-data /var/www/html/ \
    && chmod -R 755 /var/www/html/

# Create the student_images directory if it doesn't exist and make it writable for uploads
RUN mkdir -p /var/www/html/student_images \
    && chmod -R 777 /var/www/html/student_images

# Expose port 80
EXPOSE 80
