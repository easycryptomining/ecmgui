# Easy Crypto Mining GUI
Monitor and control your Monero XMR-STAK mining rig, Netgear Arlo wireless security camera and Belkin Wemo switch / insight. 

http://www.easycryptomining.com

![Screenshot](http://www.easycryptomining.com/images/ecmgui.png)

## Features
### Arlo
* Turn On / Off

### Wemo
* Turn On / Off Switch (Mostly used for light, unmetered power plug)
* Turn On / Off Insight (Metered power plug)

### Monero
* View xmr.nanopool.org pending balance (xmr.nanopool.org api)
* Current Monero unit price in € (coinmarketcap.com api)
* Your current wallet amount (static number, must be change every time in code for now)
* Your wallet value based on current unit price and your current wallet amount
* Quick view of up and down rig (If last share sent to xmr.nanopool.org > 1h = KO)
* Quick link to xmr-stak gui
* Real time xmr-stak hashrate of every rig
* Rig uptime (Linux uptime command through ssh with public/private keys)
* Real time power consumption with annual kWh estimation
* Power cost estimation by month and year
* Easy On / Off rig
* Quick view of total pool hashrate (xmr.nanopool.org api)
* Quick link to xmr.nanopool.org wallet account
* Quick link to coinmarketcap.com
* Responsive design (bootstrap v3)
* Mostly skinable :)

## Install
### Nginx
This GUI is tested only with nginx and php-fpm on a Linux server. As there is no authentification process in the GUI (and will not have) if you plan to open the GUI to the world (not in a secure and private LAN), we strongly suggest to implement HTTP Authentication.

### Composer
https://getcomposer.org/download/

### Git
```
git clone https://github.com/easycryptomining/ecmgui.git
cd ecmgui
composer install
```

## Configuration
Copy data/config.php.example to data/config.php then change value in [...].