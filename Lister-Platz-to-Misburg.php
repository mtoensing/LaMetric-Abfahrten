<?php

spl_autoload_register( function ( $class_name ) {
	include 'classes/' . $class_name . '.class.php';
} );

$LaMetricAbfahrten = new LaMetricAbfahrten(
    "Lister Platz (U), Hannover",
    "Misburg, Hannover"
);

$LaMetricAbfahrten->setFrameIcon('#23070');

$LaMetricAbfahrten->replace_in_output  = array(
    array( '(U) ','Hannover, ', ', Hannover', 'STB' ),
    array( '','', '', 'Stadtbahn ' )
);

echo $LaMetricAbfahrten->getLaMetricJSONResponse();