<?php
function dd($value) {
  echo "<pre>";
  var_dump($value);
  echo "</pre>";
}

function generateRandomString($length = 100) 
{
  $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ@';
  // $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!#$%&()*+,.:;<=>?@[]^_`{|}~';
  $randomString = '';
  $max = strlen($characters) - 1;
  for ($i = 0; $i < $length; $i++) {
    $randomString .= $characters[rand(0, $max)];
  }

  return $randomString;

  // $data = ["token" => $randomString];

  // $sql = "SELECT * FROM users WHERE token = :token";

  // $user = Connection::getData($sql, $data);

  // if($user) {
  //   generateRandomString();
  // } else return $randomString;
}

function login()
{
  if (isset($_SESSION['id'])) {
    return true;

  } else if (isset($_COOKIE['id'])) {
    // $_SESSION['token'] = $_COOKIE['token'];
    $_SESSION['id'] = $_COOKIE['id'];
    $_SESSION['username'] = $_COOKIE['username'];

    return true;
    
  } else return false;
}

function validate_data($data) {
  $errors = [];

  foreach ($data as $key => $value) {
    $specialCharacters = '/[^\w\s]/';

    // trim the value " asd " = "asd";
    $value = trim($value);

    // Validate username
    if ($key === "username") {
      if (strlen($value) < 3) {
        $errors[] = "Korisnicko ime mora sadrzati vise od 3 karaktera.";
      }
      if (strpos($value, " ") !== false) {
        $errors[] = "Korisnicko ime ne moze sadrzati razmak.";
      }

      if (preg_match($specialCharacters, $value)) {
        $errors[] = "Korisnicko ime ne moze sadrzati ni jedan specijalan karakter sem _";
      }
    }

    if ($key === "password") {
      // Validate password
      if (strpos($value, " ") !== false) {
        $errors[] = "Lozinka ne moze sadrzati razmake.";
      }
      if (strlen($value) < 3) {
        $errors[] = "Lozinka mora sadrzati najmanje 3 karaktera.";
      }
    }
  }

  return $errors;
}

function validate_cards($data) {
  $errors = [];
  $HTMLTagRegex = '/<\/?[\w\s]*>|<.+[\W]>/';
  $cards = $data['cards'];

  // filter HTML tags
  if (preg_match($HTMLTagRegex, $data['title'])) {
    $errors[] = "Cannot submit html tags.";
  }

  if(strlen($data['title']) < 4) {
    $errors[] = "Naziv seta mora biti duzi od 4 karaktera.";
  }

  if(!preg_match('/^[a-zA-Z0-9]+$/', $data['title'])) {
    $errors[] = "Naziv seta moze da sadrzi samo velika/mala slova i brojeve";
  }

  foreach($cards as $card) {
    if(strlen($card['question']) < 3) {
      $error = "Pitanje mora sadrzati 10 ili vise karaktera";
      if (!in_array($error, $errors)) {
        $errors[] = $error;
      }
    }
    
    if (preg_match($HTMLTagRegex, $card['question']) || preg_match($HTMLTagRegex, $card['answer'])) {
      $error = "Cannot submit html tags.";
      if (!in_array($error, $errors)) {
        $errors[] = $error;
      }
    }

    if(strlen($card['answer']) == 0) {
      $error = "Morate dati odgovor.";
      if (!in_array($error, $errors)) {
        $errors[] = $error;
      }
    }
  }

  return $errors;
}

function get_set($id) {
  $data = Connection::getData("SELECT * FROM card_sets WHERE id = :id", ["id" => $id]);
  
  if(count($data) > 0) {
    return $data[0];
  } else {
    return json_encode(["errors" => "Ne postoji trazeni set."]);
  }
}

function get_sets($user_id) {
  $data = Connection::getData("SELECT * FROM card_sets WHERE user_id = :user_id", ["user_id" => $user_id]);
  
  if(count($data) > 0) {
    return $data;
  } else {
    return json_encode(["errors" => "Korisnik nije kreirao ni jedan set."]);
  }
}