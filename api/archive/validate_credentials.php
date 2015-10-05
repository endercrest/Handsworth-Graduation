<?php
//REMINDER to hash the password

require_once(dirname(dirname(__FILE__)) . "/library/config.php");

$response = array();
$response['valid'] = true;
echo json_encode($response);