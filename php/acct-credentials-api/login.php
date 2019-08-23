<!DOCTYPE html>
<html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" type="text/css" href="..\..\css\wandika-main.css?version=1">
  <title>Login</title>
</head>
<body>

  <!-- add links to redirect to the splash page and the create account page  -->

  <div class="modal-content">
    <div id="login_card">
      <div id="login">
        <h1>Login</h1>
        <hr class="login_line"> </hr>
        <form action="login-verify.php" method="POST">
          <input id="username" name="username" type="text" placeholder="Username" >
          <br>
          <input id="user_pass" name="user_pass" type="text" placeholder="Password" >
          <br>
          <div id="forgot">
            <a href="forgot-password.php">Forgot Password?</a>
            <br>
            <br>
          </div>
          <input id="submit" name="submit" type="submit" value="Submit">
        </form>
      </div>
      
    <div id="alt_login" >
        <p>Or Login Using: &nbsp&nbsp&nbsp&nbsp&nbsp
          <a href=""><img src="../../Images/facebook.png" alt="Login with Facebook" class="social_media_icons facebook"/></a>
          <a href=""><img src="../../Images/twitter.png" alt="Login with Twitter" class="social_media_icons twitter"/></a>
          <a href=""><img src="../../Images/google-plus.png" alt="Login with Google" class="social_media_icons google"/></a></p>
    </div>

     <div class="bottomNavLinks">
                <a id="welcome_link" href="welcome-page.php"> Welcome Page</a>
                <a id="create_account_link" href="create-account.php"> Create Account</a>
        </div>
      </div>
    </div>
    <?php
            if(!isset($_GET['login'])){
               exit();
            }
            else{
              $loginCheck = $_GET['login'];

              if($loginCheck == "empty"){
                echo '<p class="error">Please fill all the fields!</p>';
                exit();
              }
              elseif ($loginCheck=="nosuchuser") {
                echo '<p class="error">This user does not exist!</p>';
                exit();# code...
              }
              elseif ($loginCheck=="incorrectpass") {
                echo '<p class="error">Incorrect password!</p>';
                exit();# code...
              }
            }
          ?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="../../js/wandika-main.js"></script>
    <script>
      $(document).ready(function() {
      });

    </script>

  </body>
  </html>
