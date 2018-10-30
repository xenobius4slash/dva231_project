<?php
define('CONFIG_PATH', '../config/');

require_once 'Admin.php';

$ADMIN = new Admin();
//$result = $ADMIN->removeUserById(3);
$result = $ADMIN->removeAllTownsAndWeatherDatas();
print_r($result);

?>
