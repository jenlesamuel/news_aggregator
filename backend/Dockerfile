FROM php:8.1-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    cron \
    nano

# Clear the package cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy existing application files
COPY . .

# Set permissions
RUN chown -R www-data:www-data /var/www/html

COPY ./docker/cron/schedule-runner.sh /etc/cron.d/schedule-runner.sh

RUN chmod 0644 /etc/cron.d/schedule-runner.sh && crontab /etc/cron.d/schedule-runner.sh

COPY ./docker/entrypoint.sh /entrypoint.sh

RUN chmod +x /entrypoint.sh

# Expose port 9000
EXPOSE 9000

ENTRYPOINT ["/entrypoint.sh"]