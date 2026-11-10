# Gunakan PHP 8.2 CLI (sesuai dengan requirement Laravel 12)
FROM php:8.2-cli

# Install dependency dan ekstensi PHP
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    curl \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        pdo \
        pdo_mysql \
        mysqli \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Working directory
WORKDIR /var/www

# Copy source code
COPY . .

# Konfigurasi runtime agar sesuai dengan docker-compose
ENV APP_ENV=local
ENV APP_URL=http://localhost:8000
ENV DB_CONNECTION=mysql
ENV DB_HOST=mysql-server
ENV DB_PORT=3306
ENV DB_DATABASE=API_ujikom
ENV DB_USERNAME=api_ujikom
ENV DB_PASSWORD=api_ujikom

# Install dependency Laravel
RUN composer install --no-interaction --prefer-dist --no-progress

# Permission
RUN chmod -R 775 storage bootstrap/cache

# Expose port Laravel
EXPOSE 8000

# Jalankan Laravel
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]