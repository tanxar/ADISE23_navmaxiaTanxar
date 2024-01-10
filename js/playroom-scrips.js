document.addEventListener("DOMContentLoaded", function () {

    //"lock" grid1 - deksi grid
    locker = document.getElementById("overlay");
    locker.classList.add("locked");


    // Arrays to store ship coordinates
    const ship1 = [];
    const ship2 = [];
    const ship3 = [];

    var allShipCoordinates = [];

    function getRandomInt(min, max) {
        return Math.floor(Math.random() * (max - min + 1)) + min;
    }

    function isCellOccupied(cellId) {
        const cell = document.getElementById(cellId);
        return cell && cell.classList.contains("ship");
    }

    function hasNeighboringShip(gridId, row, col) {
        for (let i = -1; i <= 1; i++) {
            for (let j = -1; j <= 1; j++) {
                const rowIndex = row + i;
                const colIndex = col + j;

                if (rowIndex >= 1 && rowIndex <= 5 && colIndex >= 1 && colIndex <= 5) {
                    const cellId = `${gridId}-cell-${String.fromCharCode(colIndex + 64)}-${rowIndex}`;
                    if (isCellOccupied(cellId)) {
                        return true;
                    }
                }
            }
        }
        return false;
    }

    function isValidShipPlacement(gridId, row, col, shipSize, isHorizontal) {
        const maxRow = isHorizontal ? row : row + shipSize - 1;
        const maxCol = isHorizontal ? col + shipSize - 1 : col;

        if (maxRow > 5 || maxCol > 5) {
            return false; // Check if the ship exceeds grid boundaries
        }

        // Check if there are no neighboring ships
        for (let i = 0; i < shipSize; i++) {
            const rowIndex = isHorizontal ? row : row + i;
            const colIndex = isHorizontal ? col + i : col;

            if (hasNeighboringShip(gridId, rowIndex, colIndex)) {
                return false;
            }
        }

        return true;
    }

    function placeShip(gridId, shipSize, shipCoordinates, shipId) {
        const grid = document.getElementById(gridId);

        let placedShip = false;
        let attempts = 0;
        const maxAttempts = 100;

        while (!placedShip && attempts < maxAttempts) {
            const row = getRandomInt(1, 5);
            const col = getRandomInt(1, 5);
            const isHorizontal = getRandomInt(0, 1) === 0;

            console.log(`Attempting to place ${shipId} at ${row}-${col}`);

            if (isValidShipPlacement(gridId, row, col, shipSize, isHorizontal)) {
                console.log(`Placing ${shipId} at ${row}-${col}`);
                const shipCoordinatesArray = [];

                for (let i = 0; i < shipSize; i++) {
                    const rowIndex = isHorizontal ? row : row + i;
                    const colIndex = isHorizontal ? col + i : col;
                    const cellId = `${gridId}-cell-${String.fromCharCode(colIndex + 64)}-${rowIndex}`;
                    const cell = document.getElementById(cellId);

                    if (cell) {
                        cell.classList.add("ship");
                        shipCoordinatesArray.push({ x: String.fromCharCode(colIndex + 64), y: rowIndex });
                    } else {
                        console.error(`Cell not found at ${String.fromCharCode(colIndex + 64)}-${rowIndex}`);
                    }
                }

                placedShip = true;
                console.log(`${shipId} placed successfully! Taken cells:`, shipCoordinatesArray);
                shipCoordinates.push({ id: shipId, coordinates: shipCoordinatesArray });
                console.log('Ship coordinates:', shipCoordinatesArray);

                allShipCoordinates.push({ id: shipId, coordinates: shipCoordinatesArray });
            }

            attempts++;
        }

        if (!placedShip) {
            console.error(`Failed to place ${shipSize}x1 ${shipId} after multiple attempts.`);
        }
    }

    // Place ships on "grid2" and store coordinates
    placeShip("grid2", 3, ship3, "ship3"); // 3x1 ship
    placeShip("grid2", 2, ship2, "ship2"); // 2x1 ship
    placeShip("grid2", 1, ship1, "ship1"); // 1x1 ship

    // Convert all ship coordinates to JSON format
    var allShipCoordinatesJSON = JSON.stringify({ ships: allShipCoordinates });
    console.log('All Ship coordinates in JSON:', allShipCoordinatesJSON);

      // Make allShipCoordinatesJSON accessible to the start function
      window.allShipCoordinatesJSON = allShipCoordinatesJSON;
      shipsJSON = window.allShipCoordinatesJSON;
    
});


