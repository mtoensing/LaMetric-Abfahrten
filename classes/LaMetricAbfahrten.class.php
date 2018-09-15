<?php

/**
 *
 * Marc Tönsing 2018
 *
 * Class LaMetricAbfahrten
 */

class LaMetricAbfahrten {

	public $frame_icon = '#23049';
	public $frame_count = 1;
	public $debug = false;
	public $journeys = array();
	public $destination = '';
	public $destination_only;
	public $replace_in_output = '';

	public function __construct( $origin, $destination) {
		$this->destination                = $destination;
		$DBreiseplanner                   = new DBreiseplanner( $origin, $destination );
		$DBreiseplanner->cache_in_minutes = 0;
		$DBreiseplanner->getXML();
		$DBreiseplanner->fillJourneys();

		$this->journeys = $DBreiseplanner->getJourneys();

		if ( isset( $_GET["debug"] ) AND htmlspecialchars( $_GET["debug"] ) == true ) {
			$this->setDebug( true );
		}
	}

	/**
	 * @param string $frame_icon
	 */
	public function setFrameIcon( $frame_icon ) {
		$this->frame_icon = $frame_icon;
	}

	/**
	 * @param bool $debug
	 */
	public function setDebug( $debug ) {
		$this->debug = $debug;
	}

	/**
	 * @param array $journeys
	 */
	public function setJourneys( $journeys ) {
		$this->journeys = $journeys;
	}


	public function getLaMetricJSONResponse() {

		$title = $this->journeys[0]->origin . ' in Richtung ' . $this->destination;
		$title = str_replace( $this->replace_in_output[0], $this->replace_in_output[1], $title );

		$delay = '';

		$frames = array();

		$count = 0;

		foreach ( $this->journeys as $journey ) {

			$text = $journey->getRealtime() . ' Min';

			$frames[] = [
				"text" => $text,
				"icon" => $this->frame_icon

			];

			$count ++;
			if($count >= $this->frame_count){
				break;
			}

		}

		$responseArray = array(
			"frames" => $frames,
		);

		header( "Cache-Control: no-store, no-cache, must-revalidate, max-age=0" );
		header( "Cache-Control: post-check=0, pre-check=0", false );
		header( "Pragma: no-cache" );
		header( "Content-type: application/json; charset=utf-8" );

		$json = json_encode( $responseArray, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT );

		return $json;
	}
}

?>