<?php

// show all possible errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(-1);

// set correct time zone
ini_set('date.timezone', 'Europe/Berlin');

// set maximum execution time to forever
ini_set('max_execution_time', 0);

// load libraries
require '../libs/ttact/autoload-script.php';

// create a new Database instance
$db = new \ttact\Database('localhost', 'root', 'lacowu#7', 'ttact');

// current user
$current_user = \ttact\Models\UserModel::findByID($db, 1);

// let the fun begin
$schichten = \ttact\Models\AuftragModel::findAll($db, $current_user);
foreach ($schichten as $schicht) {
    if ($schicht->getVon()->format("Y-m-d") != $schicht->getBis()->format("Y-m-d")) {
        $auftrag_log = \ttact\Models\AuftragLogModel::findTemp($db, $current_user, $schicht->getID());
        if ($auftrag_log instanceof \ttact\Models\AuftragLogModel) {
            echo $schicht->getID() . ";" . $schicht->getVon()->format("Y-m-d H:i:s") . ";" . $schicht->getBis()->format("Y-m-d H:i:s") . PHP_EOL;
        }
    }
}
