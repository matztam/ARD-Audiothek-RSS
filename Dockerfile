FROM php:8.2-apache
COPY ./ardaudiothek-rss.php /var/www/html/index.php
COPY ./index.html /var/www/html/index.html
