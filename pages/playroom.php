<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Battleship Game</title>
 <link rel="stylesheet" href="../css/playroom.css">
</head>
<body>

<div class="top-section">
  <div class="ekfonitis" id="ekfonitis" style="padding: 5px;">
      <p id="inner-text">Πατήστε το κουμπί για να ξεκινήσετε.</p>
  </div>
  <button id="startBtn" onclick="start()" style="margin-left:30px; background-color: rgba(35, 211, 0, 0); border-radius: 10px; cursor: pointer;">Ψάξε αντίπαλο</button>
</div>


<div>
  <!-- left Grid -->
  <div class="grid-container" id="grid2">
    <div class="label-cell"></div>
    <div class="label-cell">1</div>
    <div class="label-cell">2</div>
    <div class="label-cell">3</div>
    <div class="label-cell">4</div>
    <div class="label-cell">5</div>

    <div class="label-cell">A</div>
    <div class="grid-cell" id="grid2-cell-A-1"></div>
    <div class="grid-cell" id="grid2-cell-A-2"></div>
    <div class="grid-cell" id="grid2-cell-A-3"></div>
    <div class="grid-cell" id="grid2-cell-A-4"></div>
    <div class="grid-cell" id="grid2-cell-A-5"></div>

    <div class="label-cell">B</div>
    <div class="grid-cell" id="grid2-cell-B-1"></div>
    <div class="grid-cell" id="grid2-cell-B-2"></div>
    <div class="grid-cell" id="grid2-cell-B-3"></div>
    <div class="grid-cell" id="grid2-cell-B-4"></div>
    <div class="grid-cell" id="grid2-cell-B-5"></div>

    <div class="label-cell">C</div>
    <div class="grid-cell" id="grid2-cell-C-1"></div>
    <div class="grid-cell" id="grid2-cell-C-2"></div>
    <div class="grid-cell" id="grid2-cell-C-3"></div>
    <div class="grid-cell" id="grid2-cell-C-4"></div>
    <div class="grid-cell" id="grid2-cell-C-5"></div>

    <div class="label-cell">D</div>
    <div class="grid-cell" id="grid2-cell-D-1"></div>
    <div class="grid-cell" id="grid2-cell-D-2"></div>
    <div class="grid-cell" id="grid2-cell-D-3"></div>
    <div class="grid-cell" id="grid2-cell-D-4"></div>
    <div class="grid-cell" id="grid2-cell-D-5"></div>

    <div class="label-cell">E</div>
    <div class="grid-cell" id="grid2-cell-E-1"></div>
    <div class="grid-cell" id="grid2-cell-E-2"></div>
    <div class="grid-cell" id="grid2-cell-E-3"></div>
    <div class="grid-cell" id="grid2-cell-E-4"></div>
    <div class="grid-cell" id="grid2-cell-E-5"></div>
  </div>
  <p style="text-align: center; padding: 10px; margin-left: 40px;">Ο Πίνακας μου </p>
</div>



  <!-- right grid-->
  <div>
    <div id="overlay"></div>
    <div class="grid-container" id="grid1">
      <div class="label-cell"></div>
      <div class="label-cell">1</div>
      <div class="label-cell">2</div>
      <div class="label-cell">3</div>
      <div class="label-cell">4</div>
      <div class="label-cell">5</div>
  
      <div class="label-cell">A</div>
      <div class="grid-cell" id="grid1-cell-A-1"></div>
      <div class="grid-cell" id="grid1-cell-A-2"></div>
      <div class="grid-cell" id="grid1-cell-A-3"></div>
      <div class="grid-cell" id="grid1-cell-A-4"></div>
      <div class="grid-cell" id="grid1-cell-A-5"></div>
  
      <div class="label-cell">B</div>
      <div class="grid-cell" id="grid1-cell-B-1"></div>
      <div class="grid-cell" id="grid1-cell-B-2"></div>
      <div class="grid-cell" id="grid1-cell-B-3"></div>
      <div class="grid-cell" id="grid1-cell-B-4"></div>
      <div class="grid-cell" id="grid1-cell-B-5"></div>
  
      <div class="label-cell">C</div>
      <div class="grid-cell" id="grid1-cell-C-1"></div>
      <div class="grid-cell" id="grid1-cell-C-2"></div>
      <div class="grid-cell" id="grid1-cell-C-3"></div>
      <div class="grid-cell" id="grid1-cell-C-4"></div>
      <div class="grid-cell" id="grid1-cell-C-5"></div>
  
      <div class="label-cell">D</div>
      <div class="grid-cell" id="grid1-cell-D-1"></div>
      <div class="grid-cell" id="grid1-cell-D-2"></div>
      <div class="grid-cell" id="grid1-cell-D-3"></div>
      <div class="grid-cell" id="grid1-cell-D-4"></div>
      <div class="grid-cell" id="grid1-cell-D-5"></div>
  
      <div class="label-cell">E</div>
      <div class="grid-cell" id="grid1-cell-E-1"></div>
      <div class="grid-cell" id="grid1-cell-E-2"></div>
      <div class="grid-cell" id="grid1-cell-E-3"></div>
      <div class="grid-cell" id="grid1-cell-E-4"></div>
      <div class="grid-cell" id="grid1-cell-E-5"></div>
    </div>
    <p style="text-align: center; padding: 10px; margin-left: 100px;">Πίνακας αντιπάλου</p>
  </div>
  
<div style="position:absolute; bottom:40px; left:40px;">
<p>Είσαι ο <span><?php echo $_SESSION["username"];?></span></p> 
</div>

 <div style="position:absolute; top:40px; right:40px;">
 <a onclick="logout()" style="font-size:14px; padding:7px 10px 7px 10px; border: 1px solid red; border-radius:10px; cursor:pointer;">Έξοδος</a>
</div>
  
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="../js/playroom-scrips.js"></script>
</body>
</html>
