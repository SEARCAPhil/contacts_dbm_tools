<?php
namespace Builder;
class BSBuilder{

  public static function build($val) {
    $node = new \StdClass;
    $node->contactId = $val->contact_id;
    $node->institution = $val->bsInstitute; 
    $node->country = $val->bsCountry; 
    $node->field = $val->bsField;
    $node->grad =  $val->bsGrad;
    $node->scholarship = $val->bsScholarship;
    $node->type = $val->type;
    return $node;
  }

}
