<?php
require dirname(__DIR__) . "/bootstrap.php";

if(login()) {
  if($_SERVER['REQUEST_METHOD'] == "POST") {
    $contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
    if ($contentType !== 'application/json') {
      http_response_code(415);
      echo json_encode(["errors" => "Only JSON content is supported"]);
      exit();
    }

    if($_GET['action'] == "edit_set") {
      $data = json_decode(file_get_contents("php://input"), true)[0];
      
      unset($data['token']);
      // $data['user_id'] = $_SESSION['id'];

      $card_set = get_set($data['id']);

      if($card_set['user_id'] == $_SESSION['id']) {
        $errors = validate_cards($data);

        // Output errors
        if(count($errors) > 0) {
          $errors = ["errors" => $errors];
          echo json_encode($errors);
          die();
        }
      
        $card_set = new CardSet();
  
        echo json_encode($card_set->update($data));
      } else {
        json_encode(['errors' => 'Mozete da izmenite samo Vase setove.']);
      }
    }
  } else {
    http_response_code(405);
    echo "Allow: POST";
    exit();
  }
} else {
  echo "you must log in";
}