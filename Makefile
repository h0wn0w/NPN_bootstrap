# Makefile for MultiMarkdown-CMS based wikis
# Thanks to Dr. Drang for inspiring me to use make:
#	http://www.leancrew.com/all-this/2008/06/my-no-server-personal-wiki—part-3/


#
# NOTE: MultiMarkdown 3.0 must be installed for this to work.
#

# notouch=sphider-pdo/admin/*.txt sphider-pdo/include/*.txt sphider-pdo/include/js_suggest/*.txt

srcfiles := $(filter-out cgi/* templates/* css/* sphider-pdo/* sphider-pdo/*/* sphider-pdo/*/*/* robots.txt humans.txt, $(wildcard *.txt */*.txt */*/*.txt */*/*/*.txt))

htmlfiles := $(patsubst %.txt, %.html, $(srcfiles))

templates := $(wildcard templates/*.html)


all: $(htmlfiles) cgi/vector_index


cgi/vector_index: $(htmlfiles)
	cd cgi; ./map_my_site.pl > vector_index


%.html: %.txt # $(templates)
	cgi/mmd2web $*.txt
	chmod 755  $*.html


clean:
	rm $(htmlfiles)


fast: $(htmlfiles)
