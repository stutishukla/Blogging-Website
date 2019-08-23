<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" type="text/css" href="..\..\css\settings.css">
  <link href="https://fonts.googleapis.com/css?family=Quicksand:300,500" rel="stylesheet">
  <title>User Settings</title>
</head>
<body>
  <div>

      <div class="topNavBar">
      
              <ul>
                <li><a href="..\..\html\newsFeed-new.html">Community</a></li>
                <li><a href="..\..\html\profile-page.html">Personal Page</a></li>
                <li><a href="..\acct-credentials-api\welcome-page.php">Log Out</a></li>
              </ul>
            
    </div>
        <!----span onclick="closeModal()" class="close">&times;</span-->
        <div id="settings-card">
            <div class="settings-page-content">
                <h1>Settings</h1>
                <hr id="h-bar"> </hr>
                <form action="settings-verify.php" method="POST"> <!--TODO: php file for the profile page-->
                    <input id="username" name="username" type="text" placeholder="Username">

                    <input id="change_password" name="change_password" type="text" placeholder="Change Password">
                    
                    <input id="first_name" name="first_name" type="text" placeholder="Change First Name">
                    
                    <input id="last_name" name="last_name" type="text" placeholder="Change Last Name">
                    
                    <input id="email" name="email" type="text" placeholder="Change Email Address">
                    
                    <input id="country" name="country" type="text" placeholder="Change Country">
                    
                    <input id="country_code" name="country_code" type="text" placeholder="Change County Code">
                    
                    <input id="submit" name="submit" type="submit" value="Submit Changes">
                </form>
            </div>
            <?php
            if(!isset($_GET['settings'])){
               exit();
            }
            else{
              $settingCheck = $_GET['settings'];

              if($settingCheck == "userdoesnotexist"){
                echo '<p class="error">This user does not exist!</p>';
                exit();
              }
              elseif ($settingCheck=="invalidFirst") {
                echo '<p class="error">Please enter a valid first name!</p>';
                exit();# code...
              }
              elseif ($settingCheck=="invalidLast") {
                echo '<p class="error">Please enter a valid last name!</p>';
                exit();# code...
              }
              elseif ($settingCheck=="email") {
                echo '<p class="error">Please enter a valid e-mail!</p>';
                exit();# code...
              }
            }
          ?>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="../../js/settings.js"></script>
    <script>
        $(document).ready(function() {
        });
    </script>

</body>
</html>