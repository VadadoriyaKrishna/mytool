<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $hostname = $_POST['hostname'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    $conn = new mysqli($hostname, $username, $password);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $result = $conn->query("SHOW DATABASES");
    $databases = [];

    while ($row = $result->fetch_assoc()) {
        $databases[] = $row['Database'];
    }

    echo json_encode($databases);
    $conn->close();
}
?>
 