<?php
require_once(__DIR__ . '/../db.php');

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {

    // Check required fields
    if (empty($_POST['firstname']) || empty($_POST['lastname']) || empty($_POST['major'])) {
        http_response_code(400);
        echo json_encode(["message" => "firstname, lastname and major required"]);
        exit;
    }

    createStudent(
        $_POST['firstname'],
        $_POST['lastname'],
        $_POST['major']
    );
}

function createStudent($firstname, $lastname, $major){
    global $conn;

    $statement = $conn->prepare('INSERT INTO students(firstname, lastname, major) VALUES (?, ?, ?)');

    if(!$statement){
        http_response_code(500);
        echo json_encode(["message" => "Db error: " . $conn->error]);
        return;
    }

    $statement->bind_param('sss', $firstname, $lastname, $major);

    if($statement->execute()){
        http_response_code(201); // Created
        echo json_encode(["message" => "Student created successfully", "id" => $statement->insert_id]);
    } else {
        http_response_code(500);
        echo json_encode(["message" => "Error creating student"]);
    }

    $statement->close();
}
?>
