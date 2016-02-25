<?php
	function get_service_description($service) {
		global $CFG;
		/* return $service; */
		$webservicemanager = new webservice();
		$functions = $webservicemanager->get_external_functions($service->id);
		/* $function_info = external_function_info($functions[8]); */
		/* return $function_info; // inspect the function info field */
		$functions_details = array();
		foreach($functions as $function) {
			$info = external_function_info($function);
			$function_details[] = array(
				'name' => $info->name,
				'description' => $info->description
			);
		}
		return array(
			'service' => $service->name,
			'servicelink' => $CFG->wwwroot . '/webservice/rest/server.php?wsfunction=',
			'functions' => $function_details
		);
	}
?>
