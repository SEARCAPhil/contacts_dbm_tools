<?php
namespace Reader;

include_once(realpath(dirname(__FILE__)). "/".'../Helper/Utf8ier.php');
use Helper\Utf8ier;

class Bachelors {
  public function __construct (\PDO $DB_CON) {
    $this->DB = $DB_CON;
  }

  public function retrieve () {
    $this->sth = $this->DB->prepare('SELECT * FROM bachelor');
    $this->sth->execute();
    $result = [];
    while($row = $this->sth->fetch(\PDO::FETCH_OBJ)) {
      $row2 = Utf8ier::convert($row);
      $row = $row2;
      $result[] = $row;
    }
    return $result;
  }
}
