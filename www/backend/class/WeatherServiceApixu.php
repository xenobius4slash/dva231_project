<?php
require_once 'WeatherService.php';

/**	weather conditions: http://www.apixu.com/doc/Apixu_weather_conditions.xml
*/
class WeatherServiceApixu extends WeatherService implements WeatherServiceInterface {
	private $test = false;
	private $curlError = false;
	private $curlErrorCode;
	private $curlErrorMessage;
	private	$weatherServiceId = 1;
	private $weatherServiceName = 'apixu';
	private $creditName = 'Powered by Apixu.com';
	private $apiKey = '71280c0f25b34a539e2131857181910';
	private $baseUrl = 'http://api.apixu.com/v1/';
	private $resultWeatherService;
	private $resultDatabase;

	/** enable the test, so the results are comming from stored results	*/
	public function enableTest() {
		$this->test = true;
	}

	/**	get the weather service results from the class
	*	@return		Array
	*/
	private function getResultWS() {
		return $this->resultWeatherService;
	}

	public function getCurlError() {
		return $this->curlError;
	}

	private function setCurlError() {
		$this->curlError = true;
	}

	public function getCurlErrorCode() {
		return $this->curlErrorCode;
	}

	private function setCurlErrorCode($code) {
		$this->curlErrorCode = $code;
	}

	public function getCurlErrorMessage() {
		return $this->curlErrorMessage;
	}

	private function setCurlErrorMessage($msg) {
		$this->curlErrorMessage = $msg;
	}

	/** check for errors in the result and set error-code and error-message in class
	*	https://www.apixu.com/doc/errors.aspx
	*	error-result: {"error":{"code":<error-code>,"message":"<message>"}}
	*	HTTP code		error-code		message
	*	401				1002			API key not provided.
	*	400				1003			Parameter 'q' not provided.
	*	400				1005			API request url is invalid
	*	400				1006			No location found matching parameter 'q'
	*	401				2006			API key provided is invalid
	*	403				2007			API key has exceeded calls per month quota.
	*	403				2008			API key has been disabled.
	*	400				9999			Internal application error.
	*	@param		&$result			reference to Array
	*	@return		Bool
	*/
	private function isCurlError($result) {
		if( isset($result['error']) ) {
			$this->setCurlErrorCode($result['error']['code']);
			$this->setCurlErrorMessage($result['error']['message']);
			$this->setCurlError();
			error_log("[cURL result error] apixu: ".$this->getCurlErrorCode()." => ".$this->getCurlErrorMessage());
			return true;
		} else {
			return false;
		}
	}

	/** set the results from the weather service in the class
	*	@param		$array			Array
	*	@return		Bool
	*/
	private function setResultWS($array) {
		if( $this->isCurlError($array) ) {
			return false;
		} else {
			$this->resultWeatherService = $array;
			$this->resultDatabase = null;
			if( is_array( $this->getResultWS() ) ) {
				return true;
			} else {
				return false;
			}
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
			return $this->baseUrl.'current.json?key='.$this->apiKey.'&q='.urlencode($town);
		} elseif($this->getMode() == 'forecast') {
			return $this->baseUrl.'forecast.json?key='.$this->apiKey.'&q='.urlencode($town).'&days=5';
		} else {
			return null;
		}
	}

	/** get a defined array for the insert into the database
	*	@return		Array
	*/
	public function getArrayForDatabase() {
		$array = $this->getArrayOfOneWeatherServiceForDatabaseByData($this->getLastUpdate(true),$this->getTemperature(),$this->getSky(),$this->getWindSpeed(),$this->getWindDegree(),$this->getPressureHpa(),$this->getHumidity());
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
					if( $this->setResultWS( $this->getArrayFromJson($result) ) === false ) {
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

    public function getLastUpdate($db=false) {
		if( $this->getCurlError() ) {
			return '---';
		} else {
			if( $this->getResultWS() !== null && $this->getResultDB() === null ) {
				$result = $this->getResultWS()['current']['last_updated_epoch'];
				if($db) {
					return date('Y-m-d H:i:s', strtotime($result));
				} else {
					return date('j M Y, H:i', strtotime($result));
				}
			} elseif( $this->getResultWS() === null && $this->getResultDB() !== null ) {
				return date('j M Y, H:i', strtotime($this->getResultDB()['build_date']));
			}
		}
	}

	public function getTemperature() {
		if( $this->getCurlError() ) {
			return '---';
		} else {
			if( $this->getResultWS() !== null && $this->getResultDB() === null ) {
				if( $this->getTempUnit() == 'celsius' ) {
					return round($this->getResultWS()['current']['temp_c'],0);
				} else {
					return $this->convertCelsiusToFahrenheit($this->getResultWS()['current']['temp_c']);
				}
			} elseif( $this->getResultWS() === null && $this->getResultDB() !== null ) {
				if( $this->getTempUnit() == 'celsius' ) {
					return round($this->getResultDB()['temp_c'],0);
				} else {
					return round($this->getResultDB()['temp_f'],0);
				}
			}
		}
	}

    public function getSky() {
		if( $this->getCurlError() ) {
			return '---';
		} else {
			if( $this->getResultWS() !== null && $this->getResultDB() === null ) {
				return $this->getResultWS()['current']['condition']['text'];
			} elseif( $this->getResultWS() === null && $this->getResultDB() !== null ) {
				return $this->getResultDB()['sky_condition'];
			}
		}
	}

    public function getWindSpeed() {
		if( $this->getCurlError() ) {
			return '---';
		} else {
			if( $this->getResultWS() !== null && $this->getResultDB() === null ) {
				return round($this->getResultWS()['current']['wind_mph'],0);
			} elseif( $this->getResultWS() === null && $this->getResultDB() !== null ) {
				return round($this->getResultDB()['wind_speed_mph'],0);
			}
		}
	}

    public function getWindDegree() {
		if( $this->getCurlError() ) {
			return '---';
		} else {
			if( $this->getResultWS() !== null && $this->getResultDB() === null ) {
				return round($this->getResultWS()['current']['wind_degree'],0);
			} elseif( $this->getResultWS() === null && $this->getResultDB() !== null ) {
				return round($this->getResultDB()['wind_degree'],0);
			}
		}
	}

    public function getPressureHpa() {
		if( $this->getCurlError() ) {
			return '---';
		} else {
			if( $this->getResultWS() !== null && $this->getResultDB() === null ) {
				return round($this->getResultWS()['current']['pressure_mb'],0);
			} elseif( $this->getResultWS() === null && $this->getResultDB() !== null ) {
				return round($this->getResultDB()['pressure_hpa'],0);
			}
		}
	}

    public function getHumidity() {
		if( $this->getCurlError() ) {
			return '---';
		} else {
			if( $this->getResultWS() !== null && $this->getResultDB() === null ) {
				return $this->getResultWS()['current']['humidity'];
			} elseif( $this->getResultWS() === null && $this->getResultDB() !== null ) {
				return $this->getResultDB()['humidity'];
			}
		}
	}
	/**
	*	End interface implementation
	*/

}
?>
