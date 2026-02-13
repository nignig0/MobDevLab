<?php
require_once(__DIR__ . '/../db.php');

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'DELETE') {

    if (!isset($_GET['id'])) {
        http_response_code(400);
        echo json_encode(["message" => "Student Id is required"]);
        exit;
    }

    $id = $_GET['id'];

    if (!is_numeric($id)) {
        http_response_code(400);
        echo json_encode(["message" => "Invalid Id"]);
        exit;
    }

    deleteStudent($id);
}

function deleteStudent($StudentId){
    global $conn;

    $statement = $conn->prepare('DELETE FROM students WHERE id = ?');

    if(!$statement){
        http_response_code(500);
        echo json_encode(["message" => "Unable to prepare statement"]);
        return;
    }

    $statement->bind_param('i', $StudentId);

    if($statement->execute()){

        if($statement->affected_rows > 0){
            http_response_code(200);
            echo json_encode(["message" => "Student deleted"]);
        } else {
            http_response_code(404);
            echo json_encode(["message" => "No student found with that Id"]);
        }

    } else {
        http_response_code(500);
        echo json_encode(["message" => "Error deleting student"]);
    }

    $statement->close();
}
?>
