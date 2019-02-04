<?php 
include_once('Reader/Assoc.php');
include_once('Writer/Research.php');
include_once('Builder/ResearchAssocBuilder.php');

use Reader\Assoc;
use Writer\Research as WMasters;
use Builder\ResearchAssocBuilder;

function migrateAssocResearch () {
  include('database/connection.php');
  # OLD MS
  $masters = new Assoc($DB_CON);
  $masters_data = $masters->retrieve();
  $total_count = count($masters_data);

  # NEW MS
  $masters_new = new WMasters($DB_CON_NEW);
  $total_new_count = 0;

  $ms_built_data = [];


  foreach($masters_data as $key => $val) {
    $ms_built_data[] = ResearchAssocBuilder::build($val);
  }



  foreach($ms_built_data as $key => $val) {
    $isImported = $masters_new->write($val->contactId, $val->saaftype_id, $val->dateStarted, $val->dateEnded, $val->title, null, null, null, null, $val->isSearcaTraining);
    if($isImported) $total_new_count++;
  }

  echo "-----------------------------------------\n";
  echo "**IMPORTING RESEARCH ASSOC** \n\r";
  echo "TOTAL(orig/new):".$total_count."/".$total_new_count."\n";
  if($total_count !== $total_new_count) {
    echo "\nFinished with ERRORS!\n";
  }
  echo "-----------------------------------------\n\r";
}
