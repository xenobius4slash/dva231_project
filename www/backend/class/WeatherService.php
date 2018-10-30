<?php
require_once 'DatabaseWeatherDataCurrent.php';

interface WeatherServiceInterface {

	/** load the result of the current weather of a town regarding the mode (current or forecast) in the class
	*	Source is the weather service (API Call)
	*	@param		$town			String
	*	@return		Bool
	*/
	public function loadResultByTownApi($town);

	/** load the result of the current weather of a town regarding the mode (current or forecast) in the class
	*	Source is the database
	*	@param		$townId			Integer
	*	@return		Bool
	*/
	public function loadResultByTownIdDb($townId);

	/**	get the last updated timestamp from the loaded results
	*	@param		$db				Bool	(for using return for database should be true)
	*	@return		Timestamp
	*/
	public function getLastUpdate($db=false);

	/** get the temperature in celsius from the loaded results
	*	@return		Float
	*/
	public function getTemperature();

	/**	get the condition of the sky like sunny or cloudy from the loaded results
	*	@return		String
	*/
	public function getSky();

	/** get the wind speed in mph from the loaded results
	*	@return		Float
	*/
	public function getWindSpeed();

	/**	get the wind degree in ° from the loaded results
	*	@return		Float
	*/
	public function getWindDegree();

	/** get the pressure in hpa from the loaded results
	*	@return		Float
	*/
	public function getPressureHpa();

	/**	get the humidity in % from the loaded results
	*	@return		Integer
	*/
	public function getHumidity();
}

class WeatherService {
	// user settings
	private $mode = 'current';	// default: current ['current', 'forecast']
	private $unit = 'celsius';	// default: celsius ['celsius', 'fahrenheit']

	/**	convert a JSON to a associated HP-Array
	*	@param		$json		JSON-Object
	*	@returm		Array
	*/
	public function getArrayFromJson($json) {
		return json_decode($json, true);
	}

	/** set the mode to 'current' => no 'forecast' */
	public function setModeCurrent() {
		$this->mode = 'current';
	}

	/** set the mode to 'forecast' => no 'current' */
	public function setModeForecast() {
		$this->mode = 'forecast';
	}

	/** get the mode 
	*	@return		String ['current', 'forecast']
	*/
	public function getMode() {
		return $this->mode;
	}

	/** set the temperature unit to 'celcius' => no 'fahrenheit' */
	public function setTempCelsius() {
		$this->unit = 'celsius';
	}

	/** set the temperature unit to 'fahrenheit' => no 'celsius' */
	public function setTempFahrenheit() {
		$this->unit = 'fahrenheit';
	}

	/** get the temperature unit 
	*	@return		String	['celsius', 'fahrenheit']
	**/
	public function getTempUnit() {
		return $this->unit;
	}

	/** convert a value from "metre per second" (mps) to "miles per hour" (mph)
	*	@param		$value		Float
	*	@return		False || Float
	*/
	public function convertMetrePerSecondToMilesPerHour($value) {
		if(is_numeric($value)) {
			return round( ($value * 2.2369), 0);
		} else {
			return false;
		}
	}

	/** convert a value from "kilometre per hour" (mps) to "miles per hour" (mph)
	*	@param		$value		Float
	*	@return		False || Float
	*/
	public function convertKilometrePerHourToMilesPerHour($value) {
		if(is_numeric($value)) {
			return round( ($value * 0.6214), 0);
		} else {
			return false;
		}
	}

	/** convert a value from "celsius" to "fahrenheit"
	*	@param		$value		Float
	*	@return		False || Float
	*/
	public function convertCelsiusToFahrenheit($value) {
		if(is_numeric($value)) {
			return round( ($value * 1.8 + 32), 0);
		} else {
			return false;
		}
	}

	/** Send a cURL request to a given URL (included API-Key and Town).
	*	Get the answer of the request in JSON format.
	*	@param		$url		String
	*	@return		FALSE || JSON
	*/
	public function getResultByCurlRequest($url) {
//		error_log("API Call: '".$url."'");
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($ch);
		curl_close($ch);
//		error_log("JSON: $result");
		return $result;
	}

	/** get the data from the database
	*	@param		$townId				Integer
	*	@param		$weatherServiceId	Integer
	*	@return		NULL || Array
	*/
	public function getResultByTownIdAndWeatherServiceDb($townId, $weatherServiceId) {
		$DBWDC = new DatabaseWeatherDataCurrent();
		return $DBWDC->getLatestByTownIdAndWeatherServiceId($townId, $weatherServiceId);
	}

