<!DOCTYPE html>
<html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" type="text/css" href="..\..\css\wandika-main.css?version=1">
    <title>Forgot Password</title>
</head>

<body>

    <div class="modal-content">
        <div id="forgot_password_card">

            <div id="forgot">
                <h1 id="headingForForgot">Create New Password</h1>
                <hr class="forgot_line"> </hr>
                <form action="forgot-password-verify.php" method="POST">
                    <br>
                    <input id="username" name="username" type="text" placeholder="Please enter the username.">
                    <br>
                    <input id="new_password" name="new_password" type="text" placeholder="Please Create a New Password">
                    <br>
                    <input id="confirm_password" name="confirm_password" type="text" placeholder="Please Type in your New Password Again">
                    <br>
                    <input id="submitforForgot" name="submit" type="submit" value="Submit">
                    <br>
                </form>
            </div>

            <div class="bottomNavLinks">
                <a id="welcome_link1" href="welcome-page.php"> Welcome Page</a>
                <a id="create_account_link1" href="create-account.php"> Create Account</a>
            </div>



        </div>
    </div>
        <?php
            if(!isset($_GET['forgot-password'])){
               exit();
            }
            else{
              $passCheck = $_GET['forgot-password'];

              if($passCheck == "empty"){
                echo '<p class="error">Please fill all the fields!</p>';
                exit();
              }
              elseif ($passCheck=="userdoesnotexist") {
                echo '<p class="error">This user does not exist!</p>';
                exit();# code...
              }
              elseif ($passCheck=="passwordNotMatch") {
                echo '<p class="error">Passwords do not match!</p>';
                exit();# code...
              }
            }
          ?>
    <script>
    </script>

</body>

</html>