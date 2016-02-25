<?php
require_once('../../config.php');
require_once($CFG->libdir . '/adminlib.php');
require_once($CFG->libdir . '/externallib.php');
require_once($CFG->dirroot . '/webservice/lib.php');
require_once($CFG->dirroot . '/admin/webservice/forms.php');
// rsd local lib
require_once('lib.php');

// get all enabled services
$services = $DB->get_records('external_services', array('enabled' => 1));
header('Content-type: application/json');
print_r( json_encode(get_service_description($services[1])) );
?>
