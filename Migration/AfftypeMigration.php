<?php 
include_once('Reader/Afftype.php');
include_once('Writer/Afftype.php');


use Reader\Afftype;
use Writer\Afftype as WMasters;


function migrateAfftype () {
  include('database/connection.php');
  
  $masters = new Afftype($DB_CON);
  $masters_data = $masters->retrieve();
  $total_count = count($masters_data);

  
  $masters_new = new WMasters($DB_CON_NEW);
  $total_new_count = 0;
  $ms_built_data = [];


  foreach($masters_data as $key => $val) {
    $isImported = $masters_new->write($val->type_id, $val->afftypeName);
    if($isImported) $total_new_count++;
  }
  

  echo "-----------------------------------------\n";
  echo "**IMPORTING Afftypes** \n\r";
  echo "TOTAL(orig/new):".$total_count."/".$total_new_count."\n";
  if($total_count !== $total_new_count) {
    echo "\nFinished with ERRORS!\n";
  }
  echo "-----------------------------------------\n\r";
}