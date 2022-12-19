FROM php:8.0-apache as base
COPY ./ardaudiothek-rss.php /var/www/html/index.php
