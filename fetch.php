<?php
/**
 * @author Addshore
 * @author Ladsgroup
 * Quick script for grabbing a list of dashboards
 */
$dashboards = [
	'grafana' => [
		'ores',
		'ores-extension'
	],
	'grafana-labs' => [
		'ores-labs',
		'ores-beta-cluster',
		'ores-extension',
	]
];
if ( array_key_exists( 1, $argv ) ) {
	$requestedDashboard = $argv[1];
	if( in_array( $requestedDashboard, $dashboards ) ) {
		$dashboards = array( $requestedDashboard );
	} else {
		echo "Requested dashboard '$requestedDashboard' was not in the dashboard list\n";
		exit;
	}
}
foreach( $dashboards as $realm => $dashList ) {
	echo "Getting dashboards in $realm\n";
	foreach ( $dashList as $dashboard ) {
		echo "Getting dashboard $dashboard\n"; 
		$response = file_get_contents( "https://$realm.wikimedia.org/api/dashboards/db/$dashboard" );
		if( $response === false || $response === null ) {
			echo "Skipping $dashboard, something went wrong...\n";
			continue;
		}
		$data = json_decode( $response, true );
		$data = $data['dashboard'];
		$json = json_encode( $data, JSON_PRETTY_PRINT );
		file_put_contents( __DIR__ . '/' . "$dashboard ($realm).json", $json );
	}
}
echo "Done";
exit;
