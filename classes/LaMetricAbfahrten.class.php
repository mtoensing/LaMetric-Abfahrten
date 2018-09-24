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
	public $prefix = '';
	public $postfix = ' Min';

	/**
	 * @param string $prefix
	 */
	public function setPrefix( $prefix ) {
		$this->prefix = $prefix;
	}

	/**
	 * @param string $postfix
	 */
	public function setPostfix( $postfix ) {
		$this->postfix = $postfix;
	}

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

		$delay = '';

		$frames = array();

		$count = 0;

		foreach ( $this->journeys as $journey ) {

			$text = $journey->getRealtime();

			$frames[] = [
				"text" => $this->prefix. $text . $this->postfix,
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

		if($this->debug != true) {
			header( "Cache-Control: no-store, no-cache, must-revalidate, max-age=0" );
			header( "Cache-Control: post-check=0, pre-check=0", false );
			header( "Pragma: no-cache" );
			header( "Content-type: application/json; charset=utf-8" );
		}

		$json = json_encode( $responseArray, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT );

		return $json;
	}
}

?>