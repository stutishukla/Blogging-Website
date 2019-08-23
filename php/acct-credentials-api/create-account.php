<!DOCTYPE html>
<html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" type="text/css" href="..\..\css\wandika-main.css?version=1">
  <title>Create Account</title>
</head>
<body>
  <div class="acct-content">
      <span class="acct_close">&times;</span>
      <div id="login_card">
        <div id="login">
          <h1>Create account</h1>
          <hr class="login_line"> </hr>
          <form action="sign-up-verify.php" method="POST">
              <?php
              if(isset($_GET['username'])){
                $uid=$_GET['username'];
                echo '<input id="username" name="username" type="text" placeholder="Username"  minlength="3" maxlength="15" value="'.$uid.'">';
              }
              else{
                echo '<input id="username" name="username" type="text" placeholder="Username"  minlength="3" maxlength="15">';
              }
            ?>
            <br>
            <?php
              if(isset($_GET['email'])){
                $email=$_GET['email'];
                echo '<input id="email" name="email" type="text" placeholder="Email"  minlength="3" maxlength="15" value="'.$email.'">';
              }
              else{
                echo '<input id="email" name="email" type="text" placeholder="Email"  minlength="3" maxlength="15">';
              }
            ?> 
            <br>
            <input id="user_pass" name="user_pass" type="text" placeholder="Password" minlength="3" maxlength="15">
            <br>
            <input id="password" name="password" type="text" placeholder="Confirm Password" minlength="3" maxlength="15">
            <br>
            <?php
              if(isset($_GET['country'])){
                $country=$_GET['country'];
                echo '<input id="country" name="country" type="text" placeholder="Country"  minlength="3" maxlength="15" value="'.$country.'">';
              }
              else{
                echo '<input id="country" name="country" type="text" placeholder="Country"  minlength="3" maxlength="15">';
              }
            ?>
            <br>
            <input id="submit"  name="submit" type="submit" value="Submit">
          </form>
          <div class="bottomNavLinks">
                <a id="welcome_link" href="welcome-page.php"> Welcome Page</a>
                <a id="login_link" href="login.php"> Login</a>
        </div>
        </div>
      </div>
    </div>
    <?php
           if(!isset($_GET['signup'])){
               exit();
            }
            else{
              $signupCheck = $_GET['signup'];

              if($signupCheck == "empty"){
                echo '<p class="error">Please fill all the fields!</p>';
                exit();
              }
              elseif ($signupCheck=="invalid") {
                echo '<p class="error">Invalid UserName!</p>';
                exit();# code...
              }
              elseif ($signupCheck=="email") {
                echo '<p class="error">Invalid E-mail ID!</p>';
                exit();# code...
              }
              elseif ($signupCheck=="usertaken") {
                echo '<p class="error">This Username has already been taken!</p>';
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
