FROM mfrizal/php56-fpm:oci8
WORKDIR "/application"

RUN curl -L "http://download.newrelic.com/php_agent/archive/9.2.0.247/newrelic-php5-9.2.0.247-linux.tar.gz" | tar -C /tmp -zx \
 && export NR_INSTALL_USE_CP_NOT_LN=1 \
 && export NR_INSTALL_SILENT=1 \
 && /tmp/newrelic-php5-*/newrelic-install install \
 && rm -rf /tmp/newrelic-php5-* /tmp/nrinstall*

RUN sed -i -e s/\"REPLACE_WITH_REAL_KEY\"/e636936253e0fe438601fb165aaa9f9690755153/ \    
 -e s/newrelic.appname[[:space:]]=[[:space:]].\*/newrelic.appname="COS_PLN"/ \
 -e s/\;newrelic.daemon.address[[:space:]]=[[:space:]].\*/newrelic.daemon.address="cos-webserver"/ \
    /usr/local/etc/php/conf.d/newrelic.ini

CMD while : ; do php -r "phpinfo();" ; sleep 1 ; done
