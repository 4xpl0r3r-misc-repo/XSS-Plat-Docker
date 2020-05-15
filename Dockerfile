FROM mattrayner/lamp
RUN  sed -i "4c DocumentRoot /var/www/html/public"  /etc/apache2/sites-enabled/000-default.conf