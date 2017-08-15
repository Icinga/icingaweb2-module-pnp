# PNP module for Icinga Web 2

#### Table of Contents

1. [About](#about)
2. [License](#license)
3. [Support](#support)
4. [Requirements](#requirements)
5. [Installation](#installation)
6. [Configuration](#configuration)
7. [FAQ](#faq)
8. [Thanks](#thanks)
9. [Contributing](#contributing)

## About

This module integrates [PNP](https://docs.pnp4nagios.org/) into [Icinga Web 2](https://www.icinga.com/products/icinga-web-2/)
and allows you to view beautiful graphs in the host/service detail view.

<img src="https://github.com/Icinga/icingaweb2-module-pnp/blob/master/doc/screenshot/detail_view.png" alt="Detail View" height="300">

<img src="https://github.com/Icinga/icingaweb2-module-pnp/blob/master/doc/screenshot/iframe.png" alt="Iframe" height="300">

## License

Icinga Web 2 and this Icinga Web 2 module are licensed under the terms of the GNU General Public License Version 2, you will find a copy of this license in the LICENSE file included in the source package.

## Support

Join the [Icinga community channels](https://www.icinga.com/community/get-involved/) for questions.

## Requirements

This module glues PNP and Icinga Web 2 together. Both of them are required
to be installed and configured:

* [Icinga Web 2](https://www.icinga.com/products/icinga-web-2/) (>= 2.4.1)
* [PNP](https://docs.pnp4nagios.org/) (&gt;= 0.6.25)


## Installation

Extract this module to your Icinga Web 2 modules directory as `pnp` directory.

Git clone:

```
cd /usr/share/icingaweb2/modules
git clone https://github.com/Icinga/icingaweb2-module-pnp.git pnp
```


Tarball download (latest [release](https://github.com/Icinga/icingaweb2-module-pnp/releases/latest)):

```
cd /usr/share/icingaweb2/modules
wget https://github.com/Icinga/icingaweb2-module-pnp/archive/v1.1.0.zip
unzip v1.1.0.zip
mv icingaweb2-module-pnp-1.1.0 pnp
```

### Enable Icinga Web 2 module

Enable the module in the Icinga Web 2 frontend in `Configuration -> Modules -> pnp -> enable`.
You can also enable the module by using the `icingacli` command:

```
icingacli module enable pnp
```

## Configuration

### PNP Configuration

The base URL for PNP (e.g. `/pnp4nagios`) must be accessible on the webserver.

There is an [open issue](https://github.com/Icinga/icingaweb2-module-pnp/issues/29)
for forwarding the Icinga Web 2 auth session to PNP.

### Module Configuration

Navigate to `Modules - PNP - Config` and specify the settings.

<img src="https://github.com/Icinga/icingaweb2-module-pnp/blob/master/doc/screenshot/config_form.png" alt="Iframe" height="300">


## FAQ

### Duplicated Graphs in Detail View

There is a problem with `config.php` and `config_local.php`
in PNP (discussion [here](https://monitoring-portal.org/index.php?thread/35865-doppelte-eintr%C3%A4ge-im-icinga2-mit-pnp/&postID=228011#post228011)).

Remove the duplicated views in one of them, e.g. in `config_local.php`:

```
- $views[] = array('title' => '4 Hours',   'start' => (60*60*4) ); 
- $views[] = array('title' => '25 Hours',  'start' => (60*60*25) ); 
- $views[] = array('title' => 'One Week',  'start' => (60*60*25*7) ); 
- $views[] = array('title' => 'One Month', 'start' => (60*60*24*32) ); 
- $views[] = array('title' => 'One Year',  'start' => (60*60*24*380) );
```

The related issue can be found [here](https://github.com/Icinga/icingaweb2-module-pnp/issues/18).


## Thanks




## Contributing

There are many ways to contribute to the Icinga Web module for PNP --
whether it be sending patches, testing, reporting bugs, or reviewing and
updating the documentation. Every contribution is appreciated!


