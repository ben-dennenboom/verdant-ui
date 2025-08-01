FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    nodejs \
    npm

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy application files first
COPY . .

# Install Node dependencies and build assets for the library
RUN npm install && npm run build

# Build demo app with proper artisan file
WORKDIR /var/www/demo
RUN if [ -f composer.json ]; then \
    composer install --no-scripts --no-dev --optimize-autoloader && \
    if [ -f ../artisan ]; then cp ../artisan .; fi && \
    composer dump-autoload; \
    fi

# Install demo Node dependencies and build assets
RUN if [ -f package.json ]; then \
    npm install && \
    mkdir -p public/build && \
    npm run build; \
    fi

# Ensure build directory exists and has proper permissions
RUN mkdir -p public/build && \
    touch public/build/manifest.json

# Create Laravel storage directories with proper permissions
RUN mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs storage/app/public \
    && chmod -R 775 storage \
    && chmod -R 775 bootstrap/cache

# Go back to main directory
WORKDIR /var/www

# Change ownership of our applications
RUN chown -R www-data:www-data /var/www

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]
