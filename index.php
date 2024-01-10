
<?php
session_start();
if (isset($_SESSION['username'])) {
  header("Location: ../pages/playroom.php");
  exit();
}
?>

<html lang="en">
<head>
    <link rel="stylesheet" href="css/login.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.xe0">
    <title>Navmaxia</title>
    
</head>
<body>
<div class="all">
    <p style="position: fixed; bottom: 0; right: 0; margin: o; padding: 0px 20px 0px 10px; color: rgba(255, 255, 255, 0.779);">Made by Konstantinos Tantanasis Charalampidis</p>
    <p style="color: white; font-weight: 700; font-size: 30px; padding: 10px 10px 10px 50px;">Ναυμαχίες</p>
        <div class="login-box">
            <h2>Login</h2>
            <form action="api/login.php" method="post" id="loginForm">
              <div class="user-box">
                <input type="text" name="username" id="username" required="">
                <label>Username</label>
              </div>
              <div class="user-box">
                <input type="password" name="password" id="password" required="">
                <label>Password</label>
              </div>
              <a onclick="login()" style="cursor: pointer;">
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                Login
              </a>
            </form>
        </div>
</div>



<script>
        function login() {
            document.getElementById("loginForm").submit();
        }
</script>






 <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
  <script src="scripts.js"></script>  
</body>
</html>