	/** get a defined array of only one weather service by specific data.
	*	array is needed for inserting data into the database
	*	@param		$lastUpdate			String	(YYYY-MM-DD HH:ii:ss)
	*	@param		$temperature		Float	(celsius)
	*	@param		$sky				String
	*	@param		$windSpeed			Float	(mph)
	*	@param		$windDegree			Float	(°)
	*	@param		$pressure			Float	(hpa)
	*	@param		$humidity			Integer	(%)
	*	@return		Array
	*/
	public function getArrayOfOneWeatherServiceForDatabaseByData($lastUpdate,$temperature,$sky,$windSpeed,$windDegree,$pressure,$humidity) {
		return array('build_date' => $lastUpdate, 'temp_c' => $temperature, 'temp_f' => $this->convertCelsiusToFahrenheit($temperature), 'sky_condition' => $sky, 'wind_speed_mph' => $windSpeed, 'wind_degree' => $windDegree, 'pressure_hpa' => $pressure, 'humidity' => $humidity);
	}

	/** TEST */
	public function getTestResult($service, $option) {
		error_log("[TEST-MODE] receive stored results ($service, $option)");
		$return = null;
		$TEST = new test();
		switch($service) {
			case 'apixu':	if($option == 'current') { $return = $TEST->getApixuCurrent(); } else { $TEST->getApixuForecast(); }
							break;
			case 'owm':		if($option == 'current') { $return = $TEST->getOwmCurrent(); } else { $TEST->getOwmForecast(); }
							break;
			case 'yahoo':	if($option == 'current') { $return = $TEST->getYahooForecast(); } else { $TEST->getYahooForecast(); }
							break;
		}
		return $return;
	}
}

