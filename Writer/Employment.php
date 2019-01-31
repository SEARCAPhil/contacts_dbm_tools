<?php
namespace Writer;
class Employment{
  public function __construct (\PDO $DB_CON) {
    $this->DB = $DB_CON;
  }


  public function write($contact_id, $companyName, $companyAddress, $position, $employedFrom, $employedTo, $country, $countryCode, $zip, $fax, $areaCode, $sector) {
    $this->sth = $this->DB->prepare('INSERT INTO employment(contact_id, companyName, companyAddress, position, employedFrom, employedTo, country, countryCode, zip, fax, areaCode, sector) values(:contact_id, :companyName, :companyAddress, :position, :employedFrom, :employedTo, :country, :countryCode, :zip, :fax, :areaCode, :sector)');
    $this->sth->bindValue(':contact_id',$contact_id); 
    $this->sth->bindValue(':companyName', $companyName); 
    $this->sth->bindValue(':companyAddress', $companyAddress); 
    $this->sth->bindValue(':position', $position); 
    $this->sth->bindValue(':employedFrom', $employedFrom); 
    $this->sth->bindValue(':employedTo', $employedTo);
    $this->sth->bindValue(':country', $country);
    $this->sth->bindValue(':countryCode', $countryCode);
    $this->sth->bindValue(':zip', $zip);
    $this->sth->bindValue(':fax', $fax);
    $this->sth->bindValue(':areaCode', $areaCode);
    $this->sth->bindValue(':sector', $sector);
    $this->sth->execute();
    return $this->DB->lastInsertId();
  }

  function convert ($row) {
    $converted_row = new \StdClass;
    foreach($row as $key => $value) {
      if(!is_null($value)) {
        #$converted_row->$key = mb_convert_encoding($value, 'auto');
        $converted_row->$key = \utf8_encode($value);
      }
    }

    return $converted_row;
  }
}
