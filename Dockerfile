FROM php:8.2-apache

RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

RUN apt-get update \
    && apt install git -y zip libzip-dev \
    && docker-php-ext-install pdo_mysql

# RUN apt-get update \
#     && apt install -y zlib1g-dev g++ git libicu-dev zip libzip-dev \
#     && docker-php-ext-install intl opcache libxml pdo_mysql gd calendar dom mbstring xsl \
#     && pecl install apcu \
#     && docker-php-ext-enable apcu \
#     && docker-php-ext-configure zip \
#     && docker-php-ext-install zip

WORKDIR /var/www/hotel-availability-api

# Install Composer
RUN curl -sSk https://getcomposer.org/installer | php -- --disable-tls && \
   mv composer.phar /usr/local/bin/composer

# Install Symfony CLI
RUN curl -sS https://get.symfony.com/cli/installer | bash
RUN mv /root/.symfony5/bin/symfony /usr/local/bin/symfony

###> recipes ###
###< recipes ###

# RUN composer install & start the server!
CMD bash -c "composer install && symfony server:start"