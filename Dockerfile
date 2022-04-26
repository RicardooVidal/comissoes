# Image
FROM alpine:3.14

# Maintainer
MAINTAINER "Ricardo Vidal <contato@ricardovidal.xyz>"

# Packages
RUN apk update && apk upgrade && apk add \
    npm \
    nginx \
    curl \
    php7 \
    php7-fpm \
    php7-opcache \
    php7-pdo \
    php7-pdo_pgsql \
    php-curl \
    php-mbstring \
    php-dom \
    php-zip \
    php-json \
    php-phar \
    php-simplexml \
    php-xml \
    php-xmlwriter \
    php-tokenizer \ 
    php-fileinfo \
    php-session \
    php-openssl

RUN curl -s https://getcomposer.org/installer | php
RUN mv composer.phar /usr/local/bin/composer

COPY ./.deploy/default.conf /etc/nginx/http.d/default.conf

# Set workdir
WORKDIR /var/www

# Add project to image
COPY . .

# Deploy project
RUN cp .env.prod .env
RUN rm -rf composer.lock
RUN rm -rf package-lock.json
RUN npm install npm@6.14.16
RUN chown -R 100:101 "/root/.npm"
RUN chown -R nginx:nginx .
RUN composer install --optimize-autoloader --no-dev
RUN php . artisan config:clear
RUN php . artisan route:clear
RUN php . artisan cache:clear
RUN php . artisan view:clear
RUN chmod -R 0777 ./storage

RUN ["chmod", "+x", "./entrypoint.sh"]

# Init entrypoint (nginx and php-fpm)
CMD ["./entrypoint.sh"]