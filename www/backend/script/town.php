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


if( !isset($_POST['town_search']) || !isset($_POST['town']) || (strlen($_POST['town']) == 0) ) {
	header('Location: '.INDEX_PATH.'index.php');
} else {
	$town = htmlspecialchars($_POST['town']);
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
		echo '<div class="alert alert-info">received values are from the database</div>';
		$townUptodate = true;
		$WSA->loadResultByTownIdDb($townId);
		$WSOWM->loadResultByTownIdDb($townId);
		$WSY->loadResultByTownIdDb($townId);
	} else {
		echo '<div class="alert alert-info">received values are from the API-Call</div>';
		$townUptodate = false;
		if( !$WSA->loadResultByTownApi($town) ) {
			if( $WSA->getCurlError() ) {
				echo '<div class="alert alert-danger">cURL Error (apixu): '.$WSA->getCurlErrorCode().' => '.$WSA->getCurlErrorMessage().'</div>';
			}
		}
		if( !$WSOWM->loadResultByTownApi($town) ) {
			if( $WSOWM->getCurlError() ) {
				echo '<div class="alert alert-danger">cURL Error (open weather map): '.$WSOWM->getCurlErrorCode().' => '.$WSOWM->getCurlErrorMessage().'</div>';
			}
		}
		if( !$WSY->loadResultByTownApi($town) ) {
			if( $WSA->getCurlError() ) {
				echo '<div class="alert alert-danger">cURL Error (yahoo): '.$WSY->getCurlErrorCode().' => '.$WSY->getCurlErrorMessage().'</div>';
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
		else { echo '<div class="alert alert-danger">ERROR while inserting the new values in the database</div>'; }
	}
}
?>
