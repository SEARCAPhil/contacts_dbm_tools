<?php
namespace CSVFiles;


include_once('Reader/Contacts.php');
include_once('Writer/Engagement.php');
include_once('Writer/Training.php');
include_once('Writer/Masters.php');
include_once('Writer/Communication.php');
include_once('Writer/Employment.php');
include_once('Writer/Masters.php');
include_once('Builder/CommunicationBuilder.php');
include_once('database/connection.php');
include_once('Builder/EmploymentBuilder.php');




use Reader\Contacts;
use Writer\Engagement;
use Writer\Training;
use Writer\Masters as Education;
use Writer\Employment;
use Writer\Masters;
use Writer\Communication;
use Builder\CommunicationBuilder;
use Builder\EmploymentBuilder;

class Trainees {
  public function __construct (\PDO $DB_CON) {
    $this->file = realpath(dirname(__FILE__)). "/".'.CSVFiles/participants.csv';
    $this->DB = $DB_CON;
    $this->contacts = new Contacts($DB_CON);
    $this->engagement = new Engagement($DB_CON);
    $this->training = new Training($DB_CON);
    $this->education = new Education($DB_CON);
    $this->employment = new Employment($DB_CON);
    $this->communication = new Communication($DB_CON);
    $this->ms = new Masters($DB_CON);

    $this->row = 0;
    $this->total = 0;
    $this->inserted = 0;
  }

  private function __write($fullname, $gender, $birthdate, $nationality, $specialization, $homeAddress, $homeCountry, $homeZipCode, $homeCountryCode, $homeAreaCode, $civilStat, $others) {
    $this->sth = $this->DB->prepare('INSERT INTO contact(fullname, gender, birthdate, nationality, specialization, homeAddress, homeCountry, homeZipCode, homeCountryCode, homeAreaCode, civilStat, others) values(:fullname, :gender, :birthdate, :nationality, :specialization, :homeAddress, :homeCountry, :homeZipCode, :homeCountryCode, :homeAreaCode, :civilStat, :others)');
    $this->sth->bindParam(':fullname', $fullname); 
    $this->sth->bindParam(':gender', $gender); 
    $this->sth->bindParam(':birthdate', $birthdate); 
    $this->sth->bindParam(':nationality', $nationality); 
    $this->sth->bindParam(':specialization', $specialization); 
    $this->sth->bindParam(':homeAddress', $homeAddress); 
    $this->sth->bindParam(':homeCountry', $homeCountry); 
    $this->sth->bindParam(':homeZipCode', $homeZipCode); 
    $this->sth->bindParam(':homeCountryCode', $homeCountryCode); 
    $this->sth->bindParam(':homeAreaCode', $homeAreaCode); 
    $this->sth->bindParam(':civilStat', $civilStat); 
    $this->sth->bindParam(':others', $others); 
    $this->sth->execute();
    return $this->DB->lastInsertId();
  }

  private function buildCom ($contact_id, $email, $type) {
    $contacts_email_built_data = [];
    # get email add
    if(!empty($email) && !is_null($email)) {
      $emails = explode(';', trim($email)); 
      foreach($emails as $keyE => $valE) {
        $com = new \StdClass;
        $com->type = $type;
        $com->value = trim($valE);
        $com->contact_id = $contact_id;
        $contacts_email_built_data[] = CommunicationBuilder::build($com);
      }
    }

    # save
    foreach($contacts_email_built_data as $key => $val) {
      $isImported = $this->communication ->write($val->contactId, $val->type, $val->value);
      if($isImported) $total_new_email_count++;
    }
  }
  
  
  private function buildEmployment ($contact_id, $company, $position, $address, $supervisor, $supervisorDesignation) {
    $employment_built_data = [];
    # DO NOT include those who does not have any work experience
    $com = new \StdClass;
    $com->company = $company;
    $com->contact_id = $contact_id;
    $com->position = $position;
    $com->officeAddress = $address;
    $com->supervisor = trim($supervisor);
    $com->supervisorDesignation = trim($supervisorDesignation);
    if(!is_null($company)) $employment_built_data[] = EmploymentBuilder::build($com);

    foreach($employment_built_data as $key_emp => $val_emp) {
      $isImportedEmployment = $this->employment->write($val_emp->contactId, $val_emp->companyName, $val_emp->companyAddress, $val_emp->position, null, null, null, $val->countryCode, $val->zip, $val->fax, $val->areaCode, $val->sector, $val_emp->supervisor, $val_emp->supervisorDesignation);
      //if($isImportedEmployment) $total_employment_contact_count++;
    }
  }



