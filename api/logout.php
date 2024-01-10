<?php
session_start();
$dbHost = 'localhost';
$dbUser = 'root';
$dbPassword = '';
$dbName = 'navmaxia';

try {
    // Create a PDO instance
    $conn = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPassword);
    
    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if the user is logged in
    if (isset($_SESSION['username'])) {
        // Update the "logged_in" column to 0 for the logged-in user
        $username = $_SESSION['username'];
        $updateSql = "UPDATE users SET logged_in = 0 WHERE username = :username";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bindParam(':username', $username);
        $updateStmt->execute();

        // Unset all of the session variables
        $_SESSION = array();

        // Destroy the session
        session_destroy();

        // Redirect to the login page or any other desired page after logout
        header("Location: ../index.php");
        exit();
    } 
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
} finally {
    // Close connection
    $conn = null;
}
?>
