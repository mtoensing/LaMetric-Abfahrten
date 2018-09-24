<?php
function getResponse() {

	$json          = file_get_contents( 'https://api.marc.tv/' );
	$obj           = json_decode( $json );
	$current_users = $obj->row->visitors;
	$max_users = getMaxUsers( $current_users );

	$response = $current_users . '(' . $max_users . ')';

	return $response;
}


function getMaxUsers( $current_users ) {
	$filename         = 'marctvmaxusers.txt';
	$local_cache_file = sys_get_temp_dir() . '/' . $filename;
	$local_cache_file_day = sys_get_temp_dir() . '/day_' . $filename;
	$today = date('d');

	if ( ! file_exists( $local_cache_file ) ) {
		file_put_contents( $local_cache_file, $current_users );
	}

	if ( ! file_exists( $local_cache_file_day ) ) {
		file_put_contents( $local_cache_file_day, $today );
	}

	$cached_day = intval( file_get_contents( $local_cache_file_day ) );

	/* invalidate cache if it is the next day */
	if($cached_day != $today){
		file_put_contents( $local_cache_file_day, $today );
		file_put_contents( $local_cache_file, '0' );
	}

	$cached_users_raw = intval( file_get_contents( $local_cache_file ) );

	if ( is_int( $cached_users_raw ) ) {
		if ( $cached_users_raw < $current_users ) {
			file_put_contents( $local_cache_file, $current_users );
			$max_users = $current_users;
		} else {
			$max_users = $cached_users_raw;
		}
	} else {
		return - 1;
	}

	return $max_users;
}



spl_autoload_register( function ( $class_name ) {
	include 'classes/' . $class_name . '.class.php';
} );

$LaMetricAbfahrten = new LaMetricAbfahrten(
	"Lister Platz (U), Hannover",
	"Misburg, Hannover"
);

$LaMetricAbfahrten->setPostfix('\' '.getResponse());
$LaMetricAbfahrten->setFrameIcon('i23135');

echo $LaMetricAbfahrten->getLaMetricJSONResponse();




?>