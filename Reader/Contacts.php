<?php
namespace Reader;

include_once(realpath(dirname(__FILE__)). "/".'../Helper/Utf8ier.php');
use Helper\Utf8ier;

class Contacts {
  public function __construct (\PDO $DB_CON) {
    $this->DB = $DB_CON;
  }

  public function retrieve () {
    $this->sth = $this->DB->prepare('SELECT contact.*, trainaff.trainaff_id FROM contact LEFT JOIN trainaff on trainaff.contact_id = contact.contact_id WHERE trainaff.trainaff_id IS NULL ORDER BY contact_id ASC');
    $this->sth->execute();
    $result = [];
    while($row = $this->sth->fetch(\PDO::FETCH_OBJ)) {
      #$row2 = Utf8ier::convert($row);
      #$row = $row2;
      $result[] = $row;
    }
    return $result;
  }

  public function write($contact_id, $affiliateCode, $prefix, $lastname, $firstname, $middleinit, $gender, $birthdate, $nationality, $specialization, $homeAddress, $homeCountry, $homeZipCode, $homeCountryCode, $homeAreaCode, $civilStat, $others, $rank) {
    $this->sth = $this->DB->prepare('INSERT INTO contact(contact_id, affiliateCode, prefix, lastname, firstname, middleinit, gender, birthdate, nationality, specialization, homeAddress, homeCountry, homeZipCode, homeCountryCode, homeAreaCode, civilStat, others, rank) values(:contact_id, :affiliateCode, :prefix, :lastname, :firstname, :middleinit, :gender, :birthdate, :nationality, :specialization, :homeAddress, :homeCountry, :homeZipCode, :homeCountryCode, :homeAreaCode, :civilStat, :others, :rank)');
    $this->sth->bindParam(':contact_id',$contact_id); 
    $this->sth->bindParam(':affiliateCode',$affiliateCode);
    $this->sth->bindParam(':prefix',$prefix);  
    $this->sth->bindParam(':lastname', $lastname); 
    $this->sth->bindParam(':firstname', $firstname); 
    $this->sth->bindParam(':middleinit', $middleinit); 
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
    $this->sth->bindParam(':rank', $rank); 
    $this->sth->execute();
    return $this->DB->lastInsertId();
  }
}
