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
$town = 'Berlin';
#$town = 'London';

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
	$WSA->loadResultByTownApi($town);
	$WSOWM->loadResultByTownApi($town);
	$WSY->loadResultByTownApi($town);
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
	else { echo "ERROR while inserting the new values<br/>"; }
}


?>
