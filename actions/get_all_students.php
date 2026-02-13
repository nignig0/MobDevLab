<?php

require_once(__DIR__ . '/../db.php');
header('Content-Type: application/json');
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    //if there's a get request to this file
    //get all students if not
    //let the user know its an invalid request
    getAllStudents();
}else {
    http_response_code(400);
    echo json_encode(["message" => "Invalid request"]);
}

function getAllStudents(){
    global $conn; //db connection

    $statement = $conn->prepare("SELECT * FROM students"); //get all students 

    if (!$statement) {
        //if we cannot prepare the statement let the user know
        http_response_code(500);
        echo json_encode(["message" => "Failed to prepare statement"]);
        return;
    }

    if (!$statement->execute()) {
        //if theres an error, let the user know
        http_response_code(500);
        echo json_encode(["message" => "Failed to execute query"]);
        return;
    }

    $result = $statement->get_result();

    $students = [];

    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }

    http_response_code(200);
    echo json_encode($students);
    //if all works out return the list of students

    $statement->close();
}
?>
