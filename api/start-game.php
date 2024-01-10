<?php

session_start();

$dbHost = 'localhost';
$dbUser = 'root';
$dbPassword = '';
$dbName = 'navmaxia';

try {
    // Create a new PDO instance
    $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPassword);

    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $jsonShipsData = $_POST['data'];

    // Check if both players are logged in
    $checkLoggedInQuery = "SELECT COUNT(*) as count FROM users WHERE logged_in = 1";
    $stmtCheckLoggedIn = $pdo->prepare($checkLoggedInQuery);
    $stmtCheckLoggedIn->execute();
    $result = $stmtCheckLoggedIn->fetch(PDO::FETCH_ASSOC);

    if ($result['count'] == 2) {
        // Both players are logged in, proceed with the update
        $username = $_SESSION["username"];
        $updateQuery = "UPDATE users SET ships = :shipsData WHERE username = :username AND logged_in = 1";
        $stmt = $pdo->prepare($updateQuery);
        $stmt->bindParam(':shipsData', $jsonShipsData, PDO::PARAM_STR);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();

        // Check if the update was successful
        $rowCount = $stmt->rowCount();

        if ($rowCount > 0) {
            echo "Update successful. $rowCount row(s) affected. JSON inserted successfully.";
        } else {
            echo "No rows updated. Username '$username' not found or players not connected.";
        }
    } else {
        echo "Both players are not logged in.";
    }
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
