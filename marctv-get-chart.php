<?php

/* BONUS
 * Simple Script to get the current live visitors on marctv
 *
 * */

function getResponse() {

	$json          = file_get_contents( 'https://api.marc.tv/' );

	$last7days_string = file_get_contents( 'https://api.marc.tv/last7days.php' );

	$obj           = json_decode( $json );
	$current_users = $obj->row->visitors;

	$array_days           = array_values(explode(',' , $last7days_string ));


	$max_users = getMaxUsers( $current_users );

	$output = array(
		"frames" => array(
			[
				"index" => 0,
				"chartData" => $array_days
			]
		)
	);

	/**

	,

	 */


	header( "Cache-Control: no-store, no-cache, must-revalidate, max-age=0" );
	header( "Cache-Control: post-check=0, pre-check=0", false );
	header( "Pragma: no-cache" );
	header( "Content-type: application/json; charset=utf-8" );

	echo json_encode( $output, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT );
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


echo getResponse();


?>