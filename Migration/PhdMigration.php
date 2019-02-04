<?php 
include_once('Reader/Phd.php');
include_once('Writer/Masters.php');
include_once('Builder/PhdBuilder.php');

use Reader\Phd;
use Writer\Masters as WMasters;
use Builder\PhdBuilder;


function migratePhd () {
  include('database/connection.php');
  # OLD MS
  $masters = new Phd($DB_CON);
  $masters_data = $masters->retrieve();
  $total_count = count($masters_data);

  # NEW MS
  $masters_new = new WMasters($DB_CON_NEW);
  $total_new_count = 0;

  $ms_built_data = [];


  foreach($masters_data as $key => $val) {
    $val->type = 'phd';
    $ms_built_data[] = PhdBuilder::build($val);
  }

  foreach($ms_built_data as $key => $val) {
    $isImported = $masters_new->write($val->contactId, $val->institution, $val->country, $val->field, $val->grad, $val->scholarship, $val->type);
    if($isImported) $total_new_count++;
  }

  echo "-----------------------------------------\n";
  echo "**IMPORTING PHD** \n\r";
  echo "TOTAL(orig/new):".$total_count."/".$total_new_count."\n";
  if($total_count !== $total_new_count) {
    echo "\nFinished with ERRORS!\n";
  }
  echo "-----------------------------------------\n\r";
}
