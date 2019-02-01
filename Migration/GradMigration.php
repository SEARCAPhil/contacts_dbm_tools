<?php 
include_once('database/connection.php');
include_once('Reader/Grad.php');
include_once('Writer/Research.php');
include_once('Builder/ResearchGradBuilder.php');

use Reader\Grad;
use Writer\Research as WMasters;
use Builder\ResearchGradBuilder;


# OLD MS
$masters = new Grad($DB_CON);
$masters_data = $masters->retrieve();
$total_count = count($masters_data);

# NEW MS
$masters_new = new WMasters($DB_CON_NEW);
$total_new_count = 0;

$ms_built_data = [];


foreach($masters_data as $key => $val) {
  $ms_built_data[] = ResearchGradBuilder::build($val);
}


foreach($ms_built_data as $key => $val) {
  $isImported = $masters_new->write($val->contactId, $val->saaftype_id, $val->dateStarted, $val->dateEnded, $val->title, $val->fieldStudy, $val->funding, $val->remarks, $val->hostUniversity, $val->isSearcaTraining);
  if($isImported) $total_new_count++;
}

echo "-----------------------------------------\n";
echo "**IMPORTING RESEARCH GRAD** \n\r";
echo "TOTAL(orig/new):".$total_count."/".$total_new_count."\n";
if($total_count !== $total_new_count) {
  echo "\nFinished with ERRORS!\n";
}
echo "-----------------------------------------\n\r";

