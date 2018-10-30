<?php
require_once 'Database.php';

class DatabaseWeatherDataCurrent extends Database {

	/** get one tuple by id
	*	@param		$id			Integer
	*	@return		NULL || Array
	*/
	public function getById($id) {
		$query = sprintf("SELECT ".$this->getColumns()." FROM weather_data_current WHERE id = %u", $this->escapeString($id) );
		$result = $this->getDb()->query($query);
		if($result->num_rows > 0) {
			return $this->getOneRowArrayFromSqlResult($result);
		} else {
			return null;
		}
	}

	/** get tuples by town-id
	*	@param		$townId			Integer
	*	@return		NULL || Array
	*/
	public function getByTownId($townId) {
		$query = sprintf("SELECT ".$this->getColumns()." FROM weather_data_current WHERE town_id = %u", $this->escapeString($townId) );
		$result = $this->getDb()->query($query);
		if($result->num_rows > 0) {
			return $this->getArrayFromSqlResult($result);
		} else {
			return null;
		}
	}

	/** get the latest tuples by town-id
	*	@param		$townId			Integer
	*	@return		NULL || Array
	*/
	public function getLatestByTownId($townId) {
		$query = sprintf("SELECT ".$this->getColumns()." FROM weather_data_current WHERE town_id = %u AND latest = 1", $this->escapeString($townId) );
		$result = $this->getDb()->query($query);
		if($result->num_rows > 0) {
			return $this->getArrayFromSqlResult($result);
		} else {
			return null;
		}
	}

	/** get the latest data by a town-id and the weather-service-id
	*	@param		$townId				Integer
	*	@param		$weatherServiceId	Integer
	*	@return		
	*/
	public function getLatestByTownIdAndWeatherServiceId($townId, $weatherServiceId) {
		$query = sprintf("SELECT ".$this->getColumns()." FROM weather_data_current WHERE town_id = %u AND weather_service_id = %u AND latest = 1", $this->escapeString($townId), $this->escapeString($weatherServiceId) );
		$result = $this->getDb()->query($query);
		if($result->num_rows > 0) {
			return $this->getOneRowArrayFromSqlResult($result);
		} else {
			return null;
		}
	}

	/** insert new data into the table and set this data to 'latest' and unset the old 'latest'
	*	@param		$townId			Integer
	*	@param		$array			Array	array('<weather_Service_id>' => array(<weather data>), '<weather_Service_id>' => array(...), .. )
	*	@return		Bool
	*/
	public function insertNewLatestDataForWeatherServices($townId, $array) {
		if( is_array($array) ) {
			$this->startTransaction();

			// set current latest weather data to not latest
			$query1 = sprintf("UPDATE weather_data_current SET latest = 0 WHERE latest = 1 AND town_id = %u", $this->escapeString($townId));
			$result1 = $this->getDb()->query($query1);

			// insert new weather data
			$valuesString = '';
			for($i=0; $i<count($array); $i++) {
				$weatherServiceId = $array[$i]['weather_service_id'];
				$BuildDate = $array[$i]['data']['build_date'];
				$tempC = $array[$i]['data']['temp_c'];
				$tempF = $array[$i]['data']['temp_f'];
				$skyCondition = $array[$i]['data']['sky_condition'];
				$windSpeedMph = $array[$i]['data']['wind_speed_mph'];
				$windDegree = $array[$i]['data']['wind_degree'];
				$pressureHpa = $array[$i]['data']['pressure_hpa'];
				$humidity = $array[$i]['data']['humidity'];
				$valuesString .= sprintf("(%u, %u, 1, '%s', %f, %f, '%s', %f, %f, %f, %u),", $this->escapeString($townId), $this->escapeString($weatherServiceId), $this->escapeString($BuildDate), $this->escapeString($tempC), 
									$this->escapeString($tempF), $this->escapeString($skyCondition), $this->escapeString($windSpeedMph), $this->escapeString($windDegree), $this->escapeString($pressureHpa), $this->escapeString($humidity) );
			}
			$valuesString = rtrim($valuesString, ',');	// remove the last comma
			$query2 = 'INSERT INTO weather_data_current (town_id, weather_service_id, latest, build_date, temp_c, temp_f, sky_condition, wind_speed_mph, wind_degree, pressure_hpa, humidity) VALUES'.$valuesString;
			$result2 = $this->getDb()->query($query2);

/*
			if( $result1 ) error_log("DatabaseWeatherDataCurrent::insertNewLatestDataForWeatherServices($townId,...) => result1: TRUE"); 
			else error_log("DatabaseWeatherDataCurrent::insertNewLatestDataForWeatherServices($townId,...) => result1: FALSE");
			if( $result2 ) error_log("DatabaseWeatherDataCurrent::insertNewLatestDataForWeatherServices($townId,...) => result2: TRUE"); 
			else error_log("DatabaseWeatherDataCurrent::insertNewLatestDataForWeatherServices($townId,...) => result2: FALSE");
*/

			if( $result1 && $result2 ) {
				$this->commitTransaction();
				return true;
			} else {
				$this->rollbackTransaction();
				return false;
			}
		} else {
			return false;
		}
	}

	/** delete all weather datas
	*	@return		Bool
	*/
	public function deleteAllWeatherDatas() {
		$query = sprintf("DELETE FROM weather_data_current");
		$result = $this->getDb()->query($query);
		if($result === true) {
			return true;
		} else {
			return false;
		}
	}
}
?>
