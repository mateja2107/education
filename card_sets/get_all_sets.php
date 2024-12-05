<?php
require dirname(__DIR__) . '/bootstrap.php';

if(login()) {
  if($_SERVER['REQUEST_METHOD'] == "GET") {
    // $contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
    // if ($contentType !== 'application/json') {
    //   http_response_code(415);
    //   echo json_encode(["errors" => "Only JSON content is supported"]);
    //   exit();
    // }

    if($_GET['action'] == "get_all_sets") {
      // $data = json_decode(file_get_contents("php://input"), true);
      
      echo json_encode(get_sets($_SESSION['id']));
    }
  } else {
      http_response_code(405);
      echo "Allow: POST";
      exit();
    }
  } else {
  echo "you must log in";
}