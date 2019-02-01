<?php 
include_once('database/connection.php');
include_once('Reader/Fellow.php');
include_once('Writer/Fellow.php');
include_once('Builder/FellowBuilder.php');

use Reader\Fellow;
use Writer\Fellow as WMasters;
use Builder\FellowBuilder;


# OLD MS
$masters = new Fellow($DB_CON);
$masters_data = $masters->retrieve();
$total_count = count($masters_data);

# NEW MS
$masters_new = new WMasters($DB_CON_NEW);
$total_new_count = 0;

$ms_built_data = [];


foreach($masters_data as $key => $val) {
  $ms_built_data[] = FellowBuilder::build($val);
}

foreach($ms_built_data as $key => $val) {
  $isImported = $masters_new->write($val->contactId, $val->saaftype_id, $val->dateFrom, $val->dateTo);
  if($isImported) $total_new_count++;
}

echo "-----------------------------------------\n";
echo "**IMPORTING FELLOWS** \n\r";
echo "TOTAL(orig/new):".$total_count."/".$total_new_count."\n";
if($total_count !== $total_new_count) {
  echo "\nFinished with ERRORS!\n";
}
echo "-----------------------------------------\n\r";

