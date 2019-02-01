<?php 
include_once('database/connection.php');
include_once('Reader/Training.php');
include_once('Writer/Training.php');
include_once('Builder/TrainingBuilder.php');

use Reader\Training;
use Writer\Training as WMasters;
use Builder\TrainingBuilder;


# OLD MS
$masters = new Training($DB_CON);
$masters_data = $masters->retrieve();
$total_count = count($masters_data);

# NEW MS
$masters_new = new WMasters($DB_CON_NEW);
$total_new_count = 0;

$ms_built_data = [];


foreach($masters_data as $key => $val) {
  $ms_built_data[] = TrainingBuilder::build($val);
}


foreach($ms_built_data as $key => $val) {
  $isImported = $masters_new->write($val->contactId, $val->title, $val->saaftype_id, $val->notes, $val->dateStarted, $val->dateEnded, $val->scholarship, $val->venue, $val->sponsor, $val->supervisor, $val->supervisorDesignation, $val->trainingType, $val->organizingAgency, $val->hostUniversity, $val->isSearcaTraining);
  if($isImported) $total_new_count++;
}

echo "-----------------------------------------\n";
echo "**IMPORTING TRAININGS** \n\r";
echo "TOTAL(orig/new):".$total_count."/".$total_new_count."\n";
if($total_count !== $total_new_count) {
  echo "\nFinished with ERRORS!\n";
}
echo "-----------------------------------------\n\r";

