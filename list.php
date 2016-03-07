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

//*************************************************************
// How does this work?
// Scan all the sub directories in the services directory.
// If they contains rsd.php include it in the array.
// Convert the array in JSON and print it as output.
//*************************************************************

// directory containing all the service descriptions.
$services = 'services';
// Scan services directory. Ignore the .. and . entries.
$scanned_services = array_diff(scandir($services), array('..', '.'));

$services_description = array();
foreach($scanned_services as $service) {
	// Check if the entry it is a directory.
	$path = $services.DIRECTORY_SEPARATOR.$service;
	if(is_dir($path)) {
		// Check if the entry contains an rsd.php file.
		// If the file is a rsd.php include it.
		$rsd = $path.DIRECTORY_SEPARATOR.'rsd.php';
		if(file_exists($rsd)) {
			// include the static class
			include($rsd);
			// use the convention service_directory_name + _rsd as class name
			$class = basename($path).'_rsd';
			// call the describe function and get the standard array illustrated in services/Readme.txt file
			$services_description[] = $class::describe();
		}
	}
}

// encode in JSON and print out.
header('Content-type: application/json');
echo json_encode($services_description);
?>
