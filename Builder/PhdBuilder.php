<?php
namespace Builder;
class PhdBuilder{

  public static function build($val) {
    $node = new \StdClass;
    $node->contactId = $val->contact_id;
    $node->institution = $val->phdInstitute; 
    $node->country = $val->phdCountry; 
    $node->field = $val->phdField;
    $node->grad =  $val->phdGrad;
    $node->scholarship = $val->phdScholarship;
    $node->type = $val->type;
    return $node;
  }

}
