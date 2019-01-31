<?php
namespace Writer;
class Masters{
  public function __construct (\PDO $DB_CON) {
    $this->DB = $DB_CON;
  }


  public function write($contact_id, $institution, $country, $field, $grad, $scholarship, $type) {
    $this->sth = $this->DB->prepare('INSERT INTO education(contact_id, institution, country, field, grad, scholarship, type) values (:contact_id, :institution, :country, :field, :grad, :scholarship, :type)');
    $this->sth->bindValue(':contact_id',$contact_id); 
    $this->sth->bindValue(':institution', $institution);
    $this->sth->bindValue(':country', $country);
    $this->sth->bindValue(':field', $field); 
    $this->sth->bindValue(':grad', $grad); 
    $this->sth->bindValue(':scholarship', $scholarship); 
    $this->sth->bindValue(':type', $type);
    $this->sth->execute();
    return $this->DB->lastInsertId();
  }

}
