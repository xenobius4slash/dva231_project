<?php
require_once CLASS_PATH.'DatabaseTown.php';
require_once CLASS_PATH.'DatabaseUserTown.php';

$DBUT = new DatabaseUserTown();
$townIds = $DBUT->getTownIdsByUserId($userId);
$stringTownIds = implode(',', $townIds);

$DBT = new DatabaseTown();
$DBT->setOrder('last_update DESC');
$towns = $DBT->getTownByIds($stringTownIds);

// create array for HTML
$data = array();
for($i=0; $i<count($towns); $i++) {
	$data['towns'][] = $towns[$i]['name'];
}

?>