function start(){
    //alert(allShipCoordinatesJSON);
     // Send AJAX request
    // Get the element by its ID
    setInterval(checkTurn, 3000);
    document.getElementById("startBtn").style.visibility = "hidden";


var element = document.getElementById("inner-text");

// Check if the element exists
if (element) {
  // Change the inner HTML of the element
  element.innerHTML = "Αναζήτηση αντιπάλου...";
 
} 


$.ajax({
    type: "POST",
    url: "../api/start-game.php",
    data: { data: allShipCoordinatesJSON },
    success: function (response) {
        if (response.includes("Update successful.") && response.includes("row(s) affected.") && response.includes("JSON inserted successfully.")) {
            console.log("Both players are connected, and JSON inserted successfully.");
            setTimeout(function () {
                document.getElementById("inner-text").innerText = "Βρέθηκε αντίπαλος.";
            //kalei tin function seira checkTurn na dei pianou seira einai.
            checkTurn();
            }, 1000);         
        } else {
            // Both players are not logged in
            setTimeout(function () {
                document.getElementById("inner-text").innerText = "Δεν έχει συνδεθεί αντίπαλος.";
                document.getElementById("startBtn").style.visibility = "";
            }, 1000);
            console.log(response);
        }
    },
    error: function (xhr, status, error) {
        console.error("AJAX request failed:", status, error);
    }
});
}


//Afti i function kanei ajax request kathe 1 sec kai tsekarei pianou seira einai.
function checkTurn() {
    // AJAX request using jQuery
    $.ajax({
        url: "../api/seiraPekti.php", // Update to your PHP file name
        type: "GET",
        success: function(response) {
            //An einai i seira sou
            console.log("Your turn:", response);
            if (response.trim().toLowerCase() === "true") {
                    locker = document.getElementById("overlay");
                    //des an iparxei i class locked
                    if (locker.classList.contains("locked")) {
                        locker.classList.remove("locked");
                        element = document.getElementById("inner-text");
                        element.innerHTML = "Σειρά σου.";
                    }
 
            }
            //An den einai i seira sou
            if (response.trim().toLowerCase() === "false") {
                    locker = document.getElementById("overlay");
                    //des an den iparxei i class locked
                    if (!locker.classList.contains("locked")) {
                        locker.classList.add("locked");
                        element = document.getElementById("inner-text");
                        element.innerHTML = "Σειρά αντιπάλου.";
                    } 
            }
        },
        error: function(xhr, status, error) {
            console.error("Error occurred:", error);
        }
    });
}




















//Afti i function kalei to logout.php opou ginetai to session destroy.
function logout(){
    //alert("work");
    $.ajax({
        type: "POST",
        url: "../api/logout.php", 
        success: function(response){
            // Handle the response
            window.location.href = "index.php";
        },
        error: function(xhr, status, error) {
            console.error("AJAX request failed:", status, error);
        }
    });
}




  //define hitCoordinates
  const hitCoordinates = [];


  $(document).ready(function () {
    // Add click event listener to grid1 cells
    $('#grid1 .grid-cell').click(function () {
        // Check if the cell has already been clicked
        if ($(this).hasClass('clicked')) {
            // Cell has already been clicked, do nothing
            return;
        }else{
        const cellId = $(this).attr('id');
        const coordinates = parseCellId(cellId);
        // Save hit coordinates or perform other actions
        saveHitCoordinates(coordinates);
        // Add a class to mark the cell as clicked
        $(this).addClass('clicked');
        // Uncomment the following line if you want to show an alert
        // alert("Cell clicked and marked as clicked");
        checkTurn();
        }
    });
});




//Afti i function vriskei sto kathe click enos cell tis sintetagmenes tou.
function parseCellId(cellId) {
    // Extract row and column information from the cell ID
    const match = cellId.match(/grid1-cell-([A-E])-([1-5])/);
    if (match) {
        return { x: match[1], y: parseInt(match[2]) };
    }
    return null;
}

