<?php
	function get_service_description($service, $protocol) {
		global $CFG;
		/* return $service; */
		$webservicemanager = new webservice();
		$functions = $webservicemanager->get_external_functions($service->id);
		/* return $functions[8]; // inspect the function info field */
		/* $function_info = external_function_info($functions[8]); */
		/* return $function_info; // inspect the function info field */
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
?>
