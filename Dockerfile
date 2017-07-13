FROM php:7.1

ADD . /app
WORKDIR /app

RUN apt-get update
RUN apt-get install -y git

RUN cd /etc && git clone --depth=1 -b php7 https://github.com/phpredis/phpredis.git \
    && cd /etc/phpredis \
    && phpize \
    && ./configure \
    && make \
    && make install \
    && touch /usr/local/etc/php/conf.d/ext-redis.ini \
    && echo 'extension=redis.so' >> /usr/local/etc/php/conf.d/ext-redis.ini
