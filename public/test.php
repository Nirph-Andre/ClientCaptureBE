<?php
header('Expires: Fri, 26 Nov 1976 05:00:00 GMT');
header('Cache-Control: no-cache, must-revalidate');
header("Pragma: no-cache");
header('Content-type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
echo json_encode(array('html' => 'This is my test document!'));
?>