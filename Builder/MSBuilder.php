<?php
namespace Builder;
class MSBuilder{

  public static function build($val) {
    $node = new \StdClass;
    $node->contactId = $val->contact_id;
    $node->institution = $val->msInstitute; 
    $node->country = $val->msCountry; 
    $node->field = $val->msField;
    $node->grad =  $val->msGrad;
    $node->scholarship = $val->msScholarship;
    $node->type = $val->type;
    return $node;
  }

}
