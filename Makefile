#
#
# Build variables
#
RELVER = 5.5
DEBVER = 1
ifndef ${RELPLAT}
RELPLAT = deb$(shell lsb_release -rs 2> /dev/null)
endif
PKGNAME = firefly-logger

BUILDABLES = \
	bin \
	conf \
	web

ifdef ${DESTDIR}
DESTDIR=${DESTDIR}
endif

prefix ?= /usr
exec_prefix ?= $(prefix)
bin_prefix ?= $(exec_prefix)/bin
mandir ?= $(prefix)/share/man
docdir ?= $(prefix)/share/doc/$(PKGNAME)

ROOT_FILES = LICENSE README.md SECURITY.md CHANGELOG.txt load.sql
ROOT_INSTALLABLES = $(patsubst %, $(DESTDIR)$(docdir)/%, $(ROOT_FILES))

default:
	@echo This does nothing because of dpkg-buildpkg - use 'make install'

install: $(ROOT_INSTALLABLES)
	@echo DESTDIR=$(DESTDIR)
	$(foreach dir, $(BUILDABLES), $(MAKE) -C $(dir) install;)

$(DESTDIR)$(docdir)/%: %
	install -D -m 0644  $< $@

verset:
	perl -pi -e 's/\@\@HEAD-DEVELOP\@\@/$(RELVER)/g' `grep -rl @@HEAD-DEVELOP@@ src/ web/`

deb:	debclean debprep
	debchange --distribution stable --package $(PKGNAME) \
		--newversion $(RELVER)-$(DEBVER).$(RELPLAT) "Autobuild of $(RELVER)-$(DEBVER) for $(RELPLAT)"
	debuild
	git checkout debian/changelog

debchange:
	debchange -v $(RELVER)-$(DEBVER)
	debchange -r

debprep:	debclean
	-find . -type d -name __pycache__ -exec rm -rf {} \;
	(cd .. && \
		rm -f $(PKGNAME)-$(RELVER) && \
		rm -f $(PKGNAME)-$(RELVER).tar.gz && \
		rm -f $(PKGNAME)_$(RELVER).orig.tar.gz && \
		cp -r $(PKGNAME) $(PKGNAME)-$(RELVER) && \
		tar --exclude=".git" -h -zvcf $(PKGNAME)-$(RELVER).tar.gz $(PKGNAME)-$(RELVER) && \
		ln -s $(PKGNAME)-$(RELVER).tar.gz $(PKGNAME)_$(RELVER).orig.tar.gz )

debclean:
	rm -rf ../$(PKGNAME)_$(RELVER)*
	rm -rf ../$(PKGNAME)-$(RELVER)*
	rm -rf debian/$(PKGNAME)
	rm -f debian/files
	rm -rf debian/.debhelper/
	rm -f debian/debhelper-build-stamp
	rm -f debian/*.substvars
	rm -rf debian/$(PKGNAME)/ debian/.debhelper/
	rm -f debian/debhelper-build-stamp debian/files debian/mfamily-scripts.substvars
