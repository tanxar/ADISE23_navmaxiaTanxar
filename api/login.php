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
    
    // User input from AJAX request
    $loginUsername = $_POST['username'];
    $loginPassword = $_POST['password'];
    
    // Retrieve hashed password and logged-in status from the database
    $sql = "SELECT username, password, logged_in FROM users WHERE username = :username";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':username', $loginUsername);
    $stmt->execute();
    
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result) {
        $storedPassword = $result["password"];
        
        // Verify the entered password against the stored hashed password
        if (password_verify($loginPassword, $storedPassword)) {
            // Update the "logged-in" column in the users table
            $updateSql = "UPDATE users SET logged_in = 1 WHERE username = :username";
            $updateStmt = $conn->prepare($updateSql);
            $updateStmt->bindParam(':username', $loginUsername);
            $updateStmt->execute();
            
            // Set the session variable
            $_SESSION["username"] = $loginUsername;

            // Send JSON response for successful login
            echo json_encode(['message' => 'Login successful', 'success' => true]);

            // Redirect to playroom.php
            header("Location: ../pages/playroom.php");
            exit();
        } else {
            // Incorrect password
            echo json_encode(['message' => 'Invalid password', 'success' => false]);
        }
    } else {
        // User not found
        echo json_encode(['message' => 'User not found', 'success' => false]);
    }
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
} finally {
    // Close connection
    $conn = null;
}
?>
