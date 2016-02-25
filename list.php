<?php
require_once('../../config.php');
require_once($CFG->libdir . '/adminlib.php');
require_once($CFG->libdir . '/externallib.php');
require_once($CFG->dirroot . '/webservice/lib.php');
require_once($CFG->dirroot . '/admin/webservice/forms.php');
// rsd local lib
require_once('lib.php');

// get all enabled web services
$services = $DB->get_records('external_services', array('enabled' => 1));

$active_protocols = empty($CFG->webserviceprotocols) ?  array() : explode(',', $CFG->webserviceprotocols);

$output = array(
	'engine' => array(
		'type' => 'Moodle',
		'version' => $CFG->release,
		'name' => 'Moodle',
		'link' => $CFG->wwwroot
	),
	'services' => array()
);
foreach($services as $service) {
	foreach($active_protocols as $protocol) {
		$output['services'][] = get_service_description($service, $protocol);
	}
}
header('Content-type: application/json');
echo json_encode($output);
?>
