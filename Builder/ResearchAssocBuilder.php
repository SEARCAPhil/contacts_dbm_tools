<?php
namespace Builder;
class ResearchAssocBuilder{

  public static function build($val) {
    $node = new \StdClass;
    $node->contactId = $val->contact_id;
    $node->saaftype_id = $val->saaftype_id;
    $node->dateStarted = $val->yearStart;
    $node->dateEnded = $val->yearComplete;
    $node->title = $val->researchTitle;
    $node->isSearcaTraining = 1;
    return $node;
  }

} 
