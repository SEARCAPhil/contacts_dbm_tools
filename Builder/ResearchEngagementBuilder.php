<?php
namespace Builder;
class ResearchEngagementBuilder{

  public static function build($val) {
    $node = new \StdClass;
    $node->contactId = $val->contact_id;
    $node->title = $val->researchTitle;
    $node->isSearcaTraining = 1;
    $node->type = $val->type_id;
    $node->engageFrom = $val->engageFrom;
    $node->engageTo = $val->engageTo;
    $node->engagement = $val->engagement;
    $node->researchId = $val->researchId;
    return $node;
  }

} 
