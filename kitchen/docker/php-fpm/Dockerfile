FROM php:8.3-rc-fpm-buster

# Install dependencies
RUN apt update && apt install -y \
    build-essential \
    libzip-dev \
    libpng-dev \
    libicu-dev \
    zip \
    libfreetype6-dev \
    libjpeg-dev \
    libxslt1-dev \
    libbz2-dev \
    unzip

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-configure intl \
    && docker-php-ext-install \
      pdo_mysql \
      gd \
      intl \
      mysqli \
      xsl \
      calendar \
      bz2 \
      exif  \
      gettext \
      pcntl \
      sockets \
      sysvmsg \
      sysvsem \
      sysvshm \
      zip
# Clear cache
RUN apt clean && rm -rf /var/lib/apt/lists/*

# Get latest Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Use the default production configuration PHP.INI
ADD ./docker/php-fpm/php.ini $PHP_INI_DIR/php.ini

WORKDIR /var/www/html
ADD ./  /var/www/html

RUN chmod -R 777 storage/ bootstrap/cache
RUN chmod -R 777 /var/www/html/storage/*

RUN composer install \
    && php artisan cache:clear \
    && php artisan route:clear \
    && php artisan view:clear \
    && php artisan config:cache \
    && php artisan config:clear \
    && php artisan vendor:publish --all \
    && php artisan storage:link \
    && composer dump-autoload