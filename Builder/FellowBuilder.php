<?php
namespace Builder;
class FellowBuilder{

  public static function build($val) {
    $node = new \StdClass;
    $node->contactId = $val->contact_id;
    $node->saaftype_id = $val->saaftype_id;
    $node->dateFrom = $val->dateFrom;
    $node->dateTo = $val->dateTo;
    return $node;
  }

} 
