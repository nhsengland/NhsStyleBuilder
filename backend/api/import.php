<?php
error_reporting(0);
require_once '../lib/import.class.php';

$importHandler = new importHandler();

$dataJson = file_get_contents('php://input');
$dataObj = json_decode($dataJson);
if(empty($dataJson)) {
  $dataObj = $_REQUEST;
}

if($dataObj['post_path']) {
  $dataJson = file_get_contents(preg_replace('/\r\n|\n/i', '', $dataObj['post_path']));
  $dataObj = json_decode($dataJson);
}

echo $importHandler->parseHtml($dataObj->importedHtml);
