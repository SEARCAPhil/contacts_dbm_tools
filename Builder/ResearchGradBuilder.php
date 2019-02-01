<?php
namespace Builder;
class ResearchGradBuilder{

  public static function build($val) {
    $node = new \StdClass;
    $node->contactId = $val->contact_id;
    $node->saaftype_id = $val->saaftype_id;
    $node->dateStarted = $val->yearStart;
    $node->dateEnded = $val->yearComplete;
    $node->title = $val->thesisTitle;
    $node->fieldStudy = $val->fieldStudy;
    $node->funding = $val->funding;
    $node->remarks = $val->remarks;
    $node->hostUniversity = $val->hostUniv;
    $node->isSearcaTraining = 1;
    return $node;
  }

} 
