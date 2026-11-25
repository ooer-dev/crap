FROM dunglas/frankenphp:1.14-php8.4 AS base

# Install PHP extensions
RUN install-php-extensions \
        intl \
        opcache \
        pdo_pgsql \
        zip

# Give www-data ownership of FrankenPHP runtime directories
RUN chown -R www-data:www-data /config/caddy /data/caddy

# Configure PHP for production
COPY .docker/php.ini "$PHP_INI_DIR/php.ini"

COPY .docker/Caddyfile /etc/frankenphp/Caddyfile

# Laravel bootstrap entrypoint (runs optimize caches at container start)
COPY .docker/entrypoint.sh /usr/local/bin/laravel-entrypoint

WORKDIR /app

# Builder stage - install dependencies
FROM base AS builder

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy composer files
COPY composer.json composer.lock ./

# Install dependencies (no dev dependencies)
RUN composer install --no-dev --optimize-autoloader --no-scripts --no-interaction

# Copy application files
COPY . .

# Generate optimized autoloader
RUN composer dump-autoload --optimize

# Production stage
FROM base AS production

# Copy application from builder
COPY --from=builder /app /app

RUN chown -R www-data:www-data bootstrap/cache storage

# Bind FrankenPHP to an unprivileged port; TLS terminates at the gateway
ENV SERVER_NAME=:8080

USER www-data

EXPOSE 8080

ENTRYPOINT ["laravel-entrypoint"]
CMD ["frankenphp", "run", "--config", "/etc/frankenphp/Caddyfile"]
