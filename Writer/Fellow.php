<?php
namespace Writer;
class Fellow {
  public function __construct (\PDO $DB_CON) {
    $this->DB = $DB_CON;
  }


  public function write($contact_id, $saaftype_id, $dateFrom, $dateTo) {
    $this->sth = $this->DB->prepare('INSERT INTO fellowaff(contact_id, saaftype_id, dateFrom, dateTo) values (:contact_id, :saaftype_id, :dateFrom, :dateTo)');
    $this->sth->bindValue(':contact_id',$contact_id); 
    $this->sth->bindValue(':saaftype_id', $saaftype_id);  
    $this->sth->bindValue(':dateFrom', $dateFrom); 
    $this->sth->bindValue(':dateTo', $dateTo); 

    $this->sth->execute();
    return $this->DB->lastInsertId();
  }

}
