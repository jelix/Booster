FROM 3liz/liz-php-fpm:7.4

RUN apt-get update && apt-get -y install \
    php${PHP_VERSION}-xdebug \
    mariadb-client; \
    apt-get clean

COPY profile.start /etc/profile.d/start
COPY entrypoint.sh /bin/entrypoint.d/
COPY appctl.sh /bin/
RUN chmod 755 /bin/entrypoint.d/entrypoint.sh /bin/appctl.sh

WORKDIR /srv/booster.jelix.org/
