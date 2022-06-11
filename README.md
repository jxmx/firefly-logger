# Firefly Field Day Logger
Firefly Field Day Logger is an HTML + AJAX web "application" for use 
at [ARRL Field Day](http://www.arrl.org/field-day) operating events. 
The design of this logger is to be a simple, effective logger that 
can be used on any device that can have a reasonably modern browser
installed on it. This includes older laptops running Linux + Chromium,
tablets with Chrome or Firefox, Rasperry Pi 3 or 4 with Raspian, etc.
FFDL has been tested on Windows, Linux, iOS, and Android using the
native browsers on each platform.

Project Home Page: https://mfamily.org/firefly-logger

Live Demo: https://ffdl.packetwarriors.com

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

These generalized installation directions use Debian 11 as most users will
probably want to serve this up from a Raspberry Pi device. In general on
Debian/Raspian 11 you should get a ready stack by executing:

```
apt install apache2 php7.4-fpm mariadb-server php7.4-mysql
systemctl enable php7.4-fpm
systemctl start php7.4-fpm
systemctl enable apache2
systemctl start apache2
systemctl enable mariadb
systemctl start mariadb
```

For Debian/Raspian 10 everything will be the same except the packages for PHP will be php7.3.
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
4. Enable the PHP handling in Apache:

```
a2enconf php7.4-fpm
systemctl restart apache2
```

5. Browse to the application URL

## HTTPS / TLS Considerations

As the web evolves, the reliability of using cookies with sites not using
the https:// (i.e. TLS-encrypted) connections is becoming problematic. It
is strongly recommended that the server is configured to offer https://
connections and clients use those connections. There are known issues
with stock Chromium on Linux with non-TLS-protected cookies. Here's how to enable
https:// connections for this application.

1. Enable the SSL/TLS module and stock site for apache:

```
a2enmod ssl
a2ensite default-ssl
systemctl restart apache2
```

2. Test the TLS configuration by visiting the server on https://. For example
if the hostname is logger.fd.local, browse to https://logger.fd.local. A
warning about a certificate error will appear because the configuration
is using the default "fake" certificate. This is OKAY for non-Internet-connected
purposes. Accept the error and select the browser's equivalent of "trust this site"
or "continue anyway" or "accept the risk and continue". The site should load.

It is strongly recommended to enable HTTPS/TLS on all Firefly Logger installations
to avoid potential cookie problems.

## Upgrading

Upgrading from previous versions is as simple as:

1. Remove all existing files with `rm -rf /var/www/html/*`. Note
if customizations have been made, save/move those first.

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

In-place upgrades of existing databases are not supported. Ensure all data
has been saved/exported before upgrading the system.

## URL Endpoints

The following URL endpoints are the support "entries" into the system:

`/index.html` - Main interface

`/handkey.html` - Interface for hand-keying in a paper log

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

There is minimal configuration necessary. The `api/config_general.json`
contains all generalized site-wide configuration. The following
configuration keys are supported:

| Key          | Options            |
| ------------ | ------------------ |
| stationCall  | The callsign underwhich the operation is being held |
| fdType       | The type of Field Day event. Default is "AFD" for ARRL Field Day. Other supported option is "WFD" for Winter Field Day |
| multiOp      | Permit each logging station to set their own local operator callsign for logging purposes. Values are "N" (default) or "Y". |

To alter the ARRL section list, add or remove the appropriate
settings in `api/config_sections.json`.

To add additional bands, add or remove the bands in the list
found in `api/config_bands.json`. Users of `adif.php` and `cabrillo.php`
will also have to hand-edit those export functions to map added bands appropriately.
A future release will fix this.

## Handkey Interface

The `handkey.html` screen is for someone to hand-enter QSOs from people who 
didn't want to log electronically. Obviously not using the live interface can
cause potential dupes, etc. However this provides a way for someone to either
bulk-enter someone's paper log or provide and interface to someone who likes 
to write the QSO on paper then enter it and still the correct date/time. On 
the main screen, the log time is not editable.

## Issues

For any discovered issues, please file an Issue within Github.
