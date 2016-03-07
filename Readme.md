*****************************************************************************
This is the Service Description plugin for the Moodle platform.
The purpose of this package is to offer a JSON structure describing
the services available on the [Moodle platform](http://www.moodle.org).
Please read the descriptions below on how to include additional
services. Static service descriptions are possible as well.

Moodle is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

Moodle is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Moodle.  If not, see <http://www.gnu.org/licenses/>.


@package   local_rsd
@copyright 2016, Goran Josic <goran.josic@usi.ch>
@license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
*****************************************************************************

To add a new service description to the package please follow these steps:

1. Create a new directory with your service name under "services".
   For consistency with Moodle naming please use underscores.
   Examples: my_service, foo_extending_service_yaaay

2. Create a rsd.php file inside the directory containing a static class.
   IMPORTANT: The class should use the convention dirname + _rsd!!
   Examples: my_service_rsd, foo_extending_service_yaaay_rsd.
   IMPORTANT: The class should have the describe method that outputs
   the following array structure:

```php
array(
	'engine' => array(
		'type' => 'Moodle',
		'version' => $CFG->release,
		'name' => 'Moodle',
		'link' => $CFG->wwwroot
	),
	'services' => array(
		array(
			'service' => array(
				'servicename' => 'My additional service',
				'servicelink' => 'http://myplatform/service_url.php',
				'version' => '12345',
				'protocol' => 'your transport protocol'
			),
			'apis' => array(
				array(
					'name' => 'service name 1'
					'description' => 'the service name 1 provides bla bla'
				),
				array(
					'name' => 'service name 2'
					'description' => 'the service name 2 provides bla bla'
				),
				...
				...
				...
			)
		)
	)
)
```

Note: The engine part can be something different than Moodle.

After that check the if the RSD output contains your service description
on: http//yourplatform.url/local/rsd/list.php

As an functioning example please check the web_services/rsd.php.
The class web_services_rsd describes all the active web services on the
moodle instance.

*****************************************************************************
To install: Clone the code in the MOODLE_ROOT/local/rsd directory and check
the Notifications page as administrator.

*****************************************************************************

