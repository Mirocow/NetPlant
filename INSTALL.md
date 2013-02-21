INSTALLATION - Ubuntu 12.04 LTS
===============================

First install needed packages:

	sudo apt-get install mysql-server php5-mysql htop mc iotop bmon zip links php5-curl php-apc git php5-fpm php5-cli php5-common php5-curl php5-dev php5-gd php5-imagick php5-mcrypt php5-mysql nginx-extras zip sudo

Now clone NetPlant to the directory you prefer.

Example with `/opt/NetPlant` under user `root`:

	mkdir /opt; cd /opt
	git clone git://github.com/DevGroup-ru/NetPlant.git
	cd /opt/NetPlant

Now it's time to prepare db for NetPlant:

	mysql -uroot -p

Enter your root password and type this to create user and database(remember to replace `YOURPASSWORD` with your secret password for NetPlant db):
	
	CREATE DATABASE `netplant` CHARACTER SET utf8 COLLATE utf8_general_ci;
	CREATE USER 'netplant'@'localhost' IDENTIFIED BY 'YOURPASSWORD';
	GRANT ALL PRIVILEGES ON netplant.* TO 'netplant'@'localhost';
	USE `netplant`;
	\. docs/db.sql
	\q

This will create user, db and import default dump to it.

NetPlant supports local configs. They should be stored in protected/app.
Example configs are provided in protected/app.zip archive.

	cd /opt/NetPlant/protected
	unzip app.zip

Edit your database connection details:
	
	vi app/app.db.php

As for now we should configure nginx to serve our NetPlant installation.

We have provided an example nginx config under docs folder. Copy it and edit:

	sudo cp /opt/NetPlant/docs/netplant-nginx.conf.distr /etc/nginx/sites-enabled/netplant-nginx.conf
	sudo vi /etc/nginx/sites-enabled/netplant-nginx.conf

Then reload nginx:

	sudo service nginx restart
	sudo service php5-fpm restart

And create Yii-related folders:

	cd /opt/NetPlant/
	mkdir assets protected/runtime
	chmod -R 777 assets protected/runtime

Now you can navigate to your NetPlant hosting panel using browser.
Default credentails are:

	Username: Admin
	Password: admin

## SSH Keys

NetPlant uses ssh to connect to servers and edit configs.
SSH connects using private/public keys.
So, let's create one for our localhost under root:

	ssh-keygen

It'll be better, if you would not protect key with password. Key protected certeficates are not supported yet.
The key is created, so now we should add it to authorized keys:

	ssh-copy-id root@127.0.0.1

Localhost is already added in initial NetPlant dump.