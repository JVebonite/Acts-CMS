FROM php:8.1-fpm-alpine AS base

# Install system dependencies
RUN apk add --no-cache \
    nginx \
    supervisor \
    nodejs \
    npm \
    curl \
    gettext \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    zip \
    unzip \
    oniguruma-dev \
    libxml2-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd xml

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy composer files first for caching
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist

# Copy package files and build frontend assets
COPY package.json package-lock.json* ./
RUN npm install

# Copy application source
COPY . .

# Complete composer setup
RUN composer dump-autoload --optimize \
    && composer run-script post-autoload-dump

# Build frontend assets
RUN npm run build && rm -rf node_modules

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Copy config files
COPY server {
    listen ${PORT};
    server_name _;
    root /var/www/html/public;
    index index.php index.html;

    client_max_body_size 20M;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    location ~* \.(js|css|png|jpg|jpeg|gif|ico|svg|woff|woff2|ttf|eot)$ {
        expires 30d;
        add_header Cache-Control "public, immutable";
    }
}
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/php.ini /usr/local/etc/php/conf.d/custom.ini

# Copy and set entrypoint
COPY #!/bin/sh
set -e

cd /var/www/html

# Create .env file if it doesn't exist
if [ ! -f .env ]; then
    cp .env.example .env
fi

# Generate app key if not set
if [ -z "$APP_KEY" ]; then
    php artisan key:generate --force
fi

# Create storage directories if missing
mkdir -p storage/framework/{sessions,views,cache}
mkdir -p storage/logs
mkdir -p bootstrap/cache

# Set permissions
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Substitute PORT variable in nginx config
export PORT="${PORT:-8080}"
envsubst '${PORT}' < /etc/nginx/http.d/default.conf > /etc/nginx/http.d/default.conf.tmp
mv /etc/nginx/http.d/default.conf.tmp /etc/nginx/http.d/default.conf

# Cache config and routes for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations automatically
php artisan migrate --force

echo "==> Application ready on port $PORT"

exec "$@"
RUN chmod +x /entrypoint.sh

EXPOSE ${PORT};

ENTRYPOINT ["/entrypoint.sh"]
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
