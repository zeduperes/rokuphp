FROM php:8.2.4-apache
RUN apt-get update -y
RUN apt-get install ffmpeg -y
RUN apt-get clean
WORKDIR /var/www/html
RUN service apache2 restart
