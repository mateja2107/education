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

    if($_GET['action'] == "delete_set") {
      $data = json_decode(file_get_contents("php://input"), true);
      
      $card_set = get_set($data['id']);
    
      if($card_set['user_id'] == $_SESSION['id']) {
        echo json_encode(Connection::delete("DELETE FROM card_sets WHERE id = :id", $data['id']));
      } else {
        echo json_encode(["errors" => "Ne mozete obrisati set koji niste Vi kreirali!"]);
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