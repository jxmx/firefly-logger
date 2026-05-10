## Code Moved
This code was moved into [Firefly Logger](https://github.com/jxmx/firefly-logger) directly.

# WSJT2FFDL
This utility listens for multicasted QSO log events from WSJT-X
and publishes them to the [Firefly Field Day Logger](https://github.com/jxmx/ffdl).
This requires WSJT-X to be configured to send to a multicast IP
as described in the "WSJT-X" section of
[this support note from HRD](https://support.hamradiodeluxe.com/support/solutions/articles/51000298966-setup-configuration-hrd-alert-multicasting).
Ignore the part about HRD configuration.

## Installation
Installation is supported on in-support versions of Debian (currently
Debian 12 Bookworm and Debial 11 Bullseye) only and only
 through use of apt/deb and the PacketWarriors software repository. 
Installation on a supported Debian release is as follows.

1. Install the PacketWarriors software repository if not already
installed for Firefly Logger (i.e. running wsjt2ffdl on a different
system):
```
wget -O/packetwarriors-repo.deb https://repo.packetwarriors.com/packetwarriors-repo.deb
dpkg -i /tmp/packetwarriors-repo.deb
apt update
```

2. Install with apt:
```
apt install wsjt2ffdl
```

Upon installation, wsjt2ffdl will be started and enabled to start
on boot. Standard setups should require no additional configuration
beyond the configuration of WSJT-X.

## Configuration
For non-standard configurations, the file `/etc/default/wsjt2ffdl`
may be customized as described within the file.

## Logging
Logs go to systemctl journal and/or syslog (if syslog is installed). 
Logging can be reviewed with the command `journalctl -u wsjt2ffdl`.
Note that the logging will also note any QSOs that are not
able to be stored with FFDL (usually due to dups). There is no way
to feed duplicate QSO alerts back to the user of WSJT-X. Duplicate
QSOs will be ignored by FFDL.

## Troubleshooting
There isn't a lot to troubleshoot aside from configuration.
Starting `wsjt2ffdl` with the `--debug` option can be illuminating
but also noisy. For any issue, please include logging output or 
I will simply be asking you to add it as my first response.
