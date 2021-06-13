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

TBD

## Testing

TBD

## Basic Use

TBD
