# Firefly Field Day Logger
Firefly Field Day Logger is an HTML + AJAX web "application" for use 
at [ARRL Field Day](http://www.arrl.org/field-day) operating events. 
The design of this logger is to be a simple, effective logger that 
can be used on any device that can have a reasonably modern browser
installed on it. This includes older laptops running Linux + Chromium,
tablets with Chrome or Firefox, Rasperry Pi 3 or 4 with Raspian, etc.
The first version will be tested thoroughly with full OS-type
browsers however the intention is for it to work very well on lightweight
tablets like a Kindle Fire HD 10 with a bluetooth keyboard and mouse.

## Goals / Design Philosophy

- Operate seamlessly across multiple, low-powered devices
- Client side is HTML5 + Javascript
- Server side is a very lightweight PHP + MariaDB (MySQL) system
- Operate over a disconnected local LAN/WLAN - i.e. without Internet
- Server operates with many clients served by a Pi3/4-type device
- Limited feature set necessary for ARRL Field Day and Winter Field Day operations only
- Display page for showing on a screen at Field Days to show score and points
- ADIF export for Logbook of the World (LoTW) or other logging programs
- CSV export for spreadsheet manipulations

## Important Security Note

Please note that Firefly Field Day Logger does **NOT** have the
required web application security to run over the open
Internet. FFDL is intended to be a lightweight application for local use
at an ARRL Field Day over a local LAN/WiFi connection with a group of
well-behaved, well-meaning operators. It does not contain any authentication
security, serious input sanitization, or significant anti-XSS protections.

If you feel like you MUST implement this over the Internet, the best I can
suggest is that the server implements HTTP Basic Authentication at the
server-level. However **DO NOT** plan to actually do this - it is not
recommended.

## Prerequisites 

Firefly Field Day Logger, is not a a turnkey-complete system. Some 
Linux experience will be needed to setup a Pi (or other Linux or whatever)
server that runsMariaDB and PHP. The following components on your platform
of choice will be required.

- PHP v7.3 or greater
- MariaDB v10 or greater (also would work with MySQL)
- Webserver that supports PHP via FastCGI

In general on Debian/Raspian 11 you should get a ready stack by executing:

```
apt install apache2 php7.4-fpm mariadb-server php7.3-mysql
systemctl enable php7.4-fpm
systemctl start php7.4-fpm
systemctl enable apache2
systemctl start apache2
systemctl enable mariadb
systemctl start mariadb
```

For Debian/Raspian 11 everything will be the same except the packages for PHP will be php7.3.
As of 2022, this application will run on both Debian/Raspbian 10 and 11.

If you want to make any minor modifications, a minimal working
knowledge of HTML5 and JavaScript would be helpful.

## Installation

Installation is fairly straight forward.

1. Copy the entire package to your webserver's root directory 
such as `/var/www/html`.

2. Edit `api/db.php` and insert your MariaDB/MySQL connection information.
For the purposes of the documentation, the database, user, and password
are all assumed to be "ffdl".

3. As the root user of MariaDB, execute the following commands - adjusted
for your database, user, and password as well as the on-disk path
to the web root:

```sql
CREATE DATABASE ffdl;
GRANT ALL ON ffdl.* TO 'ffdl'@'localhost' IDENTIFIED BY 'ffdl';
FLUSH PRIVILEGES;
USE ffdl;
SOURCE /var/www/html/load.sql;
```

4. Browse to the application URL

## Upgrading

Upgrading from previous versions is as simple as:

1. Remove all existing files with `rm -rf /var/www/html/*`. Note
if customizations haev been made, save/move those first.

2. Copy the entire package to your webserver's root directory 
such as `/var/www/html`.

3. Edit `api/db.php` and insert your MariaDB/MySQL connection information.
For the purposes of the documentation, the database, user, and password
are all assumed to be "ffdl".

4. As the root user of MariaDB, execute the following commands - adjusted
for your database, user, and password as well as the on-disk path
to the web root:

```sql
USE ffdl;
DROP TABLE QSO;
SOURCE /var/www/html/load.sql;
```

## URL Endpoints

The following URL endpoints are the support "entries" into the system:

`/index.html` - Main interface

`/board.html` - Display "board" for a running tally display screen

`/cabrillo.html` - Download a Cabrillo log of all contacts

`/adif.html` - Download an ADIF export of all contacts

## Testing

To test that the system is installed properly:

1. Add a QSO

2. Edit a QSO

3. Delete a QSO

If those three operations work, you have a successful installation.

To clear out test QSOs and/or to reset the log, as the root user of
MariaDB, execute the following commands:

```sql
USE ffdl;
DELETE FROM QSO;
```

## Basic Use

Firefly Field Day Logger is intended to be an intuitative logger
system. It supports many concurrent users with a minimal footprint.
For each operator, the basic use pattern should be followed:

1. Open a web brower and point it to the hostname or IP address
of your server.

2. Set the station information at the top of the screen. The **callsign**
is the overall callsign used for the activity. The **operator** is
the particular station operator's personal callsign. Callsign and
Operator can be the same call if it's a single-operator situation.

3. Begin logging!

## Configuration

There is minimal configuration necessary. The most important configuration
item is the toggle between ARRL Field Day (default) and Winter Field Day.
On EACH logging station, scroll to the bottom of the screen and change
**Field Day Type** to the correct Field Day as appropriate. This must be
done on every logger. The only thing the toggle changes is the logic
for the class entry box.

To alter the ARRL section list, that is contained within
a JavaScript array at the very top of the `js/index.js` file. Look
for the `var section = [ ]` array. Add or remove sections as needed.
In general, the ARRL Section List will be maintained over time
with the application.

To alter the Band or Mode drop downs, edit the `index.html` and search
for either `<select id="band"` or `<select id="mode"` as desired.
Add or remove `<option>` statements as needed. Make sure that the 
`<option>` specifies both a `value=` and the name between the tags
as the same information.

## Issues

For any discovered issues, please file an Issue within Github.
