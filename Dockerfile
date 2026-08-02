FROM php:8.2-cli

# Install required tools and PHP extensions
RUN apt-get update && apt-get install -y git unzip libzip-dev \
    && docker-php-ext-install pdo pdo_mysql zip

# Get official Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copy project files (including vendor if present)
COPY . /app

# Ensure dependencies are installed
RUN composer install --ignore-platform-reqs --no-interaction --no-dev || true

# Ensure runtime and web/assets directories exist with proper permissions
RUN mkdir -p runtime web/assets && chmod -R 777 runtime web/assets

ENV PORT=8080

EXPOSE 8080

# Run startup script (directory prep + DB auto-import) then launch PHP server
CMD ["sh", "-c", "mkdir -p runtime web/assets && chmod -R 777 runtime web/assets && php startup.php && php -S 0.0.0.0:${PORT:-8080} -t web/"]
