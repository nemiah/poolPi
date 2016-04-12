# !/bin/bash

sudo aptitude update

sudo aptitude -y install mysql-server
sudo aptitude -y install phpmyadmin
sudo aptitude -y install php5 php5-dev php-pear php5-sqlite supervisor
sudo pecl -d preferred_state=beta install dio
sudo sed -i 2'i\extension=dio.so' '/etc/php5/cli/php.ini'

sudo a2dissite 000-default

cp RSPi/htaccess /var/www/.htaccess
cp RSPi/werte.htm /var/www/werte.htm
sudo cp config/listen.conf /etc/supervisor/conf.d/listen.conf

sudo service supervisor restart

echo "Set AllowOverride All in Apache!"
