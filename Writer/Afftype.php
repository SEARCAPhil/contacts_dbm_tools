<?php
namespace Writer;
class Afftype{
  public function __construct (\PDO $DB_CON) {
    $this->DB = $DB_CON;
  }


  public function write( $type_id, $afftypeName) {
    $this->sth = $this->DB->prepare('INSERT INTO afftype(afftypeName, type_id) values (:afftypeName, :type_id)');
    $this->sth->bindValue(':afftypeName', $afftypeName); 
    $this->sth->bindValue(':type_id', $type_id); 
    $this->sth->execute();
    return $this->DB->lastInsertId();
  }

}
