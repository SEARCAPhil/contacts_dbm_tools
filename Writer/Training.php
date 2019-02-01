<?php
namespace Writer;
class Training{
  public function __construct (\PDO $DB_CON) {
    $this->DB = $DB_CON;
  }


  public function write($contact_id, $title, $saaftype_id, $notes, $dateStarted, $dateEnded, $scholarship, $venue, $sponsor, $supervisor, $supervisorDesignation, $trainingType, $organizingAgency, $hostUniversity, $isSearcaTraining) {
    $this->sth = $this->DB->prepare('INSERT INTO training(contact_id, title, saaftype_id, notes, dateStarted, dateEnded, scholarship, venue, sponsor, supervisor, supervisorDesignation, trainingType, organizingAgency, hostUniversity, isSearcaTraining) values (:contact_id, :title, :saaftype_id, :notes, :dateStarted, :dateEnded, :scholarship, :venue, :sponsor, :supervisor, :supervisorDesignation, :trainingType, :organizingAgency, :hostUniversity, :isSearcaTraining)');
    $this->sth->bindValue(':contact_id',$contact_id); 
    $this->sth->bindValue(':title', $title);
    $this->sth->bindValue(':saaftype_id', $saaftype_id);  
    $this->sth->bindValue(':notes', $notes);   
    $this->sth->bindValue(':dateStarted', $dateStarted); 
    $this->sth->bindValue(':dateEnded', $dateEnded); 
    $this->sth->bindValue(':scholarship', $scholarship); 
    $this->sth->bindValue(':venue', $venue); 
    $this->sth->bindValue(':sponsor', $sponsor); 
    $this->sth->bindValue(':supervisor', $supervisor); 
    $this->sth->bindValue(':supervisorDesignation', $supervisorDesignation); 
    $this->sth->bindValue(':trainingType', $trainingType); 
    $this->sth->bindValue(':organizingAgency', $organizingAgency); 
    $this->sth->bindValue(':hostUniversity', $hostUniversity);
    $this->sth->bindValue(':isSearcaTraining', $isSearcaTraining);
    $this->sth->execute();
    return $this->DB->lastInsertId();
  }

}
