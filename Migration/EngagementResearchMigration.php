<?php 
include_once('Reader/Engagement.php');
include_once('Writer/Research.php');
include_once('Builder/ResearchEngagementBuilder.php');
include_once('Writer/Engagement.php');
include_once('Builder/EngagementBuilder.php');

use Reader\Engagement;
use Writer\Engagement as WEMasters;
use Builder\EngagementBuilder;

use Writer\Research as WMasters;
use Builder\ResearchEngagementBuilder;

function migrateEngagementResearch () {
  include('database/connection.php');
  
  # researchTitle IS NOT EMPTY
  $masters = new Engagement($DB_CON);
  $masters_data_r = $masters->retrieve_research();
  $total_count = count($masters_data_r);

  # researchTitle IS NULL
  $masters_data_null = $masters->retrieve_null();
  $total_count_null = count($masters_data_null);
  $ms_built_data_null = [];
  $total_new_count_null = 0;


  # NEW MS
  $masters_new = new WMasters($DB_CON_NEW);
  $total_new_count = 0;
  $ms_built_data = [];

  # Joint
  $mer = new WEMasters($DB_CON_NEW);
  $total_mer_count = 0;

  # all engagement W/O researchTitle
  foreach($masters_data_null as $key => $val) {
    $ms_built_data_null[] = ResearchEngagementBuilder::build($val);
  }

  foreach($ms_built_data_null as $key => $val) {  
    $isNullImported = $mer->write($val->contactId, $val->engageFrom, $val->engageTo, null, $val->engagement, $val->type);
    if($isNullImported) $total_new_count_null++;
  }

    

  # all engagement with researchTitle
  foreach($masters_data_r as $key => $val) {
    $ms_built_data[] = ResearchEngagementBuilder::build($val);
  }


  # INSERT ALL ENGAGEMENTS WITH THEIR CORRESPONDING RESEARCH
  foreach($ms_built_data as $key => $val) {
    $isImported = $masters_new->write($val->contactId, null, null, null, $val->title, null, null, null, null, $val->isSearcaTraining);
    if($isImported) {
      $total_new_count++;
      # write to engagment
      $val->researchId = $isImported;
      //$eng = EngagementBuilder::build($val);
      $isEngImported = $mer->write($val->contactId, $val->engageFrom, $val->engageTo, $val->researchId, $val->engagement, $val->type);
      if($isEngImported) $total_mer_count++;
    }
  }



  echo "-----------------------------------------\n";
  echo "**IMPORTING RESEARCH Engagement** \n\r";
  echo "TOTAL(orig/new):".$total_count."/".$total_new_count."\n";
  echo "TOTAL Engagement:".$total_mer_count."\n";
  echo "TOTAL Engagement(NO RESEARCH):".$total_count_null."/".$total_new_count_null."\n";
  if($total_count !== $total_new_count) {
    echo "\nFinished with ERRORS!\n";
  }
  echo "-----------------------------------------\n\r";
}