<?php
class CardSet {
  private $conn;

  public function  __construct() {
    $this->conn = new Connection(CONN);
  }

  public function create($data) {
    $string = "";

    foreach($data as $key => $value) {
      $string .= "{$key} = :{$key}, ";
    }
    $string = rtrim($string, ', ');

    $sql = "INSERT INTO card_sets SET {$string}";
    
    $data['cards'] = json_encode($data['cards']);
    
    $res = $this->conn->insert($sql, $data);

    if($res) {
      return ["status" => 200];
    } else return $res;
  }

  public function update($data) {
    $id = $data['id'];
    unset($data['id']);

    $string = "";

    foreach($data as $key => $value) {
      $string .= "{$key} = :{$key}, ";
    }
    $string = rtrim($string, ', ');

    $sql = "UPDATE card_sets SET {$string} WHERE id = :id;";

    $data['cards'] = json_encode($data['cards']);
    $res = $this->conn->update($sql, $id, $data);

    if($res == 1) {
      return ["success" => true];
    } else return $res;
  }
}