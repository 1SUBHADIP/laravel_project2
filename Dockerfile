FROM php:8.2-cli

WORKDIR /var/www

RUN apt-get update && apt-get install -y \
    git unzip curl libzip-dev zip \
    && docker-php-ext-install zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . .

# Install dependencies
RUN composer install

# Create MySQL DB (easy solution)
RUN touch database/database.mysql

# Set permissions
RUN chmod -R 777 storage bootstrap/cache database

# Run migration automatically
RUN php artisan migrate --force

EXPOSE 10000

CMD php artisan serve --host=0.0.0.0 --port=10000