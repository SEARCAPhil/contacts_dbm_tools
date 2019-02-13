<?php
namespace Writer;
class Communication{
  public function __construct (\PDO $DB_CON) {
    $this->DB = $DB_CON;
  }


  public function write($contact_id, $type, $value) {
    $this->sth = $this->DB->prepare('INSERT INTO communication(contact_id, type, value) values (:contact_id, :type, :value)');
    $this->sth->bindValue(':contact_id',$contact_id); 
    $this->sth->bindValue(':type', $type);  
    $this->sth->bindValue(':value', $value); 


    $this->sth->execute();
    return $this->DB->lastInsertId();
  }

}
