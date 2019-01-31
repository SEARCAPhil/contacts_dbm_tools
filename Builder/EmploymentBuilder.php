<?php
namespace Builder;
class EmploymentBuilder{

  public static function build($val) {
    $node = new \StdClass;
    $node->contactId = $val->contact_id;
    $node->companyName = $val->company; 
    $node->companyAddress = $val->officeAddress; 
    $node->position = $val->position;
    $node->countryCode =  $val->officeCountry;
    $node->zip = $val->officeZipCode;
    $node->fax = $val->officeFax;
    $node->areaCode = $val->officeAreaCode;
    $node->sector = $val->sector; 
    
    return $node;
  }

}
