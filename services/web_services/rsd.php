<?php
require_once('../../config.php');
require_once($CFG->libdir . '/adminlib.php');
require_once($CFG->libdir . '/externallib.php');
require_once($CFG->dirroot . '/webservice/lib.php');
require_once($CFG->dirroot . '/admin/webservice/forms.php');


class web_services_rsd {
	public static function get_service_description($service, $protocol) {
		global $CFG;
		$webservicemanager = new webservice();
		$functions = $webservicemanager->get_external_functions($service->id);
		$functions_details = array();
		foreach($functions as $function) {
			$info = external_function_info($function);
			$function_details[] = array(
				'name' => $info->name,
				/* 'link' => '?wsfunction='.$info->name, */
				'description' => $info->description
			);
		}
		return array(
			'service' => array(
				'servicename' => $service->name,
				'servicelink' => $CFG->wwwroot . "/webservice/$protocol/server.php",
				'version' => $CFG->release,
				'protocol' => $protocol
			),
			'apis' => $function_details
		);
	}

	public static function describe() {
		global $DB, $CFG;
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
				$output['services'][] = self::get_service_description($service, $protocol);
			}
		}
		return $output;
	}
}
?>
