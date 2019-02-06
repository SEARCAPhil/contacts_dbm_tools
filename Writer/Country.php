<?php
namespace Writer;
class Country{
  public function __construct (\PDO $DB_CON) {
    $this->DB = $DB_CON;
  }


  public function write($countryCode, $countryName, $lastRank) {
    $this->sth = $this->DB->prepare('INSERT INTO country(countryCode, countryName, lastrank) values (:countryCode, :countryName, :lastrank)');
    $this->sth->bindValue(':countryCode',$countryCode);
    $this->sth->bindValue(':countryName', $countryName); 
    $this->sth->bindValue(':lastrank', $lastRank);
    $this->sth->execute();
    return $this->DB->lastInsertId();
  }

}
