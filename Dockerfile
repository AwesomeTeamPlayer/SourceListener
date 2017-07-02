FROM awesometeamplayer/php

ADD . /app
WORKDIR /app

RUN apt install -y php-redis

RUN bash -c "echo extension=redis.so > /usr/local/etc/php/conf.d/redis.ini"

RUN service apache2 start
