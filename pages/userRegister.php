<?php
$dbHost = 'localhost';
$dbUser = 'root';
$dbPassword = '';
$dbName = 'navmaxia';
// Connect to the database
$conn = new mysqli($dbHost, $dbUser, $dbPassword, $dbName);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// New user details
$newUsername = "player3";
$newPassword = "navmaxia";  // Remember to hash the password for security

// Hash the password (you should use a stronger hashing method in a real-world scenario)
$hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

// Insert a new user into the 'users' table
$sql = "INSERT INTO users (username, password) VALUES ('$newUsername', '$hashedPassword')";

if ($conn->query($sql) === TRUE) {
    echo "User inserted successfully";
} else {
    echo "Error inserting user: " . $conn->error;
}

// Close connection
$conn->close();
?>

