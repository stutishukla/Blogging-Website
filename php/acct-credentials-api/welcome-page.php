<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" type="text/css" href="..\..\css\wandika-main.css?version=1">
  <title>Wandika Welcome Page</title>
</head>
<body>
  <div class="logoSection">
    <div class="logoIcon" id="left_side">
      <img src="..\..\Images\official_logo.png" width="200" height="170" class="d-inline-block align-top" alt="">
    </div>
  </div>
<!--  -->
  <section class="titleSection" id="right_side">
    <div class="title_group">
      <h1 class="wandika top_word">Wandika</h1>
      <h1 class="wandika bottom_word"> write</h1>
      <hr class="wandika_bar">
      <div class="title_icon">
        <img src="..\..\Images\feather_title.gif" width="200" height="170" class="d-inline-block align-top" alt="">
      </div>
    </div>
  </section>

<!-- change the display for this conent. it should not need enter  for it to be evemly spaced  -->
  <div class="main_content">
    <main id="left_side">
      <div class="content_details">
        <p>
          Write about things you want to share with the world.

          Read blogs in your favorite genres.

          Recieve constructive feedback about your blogs from your community.

          See what other bloggers say has helped them improve their blogs.
        </p>
      </div>
    </main>

<!-- chnage the spacing impelemention between these icons and also find out if you don't have to repeat code 4 times for the same icons -->
    <div class="left_navicons">
      <p>

        <img src="..\..\Images\feather_icon.png" width="100" height="70" class="d-inline-block align-top" alt="">

        <img src="..\..\Images\feather_icon.png" width="100" height="70" class="d-inline-block align-top" alt="">

        <img src="..\..\Images\feather_icon.png" width="100" height="70" class="d-inline-block align-top" alt="">

        <img src="..\..\Images\feather_icon.png" width="100" height="70" class="d-inline-block align-top" alt="">
      </p>
    </div>
  </div>

  <!-- chnage the names for the buttons to the login and create account names  -->
  <div class="container">
    <button class="signup button" onclick="window.location.href='create-account.php'" >Create Account</button>
    <button class="log button"  onclick="window.location.href='login.php'" id="left_btn">Login</button>
  </div>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="../../js/wandika-main.js"></script>
  <script>
    $(document).ready(function() {

    });

  </script>

</body>
</html>
