<?php
namespace Writer;
class Sector{
  public function __construct (\PDO $DB_CON) {
    $this->DB = $DB_CON;
  }


  public function write($sectorName) {
    $this->sth = $this->DB->prepare('INSERT INTO sector(sectorName) values (:sectorName)');
    $this->sth->bindValue(':sectorName', $sectorName);
    $this->sth->execute();
    return $this->DB->lastInsertId();
  }

}
