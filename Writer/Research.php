<?php
namespace Writer;
class Research{
  public function __construct (\PDO $DB_CON) {
    $this->DB = $DB_CON;
  }


  public function write($contact_id, $saaftype_id, $dateStarted, $dateEnded, $title, $fieldStudy, $funding, $remarks, $hostUniversity, $isSearcaTraining) {
    $this->sth = $this->DB->prepare('INSERT INTO research(contact_id, saaftype_id, dateStarted, dateEnded, title, fieldStudy, funding, remarks, hostUniversity, isSearcaTraining) values (:contact_id, :saaftype_id, :dateStarted, :dateEnded, :title, :fieldStudy, :funding, :remarks, :hostUniversity, :isSearcaTraining)');
    $this->sth->bindValue(':contact_id',$contact_id); 
    $this->sth->bindValue(':saaftype_id',$saaftype_id); 
    $this->sth->bindValue(':dateStarted', $dateStarted);
    $this->sth->bindValue(':dateEnded', $dateEnded);
    $this->sth->bindValue(':title', $title);
    $this->sth->bindValue(':fieldStudy', $fieldStudy);
    $this->sth->bindValue(':funding', $funding);
    $this->sth->bindValue(':remarks', $remarks);
    $this->sth->bindValue(':hostUniversity', $hostUniversity);
    $this->sth->bindValue(':isSearcaTraining', $isSearcaTraining);
    $this->sth->execute();
    return $this->DB->lastInsertId();
  }

}
