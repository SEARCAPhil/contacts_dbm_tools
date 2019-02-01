<?php
namespace Builder;
class TrainingBuilder{

  public static function build($val) {
    $node = new \StdClass;
    $node->contactId = $val->contact_id;
    $node->saaftype_id = $val->saaftype_id;
    $node->title = $val->trainingTitle;
    $node->notes = null;
    $node->dateStarted = $val->yearStart;
    $node->dateEnded = $val->yearComplete;
    $node->scholarship = null;
    $node->venue = $val->venueTrain;
    $node->sponsor = null;
    $node->supervisor = null;
    $node->supervisorDesignation = null;
    $node->trainingType = null;
    $node->organizingAgency =  null;
    $node->hostUniversity = $val->hostUniv;
    $node->isSearcaTraining = 1;
    return $node;
  }

} 
