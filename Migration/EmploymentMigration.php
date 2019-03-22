<?php 
include_once('Reader/Contacts.php');
include_once('Writer/Employment.php');
include_once('Builder/EmploymentBuilder.php');


use Reader\Contacts;
use Writer\Employment;
use Builder\EmploymentBuilder;

function migrateEmployments () {
  include('database/connection.php');
  $contacts = new Contacts($DB_CON);
  $contacts_data = $contacts->retrieve();
  $total_orig_contact_count = count($contacts_data);


  $employment_new = new Employment($DB_CON_NEW);
  $total_employment_contact_count = 0;
  $employment_built_data = [];

  # DO NOT include those who does not have any work experience
  foreach($contacts_data as $key => $val) {
    if(!is_null($val->company)) $employment_built_data[] = EmploymentBuilder::build($val);
  }

  foreach($employment_built_data as $key_emp => $val_emp) {
    $isImportedEmployment = (!empty($val_emp->companyName)) ? $employment_new->write($val_emp->contactId, $val_emp->companyName, $val_emp->companyAddress, $val_emp->position, null, null, null, $val->countryCode, $val->zip, $val->fax, $val->areaCode, $val->sector, $val->supervisor, $val->supervisorDesignation) : 0;
    if($isImportedEmployment) $total_employment_contact_count++;
  }

  echo "-----------------------------------------\n";
  echo "**IMPORTING EMPLOYMENT** \n\r";
  echo "TOTAL(orig/new):".$total_orig_contact_count."/".$total_employment_contact_count."\n";
  if($total_orig_contact_count !== $total_employment_contact_count) {
    echo "\nSOME OF Contacts doesnt have any  work related record!\n";
  }
  echo "-----------------------------------------\n\r";
}
