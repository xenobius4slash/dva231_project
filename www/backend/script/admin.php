<?php
require_once CLASS_PATH.'Admin.php';

$ADMIN = new Admin();
$users = $ADMIN->getAllNotAdminUser();

?>
