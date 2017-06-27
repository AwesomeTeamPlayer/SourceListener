FROM php:7.1

ADD . /app
WORKDIR /app

#RUN apt-get update && apt-get install -y libmcrypt-dev

#RUN apt-get install -y gcc make autoconf libc-dev pkg-config
#RUN pecl7.X-sp install redis
RUN pecl install redis

RUN bash -c "echo extension=redis.so > /usr/local/etc/php/conf.d/redis.ini"

#RUN cd /tmp
#RUN wget https://github.com/phpredis/phpredis/archive/php7.zip -O phpredis.zip
#RUN nzip -o /tmp/phpredis.zip && mv /tmp/phpredis-* /tmp/phpredis && cd /tmp/phpredis && phpize && ./configure && make && sudo make install
#RUN touch /etc/php/mods-available/redis.ini && echo extension=redis.so > /etc/php/mods-available/redis.ini
#RUN ln -s /etc/php/mods-available/redis.ini /etc/php/7.0/apache2/conf.d/redis.ini
#RUN ln -s /etc/php/mods-available/redis.ini /etc/php/7.0/fpm/conf.d/redis.ini
#RUN ln -s /etc/php/mods-available/redis.ini /etc/php/7.0/cli/conf.d/redis.ini
