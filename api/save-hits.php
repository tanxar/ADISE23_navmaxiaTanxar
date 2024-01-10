<?php
session_start();
require_once('../includes/db_connect.php'); // Include your database connection file

if (isset($_SESSION['username']) && isset($_POST['hits'])) {
    $username = $_SESSION['username'];
    $hitCoordinatesJSON = $_POST['hits'];

    try {
        // Update the database with the hit coordinates
        $stmt = $pdo->prepare("UPDATE users SET ships_hits = ? WHERE username = ?");
        $stmt->bindParam(1, $hitCoordinatesJSON);
        $stmt->bindParam(2, $username);
        $stmt->execute();

        echo ". Database updated.";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Invalid request.";
}
?>
