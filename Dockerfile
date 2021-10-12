FROM wyveo/nginx-php-fpm
MAINTAINER Pablo Castro <castrinho8@gmail.com>

RUN rm -rf /usr/share/nginx/html \
    && mkdir /usr/share/nginx/html

COPY . /usr/share/nginx/html
