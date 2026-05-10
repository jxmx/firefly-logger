#
# Build variables
#
SRCNAME = wsjt2ffdl
PKGNAME = $(SRCNAME)
RELVER = 1.4
DEBVER = 1
ifndef ${RELPLAT}
RELPLAT = deb$(shell lsb_release -rs 2> /dev/null)
endif

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

deb:	debclean debprep
	debchange --distribution stable --package $(PKGNAME) \
		--newversion $(RELVER)-$(DEBVER).$(RELPLAT) "Autobuild of $(RELVER)-$(DEBVER) for $(RELPLAT)"
	debuild
	git checkout debian/changelog


debchange:
	debchange -v $(RELVER)-$(DEBVER)
	debchange -r


debprep:	debclean
	(cd .. && \
		rm -f $(PKGNAME)-$(RELVER) && \
		rm -f $(PKGNAME)-$(RELVER).tar.gz && \
		rm -f $(PKGNAME)_$(RELVER).orig.tar.gz && \
		ln -s $(SRCNAME) $(PKGNAME)-$(RELVER) && \
		tar --exclude=".git" -h -zvcf $(PKGNAME)-$(RELVER).tar.gz $(PKGNAME)-$(RELVER) && \
		ln -s $(PKGNAME)-$(RELVER).tar.gz $(PKGNAME)_$(RELVER).orig.tar.gz )

debclean:
	rm -f ../$(PKGNAME)_$(RELVER)*
	rm -f ../$(PKGNAME)-$(RELVER)*
	rm -rf debian/$(PKGNAME)
	rm -f debian/files
	rm -rf debian/.debhelper/
	rm -f debian/debhelper-build-stamp
	rm -f debian/*.substvars
	rm -rf debian/wsjt2ffdl/ debian/.debhelper/
	rm -f debian/debhelper-build-stamp debian/files debian/wsjt2ffdl.substvars
	rm -f debian/wsjt2ffdl.postrm.debhelper

	
