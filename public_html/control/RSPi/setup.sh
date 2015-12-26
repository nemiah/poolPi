# !/bin/bash

sudo aptitude -y install php5 php5-dev php-pear php5-sqlite
sudo pecl -d preferred_state=beta install dio
sudo sed -i 2'i\extension=dio.so' '/etc/php5/cli/php.ini'
