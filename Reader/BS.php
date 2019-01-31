<?php
namespace Reader;

include_once(realpath(dirname(__FILE__)). "/".'../Helper/Utf8ier.php');
use Helper\Utf8ier;

class BS {
  public function __construct (\PDO $DB_CON) {
    $this->DB = $DB_CON;
  }

  public function retrieve () {
    $this->sth = $this->DB->prepare('SELECT * FROM bachelor ORDER BY bs_id ASC');
    $this->sth->execute();
    $result = [];
    while($row = $this->sth->fetch(\PDO::FETCH_OBJ)) {
      $result[] = $row;
    }
    return $result;
  }
}
