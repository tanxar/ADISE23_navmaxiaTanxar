<?php
// Include the database connection file
require_once '../includes/db_connect.php';

try {
    // Get the current session username
    session_start();
    $sessionUsername = isset($_SESSION['username']) ? $_SESSION['username'] : null;

    // Select users where player_turn is 'yes'
    $stmt = $pdo->prepare("SELECT username FROM users WHERE player_turn = 'yes'");
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($result) {
        $foundUsername = $result[0]['username'];

        // Check if the found username matches the session username
        $response = ($sessionUsername === $foundUsername) ? 'true' : 'false';

        // Output the response
        echo $response;
    } else {
        // No user found with player_turn = 'yes'
        echo "false";
    }
} catch (PDOException $e) {
    // Handle database errors
    echo "Error: " . $e->getMessage();
}
?>
