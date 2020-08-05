FROM php:7.4-apache

ARG USER_ID
ARG GROUP_ID

RUN if [ ${USER_ID:-0} -ne 0 ] && [ ${GROUP_ID:-0} -ne 0 ]; then \
    userdel -f www-data &&\
    if getent group www-data ; then groupdel www-data; fi &&\
    groupadd -g ${GROUP_ID} www-data &&\
    useradd -l -u ${USER_ID} -g www-data www-data &&\
    install -d -m 0755 -o www-data -g www-data /home/www-data &&\
    chown --changes --silent --no-dereference --recursive \
          --from=33:33 ${USER_ID}:${GROUP_ID} \
        /home/www-data \
;fi
RUN sed -i 's/80/8080/g' /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf        

USER root
RUN apt-get update 
RUN apt-get install -y vim git unzip zlib1g-dev mariadb-client libzip-dev nodejs npm build-essential libssl-dev zlib1g-dev libpng-dev libjpeg-dev libfreetype6-dev 
RUN docker-php-ext-install zip mysqli pdo_mysql \
 && pecl install xdebug \
 && docker-php-ext-enable xdebug \
 && echo 'xdebug.remote_enable=on' >> /usr/local/etc/php/conf.d/xdebug.ini \
 && echo 'xdebug.remote_host=host.docker.internal' >> /usr/local/etc/php/conf.d/xdebug.ini \
 && echo 'xdebug.remote_port=9000' >>  /usr/local/etc/php/conf.d/xdebug.ini \
 && a2enmod rewrite \
 && curl -sS https://getcomposer.org/installer \
  | php -- --install-dir=/usr/local/bin --filename=composer 


USER www-data
