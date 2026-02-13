<?php
require_once(__DIR__ . '/../db.php');

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'PUT') {

    parse_str(file_get_contents("php://input"), $_PUT);

    if (empty($_PUT['id']) || empty($_PUT['firstname']) || empty($_PUT['lastname']) || empty($_PUT['major'])) {
        http_response_code(400);
        echo json_encode(["message" => "All fields are required"]);
        exit;
    }

    if (!is_numeric($_PUT['id'])) {
        http_response_code(400);
        echo json_encode(["message" => "Invalid ID"]);
        exit;
    }

    updateStudent($_PUT['id'], $_PUT['firstname'], $_PUT['lastname'], $_PUT['major']);
}

function updateStudent($id, $firstname, $lastname, $major){
    global $conn;

    $statement = $conn->prepare(
        "UPDATE students SET firstname = ?, lastname = ?, major = ? WHERE id = ?"
    );

    if(!$statement){
        http_response_code(500);
        echo json_encode(["message" => "Database error"]);
        return;
    }

    $statement->bind_param('sssi', $firstname, $lastname, $major, $id);

    if($statement->execute()){

        if($statement->affected_rows > 0){
            echo json_encode(["message" => "Student updated successfully"]);
        } else {
            echo json_encode(["message" => "No student found with that ID"]);
        }

    } else {
        http_response_code(500);
        echo json_encode(["message" => "Error updating student"]);
    }

    $statement->close();
}
?>
