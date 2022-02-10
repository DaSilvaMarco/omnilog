FROM httpd:2.4
MAINTAINER Thibaut Cholley <thibaut.cholley@omnilog.fr>

# Enable mpm_worker_module, proxy_fcgi_module, rewrite
COPY httpd-vhosts.conf /usr/local/apache2/conf/extra/httpd-vhosts.conf

# Use vhost config from https://symfony.com/doc/4.4/setup/web_server_configuration.html
COPY httpd.conf /usr/local/apache2/conf/httpd.conf

# Add rsync
RUN apt-get update && apt-get install -y rsync
