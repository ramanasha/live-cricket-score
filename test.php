<?php
require 'vendor/autoload.php';

$cricket = new \LiveScore\Cricket();
$todaysMatch = $cricket->getTodaysMatches();
var_dump($todaysMatch[0]['id']);
var_dump($todaysMatch[0]['type']);
//var_dump($cricket->getMatchScore('1024749', 'domestic'));