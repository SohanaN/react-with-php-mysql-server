<?php
    // error_reporting(E_All);
    // ini_set('display_errors',1);

    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Headers: *");

    // Reference database connection class file and connect to MySQL Database
    include("DbConnect.php");
    $conn = new DbConnect();
    $db = $conn->connect();
    // Give you Method used to hit API
    $method = $_SERVER['REQUEST_METHOD'];
    switch($method) {
        case 'GET':
            $sql = "SELECT * FROM users";
            $stmt = $db->prepare($sql);
            $stmt->execute();
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);        
            echo json_encode($users);
            break;
        case 'POST':
            // Read the POST JSON data and convert it into PHP Object
            $user = json_decode(file_get_contents('php://input'));
            $sql = "INSERT INTO users(id, name, email, mobile, created_at) values(null, :name, :email, :mobile, :created_at)";
            $stmt = $db->prepare($sql);
            $date = date('Y-m-d');
            $stmt->bindParam(':name', $user->name);
            $stmt->bindParam(':email', $user->email);
            $stmt->bindParam(':mobile', $user->mobile);
            $stmt->bindParam(':created_at', $date);
            if($stmt->execute()) {
                $data = ['status' => 1, 'message' => "Record successfully created"];
            } else {
                $data = ['status' => 0, 'message' => "Failed to create record."];
            }
            echo json_encode($data);
            break;
}
?>