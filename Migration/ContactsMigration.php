<?php 

include_once('Reader/Contacts.php');
include_once('Builder/ContactBuilder.php');

use Reader\Contacts;
use Builder\ContactBuilder;




function migrateContacts () {
  include('database/connection.php');
  $contacts = new Contacts($DB_CON);
  # Original CONTACT data
  $contacts_data = $contacts->retrieve();
  $total_orig_contact_count = count($contacts_data);

  # Inserted data
  $contacts_new = new Contacts($DB_CON_NEW);
  $total_new_contact_count = 0;

  $contacts_built_data = [];

  foreach($contacts_data as $key => $val) {
    $contacts_built_data[] = ContactBuilder::build($val);
  }

  // write to DB
  foreach($contacts_built_data as $key => $val) {
    $isImported = $contacts_new->write($val->contactId, $val->affiliateCode, $val->prefix, $val->lastname, $val->firstname, $val->middleinit, strtolower($val->gender), $val->birthdate, $val->nationality, $val->specialization, $val->homeAddress, $val->homeCountry, $val->homeZipCode, $val->homeCountryCode, $val->homeAreaCode, strtolower($val->civilStat), $val->others, $val->rank);
    if($isImported) $total_new_contact_count++;
  }

  echo "-----------------------------------------\n";
  echo "**IMPORTING Contacts** \n\r";
  echo "TOTAL(orig/new):".$total_orig_contact_count."/".$total_new_contact_count."\n";
  if($total_orig_contact_count!==$total_new_contact_count) {
    echo "\nFinished with ERRORS!\n";
  }
  echo "-----------------------------------------\n\r";
}
