FROM php:8.0-apache

RUN apt-get update

# 1. development packages
RUN apt-get install -y \
    git \
    zip \
    curl \
    sudo \
    unzip \
    libicu-dev \
    libbz2-dev \
    libpng-dev \
    libjpeg-dev \
    libmcrypt-dev \
    libreadline-dev \
    libfreetype6-dev \
    g++ \
    npm \
    nodejs \
    yarnpkg \
    neovim

# 2. apache configs + document root
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf

# Cors
RUN echo "<Directory \"${APACHE_DOCUMENT_ROOT}\">\n        Header set Access-Control-Allow-Origin \"*\"\n        Header set Access-Control-Allow-Headers \"Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With\"\n</Directory>\n" > /etc/apache2/conf-available/cors.conf
RUN ln -sf /etc/apache2/conf-available/cors.conf /etc/apache2/conf-enabled/

# 3. mod_rewrite for URL rewrite and mod_headers
#    for .htaccess extra headers like Access-Control-Allow-Origin-
RUN a2enmod rewrite headers

# 4. start with base php config, then add extensions
RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"
RUN docker-php-ext-install \
    bz2 \
    intl \
    iconv \
    bcmath \
    opcache \
    calendar \
    pdo_mysql
# mbstring \
#    zip \

RUN npm install -g vue @vue/cli

# 5. composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 6. we need a user with the same UID/GID with host user
#    so when we execute CLI commands, all the host file's
#    ownership remains intact, otherwise command from inside
#    container will create root-owned files and directories
ARG uid
RUN useradd -G www-data,root -u $uid -d /home/devuser devuser
RUN mkdir -p /home/devuser/.composer && \
    chown -R devuser:devuser /home/devuser
