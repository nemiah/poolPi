# !/bin/bash

sudo aptitude -y install php5 php5-dev php-pear php5-sqlite supervisor
sudo pecl -d preferred_state=beta install dio
sudo sed -i 2'i\extension=dio.so' '/etc/php5/cli/php.ini'

sudo chmod 777 /var/www
sudo rm /var/www/index.html

cp htaccess /var/www/.htaccess
cp werte.htm /var/www/werte.htm
cp listen.conf /etc/supervisor/conf.d/listen.conf

sudo service supervisor restart