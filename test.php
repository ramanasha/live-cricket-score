<?php
require 'vendor/autoload.php';

$cricket = new \LiveScore\Cricket();
$matches = $cricket->getTodaysMatches();
var_dump($matches);
