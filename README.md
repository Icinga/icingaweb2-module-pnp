# PNP4Nagios module for Icinga Web 2

## General Information

This module integrates an existing [PNP4Nagios](https://docs.pnp4nagios.org/)
installation in your
[Icinga Web 2](https://www.icinga.org/icinga/screenshots/icinga-web-2/) web
frontend.

> ** Note **
> 
> This is a showcase module and to be considered an unsupported prototype
> unless explicitely stated otherwise.

## Installation

Just extract this to your Icinga Web 2 module folder. Enable the pnp4nagios
module in your Icinga Web 2 frontend
(Configuration -> Modules -> pnp4nagios -> enable) and it should work out of
the box. Check the modules config tab right there in case you are using a
customized installation instead of standard PNP4Nagios packages.

NB: It is best practice to install 3rd party modules into a distinct module
folder like /usr/share/icingaweb2/modules. In case you don't know where this
might be please check the module path in your Icinga Web 2 configuration.

## TODO

We should also provide an easy way to integrate our authentication and
permission system into PNP4Nagios. This would require a small wrapper doing an
embedded Icinga Web 2 bootstrap in order to provide our user object and a list
of allowed hosts/services to PNP4Nagios.
