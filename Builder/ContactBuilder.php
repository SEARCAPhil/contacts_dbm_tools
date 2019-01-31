<?php
namespace Builder;
class ContactBuilder{

  public static function build($val) {
    $node = new \StdClass;
    $node->contactId = $val->contact_id;
    $node->affiliateCode= $val->affiliateCode; 
    $node->prefix = $val->prefix; 
    $node->lastname = $val->lastname; 
    $node->firstname= $val->firstname; 
    $node->middleinit = $val->middleinit; 
    $node->gender= strtolower($val->gender); 
    $node->birthdate = $val->birthdate; 
    $node->nationality = $val->nationality; 
    $node->specialization = $val->specialization; 
    $node->homeAddress = $val->homeAddress; 
    $node->homeCountry = $val->homeCountry; 
    $node->homeZipCode = $val->homeZipCode; 
    $node->homeCountryCode = $val->homeCountryCode; 
    $node->homeAreaCode = $val->homeAreaCode; 
    $node->civilStat = strtolower($val->civilStat); 
    $node->others = $val->others; 
    $node->rank = $val->rank;
    return $node;
  }

}
