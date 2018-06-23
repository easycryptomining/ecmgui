# Easy Crypto Mining GUI
A web based GUI to monitor and control your Monero XMR-STAK mining rigs, Netgear Arlo wireless security camera, Belkin Wemo switch / insight and Philips Hue. 

http://www.easycryptomining.com

![Screenshot](http://www.easycryptomining.com/images/ecmgui-dashboard.png)
![Screenshot](http://www.easycryptomining.com/images/ecmgui-settings.png)

## Features
### Monero
* View xmr.nanopool.org pending balance (xmr.nanopool.org api)
* Current Monero unit price in € (coinmarketcap.com api)
* Your current wallet balance (static in settings)
* Your wallet value based on current unit price and your current wallet balance
* Quick view of up and down rig (Ping, and last share sent to xmr.nanopool.org)
* Quick link to xmr-stak gui
* Real time hashrate of every rig (xmr-stak api)
* Rig uptime (Through ssh)
* Cpu and Gpu temperature (Through ssh)
* Real time power consumption with annual kWh estimation (with wemo insight)
* Power cost estimation by month and year
* Easy On / Off rig (with wemo insight)
* Quick view of total pool hashrate (xmr-stak api)
* Quick link to xmr.nanopool.org wallet account
* Quick link to coinmarketcap.com
* Responsive design (bootstrap v3)

### Arlo (V2 : Work in progress)
* Turn On / Off

### Wemo (V2 : Work in progress)
* Turn On / Off Switch (Mostly used for light, unmetered power plug)
* Turn On / Off Insight + Power consumption of Monero rigs (Metered power plug)

### Hue
* Work in progress

## Requirement
* A Linux server to host this GUI. At this time only nginx + php-fpm7 is tested on an Ubuntu 16.04 server.
* A Linux based Monero mining rig with openssh-server service, proprietary GPU drivers and xmr-stak (with gui/api enabled) : http://www.easycryptomining.com/monero_ubuntu_16.html
* lm-sensors on the rig : ```sudo apt-get install lm-sensors```
* nvidia-smi on the rig (for Nvidia card) : Should be installed with proprietary GPU drivers

## Install
### Nginx + php-fpm
```
sudo apt-get install nginx php-fpm php-intl php-pdo php-curl php-bcmath php-ssh2 php-mbstring php-sqlite3 php-xml
mkdir /home/www/

vi /etc/nginx/sites-available/default
server {
        listen 80 default_server;
        root /home/www/ecmgui/public;
        index index.php;
        server_name _;
        location / {
                try_files $uri $uri/ /index.php$is_args$args;
        }
        client_max_body_size 100M;
        location ~ \.php$ {
                include snippets/fastcgi-php.conf;
                fastcgi_pass unix:/run/php/php7.0-fpm.sock;
                fastcgi_param  SCRIPT_FILENAME $document_root$fastcgi_script_name;
        }
        # kill cache
        add_header Last-Modified $date_gmt;
        add_header Cache-Control 'no-store, no-cache, must-revalidate, proxy-revalidate, max-age=0';
        if_modified_since off;
        expires off;
        etag off;
}
```
This GUI is meant to be run on a secure and private LAN as there is no authentification process in it. If you plan to open the GUI to the world, it's strongly suggested that you implement HTTP Authentication :
https://www.digitalocean.com/community/tutorials/how-to-set-up-basic-http-authentication-with-nginx-on-ubuntu-14-04

### Composer
```
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('SHA384', 'composer-setup.php') === '544e09ee996cdf60ece3804abc52599c22b1f40f4323403c44d44fdfdd586475ca9813a858088ffbc1f233e9b180f061') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
php composer-setup.php
php -r "unlink('composer-setup.php');"
sudo mv composer.phar /usr/local/bin/composer
```

### Git
```
sudo apt-get install git
```

### Ecmgui
```
git clone https://github.com/easycryptomining/ecmgui.git
cd ecmgui
composer install
cd data
php create_db.php
```