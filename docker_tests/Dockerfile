FROM php:7.1-cli

ADD . /app
WORKDIR /app

RUN pecl install redis

RUN bash -c "echo extension=redis.so > /usr/local/etc/php/conf.d/redis.ini"

RUN ls -lh
