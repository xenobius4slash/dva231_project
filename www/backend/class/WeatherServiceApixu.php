<?php
require_once 'WeatherService.php';

/**	weather conditions: http://www.apixu.com/doc/Apixu_weather_conditions.xml
*/
class WeatherServiceApixu extends WeatherService implements WeatherServiceInterface {
	private $test = true;
	private	$weatherServiceId = 1;
	private $weatherServiceName = 'apixu';
	private $creditName = 'Powered by Apixu.com';
	private $apiKey = '71280c0f25b34a539e2131857181910';
	private $baseUrl = 'http://api.apixu.com/v1/';
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
	*	example for 'current':	http://api.apixu.com/v1/current.json?key=71280c0f25b34a539e2131857181910&q=Paris
	*	example for 'forecast':	http://api.apixu.com/v1/forecast.json?key=71280c0f25b34a539e2131857181910&q=Paris&days=5
	*	@param		$town			String
	*	@return		NULL || String
	*/
	private function getUrlByOptions($town) {
		if($this->getMode() == 'current') {
			return $this->baseUrl.'current.json?key='.$this->apiKey.'&q='.$town;
		} elseif($this->getMode() == 'forecast') {
			return $this->baseUrl.'forecast.json?key='.$this->apiKey.'&q='.$town.'&days=5';
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
			if( $this->setResultWS( $this->getArrayFromJson( $this->getTestResult('apixu', $this->getMode()) ) ) === false ) {
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
			$result = $this->getResultWS()['current']['last_updated_epoch'];
			return date('Y-m-d H:i:s', $result);
		} elseif( $this->getResultWS() === null && $this->getResultDB() !== null ) {
			return $this->getResultDB()['build_date'];
		}
	}

	public function getTemperature() {
		if( $this->getResultWS() !== null && $this->getResultDB() === null ) {
			if( $this->getTempUnit() == 'celsius' ) {
				return round($this->getResultWS()['current']['temp_c'],1);
			} else {
				return $this->convertCelsiusToFahrenheit($this->getResultWS()['current']['temp_c']);
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
			return $this->getResultWS()['current']['condition']['text'];
		} elseif( $this->getResultWS() === null && $this->getResultDB() !== null ) {
			return $this->getResultDB()['sky_condition'];
		}
	}

    public function getWindSpeed() {
		if( $this->getResultWS() !== null && $this->getResultDB() === null ) {
			return round($this->getResultWS()['current']['wind_mph'],1);
		} elseif( $this->getResultWS() === null && $this->getResultDB() !== null ) {
			return round($this->getResultDB()['wind_speed_mph'],1);
		}
	}

    public function getWindDegree() {
		if( $this->getResultWS() !== null && $this->getResultDB() === null ) {
			return round($this->getResultWS()['current']['wind_degree'],1);
		} elseif( $this->getResultWS() === null && $this->getResultDB() !== null ) {
			return round($this->getResultDB()['wind_degree'],1);
		}
	}

    public function getPressureHpa() {
		if( $this->getResultWS() !== null && $this->getResultDB() === null ) {
			return round($this->getResultWS()['current']['pressure_mb'],1);
		} elseif( $this->getResultWS() === null && $this->getResultDB() !== null ) {
			return round($this->getResultDB()['pressure_hpa'],1);
		}
	}

    public function getHumidity() {
		if( $this->getResultWS() !== null && $this->getResultDB() === null ) {
			return $this->getResultWS()['current']['humidity'];
		} elseif( $this->getResultWS() === null && $this->getResultDB() !== null ) {
			return $this->getResultDB()['humidity'];
		}
	}
	/**
	*	End interface implementation
	*/

}
?>
