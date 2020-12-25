ARG version
FROM wordpress:${version}

ARG charitable_version

RUN apt-get update
RUN apt-get install -y --no-install-recommends unzip wget

RUN wget https://downloads.wordpress.org/plugin/charitable.${charitable_version}.zip -O /tmp/temp.zip \
    && cd /usr/src/wordpress/wp-content/plugins \
    && unzip /tmp/temp.zip \
    && rm /tmp/temp.zip
