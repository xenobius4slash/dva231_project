<?php
require_once 'WeatherService.php';

class WeatherServiceYahoo extends WeatherService implements WeatherServiceInterface {
	private $test = false;
	private $curlError = false;
	private $curlErrorCode;
	private $curlErrorMessage;
	private	$weatherServiceId = 3;
	private $weatherServiceName = 'yahoo';
	private $creditName = 'Powered by Yahoo!';
	private $baseUrl = 'https://query.yahooapis.com/v1/public/yql';
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
	*	=> no doc regarding errors
	*	error-result: {"query":{"count":0,"created":"2018-10-23T10:29:33Z","lang":"de-DE","results":null}}
	*	error-result: {"error":{"lang":"en-US","description":"No definition found for Table weather"}}
	*	error-result: 
	*	@param		&$result			reference to Array
	*	@return		Bool
	*/
	private function isCurlError($result) {
		if( isset($result['query']) && isset($result['query']['count']) ) {
			if( $result['query']['count'] == 0 ) {
				$this->setCurlErrorCode('self');
				$this->setCurlErrorMessage('No location found matching parameter');
				$this->setCurlError();
				error_log("[cURL result error] yahoo: ".$this->getCurlErrorCode()." => ".$this->getCurlErrorMessage());
				return true;
			} else {
				return false;
			}
		} elseif( isset($result['error']) ) {
			$this->setCurlErrorCode('yahoo');
			$this->setCurlErrorMessage($result['error']['descirption']);
			error_log("[cURL result error] yahoo: ".$this->getCurlErrorCode()." => ".$this->getCurlErrorMessage());
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

    public function getLastUpdate($db=false) {
		if( $this->getCurlError() ) {
			return '---';
		} else {
			if( $this->getResultWS() !== null && $this->getResultDB() === null ) {
				$result = $this->getResultWS()['query']['results']['channel']['lastBuildDate'];
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
					return round($this->getResultWS()['query']['results']['channel']['item']['condition']['temp'],0);
				} else {
					return $this->convertCelsiusToFahrenheit($this->getResultWS()['query']['results']['channel']['item']['condition']['temp']);
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
				return $this->getResultWS()['query']['results']['channel']['item']['condition']['text'];
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
				return $this->convertKilometrePerHourToMilesPerHour($this->getResultWS()['query']['results']['channel']['wind']['speed']);
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
				return round($this->getResultWS()['query']['results']['channel']['wind']['direction'],0);
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
				// there is an error in the API, which provide the wrong unit
				$pressureApi = $this->getResultWS()['query']['results']['channel']['atmosphere']['pressure'];
				$errorConstant = 33.7685;
				$pressureCorrected = $pressureApi / $errorConstant;
				return round($pressureCorrected ,0);
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
				return $this->getResultWS()['query']['results']['channel']['atmosphere']['humidity'];
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
