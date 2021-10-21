#!/bin/sh

sed -i -- "s/\${MAIL_SMTP_SERVER}/${MAIL_SMTP_SERVER}/g" /etc/msmtprc
sed -i -- "s/\${MAIL_SMTP_PORT}/${MAIL_SMTP_PORT}/g" /etc/msmtprc
sed -i -- "s/\${MAIL_FROM}/${MAIL_FROM}/g" /etc/msmtprc
sed -i -- "s/\${MAIL_USER}/${MAIL_USER}/g" /etc/msmtprc
sed -i -- "s/\${MAIL_PASSWORD}/${MAIL_PASSWORD}/g" /etc/msmtprc

sed -i -- "s%;sendmail_path =%sendmail_path = \"/usr/bin/msmtp -C /etc/msmtprc -t\"%g" /etc/php/8.0/fpm/php.ini
sed -i -- "s%;sendmail_path =%sendmail_path = \"/usr/bin/msmtp -C /etc/msmtprc -t\"%g" /etc/php/8.0/cli/php.ini

# Start supervisord and services
/usr/local/bin/supervisord -n -c /etc/supervisord.conf