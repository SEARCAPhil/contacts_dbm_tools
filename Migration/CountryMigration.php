<?php 
include_once('Reader/Country.php');
include_once('Writer/Country.php');


use Reader\Country;
use Writer\Country as WMasters;


function migrateCountry () {
  include('database/connection.php');
  
  $masters = new Country($DB_CON);
  $masters_data = $masters->retrieve();
  $total_count = count($masters_data);

  
  $masters_new = new WMasters($DB_CON_NEW);
  $total_new_count = 0;
  $ms_built_data = [];


  foreach($masters_data as $key => $val) {
    $isImported = $masters_new->write($val->countryCode, $val->countryName, $val->lastrank);
    if($isImported) $total_new_count++;
  }
  

  echo "-----------------------------------------\n";
  echo "**IMPORTING COUNTRIES** \n\r";
  echo "TOTAL(orig/new):".$total_count."/".$total_new_count."\n";
  if($total_count !== $total_new_count) {
    echo "\nFinished with ERRORS!\n";
  }
  echo "-----------------------------------------\n\r";
}