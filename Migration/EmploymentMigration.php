<?php 
include_once('database/connection.php');
include_once('Reader/Contacts.php');
include_once('Writer/Employment.php');
include_once('Builder/EmploymentBuilder.php');


use Reader\Contacts;
use Writer\Employment;
use Builder\EmploymentBuilder;


$contacts = new Contacts($DB_CON);
$contacts_data = $contacts->retrieve();
$total_orig_contact_count = count($contacts_data);


$employment_new = new Employment($DB_CON_NEW);
$total_employment_contact_count = 0;
$employment_built_data = [];


foreach($contacts_data as $key => $val) {
  $employment_built_data[] = EmploymentBuilder::build($val);
}

foreach($employment_built_data as $key_emp => $val_emp) {
  $isImportedEmployment = $employment_new->write($val_emp->contactId, $val_emp->companyName, $val_emp->companyAddress, $val_emp->position, null, null, null, $val->countryCode, $val->zip, $val->fax, $val->areaCode, $val->sector);
  if($isImportedEmployment) $total_employment_contact_count++;
}

echo "-----------------------------------------\n";
echo "**IMPORTING EMPLOYMENT** \n\r";
echo "TOTAL(orig/new):".$total_orig_contact_count."/".$total_employment_contact_count."\n";
if($total_orig_contact_count !== $total_employment_contact_count) {
  echo "\nFinished with ERRORS!\n";
}
echo "-----------------------------------------\n\r";

