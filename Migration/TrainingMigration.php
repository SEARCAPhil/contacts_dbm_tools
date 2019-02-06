<?php 
include_once('Reader/Training.php');
include_once('Writer/Training.php');
include_once('Builder/TrainingBuilder.php');

use Reader\Training;
use Writer\Training as WMasters;
use Builder\TrainingBuilder;

function migrateTrainings () {
  include('database/connection.php');
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
    # IMPORTANT
    # Original value of saaftype came from saaftype table. However, the new system merged saaflass and saaftype respectively.
    # In result, saaftype_id will not start with a type_id = 1, it will rather be the next of the last value of uploaded saafclass.
    # For an instance, if 4 records from saafclass table were uploaded, the ssaftype_id will become 5 instead of 1 
    # Saaaf type must be added by 4 because saafclass data count is known and will not change.
    $val->saaftype_id = $val->saaftype_id + 4;
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
}
