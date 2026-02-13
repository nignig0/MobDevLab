<?php

require_once(__DIR__ . '/../db.php');

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET' && isset($_GET['id'])) {
    //if there is a get request to this file and 

    if (!is_numeric($_GET['id'])) {
        //if the id is not numeric, let the user know its an invalid id
        http_response_code(400);
        echo json_encode(["message" => "Invalid ID"]);
        exit;
    }

    getStudent($_GET['id']);

} else {
    http_response_code(400);
    echo json_encode(["message" => "Invalid request"]);
}

function getStudent($id){
    global $conn; //db connection

    $statement = $conn->prepare('SELECT * FROM students WHERE id = ?');

    if(!$statement){
        http_response_code(500);
        echo json_encode(["message" => "Database error"]);
        return;
    }

    $statement->bind_param('i', $id);
    $statement->execute();

    $result = $statement->get_result();
    $student = $result->fetch_assoc();

    if (!$student) {
        //if we dont find the student let the user know
        http_response_code(404);
        echo json_encode(["message" => "Student not found"]);
        return;
    }

    http_response_code(200);
    echo json_encode($student);
    //if all is well return the user record

    $statement->close();
}
?>
