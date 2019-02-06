<?php 
include_once('Reader/SaafClass.php');
include_once('Writer/SaafClass.php');


use Reader\SaafClass;
use Writer\SaafClass as WMasters;


function migrateSaafClass() {
  include('database/connection.php');
  
  $masters = new SaafClass($DB_CON);
  $masters_data = $masters->retrieve();
  $total_count = count($masters_data);

  
  $masters_new = new WMasters($DB_CON_NEW);
  $total_new_count = 0;
  $ms_built_data = [];


  foreach($masters_data as $key => $val) {
    $isImported = $masters_new->write($val->saafclass);
    if($isImported) $total_new_count++;
  }
  

  echo "-----------------------------------------\n";
  echo "**IMPORTING SAAF CLASSES** \n\r";
  echo "TOTAL(orig/new):".$total_count."/".$total_new_count."\n";
  if($total_count !== $total_new_count) {
    echo "\nFinished with ERRORS!\n";
  }
  echo "-----------------------------------------\n\r";
}