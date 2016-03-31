<?php
require_once('../../config.php');
require_once($CFG->libdir . '/adminlib.php');
require_once($CFG->libdir . '/externallib.php');
require_once($CFG->dirroot . '/webservice/lib.php');
require_once($CFG->dirroot . '/admin/webservice/forms.php');


class web_services_rsd {
	public static function get_service_description($service, $protocol) {
		global $CFG;
		$output = array(
			'engineName' => 'Moodle',
			'engineId' => $service->name,
			'engineLink' => 'webservice/',
			'homePageLink' => $CFG->wwwroot . DIRECTORY_SEPARATOR,
			'apis' => array()
		);

		$webservicemanager = new webservice();
		$functions = $webservicemanager->get_external_functions($service->id);
		$functions_details = array();
		foreach($functions as $function) {
			$info = external_function_info($function);
			$function_details[$info->name] = array(
				'notes' => $info->description,
				'apiVersion' => $CFG->release,
				'apiLink' => $protocol . DIRECTORY_SEPARATOR . 'server.php',
				'transport' => array($protocol)
			);
		}
		$output['apis'] = $function_details;
		return $output;
	}

	public static function describe() {
		global $DB, $CFG;
		// get all enabled web services
		$transport_protocols = empty($CFG->webserviceprotocols) ?  array() : explode(',', $CFG->webserviceprotocols);
		$services = $DB->get_records('external_services', array('enabled' => 1));
		$output = array();
		foreach($services as $service) {
			foreach($transport_protocols as $protocol) {
				$output[] = self::get_service_description($service, $protocol);
			}
		}
		return $output;
	}
}
?>
