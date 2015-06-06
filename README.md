# pi-phplcd7segment
Installation (hardware)
-----------------------

TODO

Installation (software)
-----------------------

The recommended way to install is through [composer](http://getcomposer.org).

And run these two commands to install it:

``` bash
$ wget http://getcomposer.org/composer.phar
$ php composer.phar install
```

Oprionnaly, allow the `src/messagelcd.php` file to be run without sudo:
Edit your `/etc/sudoers` file:

``` bash
$ sudo visudo
```

Then add this two lines in your `/etc/sudoers` file : 
(replace MyLinuxUser with your login name & change the path to the blinker)
This will allow you and Apache2 to run the blinker without `sudo`

``` bash
www-data ALL=NOPASSWD: /path/to/messagelcd.php
```