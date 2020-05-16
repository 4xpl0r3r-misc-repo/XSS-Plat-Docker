FROM mattrayner/lamp:latest-1804
COPY html /var/www/myApp
ARG MYSQL_USER_PASS
RUN sed -i "4c DocumentRoot /var/www/myApp/public"  /etc/apache2/sites-enabled/000-default.conf \
    && sed -i "s/\/var\/www\/html/\/var\/www\/myApp/g"  /etc/apache2/sites-enabled/000-default.conf \
    && sed -i 's/<mysql_password>/'${MYSQL_USER_PASS}'/g' `grep '<mysql_password>' -rl /var/www/myApp` \
    && sed -i '58a mysql -uroot -Dxss-platform < /var/www/myApp/xss-platform.sql' /create_mysql_users.sh