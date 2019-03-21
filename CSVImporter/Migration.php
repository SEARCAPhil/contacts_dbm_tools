<?php
include('CSVImporter/ResourcePerson.php');
include_once('database/connection.php');

use CSVFiles\ResourcePerson;
global $DB_CON_NEW;

function migrateResourcePerson () {
  # hackish way to allow outide scope variable
  global $DB_CON_NEW;

  # write to new database
  $person = new ResourcePerson($DB_CON_NEW);
  $person->migrate();
}

