ifdef ${DESTDIR}
DESTDIR=${DESTDIR}
endif

prefix ?= /usr
exec_prefix ?= $(prefix)
bin_prefix ?= $(exec_prefix)/bin
pylibdir ?= $(exec_prefix)/lib/python3/dist-packages
mandir ?= $(prefix)/share/man
sysconfdir ?= /etc

BINS = \
	wsjt2ffdl-listener

PY_SRC = $(wildcard wsjt2ffdl/*.py)
BINS_EXP = $(patsubst %, $(DESTDIR)$(bin_prefix)/%, $(BINS))
PY_EXP = $(patsubst %, $(DESTDIR)$(pylibdir)/%, $(PY_SRC))

default:
	@echo This does nothing

install: $(BINS_EXP) $(PY_EXP)

$(DESTDIR)$(bin_prefix)/%:  %
	install -D -m 0755 $< $@

$(DESTDIR)$(pylibdir)/%:	%
	install -D -m 0644 $< $@
