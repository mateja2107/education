<?php
class User {
  private $conn;
  public $data = [];

  public function  __construct() {
    $this->conn = new Connection(CONN);
  }

  public function login($data) {
    $sql = "SELECT * FROM users WHERE username = :username;";

    $user = Connection::getData($sql, ["username" => $data['username']]);
    if($user) {
      $user = $user[0];

      if(password_verify($data['password'], $user['password'])) {
        // if login is successfull
        // $_SESSION['token'] = $user['token'];
        $_SESSION['id'] = $user['id'];
        $_SESSION['username'] = $user['username'];

        // setcookie("token", $user['token'], time() + 3600 * 24, '/');
        setcookie("id", $user['id'], time() + 3600 * 24, '/');
        setcookie("username", $user['username'], time() + 3600 * 24, '/');

        return ["status" => 200];
      } else return ["errors" => ['Korisnicko ime ili lozinka nisu ispravni.']];
    } else return ["errors" => ['Korisnicko ime ili lozinka nisu ispravni.']];
  }

  public function register($data) {
    $this->data = $data;

    $this->data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
    // $this->data['token'] = generateRandomString();

    $string = "";
    foreach($this->data as $key => $value) {
      $string .= "{$key} = :{$key}, ";
    }

    $string = rtrim($string, ', ');

    $sql = "INSERT INTO users SET {$string};";

    $res = $this->conn->insert($sql, $this->data);

    if($res == 1) {
      $this->login($data);
    } else return $res;
  }

  public static function getUser($id) {
    $sql = "SELECT * FROM users WHERE id = :id";

    $user = Connection::getData($sql, ["id" => $id]);

    if($user) {
      return $user[0];
    } else return false;
  }
}