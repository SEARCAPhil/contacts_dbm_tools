<?php 
include_once('database/connection.php');
include_once('Reader/Engagement.php');
include_once('Writer/Engagement.php');
include_once('Builder/EngagementBuilder.php');

use Reader\Engagement;
use Writer\Engagement as WMasters;
use Builder\EngagementBuilder;


# OLD MS
$masters = new Engagement($DB_CON);
$masters_data = $masters->retrieve();
$total_count = count($masters_data);

# NEW MS
$masters_new = new WMasters($DB_CON_NEW);
$total_new_count = 0;

$ms_built_data = [];


foreach($masters_data as $key => $val) {
  $val->researchId = 1; // test only
  $ms_built_data[] = EngagementBuilder::build($val);
}

foreach($ms_built_data as $key => $val) {
  $isImported = $masters_new->write($val->contactId, $val->engageFrom, $val->engageTo, $val->researchId, $val->engagement, $val->type);
  if($isImported) $total_new_count++;
}

echo "-----------------------------------------\n";
echo "**IMPORTING Engagement** \n\r";
echo "TOTAL(orig/new):".$total_count."/".$total_new_count."\n";
if($total_count !== $total_new_count) {
  echo "\nFinished with ERRORS!\n";
}
echo "-----------------------------------------\n\r";

