#!/usr/bin/env bash
#/bin/sh -q

sudo apt-get update
export DEBIAN_FRONTEND=noninteractive
sudo -E apt-get -q -y upgrade
sudo -E apt-get -q -y install php php-cli php-common php-curl php-gd php-json php-mysql php-readline mysql-server mysql-client libapache2-mod-php apache2 redis-server redis-tools build-essential curl vim libfile-pushd-perl git composer unzip php-simplexml php-mbstring redis-tools redis-server php-redis php-zip php-sqlite3

sudo -E rm -r /var/www
sudo -E ln -s /vagrant /var/www
sudo -E sed -i -e "s/www-data/vagrant/g" /etc/apache2/envvars
sudo -E rm /etc/apache2/sites-enabled/*
sudo dd of=/etc/apache2/sites-available/vagrant.conf <<EOF
<VirtualHost 0.0.0.0:80>
  ServerName zeelo.local
  ServerAdmin albert@horta.name
  DocumentRoot /var/www/zeelo/public

  <Directory /var/www/zeelo/public>
    Options -Indexes +FollowSymLinks
    AllowOverride All
  </Directory>

  ErrorLog /var/log/apache2/error.log
  CustomLog /var/log/apache2/access.log combined
</VirtualHost>
EOF
sudo -E ln -s /etc/apache2/sites-available/vagrant.conf /etc/apache2/sites-enabled/
sudo -E a2enmod access_compat alias auth_basic authn_core authn_file authz_core authz_host authz_user autoindex deflate dir env expires filter headers negotiation reqtimeout rewrite setenvif socache_shmcb ssl status
sudo -E apache2ctl graceful

#Avoiding performance problems on dev, moving log and cache out of the shared folder
mkdir -p /home/vagrant/zeelo/log
mkdir -p /home/vagrant/zeelo/cache
rm -R /vagrant/zeelo/var/log
rm -R /vagrant/zeelo/var/cache
ln -s /home/vagrant/zeelo/log /vagrant/zeelo/var/log
ln -s /home/vagrant/zeelo/cache /vagrant/zeelo/var/cache
#echo 'alias laravel=/home/vagrant/.config/composer/vendor/bin/laravel' >> /home/vagrant/.bashrc
