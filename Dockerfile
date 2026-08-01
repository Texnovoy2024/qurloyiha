FROM php:8.2-cli

# Install required tools and PHP extensions
RUN apt-get update && apt-get install -y git unzip libzip-dev \
    && docker-php-ext-install pdo pdo_mysql zip

# Get official Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copy project files
COPY . /app

# Install PHP dependencies (vendor directory)
RUN composer install --no-dev --optimize-autoloader --no-interaction

ENV PORT=8080

EXPOSE 8080

# Auto-import database on container start and launch server
CMD ["sh", "-c", "php -r '$h=getenv(\"DB_HOST\")?:\"mysql.railway.internal\"; $d=getenv(\"DB_NAME\")?:\"railway\"; $u=getenv(\"DB_USER\")?:\"root\"; $pw=getenv(\"DB_PASSWORD\")?:\"HmTTWnkfZxFBEmyxsDbeEzXzwRujZkNF\"; try { $p=new PDO(\"mysql:host=$h;dbname=$d\",$u,$pw); $p->exec(file_get_contents(\"database/yii2basic.sql\")); echo \"Database imported successfully!\\n\"; } catch(Exception $e) { echo $e->getMessage(); }' && php -S 0.0.0.0:${PORT:-8080} -t web/"]
