<?php 
include_once('database/connection.php');
include_once('Reader/Contacts.php');
include_once('Reader/Masters.php');
include_once('Writer/Employment.php');
include_once('Builder/ContactBuilder.php');
include_once('Builder/EmploymentBuilder.php');
include_once('Builder/MSBuilder.php');


use Reader\Contacts;
use Reader\Masters;
use Writer\Bachelor;
use Writer\Employment;

use Builder\ContactBuilder;
use Builder\EmploymentBuilder;
use Builder\MSBuilder;


$contacts = new Contacts($DB_CON);

# Original CONTACT data
$contacts_data = $contacts->retrieve();
$total_orig_contact_count = count($contacts_data);

# Inserted data
$contacts_new = new Contacts($DB_CON_NEW);
$total_new_contact_count = 0;

# Employment
$employment_new = new Employment($DB_CON_NEW);
$total_employment_contact_count = 0;

# MS
$masters = new Masters($DB_CON);
$masters_data = $masters->retrieve();
$total_masters_contact_count = 0;

$contacts_built_data = [];


foreach($contacts_data as $key => $val) {
  $contacts_built_data[] = ContactBuilder::build($val);
}

var_dump($contacts_built_data[0]);

/*$contactParent->employment = [EmploymentBuilder::build($val)];
  $contactParent->employment = [EmploymentBuilder::build($val)];

  $tree[] = $contactParent;

// write to DB
foreach($tree as $key => $val) {
  $isImported = $contacts_new->write($val->contactId, $val->affiliateCode, $val->prefix, $val->lastname, $val->firstname, $val->middleinit, strtolower($val->gender), $val->birthdate, $val->nationality, $val->specialization, $val->homeAddress, $val->homeCountry, $val->homeZipCode, $val->homeCountryCode, $val->homeAreaCode, strtolower($val->civilStat), $val->others, $val->rank);
  if($isImported) $total_new_contact_count++;

  foreach($val->employment as $key_emp => $val_emp) {
    
    $isImportedEmployment = $employment_new->write($val_emp->contactId, $val_emp->companyName, $val_emp->companyAddress, $val_emp->position, null, null, null, $val->countryCode, $val->zip, $val->fax, $val->areaCode, $val->sector);
    if($isImportedEmployment) $total_employment_contact_count++;
  }
}

// get inserted data from database and check if it it equal to the original one



echo "TOTAL Contact Imported (orig/new):".$total_orig_contact_count."/".$total_new_contact_count."\n";


foreach($contacts_data as $key => $val) {
  

  # employment
  $employment_new = new Employment($DB_CON_NEW);
  $isImportedEmployment = $employment_new->write($val->contact_id, $val->company, $val->officeAddress, $val->position, null, null, null, $val->officeCounty, $val->officeZipCode, $val->officeFax, $val->officeAreaCode, $val->sector);
  if($isImportedEmployment) $total_employment_contact_count++;
}

echo "TOTAL Contact Imported (orig/new):".$total_orig_contact_count."/".$total_new_contact_count."\n"; 
echo "TOTAL Employment Imported (orig/new):".$total_orig_contact_count."/".$total_employment_contact_count."\n"; */
