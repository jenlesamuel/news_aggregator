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
    cron

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

# Copy the schedule-runner.sh into the container
COPY ./docker/cron/schedule-runner.sh /usr/local/bin/schedule-runner.sh

# Add the cron job
RUN (crontab -l ; echo "* * * * * /usr/local/bin/schedule-runner.sh") | crontab -

# Expose port 9000
EXPOSE 9000

# Start PHP-FPM
CMD cron && php-fpm