#!/usr/bin/env bash
#/bin/sh -q

sudo apt-get update
export DEBIAN_FRONTEND=noninteractive
sudo -E apt-get -q -y upgrade
sudo -E apt-get -q -y install php php-cli php-common php-curl php-gd php-json php-mysql php-readline mysql-server mysql-client libapache2-mod-php apache2 redis-server redis-tools build-essential curl vim libfile-pushd-perl git composer unzip php-simplexml php-mbstring redis-tools redis-server php-redis php-zip php-sqlite3 phpunit

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
sudo dd of=/vagrant/zeelo/.env <<EOF
# This file is a "template" of which env vars need to be defined for your application
# Copy this file to .env file for development, create environment variables when deploying to production
# https://symfony.com/doc/current/best_practices/configuration.html#infrastructure-related-configuration

###> symfony/framework-bundle ###
APP_ENV=dev
APP_SECRET=6421a0fc1a6bef25132a2706c73aeb50
#TRUSTED_PROXIES=127.0.0.1,127.0.0.2
#TRUSTED_HOSTS=localhost,example.com
###< symfony/framework-bundle ###

###> doctrine/doctrine-bundle ###
# Format described at http://docs.doctrine-project.org/projects/doctrine-dbal/en/latest/reference/configuration.html#connecting-using-a-url
# For an SQLite database, use: "sqlite:///%kernel.project_dir%/var/data.db"
# Configure your db driver and server_version in config/packages/doctrine.yaml
DATABASE_URL=sqlite:///%kernel.project_dir%/var/data.db
###< doctrine/doctrine-bundle ###
EOF
sudo -E ln -s /etc/apache2/sites-available/vagrant.conf /etc/apache2/sites-enabled/
sudo -E a2enmod access_compat alias auth_basic authn_core authn_file authz_core authz_host authz_user autoindex deflate dir env expires filter headers negotiation reqtimeout rewrite setenvif socache_shmcb ssl status
sudo -E apache2ctl graceful

#Avoiding performance problems on dev, moving log and cache out of the shared folder
mkdir -p /home/vagrant/zeelo/log
mkdir -p /home/vagrant/zeelo/cache
rm -R /vagrant/zeelo/var/log
rm -R /vagrant/zeelo/var/cache
chown vagrant.vagrant /vagrant/zeelo/var/log
chown vagrant.vagrant /vagrant/zeelo/var/cache
ln -s /home/vagrant/zeelo/log /vagrant/zeelo/var/log
ln -s /home/vagrant/zeelo/cache /vagrant/zeelo/var/cache
cd /vagrant/zeelo

#Running composer to load dependencies
composer install
./bin/phpunit

