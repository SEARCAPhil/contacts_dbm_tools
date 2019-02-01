<?php
namespace Builder;
class EngagementBuilder{

  public static function build($val) {
    $node = new \StdClass;
    $node->contactId = $val->contact_id;
    $node->type = $val->type_id;
    $node->engageFrom = $val->engageFrom;
    $node->engageTo = $val->engageTo;
    $node->engagement = $val->engagement;
    $node->researchId = $val->researchId;
    return $node;
  }

}
