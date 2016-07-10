sudo service apache2 stop
sudo service varnish stop

sudo sed -i 's/Listen 80/Listen 8888/g' /etc/apache2/ports.conf
sudo sed -i 's/:80/:8888/g' /etc/apache2/sites-available/default.conf

sudo sed -i 's/#VARNISH_LISTEN_PORT=80/VARNISH_LISTEN_PORT=80/g' /etc/default/varnish
sudo sed -i 's/:8888/:80/g' /etc/default/varnish
sudo sed -i 's/80/8888/g' /var/varnish_config/default.vcl 


sudo service apache2 start
sudo service varnish start
