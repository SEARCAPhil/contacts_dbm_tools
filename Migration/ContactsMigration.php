<?php 

include_once('Reader/Contacts.php');
include_once('Builder/ContactBuilder.php');
include_once('Builder/CommunicationBuilder.php');
include_once('Writer/Communication.php');

use Reader\Contacts;
use Builder\ContactBuilder;
use Builder\CommunicationBuilder;
use Writer\Communication;




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

  # Email
  $comm_writer= new Communication($DB_CON_NEW);
  $contacts_email_built_data = [];
  $total_new_email_count = 0;

  foreach($contacts_data as $key => $val) {
    $contacts_built_data[] = ContactBuilder::build($val);
    # get email add
    if(!empty($val->officeEmail) && !is_null($val->officeEmail)) {
      $emails = explode(',', trim($val->officeEmail)); 
      foreach($emails as $keyE => $valE) {
        $com = new \StdClass;
        $com->type = 'email';
        $com->value = $valE;
        $com->contact_id = $val->contact_id;
        $contacts_email_built_data[] = CommunicationBuilder::build($com);
      }
    }
  }

  // write to DB
  foreach($contacts_built_data as $key => $val) {
    $isImported = $contacts_new->write($val->contactId, $val->affiliateCode, $val->prefix, $val->lastname, $val->firstname, $val->middleinit, strtolower($val->gender), $val->birthdate, $val->nationality, $val->specialization, $val->homeAddress, $val->homeCountry, $val->homeZipCode, $val->homeCountryCode, $val->homeAreaCode, strtolower($val->civilStat), $val->others, $val->rank);
    if($isImported) $total_new_contact_count++;
  }

  // write to DB
  foreach($contacts_email_built_data as $key => $val) {
    $isImported = $comm_writer->write($val->contactId, $val->type, $val->value);
    if($isImported) $total_new_email_count++;
  }

  echo "-----------------------------------------\n";
  echo "**IMPORTING Contacts** \n\r";
  echo "TOTAL(orig/new):".$total_orig_contact_count."/".$total_new_contact_count."\n";
  echo "TOTAL Email:".$total_new_email_count."\n";
  if($total_orig_contact_count!==$total_new_contact_count) {
    echo "\nFinished with ERRORS!\n";
  }
  echo "-----------------------------------------\n\r";
}
