<?php
/*
*	classes
*/
require_once CLASS_PATH.'DatabaseTown.php';
require_once CLASS_PATH.'WeatherServiceApixu.php';
require_once CLASS_PATH.'WeatherServiceOpenWeatherMap.php';
require_once CLASS_PATH.'WeatherServiceYahoo.php';
require_once CLASS_PATH.'DatabaseWeatherDataCurrent.php';

// TODO: parameter of the search or of a link
#$town = 'Paris';
#$town = 'Berlin';
#$town = 'London';
$town = htmlspecialchars($_GET['town']);
$isTown = null;
if( !isset($_GET['town']) || strlen($town) == 0 ) {
	$isTown = false;
} else {
	$isTown = true;
	/*
	*	exist town in the database?
	*/
	$townId = null;
	$DBT = new DatabaseTown();
	$resultTown = $DBT->getTownByName($town);
	if($resultTown === null) {	// town not in the database => insert
		$DBT->insertNewTown($town);
		$townId = $DBT->getLastInsertedId();
	} else {	// town in the database
		$townId = $resultTown['id'];
	}

	/*
	*	init classes of weather services
	*/
	$WSA = new WeatherServiceApixu();
	$WSOWM = new WeatherServiceOpenWeatherMap();
	$WSY = new WeatherServiceYahoo();

	/*
	*	is town up-to-date?
	*	TRUE => receive data from database
	*	FALSE => receive data from api call (weather service)
	*/
	$townUptodate = null;
	if( $DBT->isTownUptodateByDbResult($resultTown) ) {
		echo "town is up-to-date => receive data from database<br/>";
		$townUptodate = true;
		$WSA->loadResultByTownIdDb($townId);
		$WSOWM->loadResultByTownIdDb($townId);
		$WSY->loadResultByTownIdDb($townId);
	} else {
		echo "town is NOT up-to-date => receive data from API call<br/>";
		$townUptodate = false;
		if( !$WSA->loadResultByTownApi($town) ) {
			if( $WSA->getCurlError() ) {
				echo "<p>cURL Error (apixu): ".$WSA->getCurlErrorCode()." => ".$WSA->getCurlErrorMessage()."</p>";
			}
		}
		if( !$WSOWM->loadResultByTownApi($town) ) {
			if( $WSOWM->getCurlError() ) {
				echo "<p>cURL Error (open weather map): ".$WSOWM->getCurlErrorCode()." => ".$WSOWM->getCurlErrorMessage()."</p>";
			}
		}
		if( !$WSY->loadResultByTownApi($town) ) {
			if( $WSA->getCurlError() ) {
				echo "<p>cURL Error (yahoo): ".$WSY->getCurlErrorCode()." => ".$WSY->getCurlErrorMessage()."</p>";
			}
		}
	}

	/*
	*	if town NOT up-to-date then insert new data into the database
	*/
	if($townUptodate === false) {
		$DBWDC = new DatabaseWeatherDataCurrent();
		$return = $DBWDC->insertNewLatestDataForWeatherServices($townId, array($WSA->getArrayForDatabase(), $WSOWM->getArrayForDatabase(), $WSY->getArrayForDatabase()));
		
		if( $return ) {
			// set town to up-to-date
			$DBT->setTownToUptodateById($townId);
		} 
		else { echo "<p>ERROR while inserting the new values</p>"; }
	}

}
?>
