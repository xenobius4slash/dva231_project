<?php
require_once 'WeatherService.php';

class WeatherServiceYahoo extends WeatherService implements WeatherServiceInterface {
	private $test = true;
	private	$weatherServiceId = 3;
	private $weatherServiceName = 'yahoo';
	private $creditName = 'Powered by Yahoo!';
	private $baseUrl = 'https://query.yahooapis.com/v1/public/yql';
	private $resultWeatherService;
	private $resultDatabase;

	/**	get the weather service results from the class
	*	@return		Array
	*/
	private function getResultWS() {
		return $this->resultWeatherService;
	}

	/** set the results from the weather service in the class
	*	@param		$array			Array
	*	@return		Bool
	*/
	private function setResultWS($array) {
		$this->resultWeatherService = $array;
		$this->resultDatabase = null;
		if( is_array( $this->getResultWS() ) ) {
			return true;
		} else {
			return false;
		}
	}

	/**	get the database results from the class
	*	@return		Array
	*/
	private function getResultDB() {
		return $this->resultDatabase;
	}

	/** set the results from the database in the class
	*	@param		$array			Array
	*	@return		Bool
	*/
	private function setResultDB($array) {
		$this->resultWeatherService = null;
		$this->resultDatabase = $array;
		if( is_array( $this->getResultDB() ) ) {
			return true;
		} else {
			return false;
		}
	}

	/** get the url for cURL request
	*	example for 'current':	not available
	*	example for 'forecast': https://query.yahooapis.com/v1/public/yql?q=select%20*%20from%20weather.forecast%20where%20woeid%20in%20(select%20woeid%20from%20geo.places(1)%20where%20text%3D%22Paris%22)%20and%20u%3D%22c%22&format=json
	*	@param		$town			String
	*	@return		NULL || String
	*/
	private function getUrlByOptions($town) {
		if($this->getMode() == 'current' || $this->getMode() == 'forecast') {
			$query = 'select * from weather.forecast where woeid in (select woeid from geo.places(1) where text="'.$town.'") and u="c"';
			return $this->baseUrl.'?q='.urlencode($query).'&format=json';
		} else {
			return null;
		}
	}

	/** get a defined array for the insert into the database
	*	@return		Array
	*/
	public function getArrayForDatabase() {
		$array = $this->getArrayOfOneWeatherServiceForDatabaseByData($this->getLastUpdate(),$this->getTemperature(),$this->getSky(),$this->getWindSpeed(),$this->getWindDegree(),$this->getPressureHpa(),$this->getHumidity());
		return array('weather_service_id' => $this->weatherServiceId, 'weather_service_name' => $this->weatherServiceName, 'data' => $array );
	}

	/**
	*	Start interface implementation
	*	You can find the description of the function in the interface declaration (WeatherService.php)
	*/
	public function loadResultByTownApi($town) {
		if($this->test === false) {
			$url = $this->getUrlByOptions($town);
			if($url === null) {
				return false;
			} else {
				$result = $this->getResultByCurlRequest($url);
				if( $result === false ) {
					return false;
				} else {
					if( $this->setResultWS( $this->getArrayFromJson( $this->getResultByCurlRequest($url) ) ) === false ) {
						return false;
					} else {
						return true;
					}
				}
			}
		} else {
			if( $this->setResultWS( $this->getArrayFromJson( $this->getTestResult('yahoo', $this->getMode()) ) ) === false ) {
				return false;
			} else {
				return true;
			}
		}
	}

	public function loadResultByTownIdDb($townId) {
		$result = $this->getResultByTownIdAndWeatherServiceDb($townId, $this->weatherServiceId);
		if( $this->setResultDB($result) === false ) {
			return false;
		} else {
			return true;
		}
	}

    public function getLastUpdate() {
		if( $this->getResultWS() !== null && $this->getResultDB() === null ) {
			$result = $this->getResultWS()['query']['results']['channel']['lastBuildDate'];
			return date('Y-m-d H:i:s', strtotime($result));
		} elseif( $this->getResultWS() === null && $this->getResultDB() !== null ) {
			return $this->getResultDB()['build_date'];
		}
	}

    public function getTemperature() {
		if( $this->getResultWS() !== null && $this->getResultDB() === null ) {
			if( $this->getTempUnit() == 'celsius' ) {
				return round($this->getResultWS()['query']['results']['channel']['item']['condition']['temp'],1);
			} else {
				return $this->convertCelsiusToFahrenheit($this->getResultWS()['query']['results']['channel']['item']['condition']['temp']);
			}
		} elseif( $this->getResultWS() === null && $this->getResultDB() !== null ) {
			if( $this->getTempUnit() == 'celsius' ) {
				return round($this->getResultDB()['temp_c'],1);
			} else {
				return round($this->getResultDB()['temp_f'],1);
			}
		}
	}

    public function getSky() {
		if( $this->getResultWS() !== null && $this->getResultDB() === null ) {
			return $this->getResultWS()['query']['results']['channel']['item']['condition']['text'];
		} elseif( $this->getResultWS() === null && $this->getResultDB() !== null ) {
			return $this->getResultDB()['sky_condition'];
		}
	}

    public function getWindSpeed() {
		if( $this->getResultWS() !== null && $this->getResultDB() === null ) {
			return $this->convertKilometrePerHourToMilesPerHour($this->getResultWS()['query']['results']['channel']['wind']['speed']);
		} elseif( $this->getResultWS() === null && $this->getResultDB() !== null ) {
			return round($this->getResultDB()['wind_speed_mph'],1);
		}
	}

    public function getWindDegree() {
		if( $this->getResultWS() !== null && $this->getResultDB() === null ) {
			return round($this->getResultWS()['query']['results']['channel']['wind']['direction'],1);
		} elseif( $this->getResultWS() === null && $this->getResultDB() !== null ) {
			return round($this->getResultDB()['wind_degree'],1);
		}
	}

    public function getPressureHpa() {
		if( $this->getResultWS() !== null && $this->getResultDB() === null ) {
			return round($this->getResultWS()['query']['results']['channel']['atmosphere']['pressure'],1);
		} elseif( $this->getResultWS() === null && $this->getResultDB() !== null ) {
			return round($this->getResultDB()['pressure_hpa'],1);
		}
	}

    public function getHumidity() {
		if( $this->getResultWS() !== null && $this->getResultDB() === null ) {
			return $this->getResultWS()['query']['results']['channel']['atmosphere']['humidity'];
		} elseif( $this->getResultWS() === null && $this->getResultDB() !== null ) {
			return $this->getResultDB()['humidity'];
		}
	}
	/**
	*	End interface implementation
	*/

}
?>