<?php
include('CSVImporter/ResourcePerson.php');
include('CSVImporter/Trainess.php');
include_once('database/connection.php');

use CSVFiles\ResourcePerson;
use CSVFiles\Trainees;
global $DB_CON_NEW;

function migrateResourcePerson () {
  # hackish way to allow outide scope variable
  global $DB_CON_NEW;

  # write to new database
  $person = new ResourcePerson($DB_CON_NEW);
  $person->migrate();
}



function migrateTrainees () {
  # hackish way to allow outide scope variable
  global $DB_CON_NEW;

  # write to new database
  $person = new Trainees($DB_CON_NEW);
  $person->migrate();
}

