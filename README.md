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

## Features
- Full client in a browser; operates seamlessly across multiple, low-powered devices such as old laptops running lightweight Linux, Pis, low-cost tablets
- Network environment for common operating picture
- Realtime duplicates checking/avoidance
- Format checking on all fields for high-accuracy logging
- Ability to hand-key paper logs as needed
- Does not require the Internet; Operate over a disconnected local network or WiFi
- Display page for showing on a screen at Field Days to show score and points
- ADIF export for Logbook of the World (LoTW) or other logging programs
- Cabrillo export for score reporting

## Important Security Note

Please note that Firefly Field Day Logger does **NOT** have the
required web application security to run over the public
Internet. FFDL is intended to be a lightweight application for local use
at a Field Day operation over a local LAN/WiFi connection with a group of
well-behaved, well-meaning operators. It does not contain any authentication
security, serious input sanitization, or significant anti-XSS protections.

If you feel like you MUST implement this over the Internet, the best I can
suggest is that the server implements HTTP Basic Authentication at the
server-level. However **DO NOT** plan to actually do this - it is not
recommended.

## Jumpstart Installer
Firefly Field Day Logger has a companion project [FFDL Jumpstart](https://github.com/jxmx/ffdl-jumpstart)
to create a seamless installation experience on a Raspberry Pi running Raspberry Pi OS (Raspian) 11.

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
such as `/var/www/html`. The files and directory should be 
owned by the user running the webserver. For Debian this user is `www-data`
and for Fedora/Red Hat this is `apache`. A quick fix is `chown -R www-data:www-data /var/www/html`.

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

Entering the root user of MariaDB uses the command `mysql -u root ffdl`. If your
database has a password, include `-p` after the word `root`.

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
such as `/var/www/html`. Set the ownership as specified in the 
install directions.

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

## Screens

The following screens are available in the system:

* **Logger** - The main logger interface
* **Display Board** - The "brag board" for displaying on a screen
* **Handkey Interface** - A manual entry screen (see below)
* **Export Cabrillo** - Export the log as a Cabrillo-formatted file for score submissions
* **Export ADIF** - Export the log as a ADIF-formatted file for log recording in LOTW or import into other loggers
* **Export Dupesheet** - Export the ARRL-required "dupe sheet" format for subsmissions
* **Export CVS** - Export the log as a comma-separated values file editable by Excel, Google Sheets, Apple Numbers, etc.


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

There is minimal configuration necessary. 

### General Configuration
Options needed for all installations are in the **Screens** -> 
**Config Mgr**. The options should be self-explanatory.
The **Log Multiple Operators** toggle simple enables or disables the feature
where each entry has a separate Station Call and Operator Call. This feature
is useful when operators want to see their performance but it can get scrambled
if people are not diligent about setting a new operator callsign.

### ARRL/RAC Section List
The ARRL/RAC section list is statically stored in a JSON file for 
performance reasons. The ARRL/RAC list is maintained by the project. However,
to alter the ARRL section list, add or remove the appropriate
items in `api/config_sections.json`. The formatting and captialization
of the file is important.

### Bands List
The band list s statically stored in a JSON file for performance reasons.
To add or remove the bands in the list, edit the file `api/config_bands.json`.
Users of `adif.php` and `cabrillo.php` will also have to hand-edit those 
export functions to map added bands appropriately. A future release will 
use the centralized configuration.

### Modes List
The mode list is statically stored in a JSON file for performance reasons.
It is not recommended to edit this list unless you're an experienced
developer. The list is `api/config_modes.json`.

## Handkey Interface

The Handkey Interface screen is for someone to hand-enter QSOs from people who 
didn't want to log electronically. Obviously not using the live interface can
cause potential dupes, etc. However this provides a way for someone to either
bulk-enter someone's paper log or provide an interface to someone who likes 
to write the QSO on paper then enter it and still the correct date/time. On 
the main screen, the log time is not editable.

## Issues

For any discovered issues, please file an Issue within Github.
