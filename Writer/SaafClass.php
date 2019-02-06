<?php
namespace Writer;
class SaafClass{
  public function __construct (\PDO $DB_CON) {
    $this->DB = $DB_CON;
  }


  public function write($saafclass, $saafclass_parent_id = null) {
    $this->sth = $this->DB->prepare('INSERT INTO saafclass(saafclass, saafclass_parent_id) values (:saafclass, :saafclass_parent_id)');
    $this->sth->bindValue(':saafclass', $saafclass);
    $this->sth->bindValue(':saafclass_parent_id', $saafclass_parent_id);
    $this->sth->execute();
    return $this->DB->lastInsertId();
  }

}
