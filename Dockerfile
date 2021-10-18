FROM wyveo/nginx-php-fpm
LABEL maintainer="Bruno Cabado <sr.brunocabado@gmail.com>"

ARG DEBIAN_FRONTEND=noninteractive

RUN rm -rf /usr/share/nginx/html \
    && mkdir /usr/share/nginx/html

RUN apt update && apt -y install msmtp
COPY ./msmtprc /etc/msmtprc
RUN chmod 600 /etc/msmtprc
RUN chown nginx:nginx /etc/msmtprc

COPY ./src /usr/share/nginx/html

COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
CMD [ "docker-entrypoint.sh" ]