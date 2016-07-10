#!/usr/bin/env bash

sudo apt-get -y update
sudo add-apt-repository ppa:ondrej/php
sudo apt-get -y update

sudo apt-get install apache2 -y --force-yes

sudo apt-get install  -y --force-yes php7.0 libapache2-mod-php7.0 php7.0 php7.0-common php7.0-gd php7.0-mcrypt php7.0-curl php7.0-intl php7.0-xsl php7.0-mbstring php7.0-zip php7.0-bcmath php7.0-iconv php7.0-cli
sudo apt-get install  -y --force-yes php7.0-dev

sudo apt-get install software-properties-common python-software-properties -y --force-yes


sudo apt-get install -y unzip vim git-core curl wget build-essential python-software-properties
sudo apt-get install git -y
sudo debconf-set-selections <<< 'mysql-server mysql-server/root_password password password'
sudo debconf-set-selections <<< 'mysql-server mysql-server/root_password_again password password'
sudo apt-get -y install mysql-server-5.6 --force-yes

sudo apt-get install apt-transport-https -y --force-yes
sudo curl https://repo.varnish-cache.org/GPG-key.txt | apt-key add -
sudo echo "deb https://repo.varnish-cache.org/ubuntu/ trusty varnish-4.1" >> /etc/apt/sources.list.d/varnish-cache.list
sudo apt-get update
sudo apt-get install varnish -y --force-yes

cd ~
sudo git clone https://github.com/varnish/varnish-modules
cd varnish-modules/src
sudo chmod +x bootstrap
sudo ./bootstrap
sudo ./configure
sudo make
sudo make install

sudo apt-get install sqlite3 libsqlite3-dev -y --force-yes


sudo apt-get install php7.0-mysql -y --force-yes



sudo apt-get install nfs-common portmap -y --force-yes

#Install/configure xdebug

cd ~ 
sudo wget http://xdebug.org/files/xdebug-2.4.0.tgz
tar -xvzf xdebug-2.4.0.tgz
cd xdebug-2.4.0
sudo phpize
sudo ./configure
sudo make
sudo cp modules/xdebug.so /usr/lib/php/20151012
sudo echo "zend_extension = /usr/lib/php/20151012/xdebug.so" >> /etc/php/7.0/apache2/php.ini
sudo echo "xdebug.remote_enable = 1" >> /etc/php/7.0/apache2/php.ini
sudo echo "xdebug.remote_connect_back=1" >> /etc/php/7.0/apache2/php.ini
sudo echo "xdebug.remote_port = 9000" >> /etc/php/7.0/apache2/php.ini
sudo echo "xdebug.scream=0" >> /etc/php/7.0/apache2/php.ini
sudo echo "xdebug.show_local_vars=1" >> /etc/php/7.0/apache2/php.ini
sudo echo "xdebug.idekey=PHPSTORM" >> /etc/php/7.0/apache2/php.ini






curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
sudo rm -rf /etc/apache2
sudo rm -rf /etc/apache2*
#sudo rm -rf /etc/varnish/
#sudo rm -rf /etc/varnish/*
sudo ln -s /root/config/apache2 /etc/apache2
#sudo ln -s /root/config/varnish /etc/varnish
sudo sed -i "s/127.0.0.1:9000/\/var\/run\/php5-fpm.sock/g" "/etc/php5/fpm/pool.d/www.conf"

sudo a2ensite default
sudo a2enmod rewrite
sudo service apache2 restart
sudo apt-get install npm -y --force-yes
sudo apt-get install nodejs -y --force-yes
cd /var/www


#install redis
cd ~
sudo wget http://download.redis.io/redis-stable.tar.gz
tar xvzf redis-stable.tar.gz
cd redis-stable
make
sudo cp src/redis-server /usr/local/bin/
sudo cp src/redis-cli /usr/local/bin/
sudo mkdir /etc/redis
sudo mkdir /var/redis
sudo cp utils/redis_init_script /etc/init.d/redis_6379
sudo cp redis.conf /etc/redis/6379.conf
sudo mkdir /var/redis/6379
sudo sed -i "s/daemonize no/daemonize yes/g" "/etc/redis/6379.conf"
sudo sed -i "s/dir .\//dir \/var\/redis\/6379/g" "/etc/redis/6379.conf"
sudo update-rc.d redis_6379 defaults
/etc/init.d/redis_6379 start

sudo mysql -uroot -ppassword -e 'create database `vagrant`;'

sudo apt-get install zsh -y --force-yes
curl -L http://install.ohmyz.sh | sh


#Install RabbitMQ
sudo echo 'deb http://www.rabbitmq.com/debian/ testing main' | sudo tee /etc/apt/sources.list.d/rabbitmq.list
sudo wget -O- https://www.rabbitmq.com/rabbitmq-release-signing-key.asc | sudo apt-key add -
sudo apt-get update
sudo apt-get install rabbitmq-server -y --force-yes
sudo sed -i "s/#ulimit -n 1024/ulimit -n 1024/g" "/etc/default/rabbitmq-server"
sudo rabbitmq-plugins enable rabbitmq_management
#add a new user with u:p admin:admin
sudo rabbitmqctl add_user admin admin
#make the new `admin` user part of the admin group
sudo rabbitmqctl set_user_tags admin administrator
sudo service rabbitmq-server restart
