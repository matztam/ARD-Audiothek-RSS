FROM php:8.3-apache
LABEL org.opencontainers.image.source=https://github.com/matztam/ARD-Audiothek-RSS
COPY ./ardaudiothek-rss.php /var/www/html/index.php