class test {
	// http://api.apixu.com/v1/current.json?key=71280c0f25b34a539e2131857181910&q=Paris
	private $apixuCurrent = '{"location":{"name":"Paris","region":"Ile-de-France","country":"France","lat":48.87,"lon":2.33,"tz_id":"Europe/Paris","localtime_epoch":1540139693,"localtime":"2018-10-21 18:34"},"current":{"last_updated_epoch":1540138521,"last_updated":"2018-10-21 18:15","temp_c":17.0,"temp_f":62.6,"is_day":1,"condition":{"text":"Sunny","icon":"//cdn.apixu.com/weather/64x64/day/113.png","code":1000},"wind_mph":9.4,"wind_kph":15.1,"wind_degree":10,"wind_dir":"N","pressure_mb":1025.0,"pressure_in":30.8,"precip_mm":0.0,"precip_in":0.0,"humidity":48,"cloud":0,"feelslike_c":17.0,"feelslike_f":62.6,"vis_km":10.0,"vis_miles":6.0}}';
	// http://api.apixu.com/v1/forecast.json?key=71280c0f25b34a539e2131857181910&q=Paris&days=5
	private $apixuForecast = '{"location":{"name":"Paris","region":"Ile-de-France","country":"France","lat":48.87,"lon":2.33,"tz_id":"Europe/Paris","localtime_epoch":1540139692,"localtime":"2018-10-21 18:34"},"current":{"last_updated_epoch":1540138521,"last_updated":"2018-10-21 18:15","temp_c":17.0,"temp_f":62.6,"is_day":1,"condition":{"text":"Sunny","icon":"//cdn.apixu.com/weather/64x64/day/113.png","code":1000},"wind_mph":9.4,"wind_kph":15.1,"wind_degree":10,"wind_dir":"N","pressure_mb":1025.0,"pressure_in":30.8,"precip_mm":0.0,"precip_in":0.0,"humidity":48,"cloud":0,"feelslike_c":17.0,"feelslike_f":62.6,"vis_km":10.0,"vis_miles":6.0},"forecast":{"forecastday":[{"date":"2018-10-21","date_epoch":1540080000,"day":{"maxtemp_c":18.1,"maxtemp_f":64.6,"mintemp_c":12.5,"mintemp_f":54.5,"avgtemp_c":13.8,"avgtemp_f":56.9,"maxwind_mph":6.0,"maxwind_kph":9.7,"totalprecip_mm":0.0,"totalprecip_in":0.0,"avgvis_km":18.8,"avgvis_miles":11.0,"avghumidity":52.0,"condition":{"text":"Partly cloudy","icon":"//cdn.apixu.com/weather/64x64/day/116.png","code":1003},"uv":2.3},"astro":{"sunrise":"08:20 AM","sunset":"06:50 PM","moonrise":"05:59 PM","moonset":"04:20 AM"}},{"date":"2018-10-22","date_epoch":1540166400,"day":{"maxtemp_c":15.3,"maxtemp_f":59.5,"mintemp_c":9.1,"mintemp_f":48.4,"avgtemp_c":13.2,"avgtemp_f":55.8,"maxwind_mph":13.0,"maxwind_kph":20.9,"totalprecip_mm":0.3,"totalprecip_in":0.01,"avgvis_km":19.9,"avgvis_miles":12.0,"avghumidity":69.0,"condition":{"text":"Patchy rain possible","icon":"//cdn.apixu.com/weather/64x64/day/176.png","code":1063},"uv":1.1},"astro":{"sunrise":"08:21 AM","sunset":"06:48 PM","moonrise":"06:22 PM","moonset":"05:27 AM"}},{"date":"2018-10-23","date_epoch":1540252800,"day":{"maxtemp_c":16.1,"maxtemp_f":61.0,"mintemp_c":11.7,"mintemp_f":53.1,"avgtemp_c":12.0,"avgtemp_f":53.5,"maxwind_mph":9.6,"maxwind_kph":15.5,"totalprecip_mm":0.0,"totalprecip_in":0.0,"avgvis_km":20.0,"avgvis_miles":12.0,"avghumidity":63.0,"condition":{"text":"Partly cloudy","icon":"//cdn.apixu.com/weather/64x64/day/116.png","code":1003},"uv":2.1},"astro":{"sunrise":"08:23 AM","sunset":"06:46 PM","moonrise":"06:45 PM","moonset":"06:34 AM"}},{"date":"2018-10-24","date_epoch":1540339200,"day":{"maxtemp_c":17.9,"maxtemp_f":64.2,"mintemp_c":12.3,"mintemp_f":54.1,"avgtemp_c":14.2,"avgtemp_f":57.6,"maxwind_mph":5.1,"maxwind_kph":8.3,"totalprecip_mm":0.0,"totalprecip_in":0.0,"avgvis_km":20.0,"avgvis_miles":12.0,"avghumidity":73.0,"condition":{"text":"Partly cloudy","icon":"//cdn.apixu.com/weather/64x64/day/116.png","code":1003},"uv":1.9},"astro":{"sunrise":"08:24 AM","sunset":"06:44 PM","moonrise":"07:10 PM","moonset":"07:43 AM"}},{"date":"2018-10-25","date_epoch":1540425600,"day":{"maxtemp_c":17.3,"maxtemp_f":63.1,"mintemp_c":11.1,"mintemp_f":52.0,"avgtemp_c":14.0,"avgtemp_f":57.2,"maxwind_mph":2.9,"maxwind_kph":4.7,"totalprecip_mm":0.0,"totalprecip_in":0.0,"avgvis_km":20.0,"avgvis_miles":12.0,"avghumidity":73.0,"condition":{"text":"Cloudy","icon":"//cdn.apixu.com/weather/64x64/day/119.png","code":1006},"uv":1.9},"astro":{"sunrise":"08:26 AM","sunset":"06:42 PM","moonrise":"07:37 PM","moonset":"08:54 AM"}}]}}';
	// https://api.openweathermap.org/data/2.5/weather?q=Paris&appid=88bceb6b051ac97686367acdd48f46e1&units=metric
	private $owmCurrent = '{"coord":{"lon":2.35,"lat":48.86},"weather":[{"id":800,"main":"Clear","description":"clear sky","icon":"01d"}],"base":"stations","main":{"temp":16.75,"pressure":1026,"humidity":45,"temp_min":16,"temp_max":17},"visibility":10000,"wind":{"speed":2.1,"deg":350},"clouds":{"all":0},"dt":1540137600,"sys":{"type":1,"id":5610,"message":0.0047,"country":"FR","sunrise":1540102857,"sunset":1540140509},"id":2988507,"name":"Paris","cod":200}';
	// https://api.openweathermap.org/data/2.5/forecast?q=Paris&appid=88bceb6b051ac97686367acdd48f46e1&units=metric
	private $owmForecast = '{"cod":"200","message":0.0025,"cnt":40,"list":[{"dt":1540144800,"main":{"temp":11.81,"temp_min":11.81,"temp_max":12.06,"pressure":1027.49,"sea_level":1039.68,"grnd_level":1027.49,"humidity":66,"temp_kf":-0.26},"weather":[{"id":800,"main":"Clear","description":"clear sky","icon":"01n"}],"clouds":{"all":0},"wind":{"speed":3.64,"deg":16.5005},"sys":{"pod":"n"},"dt_txt":"2018-10-21 18:00:00"},{"dt":1540155600,"main":{"temp":8.19,"temp_min":8.19,"temp_max":8.39,"pressure":1028.05,"sea_level":1040.41,"grnd_level":1028.05,"humidity":85,"temp_kf":-0.19},"weather":[{"id":800,"main":"Clear","description":"clear sky","icon":"01n"}],"clouds":{"all":0},"wind":{"speed":2.96,"deg":25.5035},"sys":{"pod":"n"},"dt_txt":"2018-10-21 21:00:00"},{"dt":1540166400,"main":{"temp":7.47,"temp_min":7.47,"temp_max":7.6,"pressure":1028.69,"sea_level":1041.24,"grnd_level":1028.69,"humidity":86,"temp_kf":-0.13},"weather":[{"id":800,"main":"Clear","description":"clear sky","icon":"02n"}],"clouds":{"all":8},"wind":{"speed":3.41,"deg":357.006},"sys":{"pod":"n"},"dt_txt":"2018-10-22 00:00:00"},{"dt":1540177200,"main":{"temp":7.21,"temp_min":7.21,"temp_max":7.28,"pressure":1029.02,"sea_level":1041.56,"grnd_level":1029.02,"humidity":92,"temp_kf":-0.06},"weather":[{"id":800,"main":"Clear","description":"clear sky","icon":"01n"}],"clouds":{"all":0},"wind":{"speed":3.67,"deg":0.500275},"sys":{"pod":"n"},"dt_txt":"2018-10-22 03:00:00"},{"dt":1540188000,"main":{"temp":7.13,"temp_min":7.13,"temp_max":7.13,"pressure":1030.27,"sea_level":1042.78,"grnd_level":1030.27,"humidity":91,"temp_kf":0},"weather":[{"id":800,"main":"Clear","description":"clear sky","icon":"01n"}],"clouds":{"all":0},"wind":{"speed":3.67,"deg":359.501},"sys":{"pod":"n"},"dt_txt":"2018-10-22 06:00:00"},{"dt":1540198800,"main":{"temp":11.65,"temp_min":11.65,"temp_max":11.65,"pressure":1032.21,"sea_level":1044.55,"grnd_level":1032.21,"humidity":90,"temp_kf":0},"weather":[{"id":500,"main":"Rain","description":"light rain","icon":"10d"}],"clouds":{"all":56},"wind":{"speed":4.76,"deg":14.5022},"rain":{"3h":0.025},"sys":{"pod":"d"},"dt_txt":"2018-10-22 09:00:00"},{"dt":1540209600,"main":{"temp":14.56,"temp_min":14.56,"temp_max":14.56,"pressure":1033.04,"sea_level":1045.22,"grnd_level":1033.04,"humidity":76,"temp_kf":0},"weather":[{"id":500,"main":"Rain","description":"light rain","icon":"10d"}],"clouds":{"all":88},"wind":{"speed":6.71,"deg":24},"rain":{"3h":0.145},"sys":{"pod":"d"},"dt_txt":"2018-10-22 12:00:00"},{"dt":1540220400,"main":{"temp":15.37,"temp_min":15.37,"temp_max":15.37,"pressure":1033.56,"sea_level":1045.6,"grnd_level":1033.56,"humidity":63,"temp_kf":0},"weather":[{"id":801,"main":"Clouds","description":"few clouds","icon":"02d"}],"clouds":{"all":24},"wind":{"speed":7.48,"deg":24.0005},"rain":{},"sys":{"pod":"d"},"dt_txt":"2018-10-22 15:00:00"},{"dt":1540231200,"main":{"temp":12.31,"temp_min":12.31,"temp_max":12.31,"pressure":1034.8,"sea_level":1047.19,"grnd_level":1034.8,"humidity":62,"temp_kf":0},"weather":[{"id":800,"main":"Clear","description":"clear sky","icon":"01n"}],"clouds":{"all":0},"wind":{"speed":5.73,"deg":25.5038},"rain":{},"sys":{"pod":"n"},"dt_txt":"2018-10-22 18:00:00"},{"dt":1540242000,"main":{"temp":9.36,"temp_min":9.36,"temp_max":9.36,"pressure":1036.31,"sea_level":1048.69,"grnd_level":1036.31,"humidity":72,"temp_kf":0},"weather":[{"id":800,"main":"Clear","description":"clear sky","icon":"01n"}],"clouds":{"all":0},"wind":{"speed":4.67,"deg":24.5009},"rain":{},"sys":{"pod":"n"},"dt_txt":"2018-10-22 21:00:00"},{"dt":1540252800,"main":{"temp":6.74,"temp_min":6.74,"temp_max":6.74,"pressure":1036.64,"sea_level":1049.16,"grnd_level":1036.64,"humidity":91,"temp_kf":0},"weather":[{"id":800,"main":"Clear","description":"clear sky","icon":"01n"}],"clouds":{"all":0},"wind":{"speed":3.81,"deg":19.5012},"rain":{},"sys":{"pod":"n"},"dt_txt":"2018-10-23 00:00:00"},{"dt":1540263600,"main":{"temp":4.8,"temp_min":4.8,"temp_max":4.8,"pressure":1036.07,"sea_level":1048.63,"grnd_level":1036.07,"humidity":94,"temp_kf":0},"weather":[{"id":800,"main":"Clear","description":"clear sky","icon":"01n"}],"clouds":{"all":0},"wind":{"speed":2.67,"deg":1.00073},"rain":{},"sys":{"pod":"n"},"dt_txt":"2018-10-23 03:00:00"},{"dt":1540274400,"main":{"temp":3.47,"temp_min":3.47,"temp_max":3.47,"pressure":1035.6,"sea_level":1048.31,"grnd_level":1035.6,"humidity":91,"temp_kf":0},"weather":[{"id":800,"main":"Clear","description":"clear sky","icon":"02n"}],"clouds":{"all":8},"wind":{"speed":2.66,"deg":327.504},"rain":{},"sys":{"pod":"n"},"dt_txt":"2018-10-23 06:00:00"},{"dt":1540285200,"main":{"temp":8.84,"temp_min":8.84,"temp_max":8.84,"pressure":1035.41,"sea_level":1047.84,"grnd_level":1035.41,"humidity":77,"temp_kf":0},"weather":[{"id":801,"main":"Clouds","description":"few clouds","icon":"02d"}],"clouds":{"all":20},"wind":{"speed":1.97,"deg":291.5},"rain":{},"sys":{"pod":"d"},"dt_txt":"2018-10-23 09:00:00"},{"dt":1540296000,"main":{"temp":14.25,"temp_min":14.25,"temp_max":14.25,"pressure":1033.86,"sea_level":1046.08,"grnd_level":1033.86,"humidity":67,"temp_kf":0},"weather":[{"id":800,"main":"Clear","description":"clear sky","icon":"01d"}],"clouds":{"all":0},"wind":{"speed":4.01,"deg":291.506},"rain":{},"sys":{"pod":"d"},"dt_txt":"2018-10-23 12:00:00"},{"dt":1540306800,"main":{"temp":16.16,"temp_min":16.16,"temp_max":16.16,"pressure":1031.29,"sea_level":1043.42,"grnd_level":1031.29,"humidity":58,"temp_kf":0},"weather":[{"id":800,"main":"Clear","description":"clear sky","icon":"01d"}],"clouds":{"all":0},"wind":{"speed":5.81,"deg":301.5},"rain":{},"sys":{"pod":"d"},"dt_txt":"2018-10-23 15:00:00"},{"dt":1540317600,"main":{"temp":13.38,"temp_min":13.38,"temp_max":13.38,"pressure":1030.66,"sea_level":1042.95,"grnd_level":1030.66,"humidity":61,"temp_kf":0},"weather":[{"id":800,"main":"Clear","description":"clear sky","icon":"01n"}],"clouds":{"all":0},"wind":{"speed":5.11,"deg":313},"rain":{},"sys":{"pod":"n"},"dt_txt":"2018-10-23 18:00:00"},{"dt":1540328400,"main":{"temp":11.17,"temp_min":11.17,"temp_max":11.17,"pressure":1031.23,"sea_level":1043.58,"grnd_level":1031.23,"humidity":80,"temp_kf":0},"weather":[{"id":500,"main":"Rain","description":"light rain","icon":"10n"}],"clouds":{"all":48},"wind":{"speed":3.7,"deg":328.001},"rain":{"3h":0.01},"sys":{"pod":"n"},"dt_txt":"2018-10-23 21:00:00"},{"dt":1540339200,"main":{"temp":9.98,"temp_min":9.98,"temp_max":9.98,"pressure":1031.03,"sea_level":1043.41,"grnd_level":1031.03,"humidity":94,"temp_kf":0},"weather":[{"id":802,"main":"Clouds","description":"scattered clouds","icon":"03n"}],"clouds":{"all":32},"wind":{"speed":2.86,"deg":311.001},"rain":{},"sys":{"pod":"n"},"dt_txt":"2018-10-24 00:00:00"},{"dt":1540350000,"main":{"temp":9.41,"temp_min":9.41,"temp_max":9.41,"pressure":1030.59,"sea_level":1042.97,"grnd_level":1030.59,"humidity":94,"temp_kf":0},"weather":[{"id":801,"main":"Clouds","description":"few clouds","icon":"02n"}],"clouds":{"all":24},"wind":{"speed":2.67,"deg":310.003},"rain":{},"sys":{"pod":"n"},"dt_txt":"2018-10-24 03:00:00"},{"dt":1540360800,"main":{"temp":9.57,"temp_min":9.57,"temp_max":9.57,"pressure":1030.51,"sea_level":1042.88,"grnd_level":1030.51,"humidity":93,"temp_kf":0},"weather":[{"id":500,"main":"Rain","description":"light rain","icon":"10n"}],"clouds":{"all":80},"wind":{"speed":2.36,"deg":307.003},"rain":{"3h":0.02},"sys":{"pod":"n"},"dt_txt":"2018-10-24 06:00:00"},{"dt":1540371600,"main":{"temp":12.47,"temp_min":12.47,"temp_max":12.47,"pressure":1031.14,"sea_level":1043.48,"grnd_level":1031.14,"humidity":91,"temp_kf":0},"weather":[{"id":500,"main":"Rain","description":"light rain","icon":"10d"}],"clouds":{"all":92},"wind":{"speed":2.41,"deg":304.501},"rain":{"3h":0.06},"sys":{"pod":"d"},"dt_txt":"2018-10-24 09:00:00"},{"dt":1540382400,"main":{"temp":15.36,"temp_min":15.36,"temp_max":15.36,"pressure":1030.75,"sea_level":1042.87,"grnd_level":1030.75,"humidity":77,"temp_kf":0},"weather":[{"id":500,"main":"Rain","description":"light rain","icon":"10d"}],"clouds":{"all":64},"wind":{"speed":2.48,"deg":311.004},"rain":{"3h":0.06},"sys":{"pod":"d"},"dt_txt":"2018-10-24 12:00:00"},{"dt":1540393200,"main":{"temp":16.45,"temp_min":16.45,"temp_max":16.45,"pressure":1029.18,"sea_level":1041.27,"grnd_level":1029.18,"humidity":66,"temp_kf":0},"weather":[{"id":804,"main":"Clouds","description":"overcast clouds","icon":"04d"}],"clouds":{"all":92},"wind":{"speed":2.66,"deg":310.502},"rain":{},"sys":{"pod":"d"},"dt_txt":"2018-10-24 15:00:00"},{"dt":1540404000,"main":{"temp":14.71,"temp_min":14.71,"temp_max":14.71,"pressure":1028.44,"sea_level":1040.7,"grnd_level":1028.44,"humidity":75,"temp_kf":0},"weather":[{"id":500,"main":"Rain","description":"light rain","icon":"10n"}],"clouds":{"all":76},"wind":{"speed":2.01,"deg":314.502},"rain":{"3h":0.02},"sys":{"pod":"n"},"dt_txt":"2018-10-24 18:00:00"},{"dt":1540414800,"main":{"temp":12.58,"temp_min":12.58,"temp_max":12.58,"pressure":1028.07,"sea_level":1040.27,"grnd_level":1028.07,"humidity":86,"temp_kf":0},"weather":[{"id":500,"main":"Rain","description":"light rain","icon":"10n"}],"clouds":{"all":44},"wind":{"speed":2.11,"deg":324.004},"rain":{"3h":0.03},"sys":{"pod":"n"},"dt_txt":"2018-10-24 21:00:00"},{"dt":1540425600,"main":{"temp":7.4,"temp_min":7.4,"temp_max":7.4,"pressure":1027.41,"sea_level":1039.76,"grnd_level":1027.41,"humidity":89,"temp_kf":0},"weather":[{"id":800,"main":"Clear","description":"clear sky","icon":"01n"}],"clouds":{"all":0},"wind":{"speed":1.86,"deg":320},"rain":{},"sys":{"pod":"n"},"dt_txt":"2018-10-25 00:00:00"},{"dt":1540436400,"main":{"temp":4.46,"temp_min":4.46,"temp_max":4.46,"pressure":1025.95,"sea_level":1038.49,"grnd_level":1025.95,"humidity":88,"temp_kf":0},"weather":[{"id":800,"main":"Clear","description":"clear sky","icon":"01n"}],"clouds":{"all":0},"wind":{"speed":1.38,"deg":323.001},"rain":{},"sys":{"pod":"n"},"dt_txt":"2018-10-25 03:00:00"},{"dt":1540447200,"main":{"temp":2.78,"temp_min":2.78,"temp_max":2.78,"pressure":1024.74,"sea_level":1037.28,"grnd_level":1024.74,"humidity":86,"temp_kf":0},"weather":[{"id":800,"main":"Clear","description":"clear sky","icon":"02n"}],"clouds":{"all":8},"wind":{"speed":1.36,"deg":301.001},"rain":{},"sys":{"pod":"n"},"dt_txt":"2018-10-25 06:00:00"},{"dt":1540458000,"main":{"temp":9.28,"temp_min":9.28,"temp_max":9.28,"pressure":1024.37,"sea_level":1036.67,"grnd_level":1024.37,"humidity":97,"temp_kf":0},"weather":[{"id":500,"main":"Rain","description":"light rain","icon":"10d"}],"clouds":{"all":80},"wind":{"speed":2.16,"deg":314.503},"rain":{"3h":0.04},"sys":{"pod":"d"},"dt_txt":"2018-10-25 09:00:00"},{"dt":1540468800,"main":{"temp":12.88,"temp_min":12.88,"temp_max":12.88,"pressure":1022.95,"sea_level":1034.97,"grnd_level":1022.95,"humidity":78,"temp_kf":0},"weather":[{"id":500,"main":"Rain","description":"light rain","icon":"10d"}],"clouds":{"all":12},"wind":{"speed":1.96,"deg":282.5},"rain":{"3h":0.05},"sys":{"pod":"d"},"dt_txt":"2018-10-25 12:00:00"},{"dt":1540479600,"main":{"temp":14.82,"temp_min":14.82,"temp_max":14.82,"pressure":1020.27,"sea_level":1032.24,"grnd_level":1020.27,"humidity":65,"temp_kf":0},"weather":[{"id":800,"main":"Clear","description":"clear sky","icon":"01d"}],"clouds":{"all":0},"wind":{"speed":1.98,"deg":283.5},"rain":{},"sys":{"pod":"d"},"dt_txt":"2018-10-25 15:00:00"},{"dt":1540490400,"main":{"temp":8.88,"temp_min":8.88,"temp_max":8.88,"pressure":1018.86,"sea_level":1031.06,"grnd_level":1018.86,"humidity":91,"temp_kf":0},"weather":[{"id":800,"main":"Clear","description":"clear sky","icon":"01n"}],"clouds":{"all":0},"wind":{"speed":1.05,"deg":276.505},"rain":{},"sys":{"pod":"n"},"dt_txt":"2018-10-25 18:00:00"},{"dt":1540501200,"main":{"temp":5.2,"temp_min":5.2,"temp_max":5.2,"pressure":1017.5,"sea_level":1029.84,"grnd_level":1017.5,"humidity":84,"temp_kf":0},"weather":[{"id":800,"main":"Clear","description":"clear sky","icon":"01n"}],"clouds":{"all":0},"wind":{"speed":1.18,"deg":177.503},"rain":{},"sys":{"pod":"n"},"dt_txt":"2018-10-25 21:00:00"},{"dt":1540512000,"main":{"temp":4.16,"temp_min":4.16,"temp_max":4.16,"pressure":1016.31,"sea_level":1028.75,"grnd_level":1016.31,"humidity":83,"temp_kf":0},"weather":[{"id":802,"main":"Clouds","description":"scattered clouds","icon":"03n"}],"clouds":{"all":48},"wind":{"speed":1.36,"deg":242.5},"rain":{},"sys":{"pod":"n"},"dt_txt":"2018-10-26 00:00:00"},{"dt":1540522800,"main":{"temp":5.16,"temp_min":5.16,"temp_max":5.16,"pressure":1014.53,"sea_level":1026.81,"grnd_level":1014.53,"humidity":84,"temp_kf":0},"weather":[{"id":803,"main":"Clouds","description":"broken clouds","icon":"04n"}],"clouds":{"all":80},"wind":{"speed":1.66,"deg":266},"rain":{},"sys":{"pod":"n"},"dt_txt":"2018-10-26 03:00:00"},{"dt":1540533600,"main":{"temp":5.79,"temp_min":5.79,"temp_max":5.79,"pressure":1013.7,"sea_level":1025.96,"grnd_level":1013.7,"humidity":93,"temp_kf":0},"weather":[{"id":804,"main":"Clouds","description":"overcast clouds","icon":"04n"}],"clouds":{"all":92},"wind":{"speed":1.28,"deg":279.502},"rain":{},"sys":{"pod":"n"},"dt_txt":"2018-10-26 06:00:00"},{"dt":1540544400,"main":{"temp":10.09,"temp_min":10.09,"temp_max":10.09,"pressure":1013.89,"sea_level":1026.13,"grnd_level":1013.89,"humidity":94,"temp_kf":0},"weather":[{"id":500,"main":"Rain","description":"light rain","icon":"10d"}],"clouds":{"all":92},"wind":{"speed":2.77,"deg":326.001},"rain":{"3h":0.38},"sys":{"pod":"d"},"dt_txt":"2018-10-26 09:00:00"},{"dt":1540555200,"main":{"temp":10.8,"temp_min":10.8,"temp_max":10.8,"pressure":1013.98,"sea_level":1026.07,"grnd_level":1013.98,"humidity":93,"temp_kf":0},"weather":[{"id":500,"main":"Rain","description":"light rain","icon":"10d"}],"clouds":{"all":92},"wind":{"speed":1.96,"deg":297.507},"rain":{"3h":2.67},"sys":{"pod":"d"},"dt_txt":"2018-10-26 12:00:00"},{"dt":1540566000,"main":{"temp":10.39,"temp_min":10.39,"temp_max":10.39,"pressure":1013.42,"sea_level":1025.58,"grnd_level":1013.42,"humidity":86,"temp_kf":0},"weather":[{"id":500,"main":"Rain","description":"light rain","icon":"10d"}],"clouds":{"all":92},"wind":{"speed":5.38,"deg":353.001},"rain":{"3h":0.61},"sys":{"pod":"d"},"dt_txt":"2018-10-26 15:00:00"}],"city":{"id":2988507,"name":"Paris","coord":{"lat":48.8566,"lon":2.3515},"country":"FR","population":2138551}}';
	private $yahooCurrent = '';
	// https://query.yahooapis.com/v1/public/yql?q=select%20*%20from%20weather.forecast%20where%20woeid%20in%20(select%20woeid%20from%20geo.places(1)%20where%20text%3D%22Paris%22)%20and%20u%3D%22c%22&format=json
	private $yahooForecast = '{"query":{"count":1,"created":"2018-10-21T16:34:46Z","lang":"de-DE","results":{"channel":{"units":{"distance":"km","pressure":"mb","speed":"km/h","temperature":"C"},"title":"Yahoo! Weather - Paris, Ile-de-France, FR","link":"http://us.rd.yahoo.com/dailynews/rss/weather/Country__Country/*https://weather.yahoo.com/country/state/city-615702/","description":"Yahoo! Weather for Paris, Ile-de-France, FR","language":"en-us","lastBuildDate":"Sun, 21 Oct 2018 06:34 PM CEST","ttl":"60","location":{"city":"Paris","country":"France","region":" Ile-de-France"},"wind":{"chill":"66","direction":"15","speed":"12.87"},"atmosphere":{"humidity":"37","pressure":"34473.45","rising":"0","visibility":"25.91"},"astronomy":{"sunrise":"8:21 am","sunset":"6:48 pm"},"image":{"title":"Yahoo! Weather","width":"142","height":"18","link":"http://weather.yahoo.com","url":"http://l.yimg.com/a/i/brand/purplelogo//uh/us/news-wea.gif"},"item":{"title":"Conditions for Paris, Ile-de-France, FR at 06:00 PM CEST","lat":"48.85693","long":"2.3412","link":"http://us.rd.yahoo.com/dailynews/rss/weather/Country__Country/*https://weather.yahoo.com/country/state/city-615702/","pubDate":"Sun, 21 Oct 2018 06:00 PM CEST","condition":{"code":"32","date":"Sun, 21 Oct 2018 06:00 PM CEST","temp":"18","text":"Sunny"},"forecast":[{"code":"34","date":"21 Oct 2018","day":"Sun","high":"18","low":"6","text":"Mostly Sunny"},{"code":"30","date":"22 Oct 2018","day":"Mon","high":"16","low":"10","text":"Partly Cloudy"},{"code":"30","date":"23 Oct 2018","day":"Tue","high":"16","low":"6","text":"Partly Cloudy"},{"code":"30","date":"24 Oct 2018","day":"Wed","high":"17","low":"10","text":"Partly Cloudy"},{"code":"28","date":"25 Oct 2018","day":"Thu","high":"16","low":"10","text":"Mostly Cloudy"},{"code":"28","date":"26 Oct 2018","day":"Fri","high":"16","low":"8","text":"Mostly Cloudy"},{"code":"39","date":"27 Oct 2018","day":"Sat","high":"12","low":"7","text":"Scattered Showers"},{"code":"39","date":"28 Oct 2018","day":"Sun","high":"11","low":"6","text":"Scattered Showers"},{"code":"12","date":"29 Oct 2018","day":"Mon","high":"12","low":"4","text":"Rain"},{"code":"28","date":"30 Oct 2018","day":"Tue","high":"12","low":"5","text":"Mostly Cloudy"}],"description":"<![CDATA[<img src=\"http://l.yimg.com/a/i/us/we/52/32.gif\"/>\n<BR />\n<b>Current Conditions:</b>\n<BR />Sunny\n<BR />\n<BR />\n<b>Forecast:</b>\n<BR /> Sun - Mostly Sunny. High: 18Low: 6\n<BR /> Mon - Partly Cloudy. High: 16Low: 10\n<BR /> Tue - Partly Cloudy. High: 16Low: 6\n<BR /> Wed - Partly Cloudy. High: 17Low: 10\n<BR /> Thu - Mostly Cloudy. High: 16Low: 10\n<BR />\n<BR />\n<a href=\"http://us.rd.yahoo.com/dailynews/rss/weather/Country__Country/*https://weather.yahoo.com/country/state/city-615702/\">Full Forecast at Yahoo! Weather</a>\n<BR />\n<BR />\n<BR />\n]]>","guid":{"isPermaLink":"false"}}}}}}';

	public function getApixuCurrent() {
		return $this->apixuCurrent;
	}

	public function getApixuForecast() {
		return $this->apixuForecast;
	}

	public function getOwmCurrent() {
		return $this->owmCurrent;
	}

	public function getOwmForecast() {
		return $this->owmForecast;
	}

	public function getYahooCurrent() {
		return $this->yahooCurrent;
	}

	public function getYahooForecast() {
		return $this->yahooForecast;
	}

}
?>
