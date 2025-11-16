# Dockerfile
# Mulai dengan image PHP 8.2 (sesuai composer.json Anda)
FROM php:8.2-cli

# Instalasi tools sistem dan ekstensi PHP yang dibutuhkan Laravel
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    curl \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev

# Instal ekstensi PHP
RUN docker-php-ext-install pdo_mysql gd zip exif pcntl

# Instal Composer (global)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Instal Node.js & npm
RUN curl -sL https://deb.nodesource.com/setup_20.x | bash -
RUN apt-get install -y nodejs

# Tentukan direktori kerja di dalam container
WORKDIR /app

# Salin semua file proyek Anda ke dalam container
COPY . .