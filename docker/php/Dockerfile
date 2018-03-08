FROM php:7.1.8-fpm

RUN apt-get update && apt-get install -y libmcrypt-dev curl nano zip

# images
RUN apt-get update \
	&& apt-get install -y \
	libfreetype6-dev \
	libjpeg62-turbo-dev \
	libpng-dev \
	libgd-dev \
	&& docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
	&& docker-php-ext-install \
		gd \
		exif

# database
RUN docker-php-ext-install \
	mcrypt \
	pdo \
	pdo_mysql

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN pecl install -o -f redis \
    &&  rm -rf /tmp/pear \
    &&  echo "extension=redis.so" > /usr/local/etc/php/conf.d/redis.ini