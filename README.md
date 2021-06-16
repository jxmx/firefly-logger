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

Goals / Design Philosophy

- Operate seamlessly across multiple, low-powered devices
- Operates without the Internet
- Client side is HTML5 + Javascript
- Server side is a very lightweight PHP + MariaDB (MySQL) system
- Operate over a disconnected local LAN/WLAN without Internet
- Server operates with many clients served by a Pi3/4-type device
- Limited feature set necessary for ARRL Field Day operations only
- Display page for showing on a screen at Field Days to show score and points
- ADIF export for Logbook of the World (LoTW) or other logging programs
- CSV export for spreadsheet manipulations

## Immportant Security Note

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

Firefly Field Day Logger, at least in its first version, is not a
a turnkey-complete system. Some Linux experience will be
needed to setup a Pi (or other Linux or whatever) server that runs
MariaDB and PHP. The following components on your platform of choice
will be required.

- PHP v7.3 or greater
- MariaDB v10 or greater (also would work with MySQL)
- Webserver that supports PHP via FastCGI

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

`CREATE DATABASE ffdl;
GRANT ALL ON ffdl.* TO 'ffdl'@'localhost' IDENTIFIED BY 'ffdl';
FLUSH PRIVILEGES;
USE ffdl;
SOURCE /var/www/html/load.sql;`

4. Browse to the application URL

## Testing

To test that the system is installed properly:

1. Add a QSO

2. Edit a QSO

3. Delete a QSO

If those three operations work, you have a successful installation.

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

## Customizations

Any customizations currently have to be dealt with in the code. I can
only foresee two needed customizations.

To alter the ARRL section list, that is contained within
a JavaScript array at the very top of the `js/index.js` file. Look
for the `var section = [ ]` array. Add or remove sections as needed.

To alter the Band or Mode drop downs, edit the `index.html` and search
for either `<select id="band"` or `<select id="mode"` as desired.
Add or remove `<option>` statements as needed. Make sure that the 
`<option>` specifies both a `value=` and the name between the tags
as the same information.

## Issues

For any discovered issues, please file an Issue within Github.


TBD
