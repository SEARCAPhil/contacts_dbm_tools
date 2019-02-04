<?php 
include_once('Reader/Masters.php');
include_once('Writer/Masters.php');
include_once('Builder/MSBuilder.php');

use Reader\Masters;
use Writer\Masters as WMasters;
use Builder\MSBuilder;


function migrateMS () {
  include('database/connection.php');
  # OLD MS
  $masters = new Masters($DB_CON);
  $masters_data = $masters->retrieve();
  $total_count = count($masters_data);

  # NEW MS
  $masters_new = new WMasters($DB_CON_NEW);
  $total_new_count = 0;

  $ms_built_data = [];


  foreach($masters_data as $key => $val) {
    $val->type = 'ms';
    $ms_built_data[] = MSBuilder::build($val);
  }

  foreach($ms_built_data as $key => $val) {
    $isImported = $masters_new->write($val->contactId, $val->institution, $val->country, $val->field, $val->grad, $val->scholarship, $val->type);
    if($isImported) $total_new_count++;
  }

  echo "-----------------------------------------\n";
  echo "**IMPORTING Masters** \n\r";
  echo "TOTAL(orig/new):".$total_count."/".$total_new_count."\n";
  if($total_count !== $total_new_count) {
    echo "\nFinished with ERRORS!\n";
  }
  echo "-----------------------------------------\n\r";
}