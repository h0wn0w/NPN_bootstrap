#!/usr/local/bin/perl

    # This script should be uploaded to the web server.

    use warnings;
    use strict;
    use File::Find;
    find (\&wanted, ("."));
    sub wanted
    {
        if (/(.*\.(?:html|htm|css|js)$)/i) {
            print "Compressing $File::Find::name\n";
            if (! -f "$_.gz") {
                system ("gzip --keep --best --force $_");
            }
        }
    }