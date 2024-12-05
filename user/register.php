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

    if ($_GET['action'] == "register_user") {
      $data = json_decode(file_get_contents("php://input"), true);

      $errors = validate_data($data);

      // Output errors
      if(count($errors) > 0) {
        $errors = ["errors" => $errors];
        echo json_encode($errors);
        die();
      }

      $user = new User();
      echo json_encode($user->register($data));

      die();
    }
  }
} else {
  // header("Location: index.php");
  echo 'you already have an account';
}