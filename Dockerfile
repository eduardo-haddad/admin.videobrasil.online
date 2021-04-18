FROM ubuntu:16.04
ARG xdebug_remote_addr=localhost
ENV RUNLEVEL 1
RUN sed -i "s/^exit 101$/exit 0/" /usr/sbin/policy-rc.d

RUN apt-get update && apt-get install -y apt-utils
RUN apt-get install -y \
    wget \
    curl \
    git \
    zip \
    unzip \
    vim \
    locales \
    software-properties-common \
    python-software-properties \
    graphviz \
    imagemagick

RUN rm -rf /var/www/html/*; rm -rf /etc/apache2/sites-enabled/*; \
    mkdir -p /etc/apache2/external

RUN locale-gen en_US.UTF-8
ENV LANG C.UTF-8
ENV LANGUAGE C.UTF-8
ENV LC_ALL C.UTF-8
RUN /usr/sbin/update-locale

RUN add-apt-repository ppa:ondrej/php && \
    add-apt-repository ppa:ondrej/apache2 && \
    apt-get update && \
    apt-get -y upgrade
RUN DEBIAN_FRONTEND=noninteractive apt-get -y install \
        apache2 \
        openssl \
        libapache2-mod-fastcgi \
        apache2-utils \
        memcached \
        vim
RUN DEBIAN_FRONTEND=noninteractive apt-get -y install \
        php7.1-cli \
        php7.1-curl \
        php7.1-fpm \
        php7.1-mbstring \
        php7.1-memcache \
        php7.1-xdebug \
        php7.1-mysql \
        php7.1-xml \
        php7.1-intl \
        php7.1-gd \
        php7.1-zip

RUN sed -i 's/^ServerSignature/#ServerSignature/g' /etc/apache2/conf-enabled/security.conf; \
    sed -i 's/^ServerTokens/#ServerTokens/g' /etc/apache2/conf-enabled/security.conf; \
    echo "ServerSignature Off" >> /etc/apache2/conf-enabled/security.conf; \
    echo "ServerTokens Prod" >> /etc/apache2/conf-enabled/security.conf; \
    echo "SSLProtocol ALL -SSLv2 -SSLv3" >> /etc/apache2/apache2.conf; \
    echo "xdebug.remote_enable=on" >> /etc/php/7.1/mods-available/xdebug.ini; \
    echo "xdebug.remote_autostart=off" >> /etc/php/7.1/mods-available/xdebug.ini; \
    echo "xdebug.remote_port=9882" >> /etc/php/7.1/mods-available/xdebug.ini; \
    echo "xdebug.remote_host=$xdebug_remote_addr" >> /etc/php/7.1/mods-available/xdebug.ini; \
    echo "xdebug.remote_connect_back=off" >> /etc/php/7.1/mods-available/xdebug.ini; \
    echo "xdebug.remote_log=\"/tmp/xdebug.log\"" >> /etc/php/7.1/mods-available/xdebug.ini

RUN a2dismod mpm_prefork
RUN a2enmod ssl \
        http2 \
        headers \
        proxy \
        proxy_fcgi \
        proxy_fcgi \
        setenvif \
        actions \
        rewrite \
        mpm_event

COPY php-fpm.conf /etc/apache2/conf-available/php-fpm.conf
RUN a2enconf php-fpm

RUN sed -i "s/short_open_tag = Off/short_open_tag = On/" /etc/php/7.1/fpm/php.ini
RUN sed -i "s/short_open_tag = .*$/short_open_tag = On/" /etc/php/7.1/fpm/php.ini
RUN sed -i "s/;user_ini.filename = .*$/user_ini.filename = \".user.dev.ini\"/" /etc/php/7.1/fpm/php.ini

ENV APACHE_RUN_USER www-data
ENV APACHE_RUN_GROUP www-data
ENV APACHE_LOG_DIR /var/log/apache2
ENV APACHE_LOCK_DIR /var/lock/apache2
ENV APACHE_PID_FILE /var/run/apache2.pid

COPY virtual-host.conf /etc/apache2/sites-available/admin.agenteimovel.localhost.conf
RUN cd /etc/apache2/sites-enabled/ && ln -s ../sites-available/admin.agenteimovel.localhost.conf ./

# Instal PHPDoc
RUN wget https://github.com/phpDocumentor/phpDocumentor2/releases/download/v2.9.0/phpDocumentor.phar
RUN cp phpDocumentor.phar /usr/bin/phpdoc
RUN chmod a+x /usr/bin/phpdoc
RUN rm phpDocumentor.phar

# Install Composer
RUN curl -sS https://getcomposer.org/installer | \
    php -- --install-dir=/usr/bin/ --filename=composer

# Change directories permission for Laravel to work
CMD chmod -R 777 /var/www/admin/v2/storage
CMD chmod -R 777 /var/www/admin/v2/bootstrap/cache

# Create symlink to access local storage from browser
CMD cd /var/www/admin/v2 && php artisan storage:link

RUN mkdir -p 777 /var/www/output/sitemap

#Setup python virtualenv
CMD sudo rm -rf /var/www/vhosts/agenteimovel.com/admin/python-scripts/virtualenv
CMD sudo python3 -m venv /var/www/vhosts/agenteimovel.com/admin/python-scripts/virtualenv
CMD sudo chmod -R 777 /var/www/vhosts/agenteimovel.com/admin/python-scripts/
CMD source /var/www/vhosts/agenteimovel.com/admin/python-scripts/virtualenv/bin/activate
CMD pip install -r /var/www/vhosts/agenteimovel.com/admin/python-scripts/requirements.txt
CMD deactivate

# Setup Laravel Task Scheduling (Cronjob)
#RUN (crontab -l ; echo "* * * * * php /var/www/admin/v2/artisan schedule:run >> /dev/null 2>&1") | crontab

# Start Cron
RUN service cron start

ADD entrypoint.sh /opt/entrypoint.sh
RUN chmod a+x /opt/entrypoint.sh

EXPOSE 80
EXPOSE 443
EXPOSE 9882
ENTRYPOINT ["/opt/entrypoint.sh"]
CMD /etc/init.d/memcached start && service php7.1-fpm start && apache2ctl start && tail -f /var/log/apache2/error.log
