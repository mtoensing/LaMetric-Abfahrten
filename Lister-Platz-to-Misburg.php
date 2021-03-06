<?php

spl_autoload_register( function ( $class_name ) {
	include 'classes/' . $class_name . '.class.php';
} );

$LaMetricAbfahrten = new LaMetricAbfahrten(
    "Lister Platz (U), Hannover",
    "Misburg, Hannover"
);

$LaMetricAbfahrten->setFrameIcon('i23135');

echo $LaMetricAbfahrten->getLaMetricJSONResponse();