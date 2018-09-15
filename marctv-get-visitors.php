<?php

/* BONUS
 * Simple Script to get the current live visitors on marctv
 *
 * */

function getResponse(){

	$json = file_get_contents('https://marc.tv/api/');
	$obj = json_decode($json);
	$visits = $obj->row->visits;


	$output = array(
		"frames" => array([
			"text" => $visits,
			"icon" => "i23049"
		])
	);


	header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
	header("Content-type: application/json; charset=utf-8");

	echo json_encode($output, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
}


echo getResponse();
?>