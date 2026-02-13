<?php

require_once(__DIR__ . '/../db.php');
header('Content-Type: application/json');
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    getAllStudents();
}

function getAllStudents(){
    global $conn;

    $statement = $conn->prepare("SELECT * FROM students");

    if (!$statement) {
        http_response_code(500);
        echo json_encode(["message" => "Failed to prepare statement"]);
        return;
    }

    if (!$statement->execute()) {
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

    $statement->close();
}
?>
