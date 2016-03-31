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


Copyright: 2016, Goran Josic <goran.josic@usi.ch>.

License: http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
*****************************************************************************

This is the moodle local plugin for collecting Remote Service Descriptions (RSD).
The plugin functions by scanning the moodle root and local folder searching for
directories that contain the rsd.php file.

The Mechanism steps are the following:
- Moodle root and local directory are scanned for directories which contain rsd.php files inside.
- The rsd.php files contain no functions only code used for creating the service apis.
- The rsd.php file is included in a function to encapsulate the code execution and to restrict the scope.
- In the rds.php code the array $apis is created.
- The array $apis is used for storing the service function descriptions.
- The resulting $apis content is appended to the final output.

Please check the list.php file for details. It should be easy enought to understand.

*****************************************************************************
To install: Clone the code in the MOODLE_ROOT/local/rsd directory and check
the Notifications page as administrator.

*****************************************************************************

