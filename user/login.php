<?php
require dirname(__DIR__) . "/bootstrap.php";
if (!login()) {
  if($_SERVER['REQUEST_METHOD'] == "POST") {
    $contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
    if ($contentType !== 'application/json') {
      http_response_code(415);
      echo json_encode(["errors" => "Only JSON content is supported"]);
      exit();
    }

    if ($_GET['action'] == "login_user") {
      $data = json_decode(file_get_contents("php://input"), true);
  
      $errors = validate_data($data);

      // Output errors
      if(count($errors) > 0) {
        $errors = ["errors" => $errors];
        echo json_encode($errors);
        die();
      }
  
      $user = new User();
    
      echo json_encode($user->login($data));
    
      die();
    }
  } else {
    http_response_code(405);
    exit();
  }
} else {
  header("Location: index.php");
}