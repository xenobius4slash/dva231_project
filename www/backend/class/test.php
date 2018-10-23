<?php
define('CONFIG_PATH', '../config/');
require_once 'DatabaseTown.php';
require_once 'WeatherServiceApixu.php';
require_once 'WeatherServiceOpenWeatherMap.php';
require_once 'WeatherServiceYahoo.php';
require_once 'DatabaseWeatherDataCurrent.php';

$town = 'Paris';
#$town = 'Berlin';
#$town = 'London';

$townId = null;
$DBT = new DatabaseTown();
$resultTown = $DBT->getTownByName($town);
if($resultTown === null) {
	echo "town not in database\n";
	$DBT->insertNewTown($town);
	$townId = $DBT->getLastInsertedId();
	echo "new town-id: $townId\n";
} else {
	$townId = $resultTown['id'];
	echo "town (id: $townId) in database\n";
	print_r($resultTown);
}

$WSA = new WeatherServiceApixu();
$WSA->enableTest();
$WSOWM = new WeatherServiceOpenWeatherMap();
$WSOWM->enableTest();
$WSY = new WeatherServiceYahoo();
$WSY->enableTest();

$townUptodate = null;
if( $DBT->isTownUptodateByDbResult($resultTown) ) {
	echo "town is up-to-date => receive data from database\n";
	$townUptodate = true;
	$WSA->loadResultByTownIdDb($townId);
	$WSOWM->loadResultByTownIdDb($townId);
	$WSY->loadResultByTownIdDb($townId);
} else {
	echo "town is NOT up-to-date => receive data from API call\n";
	$townUptodate = false;
	if( !$WSA->loadResultByTownApi($town) ) {
		if( $WSA->getCurlError() ) {
			echo "cURL Error: ".$WSA->getCurlErrorCode()." => ".$WSA->getCurlErrorMessage();
		}
	}
	if( !$WSOWM->loadResultByTownApi($town) ) {
		if( $WSOWM->getCurlError() ) {
			echo "cURL Error: ".$WSOWM->getCurlErrorCode()." => ".$WSOWM->getCurlErrorMessage();
		}
	}
	if( !$WSY->loadResultByTownApi($town) ) {
		if( $WSA->getCurlError() ) {
			echo "cURL Error: ".$WSY->getCurlErrorCode()." => ".$WSY->getCurlErrorMessage();
		}
	}
}

echo "\nApixu\n"; 
echo "getLastUpdate: ".$WSA->getLastUpdate()."\n";
echo "getTemperature: ".$WSA->getTemperature()."\n";
echo "getSky: ".$WSA->getSky()."\n";
echo "getWindSpeed: ".$WSA->getWindSpeed()." mph\n";
echo "getWindDegree: ".$WSA->getWindDegree()."°\n";
echo "getPressureHpa: ".$WSA->getPressureHpa()." hpa\n";
echo "getHumidity: ".$WSA->getHumidity()."%\n";
echo "\n";
#print_r($WSA->getArrayForDatabase());

echo "OpenWeatherMap\n"; 
echo "getLastUpdate: ".$WSOWM->getLastUpdate()."\n";
echo "getTemperature: ".$WSOWM->getTemperature()."\n";
echo "getSky: ".$WSOWM->getSky()."\n";
echo "getWindSpeed: ".$WSOWM->getWindSpeed()." mph\n";
echo "getWindDegree: ".$WSOWM->getWindDegree()."°\n";
echo "getPressureHpa: ".$WSOWM->getPressureHpa()." hpa\n";
echo "getHumidity: ".$WSOWM->getHumidity()."%\n";
echo "\n";
#print_r($WSOWM->getArrayForDatabase());

echo "Yahoo\n"; 
echo "getLastUpdate: ".$WSY->getLastUpdate()."\n";
echo "getTemperature: ".$WSY->getTemperature()."\n";
echo "getSky: ".$WSY->getSky()."\n";
echo "getWindSpeed: ".$WSY->getWindSpeed()." mph\n";
echo "getWindDegree: ".$WSY->getWindDegree()."°\n";
echo "getPressureHpa: ".$WSY->getPressureHpa()." hpa\n";
echo "getHumidity: ".$WSY->getHumidity()."%\n";
echo "\n";
#print_r($WSY->getArrayForDatabase());


if($townUptodate === false) {
	$DBWDC = new DatabaseWeatherDataCurrent();
	$return = $DBWDC->insertNewLatestDataForWeatherServices($townId, array($WSA->getArrayForDatabase(), $WSOWM->getArrayForDatabase(), $WSY->getArrayForDatabase()));
	if( $return ) {
		echo "all fine => set town to up-to-date\n";
		$DBT->setTownToUptodateById($townId);
	} else {
		echo "ERROR\n";
	}
}

?>
