FROM ubuntu:20.04

WORKDIR /
RUN DEBIAN_FRONTEND=noninteractive apt-get update \
    && DEBIAN_FRONTEND=noninteractive apt-get upgrade -y \
    && DEBIAN_FRONTEND=noninteractive apt-get install -y \
    	apache2 \
	git \
	libapache2-mod-php \
	php \
	php-pgsql \
	postgresql \
	tzdata \
    && DEBIAN_FRONTEND=noninteractive apt-get autoremove -y \
    && mv /var/www /var/www.orig \
    && mkdir /var/www \
    && git clone https://github.com/yeastgenome/yplp-docker.git /var/www \
    && rm -rf /var/www/html/images /var/www/html/images_sup \
    && mkdir /var/www/html/images /var/www/html/images_sup \
    && mv /var/www/yplp.conf /etc/apache2/sites-available/ \
    && a2enmod socache_shmcb \
    && a2ensite yplp

CMD ["apachectl", "-D", "FOREGROUND"]
