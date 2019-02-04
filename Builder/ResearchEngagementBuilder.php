<?php
namespace Builder;
class ResearchEngagementBuilder{

  public static function build($val) {
    $node = new \StdClass;
    $node->contactId = $val->contact_id;
    $node->title = $val->researchTitle;
    $node->isSearcaTraining = 1;
    return $node;
  }

} 
