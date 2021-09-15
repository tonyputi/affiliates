FROM nomadnt/php-8-alpine

ARG UID=0
ARG GID=0
ARG USER=www-data

ENV XDEBUG_CONFIG=start_with_request=yes discover_client_host=yes log=/tmp/xdebug.log
ENV XDEBUG_SESSION=affiliates

RUN if [ $UID -ne 0 ] && [ $GID -ne 0 ]; then \
    deluser $USER \
    && addgroup -S -g ${GID} $USER \
	&& adduser -S -u ${UID} -s /bin/sh $USER \
	&& adduser $USER $USER \
    && mkdir -p /home/$USER/.ssh \
    && ssh-keyscan -H bitbucket.org >> /home/$USER/.ssh/known_hosts \
;fi

# add npm
RUN apk update && apk add npm

# turn on app scheduler
RUN echo "* * * * * cd /app && php artisan schedule:run >> /dev/null 2>&1" > /etc/crontabs/$USER

# USER $USER