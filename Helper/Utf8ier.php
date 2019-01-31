<?php
namespace Helper;
class Utf8ier {
  public function __construct () {

  }

  public static function convert ($row) {
    $converted_row = new \StdClass;
    foreach($row as $key => $value) {
      if(!is_null($value)) {
        $converted_row->$key = \utf8_encode($value);
      }
    }

    return $converted_row;
  }
}
