# Easy Crypto Mining GUI
A web based GUI to monitor and control your Monero XMR-STAK mining rigs, Netgear Arlo wireless security camera and Belkin Wemo switch / insight. 

http://www.easycryptomining.com

![Screenshot](http://www.easycryptomining.com/images/ecmgui.png)

## Features
### Arlo
* Turn On / Off

### Wemo
* Turn On / Off Switch (Mostly used for light, unmetered power plug)
* Turn On / Off Insight + Power consumption of Monero rigs (Metered power plug)

### Monero
* View xmr.nanopool.org pending balance (xmr.nanopool.org api)
* Current Monero unit price in € (coinmarketcap.com api)
* Your current wallet amount (static number, must be change every time in code for now)
* Your wallet value based on current unit price and your current wallet amount
* Quick view of up and down rig (If last share sent to xmr.nanopool.org > 1h = KO)
* Quick link to xmr-stak gui
* Real time hashrate of every rig (xmr-stak api)
* Rig uptime (Linux uptime command through ssh with public/private keys)
* Cpu and Gpu temperature
* Real time power consumption with annual kWh estimation (with wemo insight)
* Power cost estimation by month and year
* Easy On / Off rig
* Quick view of total pool hashrate (xmr-stak api)
* Quick link to xmr.nanopool.org wallet account
* Quick link to coinmarketcap.com
* Responsive design (bootstrap v3)
* Mostly skinable :)

## Requirement
* A Linux server to host this GUI, with a webserver and php. At this time only nginx + php-fpm7 is tested on an Ubuntu 16.04 server : ```sudo apt-get install nginx php-fpm php-xdebug```
* python : ```sudo apt-get install python```
* php-ssh2 : ```sudo apt-get install php-ssh2```
* php-curl : ```sudo apt-get install php-curl```
* A Linux based Monero mining rig with openssh-server service, proprietary GPU drivers and xmr-stak (with gui/api enabled) : http://www.easycryptomining.com/monero_ubuntu_16.html
* lm-sensors on the rig : ```sudo apt-get install lm-sensors```
* nvidia-smi on the rig (for Nvidia card) : Should be installed with proprietary GPU drivers

## Install
### Nginx
This GUI is meant to be run on a secure and private LAN as there is no authentification process in it. If you plan to open the GUI to the world, it's strongly suggested that you implement HTTP Authentication :
https://www.digitalocean.com/community/tutorials/how-to-set-up-basic-http-authentication-with-nginx-on-ubuntu-14-04

### Composer
https://getcomposer.org/download/

### Git
```
sudo apt-get install git
git clone https://github.com/easycryptomining/ecmgui.git
cd ecmgui
composer install
```

## Configuration
Copy data/config.php.example to data/config.php then change value in [...].