<?php
require_once(__DIR__ . '/../db.php');

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'PUT') {
    //if it's a put request to this file
    //parse the input as json and validate

     $input = json_decode(file_get_contents("php://input"), true);

    if (empty($input['id']) || empty($input['firstname']) || empty($input['lastname']) || empty($input['major'])) {
        http_response_code(400);
        echo json_encode(["message" => "All fields are required"]);
        exit;
    }

    if (!is_numeric($input['id'])) {
        http_response_code(400);
        echo json_encode(["message" => "Invalid ID"]);
        exit;
    }

    updateStudent($input['id'], $input['firstname'], $input['lastname'], $input['major']);
}else {
    http_response_code(400);
    echo json_encode(["message" => "Invalid request"]);
}

function updateStudent($id, $firstname, $lastname, $major){
    global $conn; //db connection

    $statement = $conn->prepare(
        "UPDATE students SET firstname = ?, lastname = ?, major = ? WHERE id = ?"
    );
    //update query

    if(!$statement){
        http_response_code(500);
        echo json_encode(["message" => "Database error"]);
        return;
    }

    $statement->bind_param('sssi', $firstname, $lastname, $major, $id);

    if($statement->execute()){
        //if we execute the query and all is wellthen let the user know

        if($statement->affected_rows > 0){
            http_response_code(200);
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
