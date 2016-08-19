<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * @package   local_rsd
 * @copyright 2016, Goran Josic <goran.josic@usi.ch>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

//*******************************************************************
// How does this work?
// All the active webservices are extracted from the database.
// The array $description is created and used for the webservices
// information. The description array fields are RSD Specification
// compliant.
// The resulting code can be included and the $description array is
// then available for further usage.
//*******************************************************************

require_once($CFG->libdir . '/adminlib.php');
require_once($CFG->libdir . '/externallib.php');
require_once($CFG->dirroot . '/webservice/lib.php');
require_once($CFG->dirroot . '/admin/webservice/forms.php');

// get all enabled web services
$services = $DB->get_records('external_services', array('enabled' => 1));

// get the list of the active transport protocols
$transport_protocols = empty($CFG->webserviceprotocols) ?  array() : explode(',', $CFG->webserviceprotocols);

global $CFG;

foreach($services as $service) {
    foreach($transport_protocols as $protocol) {
        $api_entry = array();
        $webservicemanager = new webservice();

		// get the webservice functions
		$functions = $webservicemanager->get_external_functions($service->id);
		foreach($functions as $function) {
            if (intval($CFG->version) < 2016052300) {
                $info = external_function_info($function);
            }
            else {
                $info = external_api::external_function_info($function);
            }

            // create reverse domain notation to the moodle api names
            // note that because the API Links are different for each protocol,
            // the services need to have different names
            $apiName = "org.moodle." . $protocol . "." . $info->name;

            // append the service to the API list
            $apis[$apiName] = array(
                'notes' => $info->description,
                'apiVersion' => $CFG->release,
                'apiLink' => 'webservice' . DIRECTORY_SEPARATOR . $protocol . DIRECTORY_SEPARATOR . 'server.php',
                'transport' => array($protocol)
            );
        }
    }
}
?>
