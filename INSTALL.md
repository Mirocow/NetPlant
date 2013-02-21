INSTALLATION - Ubuntu 12.04 LTS
===============================

First install needed packages:

	sudo apt-get install mysql-server php5-mysql htop mc iotop bmon zip links php5-curl php-apc git php5-fpm php5-cli php5-common php5-curl php5-dev php5-gd php5-imagick php5-mcrypt php5-mysql nginx-extras zip

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

This will create user, db and import default dump to it.

NetPlant supports local configs. They should be stored in protected/app.
Example configs are provided in protected/app.zip archive.

	cd /opt/NetPlant/protected
	unzip app.zip

Edit your database connection details:
	
	vi app/app.db.php

