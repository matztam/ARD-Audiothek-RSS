FROM php:8.5-apache
LABEL org.opencontainers.image.source=https://github.com/matztam/ARD-Audiothek-RSS
COPY ./ardaudiothek-rss.php /var/www/html/index.php
COPY ./index.html /var/www/html/index.html
