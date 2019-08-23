<!DOCTYPE html>
<html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" type="text/css" href="..\..\css\wandika-main.css?version=1">
  <title>New User Information</title>
</head>
<body>
    <div class="new-user-content">
        <!--<span onclick="closeModal()" class="close">&times;</span>-->
        <span class="acct_close">&times;</span>
        <div id="new_user_card">
            <div id="new_user">
                <h1>New User Information</h1>
                <hr class="new_user_info"> </hr>
                <form action="new-user-info.php" method="POST"> <!--TODO: php file for the profile page-->
                    <input id="username" name="username" type="text" placeholder="Username" minlength="3" maxlength="15">
                    <br>
                    <input id="first_name" name="first_name" type="text" placeholder="First Name"  minlength="2" maxlength="20">
                    <br>
                    <input id="last_name" name="last_name" type="text" placeholder="Last Name" minlength="2" maxlength="30">
                    <br>
                    <input id="dob" name="dob" type="text" placeholder="Date of Birth mm/dd/yyyy" >
                    <br>
                    <input id="country_code" name="country_code" type="text" placeholder="County Code" minlength="2" maxlength="3">
                    <br>
                    <input id="submit" name="submit" type="submit" value="Submit">
                </form>
            </div>

            <?php
            //error handling
            if(!isset($_GET['new-user'])){
               exit();
            }
            else{
              $newuserCheck = $_GET['new-user'];

              if($newuserCheck == "empty"){
                echo '<p class="error">Please fill all the fields!</p>';
                exit();
              }
              elseif ($newuserCheck=="userdoesnotexist") {
                echo '<p class="error">This user does not exist!</p>';
                exit();# code...
              }
              elseif ($newuserCheck=="invalidDOB") {
                echo '<p class="error">Please enter a valid date of birth!</p>';
                exit();# code...
              }
            }
          ?>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="../../js/wandika-main.js"></script>
    <script>
        $(document).ready(function() {
        });
    </script>
            
</body>
<!--Names for php _POSTS: country, first_name, last_name, dob, country_code
    Date can be pulled from the date class in php.-->
</html>