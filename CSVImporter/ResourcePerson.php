<?php
namespace CSVFiles;


use Reader\Contacts;
use Writer\Engagement;
use Writer\Training;
use Writer\Masters as Education;
use Writer\Employment;
use Writer\Communication;

class ResourcePerson {
  public function __construct (\PDO $DB_CON) {
    $this->file = realpath(dirname(__FILE__)). "/".'.CSVFiles/resource_person.csv';
    $this->DB = $DB_CON;
    $this->engagement = new Engagement($DB_CON);
    $this->training = new Training($DB_CON);
    $this->education = new Education($DB_CON);
    $this->employment = new Employment($DB_CON);
    $this->communication = new Communication($DB_CON);
  }

  private function __writeAccount($gender, $nationality, $specialization, $fullname) { 
    $this->DB->setAttribute( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION );
    $this->sth = $this->DB->prepare("INSERT INTO contact(gender, nationality, specialization, fullname) values (:gender, :nationality, :specialization, :fullname)");
    $this->sth->bindParam(':gender', $gender, \PDO::PARAM_STR);
    $this->sth->bindParam(':nationality', $nationality, \PDO::PARAM_STR); 
    $this->sth->bindParam(':specialization', $specialization, \PDO::PARAM_STR); 
    $this->sth->bindParam(':fullname', $fullname, \PDO::PARAM_STR); 
    $this->sth->execute();


    return $this->DB->lastInsertId();
  }

  public function migrate () {
    $row = 0;
    $total = 0;
    $header = [];
    $inserted = 0;
    $engaged = 0;
    $trained = 0;
    $training_records = 0;
    $education_records = 0;
    $educational_attainment = 0;
    $employment_records = 0;
    $employments = 0;

    if (($handle = fopen($this->file, "r")) !== FALSE) {
        while (($data = fgetcsv($handle)) !== FALSE) {
            $num = count($data);
            $csv= [];
            for ($c=0; $c < $num; $c++) {
                
                # add to header fields
                if($row === 0) {
                  $arr = explode(' ', strtolower($data[$c]));   
                  $header[] =  implode('_', $arr);
                } else {
                  $csv[$header[strtolower($c)]] = $data[$c];
                }  
             
            }

            if(count($csv) > 0) {
              $writtenID = self::write($csv['sex'],$csv['nationality'], $csv['field_of_specialization'],$csv['name']);
              if($writtenID > 0 && $writtenID) {
                $inserted++;
                # ENGAGEMENT
                # resource person
                # NOTE: THIS PART IS HARDCODED
                $eng = self::writeEngagement($writtenID , null, null, null, null, 4);
                if($eng > 0 && $eng) $engaged++;

                # TRAINING
                if(!empty($csv['course_attended'])) {
                  $training_records++;
                  $train = self::writeTraining($writtenID , $csv['course_attended'], null, null, null, null, null, null, null, null, null, null, null, null, 0);
                  if($train > 0 && $train) $trained++;
                }
                
                # EDUCATION
                if(!empty($csv['educational_attainment'])) { 
                  $education_records++;
                  $educ = self::writeEducation($writtenID , null, null, $csv['educational_attainment'], null, null, null);
                  
                  if($educ > 0 && $educ) $educational_attainment++;
                }

                # EMPLOYMENT
                if(!empty($csv['position_title'])) {
                  $employment_records++;
                  $pos = self::writeEmployment($writtenID, $csv['organization'], null, $csv['position_title'], null, null, null, null, null, null, null, null);
                  if($pos > 0 && $pos) $employments++;
                }

                # PARSE CONTACT INFO
                $contacts = explode(';', $csv['contact_address']);
                $emails = [];
                $phones = [];
                $fax = [];

                foreach($contacts as $key => $value) {
                  if(stripos($value, 'Tel.:') != false) $phones[] = str_ireplace('Tel.:', '', trim($value));
                  if(stripos($value, 'Mobile:') != false) $phones[] = str_ireplace('Mobile:', '', trim($value));
                  if(stripos($value, 'Email:') != false) $emails[] = str_ireplace('Email:', '', trim($value));
                  if(stripos($value, 'E-mail:') != false) $emails[] = str_ireplace('E-mail:', '', trim($value));
                  if(stripos($value, 'Fax:') != false) $fax[] = str_ireplace('Fax:', '', trim($value));
                 
                }
                
                # COMMUNICATION
                # Email
                foreach($emails as $key => $val) {
                  $this->communication->write($writtenID, 'email', $val);
                }
                # Fax
                foreach($fax as $key => $val) {
                  $this->communication->write($writtenID, 'fax', $val);
                }
                # Fax
                foreach($phones as $key => $val) {
                  $this->communication->write($writtenID, 'phone', $val);
                }
                
                
              }
              $total++;
            }

            $row++;

        }


        echo "-----------------------------------------\n";
        echo "**IMPORTING RESOURCE PERSON CSV**\n";
        echo "Inserted {$inserted} out of {$total}\n";
        echo "Engaged {$engaged} out of {$total}\n";
        echo "Training records {$trained} out of {$training_records}\n";
        echo "Education Background records {$educational_attainment} out of {$education_records}\n";
        echo "Employment records {$employments} out of {$employment_records}\n";
        if($inserted === $engaged) {
          echo "*Imported all resource persons without error\n";
        }

        if($trained === $training_records) {
          echo "*Imported all trainings without error\n";
        }
        echo "-----------------------------------------\n";

        

        fclose($handle);

        
    }
  }

  public function write($gender, $nationality, $specialization, $fullname) { 
    return self::__writeAccount($gender, $nationality, $specialization, $fullname);
  }

  public function writeEngagement($contact_id, $engageFrom, $engageTo, $researchId, $engagement, $type) {
    return $this->engagement->write($contact_id, $engageFrom, $engageTo, $researchId, $engagement, $type);
  }

  private function writeTraining($contact_id, $title, $saaftype_id, $notes, $dateStarted, $dateEnded, $scholarship, $venue, $sponsor, $supervisor, $supervisorDesignation, $trainingType, $organizingAgency, $hostUniversity, $isSearcaTraining) {
    return $this->training->write($contact_id, $title, $saaftype_id, $notes, $dateStarted, $dateEnded, $scholarship, $venue, $sponsor, $supervisor, $supervisorDesignation, $trainingType, $organizingAgency, $hostUniversity, $isSearcaTraining);
  }

  private function writeEducation($contact_id, $institution, $country, $field, $grad, $scholarship, $type) {
    return $this->education->write($contact_id, $institution, $country, $field, $grad, $scholarship, $type);
  }

  private function writeEmployment($contact_id, $companyName, $companyAddress, $position, $employedFrom, $employedTo, $country, $countryCode, $zip, $fax, $areaCode, $sector) {
    return $this->employment->write($contact_id, $companyName, $companyAddress, $position, $employedFrom, $employedTo, $country, $countryCode, $zip, $fax, $areaCode, $sector);
  }
}

