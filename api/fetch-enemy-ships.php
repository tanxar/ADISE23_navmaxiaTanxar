<?php
session_start();

try {
    if (isset($_SESSION['username'])) {
        require_once '../includes/db_connect.php';

        $sessionUsername = $_SESSION['username'];

        // Fetch ships data from other users
        $stmt = $pdo->prepare("SELECT ships FROM users WHERE username != ?");
        $stmt->bindParam(1, $sessionUsername);
        $stmt->execute();
        $shipsData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Fetch hits data for the logged-in user
        $stmt = $pdo->prepare("SELECT ships_hits FROM users WHERE username = ?");
        $stmt->bindParam(1, $sessionUsername);
        $stmt->execute();
        $hitsData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Check if data retrieval was successful
        if (!$shipsData || !$hitsData) {
            throw new Exception("Failed to fetch ships or hits data.");
        }

        // Convert JSON strings to PHP arrays
        $shipsData = json_decode($shipsData[0]['ships'], true);
        $hitsData = json_decode($hitsData[0]['ships_hits'], true);

        global $isHit;

        // Function to check for hits
        function checkHits($ships, $hits)
        {
            $result = [];
            global $isHit; // Use global variable

            foreach ($hits as $hit) {
                $hitCoordinate = ['x' => $hit['x'], 'y' => $hit['y']];
                $isHit = "no";

                // Check if the hit coordinate matches any ship coordinate
                foreach ($ships['ships'] as $ship) {
                    foreach ($ship['coordinates'] as $coordinate) {
                        if ($coordinate == $hitCoordinate) {
                            $isHit = "yes";
                            break 2; // Break both inner and outer loops
                        }
                    }
                }

                $result[] = [
                    'coordinate' => $hitCoordinate,
                    'isHit' => $isHit
                ];
            }

            return $result;
        }

        // Check for hits and get the result
        $hitResult = checkHits($shipsData, $hitsData['hits']);

        // Update player_turn based on the last shot
        if (!empty($hitResult)) {
            $lastHitResult = $hitResult[count($hitResult) - 1];
            
            if ($lastHitResult['isHit'] == "yes") {
                // If the last shot is a hit, set player_turn to 'yes' for the current user
                $stmt = $pdo->prepare("UPDATE users SET player_turn = 'yes' WHERE username = ?");
                $stmt->bindParam(1, $sessionUsername);
                $stmt->execute();

                // Set player_turn to 'no' for other users
                $stmt = $pdo->prepare("UPDATE users SET player_turn = 'no' WHERE username != ?");
                $stmt->bindParam(1, $sessionUsername);
                $stmt->execute();
            } else {
                // If the last shot is not a hit, set player_turn to 'no' for the current user
                $stmt = $pdo->prepare("UPDATE users SET player_turn = 'no' WHERE username = ?");
                $stmt->bindParam(1, $sessionUsername);
                $stmt->execute();

                // Set player_turn to 'yes' for other users
                $stmt = $pdo->prepare("UPDATE users SET player_turn = 'yes' WHERE username != ?");
                $stmt->bindParam(1, $sessionUsername);
                $stmt->execute();
            }
        }

        // Output the result
        echo json_encode($hitResult, JSON_PRETTY_PRINT);

    } else {
        throw new Exception("User not logged in.");
    }
} catch (Exception $e) {
    // Log the error and send an error response
    error_log("Error: " . $e->getMessage());
    http_response_code(500); // Internal Server Error
    echo json_encode(['error' => $e->getMessage()]);
}
?>
