<?php
if ($_SERVER["REQUEST_METHOD"] == "GET") { 
    // Retrieve form data
$name = $_GET['name'] ?? ' ';
$email = $_GET['email'] ?? ' ';
$number = $_GET['number'] ?? ' ';
$company = $_GET['company'] ?? ' ';
$message = $_GET['message'] ?? ' ';


    // Database connection
    // $conn = new mysqli('localhost', 'root', '', 'testphp');
    $conn = new mysqli('sql106.infinityfree.com', 'if0_38530000', 'CghLSOtRVY', 'if0_38530000_testphp');

    // Check connection
    if ($conn->connect_error) {
        die('Connection Failed: ' . $conn->connect_error);
    } else {
        // Prepare SQL statement
        $stmt = $conn->prepare("INSERT INTO registration (name, email, number, company, message) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $email, $number, $company, $message);

        if ($stmt->execute()) {
            echo "<script>alert('Registration Successful!'); window.location.href='index.html';</script>";
        } else {
            echo "Error: " . $stmt->error;
        }
        
        // Close statement and connection
        $stmt->close();
        $conn->close();
    }
} else {
    echo "Invalid request.";
}
?>
