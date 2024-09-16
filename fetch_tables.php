<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $hostname = $_POST['hostname'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $database = $_POST['database'];

    $conn = new mysqli($hostname, $username, $password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $result = $conn->query("SHOW TABLES");
    $tables = [];

    while ($row = $result->fetch_array()) {
        $tables[] = $row[0];
    }

    echo json_encode($tables);
    $conn->close();
     
}
?>
