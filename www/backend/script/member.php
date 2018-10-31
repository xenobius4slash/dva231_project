<?php
require_once CLASS_PATH.'DatabaseTown.php';
require_once CLASS_PATH.'DatabaseUserTown.php';

$data = array();
$DBUT = new DatabaseUserTown();
$townIds = $DBUT->getTownIdsByUserId($userId);
if( $townIds !== null && count($townIds) > 0 ) {
	$stringTownIds = implode(',', $townIds);

	$DBT = new DatabaseTown();
	$DBT->setOrder('last_update DESC');
	$towns = $DBT->getTownByIds($stringTownIds);

	// create array for HTML
	for($i=0; $i<count($towns); $i++) {
		$data[] = $towns[$i]['name'];
	}
}

?>
