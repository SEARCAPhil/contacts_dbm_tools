<?php 
/**
 * Migration entry point
 * 
 * This Script contains utilities for migrating OLD database to NEW database
 * DO NOT TOUCH THIS FILE UNLESS URGENTLY NEEDED. 
 */
include_once('Migration/ContactsMigration.php');
include_once('Migration/EmploymentMigration.php');
include_once('Migration/BSMigration.php');
include_once('Migration/MSMigration.php');
include_once('Migration/PhdMigration.php');
include_once('Migration/FellowMigration.php');
include_once('Migration/AssocMigration.php');
include_once('Migration/GradMigration.php');
include_once('Migration/TrainingMigration.php');
include_once('Migration/EngagementResearchMigration.php');
include_once('Migration/CountryMigration.php');
include_once('Migration/SectorMigration.php');
include_once('Migration/AfftypeMigration.php');
include_once('Migration/SaafClassMigration.php');
include_once('Migration/SaafTypeMigration.php');
include_once('CSVImporter/Migration.php');


# General
# DO NOT migrate SaafType before SaafClass! Doing so
# will yield undesirable results
migrateCountry();
migrateSector();
migrateAfftype();
migrateSaafClass();
migrateSaafType();

# Contacts Background
migrateContacts();
migrateEmployments();
migrateBS();
migrateMS();
migratePhd();

# Research and trainings
migrateFellow();
migrateAssocResearch();
migrateGradResearch();
migrateTrainings();
migrateEngagementResearch();

#CSV
migrateResourcePerson();
