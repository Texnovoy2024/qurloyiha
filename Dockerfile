FROM php:8.2-cli

# Install PDO MySQL extension
RUN docker-php-ext-install pdo pdo_mysql

WORKDIR /app

# Copy application files
COPY . /app

ENV PORT=8080

EXPOSE 8080

# Auto-import database if needed, then start PHP web server
CMD ["sh", "-c", "php -r '$h=getenv(\"DB_HOST\")?:\"mysql.railway.internal\"; $d=getenv(\"DB_NAME\")?:\"railway\"; $u=getenv(\"DB_USER\")?:\"root\"; $pw=getenv(\"DB_PASSWORD\")?:\"HmTTWnkfZxFBEmyxsDbeEzXzwRujZkNF\"; try { $p=new PDO(\"mysql:host=$h;dbname=$d\",$u,$pw); $p->exec(file_get_contents(\"database/yii2basic.sql\")); echo \"Database imported successfully!\\n\"; } catch(Exception $e) { echo $e->getMessage(); }' && php -S 0.0.0.0:${PORT:-8080} -t web/"]