  public function migrate () {

    if (($handle = fopen($this->file, "r")) !== FALSE) {
        while (($data = fgetcsv($handle)) !== FALSE) {
            $num = count($data);
            $csv = []; 
            for ($c=0; $c < $num; $c++) {
                
                # add to header fields
                if($this->row === 0) {
                  $arr = explode(' ', strtolower($data[$c]));   
                  $header[] =  implode('_', $arr);
                } else {
                  $csv[$header[strtolower($c)]] = $data[$c];
                }  

            }

            if(count($csv) > 0) {
              # parse name
              # remove prefix such as Mr. , Mrs., Ms. etc...
              $name = trim(strtolower($csv['name']));
              $name = str_replace('mr. ', '', $name);
              $name = str_replace('ms. ', '', $name);
              $name = str_replace('mrs. ', '', $name);
              $name = str_replace('dr. ', '', $name);
              $name = str_replace('prof. ', '', $name);
             
              
              if(!empty($name)) {
                $this->total++;
                $id = self::write(ucwords($name), $csv['gender'], $csv['birth_date'], null, $csv['field_of_specialization'], null, $csv['country'], null, null, null, $csv['civil_status'], null);
                if($id && $id > 0) {
                  # write email
                  self::buildCom ($id, $csv['email'], 'email');
                  # write number
                  self::buildCom ($id, $csv['contact_number'], 'phone');
                  # write fax
                  self::buildCom ($id, $csv['fax_no'], 'fax');
                  # employment records
                  self::buildEmployment ($id, $csv['organization'], $csv['current_position_title'],$csv['office_home_address'], $csv['immediate_supervisor'], $csv['supervisor_designation']);

                }

                # BS, MS, and PHD
                if(!empty($csv['bs_institution']) && $id) {
                  $date = (!empty($bs_award_date) ? @(new \DateTime(str_replace('/', '-',$csv['bs_award_date'])))->format('Y-m-d') : '0000-00-00');
                  self::writeMs($id, $csv['bs_institution'], null, $csv['bs_degree'], $date, null, 'bs');
                }  

                if(!empty($csv['ms_institution']) && $id) {
                  $date = (!empty($ms_award_date) ? @(new \DateTime(str_replace('/', '-',$csv['ms_award_date'])))->format('Y-m-d') : '0000-00-00');
                  self::writeMs($id, $csv['ms_institution'], null, $csv['ms_degree'], $date, null, 'ms');
                }
                
                if(!empty($csv['phd_institution']) && $id) {
                  $date = (!empty($csv['phd_award_date']) ? @(new \DateTime(str_replace('/', '-',$csv['phd_award_date'])))->format('Y-m-d') : '0000-00-00');
                  self::writeMs($id, $csv['phd_institution'], null, $csv['phd_degree'], $date, null, 'phd');
                }

                 # TRAINING
                 if(!empty($csv['course_attended']) && $id) {
                  $train = self::writeTraining($id , $csv['course_attended'], null, null, $csv['date_started'], $csv['date_ended'], null, $csv['venue'], $csv['sponsor'], null, null, null, $csv['organizing_agency'], null, 1);
                }
              } 
            }
            # next
            $this->row++;
           
          }
        
          
        fclose($handle);
        // show status
        self::showMessage();
    }
  }

  public function write($fullname, $gender, $birthdate, $nationality, $specialization, $homeAddress, $homeCountry, $homeZipCode, $homeCountryCode, $homeAreaCode, $civilStat, $others) {
    $id = self::__write($fullname, $gender, $birthdate, $nationality, $specialization, $homeAddress, $homeCountry, $homeZipCode, $homeCountryCode, $homeAreaCode, $civilStat, $others);
    if($id > 0 && $id) {
      $this->inserted++;
    }
    return $id;
  }

  private function writeTraining($contact_id, $title, $saaftype_id, $notes, $dateStarted, $dateEnded, $scholarship, $venue, $sponsor, $supervisor, $supervisorDesignation, $trainingType, $organizingAgency, $hostUniversity, $isSearcaTraining) {
    return $this->training->write($contact_id, $title, $saaftype_id, $notes, $dateStarted, $dateEnded, $scholarship, $venue, $sponsor, $supervisor, $supervisorDesignation, $trainingType, $organizingAgency, $hostUniversity, $isSearcaTraining);
  }

  private function writeMs($contact_id, $institution, $country, $field, $grad, $scholarship, $type) {
    return $this->ms->write($contact_id, $institution, $country, $field, $grad, $scholarship, $type);
  }

  public function showMessage () {

    echo "-----------------------------------------\n";
    echo "**IMPORTING TRAINEES' CSV**\n";
    echo "Inserted {$this->inserted} out of {$this->total}\n";
    echo "-----------------------------------------\n";


  }
}


$a = new Trainees($DB_CON_NEW);
$a->migrate();