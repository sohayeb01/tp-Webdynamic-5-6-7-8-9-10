FROM php:8.1-apache

WORKDIR /var/www/html/tp10

# Install PostgreSQL PDO extension
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Copy application files
COPY . .

# Set permissions
RUN chown -R www-data:www-data /var/www/html

# Configure Apache DocumentRoot
RUN sed -i 's|/var/www/html|/var/www/html/tp10|g' /etc/apache2/sites-available/000-default.conf

# Expose port
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"] 