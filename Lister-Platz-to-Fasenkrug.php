<?php

spl_autoload_register( function ( $class_name ) {
	include 'classes/' . $class_name . '.class.php';
} );

$LaMetricAbfahrten = new LaMetricAbfahrten(
    "Lister Platz (U), Hannover",
    "Fasanenkrug, Hannover"
);

$LaMetricAbfahrten->setFrameIcon('i23138');

echo $LaMetricAbfahrten->getLaMetricJSONResponse();