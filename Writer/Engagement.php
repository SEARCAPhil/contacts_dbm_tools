<?php
namespace Writer;
class Engagement {
  public function __construct (\PDO $DB_CON) {
    $this->DB = $DB_CON;
  }


  public function write($contact_id, $engageFrom, $engageTo, $researchId, $engagement, $type) {
    $this->sth = $this->DB->prepare('INSERT INTO engagement(contact_id, engageFrom, engageTo, researchId, engagement, type) values(:contact_id, :engageFrom, :engageTo, :researchId, :engagement, :type)');
    $this->sth->bindValue(':contact_id',$contact_id); 
    $this->sth->bindValue(':engageFrom', $engageFrom);
    $this->sth->bindValue(':engageTo', $engageTo); 
    $this->sth->bindValue(':researchId', $researchId); 
    $this->sth->bindValue(':engagement', $engagement); 
    $this->sth->bindValue(':type', $type);
    $this->sth->execute();
    return $this->DB->lastInsertId();
  }

}
