apacheconf ?= /etc/apache2/conf-available

CONF_APACHE_FILES = firefly-logger.conf
CONF_APACHE = $(patsubst %, $(DESTDIR)$(apacheconf)/%, $(CONF_APACHE_FILES))

INSTALLABLES = $(CONF_APACHE)

.PHONY:	install
install:	$(INSTALLABLES)

$(DESTDIR)$(apacheconf)/%:	%
	install -D -m 0644 $< $@


