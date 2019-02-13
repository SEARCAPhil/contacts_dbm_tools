<?php
namespace Builder;
class CommunicationBuilder{

  public static function build($val) {
    $node = new \StdClass;
    $node->contactId = $val->contact_id;
    $node->type = $val->type; 
    $node->value = $val->value; 
    
    return $node;
  }

}