//Afti i function (kathe fora poy ginetai ena click sto deksi grid) mazevei oles tis sintetagmenes mazi apo ta synolika hits kai kanei save stin db. 
function saveHitCoordinates(coordinates) {
    // Check if the coordinates are not null
    if (coordinates) {
        // Assuming you have an array to store hit coordinates
        hitCoordinates.push(coordinates);

        // Log hit coordinates in the console
        console.log('Current Hit Coordinates:', hitCoordinates);

        // Convert hit coordinates to JSON format
        const hitCoordinatesJSON = JSON.stringify({ hits: hitCoordinates });

        // Make hitCoordinatesJSON accessible to other functions or send it to the server if needed
        window.hitsJSON = hitCoordinatesJSON;

        // Send hit coordinates to the server (PHP script) for database update

        $.ajax({
            type: "POST",
            url: "../api/save-hits.php", // URL of the server-side script
            data: { hits: hitCoordinatesJSON }, // Data to be sent to the server in JSON format
            success: function (response) {
                // Callback function called on successful AJAX request
                console.log(response); // Log the server's response to the console
            },
            error: function (xhr, status, error) {
                // Callback function called when there is an error in the AJAX request
                console.error("AJAX request failed:", status, error);
            }
        });


        //alert(hitsJSON);
        compareHitsWithShips();
    }
}










//Afti i function:
//(1) Pairnei apo tin db tis syntetagmenes apo ta ploia toy antipaloy.
//(2) Sygrinei an to hit pou egine teleftaio xtypaei kapoio ploio toy antipalou kai allazei to css tou keliou analoga.
function compareHitsWithShips() {
    console.log('Entering compareHitsWithShips');

    $.ajax({
        url: '../api/fetch-enemy-ships.php',
        type: 'POST',
        success: function (hitResult,response) {
            try {
                console.log('AJAX success. Received hitResult:', hitResult);
                console.log(response);

                // Check if the response is valid JSON
                var parsedResult = JSON.parse(hitResult);

                // Check if the parsed result has the expected structure
                if (!Array.isArray(parsedResult)) {
                    throw new Error("Invalid response format");
                }

                console.log('Parsed result:', parsedResult);

                // Iterate through each result and call markCell
                parsedResult.forEach(function (result) {
                    var xCoordinate = result.coordinate.x;
                    var yCoordinate = result.coordinate.y;

                    //window hia na mporei na to parei pio katw ektos scope
                    window.isHit = result.isHit;

                    console.log('Calling markCell with x:', xCoordinate, 'y:', yCoordinate, 'isHit:', isHit);

                    // Call markCell with the received values
                    markCell(xCoordinate, yCoordinate, isHit);
                });
            } catch (error) {
                console.error('Error processing AJAX response:', error.message);
                // Handle the error, show a user-friendly message, or take appropriate action
            }
        },
        error: function (error) {
            console.error('AJAX request failed:', error.statusText);
            // Handle the error, show a user-friendly message, or take appropriate action
        }
    });

    console.log('Exiting compareHitsWithShips');
}




function markCell(xCoordinate, yCoordinate, isHit) {

    try {
        console.log('Received isHit:', isHit);

        // Trim any leading or trailing whitespace
        isHit = isHit.trim();

        console.log('Trimmed isHit:', isHit);

        // Check if the required parameters are present
        if (xCoordinate === undefined || yCoordinate === undefined || isHit === undefined) {
            throw new Error("Incomplete data received");
        }

        // Cell ID based on the hit coordinates
        var cellId = `grid1-cell-${xCoordinate}-${yCoordinate}`;

        // Check if the cell with the specified ID exists
        if ($(`#${cellId}`).length === 0) {
            throw new Error(`Cell with ID ${cellId} not found`);
        }

        // Mark the cell as hit or miss based on the 'isHit' value (case-insensitive)
        if (isHit.trim().toLowerCase() === 'yes') {
            //alert("petixes");
            // Add a CSS class to mark the cell as hit
            $(`#${cellId}`).addClass('hit-cell');
        } else if (isHit.trim().toLowerCase() === 'no') {
            $(`#${cellId}`).addClass('empty-cell');
           //alert("astoxises");
        } else {
            throw new Error(`Invalid 'isHit' value: ${isHit}`);
        }
    } catch (error) {
        console.error('Error in markCell:', error.message);
        // Handle the error, show a user-friendly message, or take appropriate action
    }
}





