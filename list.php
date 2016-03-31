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

require_once('../../config.php');

global $CFG;

//*******************************************************************
// How does this work?
// Scan all the directories in the moodle root and local folder.
// If they contains rsd.php include it in the array.
// Convert the array in JSON and print it as output.
//*******************************************************************
function extract_apis($rsd_path) {
	include($rsd_path);
	// the description element has been initialized in the rsd.php file.
	return $apis;
}

function dirscan($list) {
	$apis = array();
	foreach($list as $directory) {
		$iterator = new \DirectoryIterator ( $directory );
		foreach($iterator as $fileinfo) {
			if($fileinfo->isDir() && !$fileinfo->isDot()) { // filter files and dot directories
				$rsd_path = $fileinfo->getPathname().DIRECTORY_SEPARATOR.'rsd.php';
				if(file_exists($rsd_path)) {
					$apis = extract_apis($rsd_path);
				}
			}
		}
	}
	return $apis;
}

$services_descriptions = array(
			'engineName' => 'Moodle',
			'engineLink' => 'webservice/',
			'homePageLink' => $CFG->wwwroot . DIRECTORY_SEPARATOR
		);


/* $services_descriptions['apis'] = dirscan(array('../..', '..')); */
$services_descriptions['apis'] = dirscan(array('..'));
// encode in JSON and print out.
header('Content-type: application/json');
echo json_encode($services_descriptions);
?>
