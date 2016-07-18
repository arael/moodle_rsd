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

// Don't assume a specific installation location but find the moodle root
$cwd = dirname(__FILE__);

while ($cwd != "/")
{
    // detect the moodle root
    if (file_exists($cwd . DIRECTORY_SEPARATOR . 'config.php') &&
        file_exists($cwd . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'moodlelib.php'))
    {
        break;
    }
    $cwd = dirname($cwd);
}

chdir($cwd);
// will fail if we are not within a moodle instance
require_once('config.php');

global $CFG, $SITE;

//*******************************************************************
// How does this work?
// Scan all the directories in the moodle root and local folder.
// If they contain a rsd.php then extract the APIS and map to the global array.
// Convert the array in JSON and print it as output.
//*******************************************************************
function extract_apis($rsd_path, $homePageLink, $engineLink) {
    // plugins may need to verify that the homePageLink and the engineLink
    // are not empty in order to provide absolute links

    global $CFG, $DB; // ensure all globals are present within the scope
    $apis = array();  // instanciate a local api list for the plugin

    // NOTE: the $apis is an associative array (key => value), don't use push or unshift
	include($rsd_path);

	// return the api list
	return $apis;
}

$service_descriptions = array(
			'engineName' => $SITE->fullname, // there will be more than 1 moodle instance
			'engineLink' => '', // in moodle there is no single webservice folder
			'homePageLink' => $CFG->wwwroot . DIRECTORY_SEPARATOR,
            'apis'=> array()
		);

// load the web-service APIs for all plugins in the system
foreach (array($CFG->dirroot,
               $CFG->dirroot .DIRECTORY_SEPARATOR. 'auth',
               $CFG->dirroot .DIRECTORY_SEPARATOR. 'local') as $dir) {
    if (!empty($dir)) {
        $iterator = new DirectoryIterator($dir);
        foreach($iterator as $fileinfo) {
            if($fileinfo->isDir() && !$fileinfo->isDot()) { // filter files and dot directories
                $rsd_path = $fileinfo->getPathname().DIRECTORY_SEPARATOR.'rsd.php';
                if(file_exists($rsd_path)) {
                    $localapis = extract_apis($rsd_path,
                                              $service_descriptions['homePageLink'],
                                              $service_descriptions['engineLink']);

                    // map the plugin APIs into the global list
                    foreach ($localapis as $name => $api) {
                        $service_descriptions['apis'][$name] = $api;
                    }
                }
            }
        }
    }
}

// encode in JSON and print out.
header('Content-type: application/json');
echo json_encode($service_descriptions);
?>
