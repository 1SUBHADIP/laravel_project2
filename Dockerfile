FROM php:8.2-cli

WORKDIR /var/www

RUN apt-get update && apt-get install -y \
    git unzip curl libzip-dev libsqlite3-dev sqlite3 zip \
    && docker-php-ext-install zip pdo_sqlite

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . .

RUN composer install

# create sqlite db
RUN mkdir -p database && touch database/database.sqlite

# permissions
RUN chmod -R 777 storage bootstrap/cache database

EXPOSE 10000

CMD sh -c "mkdir -p storage/framework/{cache,sessions,views} storage/logs bootstrap/cache database && \
        chmod -R 777 storage bootstrap/cache database && \
        if [ \"${DB_CONNECTION:-sqlite}\" = \"sqlite\" ]; then \
            case \"${DB_DATABASE:-}\" in \
                *.sqlite|/*) ;; \
                *) export DB_DATABASE=/var/www/database/database.sqlite ;; \
            esac; \
            touch \"${DB_DATABASE}\"; \
        fi && \
        if [ -z \"${APP_KEY:-}\" ]; then php artisan key:generate --force; fi && \
        php artisan config:clear && \
        php artisan cache:clear && \
        php artisan migrate --force && \
        php artisan serve --host=0.0.0.0 --port=${PORT:-10000}"